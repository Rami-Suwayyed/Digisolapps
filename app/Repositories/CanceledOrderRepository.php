<?php

namespace App\Repositories;

use App\Models\CanceledOrder;
use App\Models\CanceledOrderAfterReview;
use App\Models\CanceledOrderBeforeReview;
use App\Models\Order;
use App\Models\OrderSingleAccept;
use App\Models\OrderStaffAccept;
use App\Models\OrderTeacherRequest;
use App\Models\Settings;
use App\Services\ZoneService;
use Illuminate\Support\Facades\Auth;

class CanceledOrderRepository
{

    public function createCanceledOrder($orderId, $teacherId, $type = 1): CanceledOrder
    {
        $canceledOrder = new CanceledOrder();
        $canceledOrder->order_id = $orderId;
        $canceledOrder->teacher_id = $teacherId;
        $canceledOrder->type = $type;
        $canceledOrder->save();
        return $canceledOrder;
    }

    public function createCanceledOrderBeforeReview($canceledOrderId, $reasonLetter){
        $canceledOrderAfter = new CanceledOrderBeforeReview();
        $canceledOrderAfter->canceled_order_id = $canceledOrderId;
        $canceledOrderAfter->reason_letter = $reasonLetter;
        $canceledOrderAfter->save();
    }

    public function deleteAcceptOrderAfterCansel($order_id){
        $user = Auth::user();

        if($user->type == "t" && $user->teacher->type == "t")
            $acceptOrder = OrderSingleAccept::where("order_id" , $order_id)->first();
        else
            $acceptOrder = OrderStaffAccept::where("order_id" , $order_id)->first();
        $acceptOrder->delete();

        $this->resendOrder($order_id);
    }

    public function resendOrder($order_id){
        $orderRequests = OrderTeacherRequest::where("order_id", $order_id)->get();
        foreach ($orderRequests as $orderRequest) {
            $orderRequest->delete();
        }

        $order =  Order::find($order_id);
        $order->accept_type = Null;
        $order->save();

        $zone = (new ZoneRepository())->createZone($order->id);
        if(Settings::getInstance()->getGeneral()->is_order_publishing)
            (new ZoneService())->start($zone);
    }

    public function createCanceledOrderAfterReview($canceledOrderId, $reviewPrice){
        $canceledOrderBefore = new CanceledOrderAfterReview();
        $canceledOrderBefore->canceled_order_id = $canceledOrderId;
        $canceledOrderBefore->review_price = 10.000;
        $canceledOrderBefore->save();
    }
}
