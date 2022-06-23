<?php

namespace App\Calculators;

use App\Helpers\Notifications\Types\UserNotification;
use App\Models\Invoice\Invoice;
use App\Models\SharingCode;
use App\Models\TeacherWallet;
use App\Models\UsePromocodeUser;
use App\Repositories\CommissionRepository;
use Illuminate\Support\Facades\Auth;

class InvoiceCalculator
{

    public function calculateTotals(Invoice $invoice): array
    {
        $servicesTotalPrice = 0;
        $extraServicesTotalPrice = 0;
        $partsTotalPrice = 0;
        $totalAmount = 0;

        foreach ($invoice->services as $service){
            $servicesTotalPrice += $service->price * $service->count;
            $totalAmount += $service->price * $service->count;
        }

        foreach ($invoice->extraServices as $extraService){
            $extraServicesTotalPrice += $extraService->price * $extraService->count;
            $totalAmount += $extraService->price * $extraService->count;
        }

        $commission = (new CommissionRepository())->getForPriceTeacher($totalAmount);
        if(empty($commission)) {
            $commission = (new CommissionRepository())->getForPrice($totalAmount);
        }
        if($commission){
            $transaction_wallet = new TeacherWallet();
            $transaction_wallet->type_wallet_id = 5;
            $transaction_wallet->in_out = -1;
            $transaction_wallet->value = $totalAmount * ($commission->commission / 100);
            $transaction_wallet->type_id = $invoice->order->id;
            $transaction_wallet->user_id = Auth::user()->id;
            $transaction_wallet->balance = $this->getTotallInWallet(Auth::user()->id) - $totalAmount * ($commission->commission / 100);

            $transaction_wallet->save();
        }

        foreach ($invoice->parts as $part){
            $partsTotalPrice += $part->price * $part->count;
            $totalAmount += $part->price * $part->count;
        }

        $result = [
            "servicesTotalPrice" => $servicesTotalPrice,
            "extraServicesTotalPrice" => $extraServicesTotalPrice,
            "partsTotalPrice" => $partsTotalPrice,
            "totalAmount" => $totalAmount ,
        ];


        $discountInvoice = 0;
        $promocode = $invoice->order->promocode;
        if($promocode){
            $UsePromoCode = UsePromocodeUser::where("user_id" , $invoice->order->user_id)->where("promocode_id" , $invoice->order->promocode_id)->first();
            if(!$UsePromoCode){
                $UsePromoCodeUser = new UsePromocodeUser();
                $UsePromoCodeUser->user_id = $invoice->order->user_id;
                $UsePromoCodeUser->promocode_id = $promocode->id;
                $UsePromoCodeUser->user_uses_number = 1;
                $UsePromoCodeUser->save();
                if($promocode->type == "percentage"){
                    $discount = 100 - $promocode->value;
                    $discountInvoice = $totalAmount * ($promocode->value / 100);
                    if($discountInvoice > $promocode->max_discount) {
                        $totalAmount = $totalAmount - $promocode->max_discount;
                        $discountInvoice = $promocode->max_discount;
                    }else {
                        $discountInvoice =  $totalAmount * ($promocode->value / 100);
                        $totalAmount = $totalAmount * ($discount / 100);
                    }
                }else{
                    if($totalAmount < $promocode->value) {
                        $UsePromoCodeUser->value_uses = $totalAmount;
                        $UsePromoCodeUser->save();
                        $discountInvoice = $totalAmount;
                        $totalAmount = 0;
                    }else{
                        $UsePromoCodeUser->value_uses = $promocode->value;
                        $UsePromoCodeUser->save();
                        $discountInvoice = $promocode->value;
                        $totalAmount = $totalAmount - $promocode->value;
                    }
                }
            }else{
                if($promocode->type == "percentage"){
                    $UsePromoCode->user_uses_number = $UsePromoCode->user_uses_number + 1;
                    $UsePromoCode->save();
                    $discount = 100 - $promocode->value;
                    $discountInvoice = $totalAmount * ($promocode->value / 100);
                    if($discountInvoice > $promocode->max_discount) {
                        $totalAmount = $totalAmount - $promocode->max_discount;
                        $discountInvoice = $promocode->max_discount;
                    }else {
                        $discountInvoice =  $totalAmount * ($promocode->value / 100);
                        $totalAmount = $totalAmount * ($discount / 100);
                    }
                }else{
                    if($totalAmount < $promocode->value) {
                        $UsePromoCode->user_uses_number = $UsePromoCode->user_uses_number + 1;
                        $UsePromoCode->value_uses = $UsePromoCode->value + $totalAmount;
                        $UsePromoCode->save();
                        $discountInvoice = $totalAmount;
                        $totalAmount = 0;
                    }else{
                        $UsePromoCode->user_uses_number = $UsePromoCode->user_uses_number + 1;
                        $UsePromoCode->value_uses = $promocode->value;
                        $UsePromoCode->save();
                        $discountInvoice = $promocode->value;
                        $totalAmount = $totalAmount - $promocode->value;
                    }
                }
            }
            $transaction_wallet = new TeacherWallet();
            $transaction_wallet->type_wallet_id = 6;
            $transaction_wallet->in_out = 1;
            $transaction_wallet->value = $discountInvoice;
            $transaction_wallet->type_id = $invoice->order->id;
            $transaction_wallet->user_id = Auth::user()->id;
            $transaction_wallet->balance = $this->getTotallInWallet(Auth::user()->id) + $discountInvoice;
            $transaction_wallet->save();
        }

        $result_wallets = 0;
        if ($invoice->order->use_wallet == 1){
            $transaction_wallets = TeacherWallet::where("user_id" , $invoice->order->user_id)->get();
            $wallets_user_in_out=0;
            if($transaction_wallets->isNotEmpty()) {
                foreach ($transaction_wallets as $wallets) {
                    $wallets_user_in_out += ($wallets->value) * ($wallets->in_out);
                }

                if ($wallets_user_in_out > 0) {

                    if ($wallets_user_in_out >= $totalAmount) {
                        $result_wallets = $totalAmount;
                    } elseif ($wallets_user_in_out < $totalAmount) {
                        $result_wallets = $wallets_user_in_out;
                    }

                    $transaction_wallet = new TeacherWallet();
                    $transaction_wallet->type_wallet_id = 2;
                    $transaction_wallet->in_out = -1;
                    $transaction_wallet->value = $result_wallets;
                    $transaction_wallet->type_id = $invoice->order->id;
                    $transaction_wallet->user_id = $invoice->order->user_id;
                    $transaction_wallets->balance = $this->getTotallInWallet($invoice->order->user_id) - $result_wallets;
                    $transaction_wallet->save();

                    $transaction_walletsTeacher = new TeacherWallet();
                    $transaction_walletsTeacher->type_wallet_id = 2;
                    $transaction_walletsTeacher->in_out = 1;
                    $transaction_walletsTeacher->value = $result_wallets;
                    $transaction_walletsTeacher->type_id = $invoice->order->id;
                    $transaction_walletsTeacher->user_id = Auth::user()->id;
                    $transaction_walletsTeacher->balance = $this->getTotallInWallet(Auth::user()->id) + $result_wallets;
                    $transaction_walletsTeacher->save();

                    $user = Auth::user();
                    $userNotification = new UserNotification($invoice->order->user_id, $invoice->order->user->device_token, "transaction_wallet");
                    $userNotification->setBodyArgs(["orderId" => $invoice->order->id]);
                    $userNotification->send();

                    $userNotification = new UserNotification($user->id, $user->device_token ,"teacher.transaction_wallet");
                    $userNotification->setBodyArgs(["orderId" => $invoice->order->id, "value" => $result_wallets]);
                    $userNotification->send();

                    $userNotification = new UserNotification($user->id,  $user->device_token,"invoice_wallet");
                    $userNotification->setTitleArgs(["orderId" => $invoice->order->id]);
                    $userNotification->setBodyArgs(["orderId" => $invoice->order->id, "total" => $result_wallets, "value" => $totalAmount - $result_wallets]);
                    $userNotification->send();
                }
            }
        }

        $sharig_code = SharingCode::where("user_id", $invoice->order->user_id)->first();
        if($sharig_code){
            if($sharig_code->first_order == 0 && $sharig_code->inviting_user_id != Null){
                $users = [$invoice->order->user_id, $sharig_code->inviting_user_id];
                $device = [$invoice->order->user->device_token,$sharig_code->user->device_token];
                $sharig_code->first_order = 1;
                $sharig_code->save();
                for ($i = 0; $i < count($users); $i++){
                    $transaction = new TeacherWallet();
                    $transaction->type_wallet_id = 1;
                    $transaction->in_out = 1;
                    $transaction->value = 5000;
                    $transaction->user_id = $users[$i];
                    $transaction->type_id = $invoice->order->id;
                    $transaction->balance = $this->getTotallInWallet($users[$i]) + 5000;
                    $transaction->save();
                    $notification = new UserNotification($users[$i],$device[$i],"sharing_code");
                    $notification->setBodyArgs(["code" => $sharig_code->code]);
                    $notification->send();
                }
            }
        }

        if($commission){
            $result["tax"] = $commission->commission;
            $result["totalAmount"] = $totalAmount - $result_wallets;
            $result["discountInvoice"] = $discountInvoice;
            $result["result_wallets"] = $result_wallets;
//            $result["totalAmount"] = $totalAmount + ($totalAmount * ($commission->commission / 100));
        }
        return $result;
    }


    public function getTotallInWallet($userID){
        $wallets = TeacherWallet::where("user_id", $userID)->get();
        $wallets_user_in_out=0;
        if($wallets->isNotEmpty()){
            foreach ($wallets as $wallet) {
                $wallets_user_in_out += ($wallet->value) * ($wallet->in_out);
            }
        }
        return $wallets_user_in_out;
    }
}
