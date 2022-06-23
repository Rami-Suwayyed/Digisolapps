<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SendEmail extends Model
{
    use HasFactory;

    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }

    public function getBodyAttribute(){
        return $this->{"body_" . App::getLocale()};
    }
}
