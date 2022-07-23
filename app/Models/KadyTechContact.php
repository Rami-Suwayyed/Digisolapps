<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KadyTechContact extends Model
{
    use HasFactory;

    protected $table='kadytech_contact';
    protected $fillable = [
        'full_name','company_name', 'email',"phone_number",'WebSite_URL',"social_media_account"
    ];
}
