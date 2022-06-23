<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationRepository
{
    public function saveNotification($collection){

    }

    public function deleteNotification(){
        return Notification::where(DB::raw('DATE_ADD(created_at, INTERVAL 1 DAY)'), "<=", now())->get();
    }
}
