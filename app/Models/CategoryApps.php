<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CategoryApps extends Model
{
    use HasFactory;

    protected $fillable = ["name_en", "name_ar", "order", "status"];
    //Attributes

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }

    public function setMainDirectoryPath(): string
    {
        return "main_categories";
    }
}
