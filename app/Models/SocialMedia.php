<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    const IMAGE_PATH = "SocialMedia";


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }
}
