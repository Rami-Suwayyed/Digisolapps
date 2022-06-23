<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AdminNotification extends Model
{
    use HasFactory;

    public function getCreatedAttribute(){
        return $this->{$this->getCreatedAtColumn()}->diffForHumans();
    }

    public function getTitleAttribute(){
        return $this->{"title_" . App::getLocale()};
    }

    public function getBodyAttribute(){
        return $this->{"body_" . App::getLocale()};
    }
}
