<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaGroups;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KadyTechSocialMedia extends Model implements IMedia
{
    use HasFactory, MediaInitialization;

    protected $table = "kadytech_social_media";
    protected $fillable = ["type", "url"];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    const IMAGE_PATH = "KadyTechSocialMedia";


    public function setMainDirectoryPath(): string
    {
        return self::IMAGE_PATH;
    }
}
