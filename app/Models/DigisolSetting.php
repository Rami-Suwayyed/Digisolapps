<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigisolSetting extends Model
{
    use HasFactory;
    protected $fillable = ["whatsapp","SoS_whatsapp","SoS_Phone"];

}
