<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;
    protected $fillable = ["time_pay","Time_accept","whatsapp","SoS_whatsapp","SoS_Phone"];
}
