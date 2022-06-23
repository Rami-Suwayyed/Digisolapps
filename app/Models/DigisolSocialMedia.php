<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigisolSocialMedia extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    protected $table = "digisol_social_media";
    protected $fillable = ["type", "url"];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    const IMAGE_PATH = "DigisolSocialMedia";


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }
}
