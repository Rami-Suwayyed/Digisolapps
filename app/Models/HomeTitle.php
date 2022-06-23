<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class HomeTitle extends Model
{
    use HasFactory;
    protected $table = "home_title";

    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }
}
