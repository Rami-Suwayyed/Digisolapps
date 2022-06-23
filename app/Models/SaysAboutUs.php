<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SaysAboutUs extends Model
{
    use HasFactory;
    protected $table = "website_about_us";

    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }
}
