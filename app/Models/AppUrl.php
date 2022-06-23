<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUrl extends Model
{
    use HasFactory;
    protected $table = "app_urls";
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
