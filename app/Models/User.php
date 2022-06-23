<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\SelectBuilder\Builders\User as UserSelectBuilder;

class User extends Authenticatable implements IMedia
{
    use  HasFactory, Notifiable, HasApiTokens, MediaInitialization;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'full_name',
        'email',
        'type',
        'description',
        "gender",
        "birth_date",
        "longitude",
        "latitude",
        'device_token'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const IMAGE_PATH = "users";
    public static function selectBuilder(): UserSelectBuilder
    {return new UserSelectBuilder();}

    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }

    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "profile_photo", DS);
    }

    // Relations
//    public function attachments(){
//        return $this->hasOne(UserAttachments::class);
//    }

    public function locations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserLocation::class);
    }

    public function Livelocations()
    {
        return $this->hasOne(UserLiveLocation::class);
    }

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class,"user_id","id");
    }

    public function commissionTeacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CommissionTeacher::class, "teacher_id");
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function authAccessToken(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function rating(){
        return $this->hasMany(Rating::class, "teacher_id");
    }

    public function code(){
        return $this->hasOne(SharingCode::class, "user_id");
    }

    public function specialty(){
        return $this->hasMany(MainSpecialty::class, "teacher_id");
    }

    public function getAllNameCategory(){
        $name = "";
        foreach ($this->specialty as $index => $item) {
            if($index == 0)
                $name .= $item->category->name;
            else
                $name .= " - " . $item->category->name;
        }
        return $name;
    }

    public function getPhoneNumber(): array
    {
        $data = [];
        if($this->type == "t"){
            if($this->teacher->phone_number1)
                $data[] = $this->teacher->phone_number_1;
            if($this->teacher->phone_number_2)
                $data[] = $this->teacher->phone_number_2;
        }else{
            $data[] = $this->student->phone_number;
        }
        return $data;
    }



    //Appends
    public function getGenderNameAttribute(){
        if ($this->gender === 1)
            return __("Male");
        else if($this->gender === 2)
            return __("Female");
        else
            return __("Unknown");

    }


    public function getTypesRelatedItAsArray(){
        $types = $this->specialty;
        $arr = [];
        if($types->isNotEmpty()){
            foreach ($types as $type){
                $arr[] = $type->main_categories_id;
            }
        }
        return $arr;
    }

    // Profiles
    public function subject(){
        return$this->hasMany(SubjectsTeachers::class,'subject_id');
    }


}
