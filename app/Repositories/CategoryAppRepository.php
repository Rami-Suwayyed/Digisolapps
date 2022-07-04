<?php

namespace App\Repositories;

use App\Models\CategoryApps;

class CategoryAppRepository
{

    public function getAllWithSort(){
        return CategoryApps::where('status',1)->orderBy("order", "asc")->get();
    }

    public function getById($id){
        return CategoryApps::findOrFail($id);
    }

    public function delete($mainCategory){
        if($mainCategory->subCategories->isNotEmpty())
            foreach ($mainCategory->subCategories as $sub)
                (new SubCategoryRepository())->delete($sub);
        $mainCategory->removeAllFiles();
        $mainCategory->delete();
    }


}
