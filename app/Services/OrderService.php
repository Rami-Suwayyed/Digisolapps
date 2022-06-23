<?php

namespace App\Services;

use App\Helpers\Notification\Types\AdminNotification;
use App\Helpers\Notifications\Types\UserNotification;
use App\Models\CanceledOrder;
use App\Models\ClassLink;
use App\Models\GeneralSettings;
use App\Models\OrderSingleAccept;
use App\Models\OrderStaffAccept;
use App\Models\User;
use App\Repositories\CanceledOrderRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Support\Carbon;

class OrderService
{
    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function assign($order, $user){
        $time_pay = GeneralSettings::first()->time_pay;
        $finishTime = (Carbon::parse($time_pay)->format('H')*60)+(Carbon::parse($time_pay)->format('i'));
        switch ($user->teacher->type){
           case "t":
               $order->accept_type = "si";
               $orderAccept = new OrderSingleAccept();
               $orderAccept->teacher_id = $user->id;
           break;
           case "ts":
               $order->accept_type = "st";
               $orderAccept = new OrderStaffAccept();
               $orderAccept->teacher_id = $user->id;
               $orderAccept->leader_id = $user->teacher->leader->user->id;
           break;
           case "s":
               $order->accept_type = "st";
               $orderAccept = new OrderStaffAccept();
               $orderAccept->teacher_id = $user->id;
               $orderAccept->leader_id = $user->id;
           break;
           default:
               throw new \Exception("un-access", 401);
           break;
       }

       $orderAccept->order_id = $order->id;
       $orderAccept->save();
       $order->status = 1;
       $order->finish_at = Carbon::now()->addMinute($finishTime);
        $save = $order->save();
        if($save == true) {
            $device_token = User::where('id', $order->user_id)->first()->device_token;
            $notification = new UserNotification($order->user_id, $device_token, "accept_order");
            $notification->send();
            $Adminnotification = new AdminNotification("accept_order");
            $Adminnotification->setBodyArgs(["orderId" => $order->id])
                ->setTitleArgs(["orderId" => $order->id, "teacher" => $order->user->full_name])->setUrl(route("admin.orders.show", ["id" => $order->id]));
            $Adminnotification->send();
        }


//       //Remove Zone If Exists
//        $zoneRepository = new ZoneRepository();
//        $zone = $zoneRepository->getZoneByOrderId($order->id);
//        if($zone)
//            $zoneRepository->deleteZone($zone);

//       //Send Notification
//       $notification = new UserNotification($order->user_id, "accept_order");
//       $notification->setBodyArgs(["teacher" => $user->full_name]);
//       $notification->send();
    }


    protected function cancelOrder($order, $user, int $type): CanceledOrder
    {
        $order->status = 0;
        if($type == 1)
            $order->status = 0;
        $order->save();
        return (new CanceledOrderRepository())->createCanceledOrder($order->id, $user->id, $type);
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelBeforeReview($order, $user, $reasonLetter){
        $orderTime = strtotime($order->order_day . " " . $order->order_time_from);
        $orderTimeToStart = ($orderTime - time()) / 60 / 60;
        if($orderTimeToStart >= 2){
            $canceledOrder = $this->cancelOrder($order, $user, 1);
            (new CanceledOrderRepository())->createCanceledOrderBeforeReview($canceledOrder->id, $reasonLetter);
            (new CanceledOrderRepository())->deleteAcceptOrderAfterCansel($order->id);

            $adminNotification = new AdminNotification("admin.cancel_order");
            $adminNotification->setBodyArgs(["teacher" => $user->full_name, "orderId" => $order->id]);
            $adminNotification->setTitleArgs(["orderId" => $order->id]);
            $adminNotification->setUrl(route("admin.orders.show", ["id" => $order->id]));
            $adminNotification->send();

            $userNotification = new UserNotification($order->user->id,"cancel_order");
            $userNotification->setTitleArgs(["orderId" => $order->id]);
            $userNotification->setBodyArgs(["orderId" => $order->id]);
            $userNotification->send();
        }else{
            throw new \Exception("you can't cancel order",406);
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelAfterReview($order, $user, $reviewPrice){
        $canceledOrder = $this->cancelOrder($order, $user, 2);
        (new CanceledOrderRepository())->createCanceledOrderAfterReview($canceledOrder->id, $reviewPrice);

        $adminNotification = new AdminNotification("admin.cancel_order");
        $adminNotification->setBodyArgs(["teacher" => $user->full_name, "orderId" => $order->id]);
        $adminNotification->setTitleArgs(["orderId" => $order->id]);
        $adminNotification->setUrl(route("admin.orders.show", ["id" => $order->id]));
        $adminNotification->send();

        $userNotification = new UserNotification($order->user->id,"cancel_order");
        $userNotification->setTitleArgs(["orderId" => $order->id]);
        $userNotification->setBodyArgs(["orderId" => $order->id]);
        $userNotification->send();
    }

    public function finish($order){
        $order->finish_at = getMySqlTimeStamp();
        $order->status = 3;
        $order->save();
    }
}
