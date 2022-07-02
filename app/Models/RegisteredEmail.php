<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredEmail extends Model
{
    protected $fillable = ["media_type", "type_id", "full_name", "username", "Password", "email", "status"];
    use HasFactory;
}
