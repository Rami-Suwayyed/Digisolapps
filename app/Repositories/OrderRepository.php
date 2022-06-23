<?php

namespace App\Repositories;
use App\Helpers\Notification\Types\UserNotification;
use App\Jobs\OrderTimeOut;
use App\Models\AvailableTimesTeacher;
use App\Models\CanceledOrder;
use App\Models\Order;
use App\Models\OrderTeacherRequest;
use App\Models\Settings;
use App\Repositories\Traits\Order\ForTeachers;
use App\Repositories\Traits\Order\ForUsers;
use App\Repositories\Traits\Order\Saving;
use App\Services\ZoneService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    use ForTeachers, Saving, ForUsers;
    /**
     * @throws \Exception
     */
    public function ordersStatusQuery(&$query, $status){
        if(is_array($status))
            $query->where(function($q) use ($status){
                foreach ($status as $val){
                    $q->orWhere("status", $val);
                }
            });
        else
            if($status !== "all")
                $query->where("status", $status);
    }


    /**
     * @throws \Exception
     */
    public function getAllOrders($status = "all", $activation = 'all'){
        $query = Order::query();
        $this->ordersStatusQuery($query, $status);
        if(inArray($activation, [0, 1]))
            $query->where("activation", $activation);
        return $query->orderBy('id','Desc')->get();
    }

    public function getAllOrdersUnder($status = "all"){
        $query = Order::query();
        $this->ordersStatusQuery($query, $status);
        return $query->join('order_subjects', function ($q){
            $q->on('order_subjects.order_id', '=', 'orders.id');
            $q->on('order_subjects.guarantee_days', '!=', DB::raw(0));
            $q->on('order_subjects.guarantee_days', '>', DB::raw('TIMESTAMPDIFF(DAY, orders.finish_at, NOW())'));
        })->groupBy("orders.id")->get(['orders.*', DB::raw('COUNT(`order_subjects`.`id`) as subject_guarantee_count')]);

    }
    /**
     * @throws \Exception
     */
    public function getOrderById($orderId, $status = "all"){
        $query = Order::where("id", $orderId);
        $this->ordersStatusQuery($query, $status);
        return $query->firstOrFail();
    }

    public function deleteAcceptedIsExist($order){
        if($order->getAccept())
            $order->getAccept()->delete();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function update($type, Request $request){
        switch ($type){
            case "teacher":
                $teacherRepository = new TeacherRepository();
                $user = $teacherRepository->getById($request->teacher)->user;
                $order = $this->getOrderById($request->id, [-1, 0, 1]);
                $this->deleteAcceptedIsExist($order);
                $order->activation = 1;
                $order->save();
                (new \App\Services\OrderService())->assign($order, $user);
            break;
            case "activation":
                $order = $this->getOrderById($request->id, [-1]);
                $order->activation = (int)$request->activation;
                $order->save();
            break;
        }
    }

    /**
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($orderId, $userId){
        $order = $this->getOneUserOrder($userId, $orderId, [-1, 1, 0]);
        $orderTime = strtotime($order->created_at);
        $orderTimeToStart = ($orderTime - time()) / 60;
        if($orderTimeToStart <= 15){
        foreach ($order->orderSubjects as $orderSubject){
            $notes = $orderSubject->notes;
            if($notes)
                $notes->removeAllFiles();
        }
        if($order->status == "1"){
            $user = $order->acceptOrder()->teacher;
            $userNotification = new UserNotification($user->id,"cancel_order_by_user");
            $userNotification->setBodyArgs(["orderId" => $order->id]);
            $userNotification->send();
        }
        $order->status = 0;
        $teacher_id=$order->teacher_id;
        $start_time=$order->order_time_from;
        $end_time=$order->order_time_to;
        $TimesTeachers=AvailableTimesTeacher::where([['teacher_id',$teacher_id],['start_time',$start_time],['end_time',$end_time]])->delete();
        $order->delete();
        }else{
            throw new \Exception("you can't cancel order", 400);
        }
    }

    public function UpdatePay($orderId, $userId){
        $order = $this->getOneUserOrder($userId, $orderId, 1);
            if($order->pay == "0"){
                $order->pay = 1;
                $order->save();
            }
    }


    public function cancelOrderStudent(int $orderId, $userId ,$reason)
    {
        $order = Order::where([['id', $orderId], ['user_id', $userId]])->first();
        if ($order) {
            if ($order->status == 0) {
                throw new \Exception("you can't is order has been cancel", 400);
            } else {
                if ($order->status != 3) {
                    $order->status = 0;
                    $order->save();
                    $CanceledOrder = new CanceledOrder();
                    $CanceledOrder->type = 1;
                    $CanceledOrder->order_id = $orderId;
                    $CanceledOrder->teacher_id = $order->teacher_id;
                    $CanceledOrder->student_id = $order->user_id;
                    $CanceledOrder->reason = $reason;
                    $CanceledOrder->save();
                } else {
                    throw new \Exception("you can't cancel order", 400);
                }
            }
        }
    }

}
