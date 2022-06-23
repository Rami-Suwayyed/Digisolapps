<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class InstructionsAndLaws extends Model
{
    use HasFactory;
    protected $table = "instructions_and_laws";
    protected $fillable = ["name_ar", "name_en"];
    protected $hidden = ["name_ar", "name_en"];
    protected $appends = ["name"];

    public function getNameAttribute(){
        return $this->{"name_" . App::getLocale()};
    }
}
