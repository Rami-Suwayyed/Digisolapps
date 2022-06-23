<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigisolContact extends Model
{
    use HasFactory;

    protected $table='digisol_contact';
    protected $fillable = [
        'title_en',
        'title_ar',
        'text_en',
        "text_ar",
    ];
}
