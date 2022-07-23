<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KadyTechSetting extends Model
{
    use HasFactory;
    protected $table = "kadytech_settings";
    protected $fillable = ["whatsapp","SoS_whatsapp","SoS_Phone"];

}
