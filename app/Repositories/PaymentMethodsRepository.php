<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\File;

class PaymentMethodsRepository
{

    public function save(PaymentMethod $method, $active, $image){
        $method->active = $active;
        if(File::isFile($image)){
            if($method->getFirstMediaFile())
                $method->removeAllFiles();
            $method->saveMedia($image);
        }
        $method->save();
    }

}
