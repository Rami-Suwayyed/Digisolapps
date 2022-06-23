<?php

namespace App\Repositories\Traits\Order;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\Notification\Types\AdminNotification;
use App\Helpers\Notifications\Types\UserNotification;
use App\Models\CanceledOrder;
use App\Models\ClassLink;
use App\Models\Order;
use App\Models\OrderStaffAccept;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait ForTeachers
{
    /**
     * @throws Exception
     */
    public function teacherRequestOrdersQuery($userId, $status){
        if(!in_array($status, [-1, 'all']))
            throw new Exception("un-access");
        $query = User::find($userId)->teacher->requestOrders();
        if($status != 'all')
            if (is_array($status))
                $query->where(function ($q) use ($status) {
                    foreach ($status as $val)
                        $q->orWhere("status", $val);
                });
            else
                $query->where("status", $status);
//            dd($query);
        return $query;
    }

    /**
     * @throws Exception
     */
    public function orderAcceptQuery($user, $status = 'all'){
        if(!in_array($status, [1, [1,2], 2, 3, 0,'all']))
            throw new Exception("un-access");

        $query = $user->teacher->acceptOrders();
        if($status !== 'all')
            if (is_array($status))
                $query->where(function ($q) use ($status) {
                    foreach ($status as $val)
                        $q->orWhere("status", $val);
                });
            else
                $query->where("status", $status);
        return $query;
    }

    public function orderCanselQuery($status = 'all'){
        if($status != 0)
            throw new Exception("un-access");

        $query = new Order();

        return $query;
    }

    /**
     * @throws Exception
     */
    public function getAllTeacherAcceptOrders($userId, $status = 'all'){
        $user = User::findOrFail($userId);
        if($status === 0) {
            $query = $this->orderCanselQuery($status);
            return $this->teacherCanceledOrder($query);
        }else{
            $query = $this->orderAcceptQuery($user, $status);
        }
        return $query->get();
    }

    public function teacherCanceledOrder($query): array
    {
        $beforeQuery = clone $query;
        $afterQuery = clone $query;
        $before = $beforeQuery->join("canceled_orders", function($q){
            $q->on('canceled_orders.order_id','orders.id');
            $q->on('canceled_orders.type',DB::raw(1));
        })->where("canceled_orders.teacher_id", Auth::user()->id)->get(["orders.*"]);
        $after = $afterQuery->join("canceled_orders", function($q){
            $q->on('canceled_orders.order_id','orders.id');
            $q->on('canceled_orders.type',DB::raw(2));
        })->where("canceled_orders.teacher_id", Auth::user()->id)->get(["orders.*"]);
        return ["after" => $after, "before" => $before];
    }

    /**
     * @throws Exception
     */
    public function getTeacherAcceptOrderById($orderId, $userId, $status = 'all'){
        $user = User::findOrFail($userId);
//        dd($status, $orderId);
        return $this->orderAcceptQuery($user, $status)->where('orders.id', $orderId)->firstOrFail();
    }


    /**
     * @throws Exception
     */
    public function getAllTeacherRequestOrders($userId, $status = 'all')
    {
        $query = $this->teacherRequestOrdersQuery($userId, $status);
        return $this->teacherRequestOrders($query);
    }

    public function teacherRequestOrders($query): array
    {
        $activeQuery = clone $query;
        $inActiveQuery = clone $query;

        $active = $activeQuery->where(DB::raw("CONCAT(orders.order_day, ' ', orders.order_time_to)") , ">", now())->get();
        $inActive = $inActiveQuery->where(DB::raw("CONCAT(orders.order_day, ' ', orders.order_time_to)") , "<", now())->get();

        return ["active" => $active, "in_active" => $inActive];
    }

    /**
     * @throws Exception
     */
    public function getTeacherRequestOrderById($orderId, $userId, $status = 'all')
    {
        return $this->teacherRequestOrdersQuery($userId, $status)->where('orders.id', $orderId)->first();
    }


    /**
     * @throws Exception
     */
    public function geAllTeacherUnderGuaranteeOrders($userId){
        $user = User::findOrFail($userId);
        return $this->orderAcceptQuery($user, 3)->join('order_subjects', function ($q){
            $q->on('order_subjects.order_id', '=', 'orders.id');
            $q->on('order_subjects.guarantee_days', '!=', DB::raw(0));
            $q->on('order_subjects.guarantee_days', '>', DB::raw('TIMESTAMPDIFF(DAY, orders.finish_at, NOW())'));
        })->groupBy("orders.id")->get(['orders.*', DB::raw('COUNT(`order_subjects`.`id`) as subject_guarantee_count')]);
    }

    /**
     * @throws Exception
     */
    public function getTeacherUnderGuaranteeOrderById($orderId, $userId){
        $user = User::findOrFail($userId);
        return $this->orderAcceptQuery($user, 3)->join('order_subjects', function ($q){
            $q->on('order_subjects.order_id', '=', 'orders.id');
            $q->on('order_subjects.guarantee_days', '!=', DB::raw(0));
            $q->on('order_subjects.guarantee_days', '>', DB::raw('TIMESTAMPDIFF(DAY, orders.finish_at, NOW())'));
        })->groupBy("orders.id")->where('orders.id', $orderId)->first(['orders.*', DB::raw('COUNT(`order_subjects`.`id`) as subject_guarantee_count')]);
    }



    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function action(Request $request, $teacherId, $orderId){
        switch ($request->action){
            case "accept":
                $this->acceptOrder($request->id, $teacherId);
                break;
            case "start":
                $this->startOrder($orderId, $teacherId, $request);
                break;
            case "cancel":
                $this->cancelOrder($orderId, $teacherId, $request);
                break;
            case "convert":
                $this->convertTo($orderId, $teacherId, $request->staff_id);
                break;
            default:
                throw new Exception("action undefined",404);
                break;
        }
    }


    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function acceptOrder($orderId, $userId ){
        $order = $this->getTeacherRequestOrderById($orderId, $userId, -1);
        $user = User::findOrFail($userId);
        if($order){
            (new \App\Services\OrderService())->assign($order, $user);

        }else{
            throw new Exception("un-access",406);
        }
    }


    /**
     * @throws Exception
     */
    public function startOrder($orderId, $userId, Request $request){
        $user = User::findOrFail($userId);
        $order = $this->getTeacherAcceptOrderById($orderId, $user->id, 1);
        if(date('Y-m-d H:i:s', strtotime('-2 hours', strtotime( $order->order_day . " " . $order->order_time_from))) > date("Y-m-d H:i:s")){
//            throw new Exception("Class can just start 1 hour before", 403);
            throw new Exception("The class cannot start before the class start time", 403);

        }
        if($order){
            if ( $order->ues_online == 1){
                $class_link = new ClassLink();
                $class_link->link = $request->link ??"https://us05web.zoom.us/j/3640784498?pwd=TnpPcHJzRGtQVytQS3ExZUVremM2QT09";
                $class_link->id_order =$order->id;
                $class_link->save();
            }
            $order->status = 2;
            $save = $order->save();
            if($save == true) {
                $device_token = User::where('id', $order->user_id)->first()->device_token;
                $notification = new UserNotification($order->user_id, $device_token, "start_Order");
                $notification->send();
                $Adminnotification = new AdminNotification("start_Order");
                $Adminnotification->setBodyArgs(["orderId" => $order->id])
                    ->setTitleArgs(["orderId" => $order->id, "teacher" => $order->userT->full_name])->setUrl(route("admin.orders.show", ["id" => $order->id]));
                $Adminnotification->send();
            }
        }else{
            throw new Exception("un-access",406);
        }
    }


    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function cancelOrder($orderId, $userId, Request $request)
    {
        $order = Order::where([['id', $orderId], ['teacher_id', $userId]])->first();
        if ($order) {
            if ($order->status == 0) {
                throw new \Exception("you can't is order has been cancel", 400);
            }else{
                if ($order->status != 3){
                    $order->status = 0;
                    $save = $order->save();
                    $CanceledOrder = new CanceledOrder();
                    $CanceledOrder->type = 2;
                    $CanceledOrder->order_id = $orderId;
                    $CanceledOrder->teacher_id = $order->teacher_id;
                    $CanceledOrder->student_id = $order->user_id;
                    $CanceledOrder->reason = $request->reason;
                    $CanceledOrder->save();
                    if($save == true) {
                        $device_token = User::where('id', $order->user_id)->first()->device_token;
                        $notification = new UserNotification($order->user_id, $device_token, "cancel_Order");
                        $notification->send();
                        $Adminnotification = new AdminNotification("cancel_Order");
                        $Adminnotification->setBodyArgs(["orderId" => $order->id])
                            ->setTitleArgs(["orderId" => $order->id, "teacher" => $order->user->full_name])->setUrl(route("admin.orders.show", ["id" => $order->id]));
                        $Adminnotification->send();
                    }
                }else{
                    throw new \Exception("you can't cancel order", 400);
                }
            }
        }
    }

//        $user = User::findOrFail($userId);
//        $order = $this->getTeacherAcceptOrderById($orderId, $user->id, [1, 2]);
//        if($order){
////            if($order->status === 1)
////                (new \App\Services\OrderService())->cancelBeforeReview($order, $user, $request->reason_letter);
////            else
////          (new \App\Services\OrderService())->cancelAfterReview($order, $user,  $request->review_price);
//
//        }else{
//            throw new Exception("un-access",406);
//        }
//    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function convertTo($orderId, $userId , $staffId){
        $staff = Teacher::where(["user_id" => $staffId, "leader_id" => Auth::user()->teacher->id, "type" => "ts"])->firstOrFail()->user;
        $order = $this->getTeacherRequestOrderById($orderId, $userId, -1);
        if($order){
            (new \App\Services\OrderService())->assign($order, $staff);
            $notification = new UserNotification($staff->id, "convert");
            $notification->setBodyArgs(["teacher" => Auth::user()->full_name, "orderId" => $orderId]);
            $notification->send();
        }else{
            throw new Exception("un-access",406);
        }
    }

//    /**
//     * @throws \Exception
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function finishOrder($orderId, $userId){
//        $user = User::findOrFail($userId);
//        $order = $this->getTeacherAcceptOrderById($orderId, $user->id, 2);
//        if($order){
//            (new \App\Subjects\OrderSubject())->finish($order);
//        }else{
//            throw new \Exception("un-access",406);
//        }
//    }
}
