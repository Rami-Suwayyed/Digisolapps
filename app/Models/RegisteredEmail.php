<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class RegisteredEmail extends Model
{
    use HasFactory;
    protected $fillable = ["media_type", "type_id", "full_name", "username", "Password", "email", "status"];
    protected $table = "registereds";

}
