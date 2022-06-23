<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\SendEmail;
use Illuminate\Support\Facades\DB;

class SendEmailRepository
{
    public function index(){
        return SendEmail::where("status", 0)->get();
    }
}
