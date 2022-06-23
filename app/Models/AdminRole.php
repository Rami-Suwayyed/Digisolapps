<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AdminRole extends Model
{
    use HasFactory;
    protected $fillable = ["id","name_en", "name_ar"];
    protected $table = "admin_roles";

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, "admin_role_permission", "role_id", "permission_id");
    }

    public function admins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Admin::class, "role_id", "id");
    }

    //Attributes
    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }
}
