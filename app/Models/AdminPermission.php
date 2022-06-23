<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AdminPermission extends Model
{
    use HasFactory;
    protected $fillable = ["name_en", "name_ar", "slug"];
    protected $table = "admin_permissions";

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, "admin_role_permission", "permission_id", "role_id");
    }

    //Attributes
    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }
}
