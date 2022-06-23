<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;

class HomeTestimonial extends Model implements IMedia
{
    use HasFactory , MediaInitialization;

    const IMAGE_PATH = "SocialMedia";



    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }
}
