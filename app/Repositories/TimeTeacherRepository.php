<?php

namespace App\Repositories;

use App\Models\Timer;
use Illuminate\Support\Facades\DB;

class TimeTeacherRepository
{
    public function saveNotification($collection){

    }

    public function deleteTimeTeacher(){
        return Timer::where('created_at'-1,'>', now())->get();
    }
}
