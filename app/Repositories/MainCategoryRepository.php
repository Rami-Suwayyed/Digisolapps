<?php

namespace App\Repositories;

use App\Models\MainCategory;
use App\Models\Order;

class MainCategoryRepository
{

    public function getAllWithSort(){
        return MainCategory::where('status',1)->orderBy("order", "asc")->get();
    }

    public function getById($id){
        return MainCategory::findOrFail($id);
    }

    public function delete($mainCategory){
        if($mainCategory->subCategories->isNotEmpty())
            foreach ($mainCategory->subCategories as $sub)
                (new SubCategoryRepository())->delete($sub);
        $mainCategory->removeAllFiles();
        $mainCategory->delete();
    }


    public function getAllOrderWithSort(){
        return Order::where('status',2)->get();
    }


}
