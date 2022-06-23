<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class DigisolApp extends Model
{
    use HasFactory;
    protected $table = "digisol_apps";


    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function getDescriptionAttribute(){
        return $this->{"description_" . App::getLocale()};
    }
}
