<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\RegisteredEmail;
use Illuminate\Support\Facades\DB;

class RegisteredRepository
{
    public function index(){
        return RegisteredEmail::where("status", 0)->get();
    }
}
