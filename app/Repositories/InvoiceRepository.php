<?php

namespace App\Repositories;

use App\Calculators\InvoiceCalculator;
use App\Helpers\Notification\Types\UserNotification;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceExtraSubject;
use App\Models\Invoice\InvoicePart;
use App\Models\Invoice\InvoiceSubject;
use App\Models\Order;
use App\Models\OrderSubject;
use App\Rules\Custom\Invoice\InvoiceOrder;
use App\Rules\Custom\Invoice\InvoiceSubjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceRepository
{

    public function getByOrderAndUser($orderId, $userId){
        return Invoice::join("orders", function ($q) use ($orderId, $userId){
            $q->on("invoices.order_id", "=", "orders.id");
        })->where("orders.id", $orderId)->where("orders.user_id", $userId)->firstOrFail(["invoices.*"]);
    }

    public function getByOrderId($orderId){
        return Order::where("id", $orderId)->first();
    }

    public function getByAllOrder(){
        return Order::all();
    }

    public function columns(){
        return [
            "extra_subjects.*" => "extra_subjects",
            "extra_subjects.*.text" => "text",
            "extra_subjects.*.count" => "count",
            "extra_subjects.*.price" => "price",
            "parts.*" => "parts",
            "parts.*.text" => "text",
            "parts.*.count" => "count",
            "parts.*.price" => "price",
        ];
    }

    public function rules(): array
    {
        return [
            "subjects" => ["required", "array"],
            "subjects.*" => ["required", new InvoiceSubjects()],
            "extra_subjects.*.name" => ["nullable", "max:255"],
            "extra_subjects.*.count" => ["nullable","numeric"],
            "extra_subjects.*.price" => ["nullable","numeric"],
            "parts.*.name" => ["nullable","max:255"],
            "parts.*.count" => ["nullable","numeric"],
            "parts.*.price" => ["nullable","numeric"],
            "order_id" => ["required", new InvoiceOrder()]
        ];
    }

    public function validation(Request $request): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $this->rules(), [], $this->columns());
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }
        return $result;
    }

    public function store(Request $request){
        $invoice = new Invoice();
        $this->saveInvoice($invoice, $request->only("order_id"));

        //save invoice subjects
        foreach ($request->subjects as $subject){
            $orderSubject = OrderSubject::where("order_id", $request->only("order_id"))->where("subject_id", $subject["id"])->first();
            if($orderSubject)
                foreach ($orderSubject->properties as $property)
                    $subject["price"] += $property->value_price;

            $subject["price"] = $subject["count"] * $subject["price"];
            $invoiceSubject = new InvoiceSubject();
            $invoiceSubject->subject_id = $subject["id"];
            $invoiceSubject->invoice_id = $invoice->id;
            $this->saveInvoiceSubject($invoiceSubject, $subject);
            $order = Order::find($request->order_id);
            (new \App\Services\OrderService())->finish($order);
            $notification = new UserNotification($order->user_id, "create_invoice");
            $notification->setBodyArgs(["teacher" => Auth::user()->full_name, "orderId" => $request->order_id]);
            $notification->send();
        }

        //save invoice extra subjects
        if($request->extra_subjects){
            foreach ($request->extra_subjects as $extraSubject){
                $invoiceExtraSubject = new InvoiceExtraSubject();
                $invoiceExtraSubject->invoice_id = $invoice->id;
                $this->saveInvoiceExtraSubject($invoiceExtraSubject, $extraSubject);
            }
        }

        //save invoice parts
        if($request->parts){
            foreach ($request->parts as $part){
                $invoicePart = new InvoicePart();
                $invoicePart->invoice_id = $invoice->id;
                $this->saveInvoicePart($invoicePart, $part);
            }
        }

        //calculate totals
        $result = (new InvoiceCalculator())->calculateTotals($invoice);
        $this->saveInvoice($invoice, [
                                "total_price_subjects" => $result["subjectsTotalPrice"],
                                "total_price_extra_subjects" => $result["extraSubjectsTotalPrice"],
                                "total_price_parts" => $result["partsTotalPrice"],
                                "total_amount" => $result["totalAmount"],
                                "tax" => $result['tax'],
                                "discount" => $result['discountInvoice'],
                                "wallets" => $result['result_wallets']
                            ]);
        return true;
    }

    public function saveInvoiceSubject(InvoiceSubject $invoiceSubject, $data){
        $invoiceSubject->count = $data["count"] ?? $invoiceSubject->count;
        $invoiceSubject->price = $data["price"] ?? $invoiceSubject->price;
        $invoiceSubject->save();
    }

    public function saveInvoiceExtraSubject(InvoiceExtraSubject $invoiceExtraSubject, $data){
        $invoiceExtraSubject->name = $data["name"] ?? $invoiceExtraSubject->name;
        $invoiceExtraSubject->count = $data["count"] ?? $invoiceExtraSubject->count;
        $invoiceExtraSubject->price = $data["price"] ?? $invoiceExtraSubject->price;
        $invoiceExtraSubject->save();
    }

    public function saveInvoicePart(InvoicePart $invoicePart, $data){
        $invoicePart->name = $data["name"] ?? $invoicePart->name;
        $invoicePart->count = $data["count"] ?? $invoicePart->count;
        $invoicePart->price = $data["price"] ?? $invoicePart->price;
        $invoicePart->save();
    }

    protected function saveInvoice(&$invoice, $data){
        $invoice->order_id = $data["order_id"] ?? $invoice->order_id;
        $invoice->total_price_subjects = $data["total_price_subjects"] ?? $invoice->total_price_subjects ?? 0;
        $invoice->tax = $data["tax"] ?? $invoice->tax ?? 0;
        $invoice->total_price_extra_subjects = $data["total_price_extra_subjects"] ?? $invoice->total_price_extra_subjects ?? 0;
        $invoice->total_price_parts = $data["total_price_parts"] ?? $invoice->total_price_parts ?? 0;
        $invoice->total_amount = $data["total_amount"] ?? $invoice->total_amount ?? 0;
        $invoice->accepting = $data["accepting"] ?? $invoice->accepting ?? -1;
        $invoice->discount = $data['discount'] ?? $invoice->discount ?? 0;
        $invoice->wallets = $data['wallets'] ?? $invoice->wallets ?? 0;
        $invoice->save();
    }


    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function confirm($orderId, $userId){
        $order = $this->getByOrderAndUser($orderId, $userId)->order;
        $invoice = $order->invoice;
        if($order->status == 3 && $invoice->accepting == -1){
            $invoice->accepting = 1;
            $invoice->save();
            $userNotification = new UserNotification($order->acceptOrder()->teacher->id,"confirm_invoice");
            $userNotification->setTitleArgs(["orderId" => $order->id]);
            $userNotification->setBodyArgs(["orderId" => $order->id]);
            $userNotification->setTypeId($order->id);
            $userNotification->setStatus(2);
            $userNotification->send();
        }
        else
            throw new \Exception("the order has been finished", 400);
    }
}
