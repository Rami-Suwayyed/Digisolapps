<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AppsPage extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    const IMAGE_PATH = "digisol/apps";

    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }

    public function setGroups(): MediaGroups
    {
        return (new MediaGroups())->setGroup("single", "icon", DS)->setGroup("single", "phone", DS)->setGroup("single", "background", DS);
    }



    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }

    public function Category(){
        return $this->belongsTo(CategoryApps::class, "category_id");
    }

}
