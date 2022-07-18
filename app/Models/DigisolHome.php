<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class DigisolHome extends Model implements IMedia
{
    use HasFactory, MediaInitialization;
    protected $table = "digisol_home";

    const IMAGE_PATH = "SocialMedia";


    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }
}
