<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaDefaultPhotos;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable implements IMedia , MustVerifyEmail
{
    use HasFactory, MediaInitialization, MediaDefaultPhotos ,Notifiable;
    protected $fillable = ["full_name", "username", "email", "is_super_admin" ,"is_email_verified","device_token"];
    protected $table = "admins";

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ["profile_photo"];
    protected $collectionsRelated;

    /**
     * @throws \Exception
     */
    public function getProfilePhotoAttribute(){
        return $this->getFirstMediaFile() ? $this->getFirstMediaFile()->url : self::defaultUserPhoto();
    }

    public function setMainDirectoryPath(): string
    {
        return "managers";
    }

    public function setPasswordAttribute($value){
        $this->attributes["password"] = Hash::make($value);
    }

    public function adminNotification(): array
    {
        if(!isset($this->collectionsRelated["adminNotifications"]))
            $this->collectionsRelated["adminNotifications"] = ["all" => AdminNotification::orderBy("created_at", "desc")->get(),
                                                                "unOpenCount" => AdminNotification::where("is_open", 0)->count()];
        return $this->collectionsRelated["adminNotifications"];
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(AdminRole::class, AdminRelatedRole::class, "admin_id", "id", "id", "role_id");
    }

    public function relatedRole(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdminRelatedRole::class);
    }


    public function isAdministrator(){
        return $this->is_super_admin;
    }


}
