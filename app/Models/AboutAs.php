<?php

namespace App\Models;

use App\Helpers\Media\Src\IMedia;
use App\Helpers\Media\Src\MediaInitialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AboutAs extends Model implements IMedia
{
    use HasFactory, MediaInitialization;
    protected $table="about_us";
    protected $fillable = ["about_en", "about_ar"];
    protected $hidden = ["about_en", "about_ar"];
    protected $appends = ["about"];
    public static bool $withAppends = true;
    public static array $appendsActive = [];

    public function getNameAttribute(){
        return $this->{"about_" . App::getLocale()};
    }

    public function setMainDirectoryPath(): string
    {
        return "about_us";
    }
}
