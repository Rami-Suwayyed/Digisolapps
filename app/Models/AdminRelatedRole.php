<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRelatedRole extends Model
{
    use HasFactory;
    protected $fillable = ["role_id", "admin_id"];
    protected $table = "admin_related_role";


}
