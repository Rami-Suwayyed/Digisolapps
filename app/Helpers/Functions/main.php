<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

function gateCRUDPermissions($mainName){
    return Gate::check("view-" . $mainName) || Gate::check("create-" . $mainName)
        || Gate::check("update-" . $mainName) || Gate::check("delete-" . $mainName);
}

function isJson($string){
    $data = json_decode($string, true);
    if (is_null($data) || !is_array($data))
       return false;
    return true;
}
function isArabic($string){
    if(mb_detect_encoding($string[0]) == "UTF-8")
        return true;
    return false;

}

function hasPermissions($permissions){
    $user = Auth::user();

    if($permissions == "admin-control"){
        if($user->is_super_admin == 1)
            return true;
        return false;
    }

    if($user->is_super_admin == 1)
        return true;

    if(is_array($permissions)){
        foreach ($permissions as $permission){
            if(Gate::allows($permission)){
                return true;
            }
        }
    }else{
        if(Gate::allows($permissions)){
            return true;
        }
    }
    return false;
}

function isPermissionsAllowed(...$permissions){
    $user = Auth::user();

    if($permissions[0] == "admin-control"){
        return $user->isAdministrator();
    }

    if($user->isAdministrator())
        return true;

    foreach ($permissions as $permission){
        if(Gate::allows($permission)){
            return true;
        }
    }
    return false;
}

function getUserTypeName($type){
    switch ($type){
        case "wk": return __("Worker"); break;
        case "cr": return __("Contractor"); break;
        case "cc": return __("Contractor Company"); break;
        case "eo": return __("Engineering Office"); break;
        case "om": return __("Owner Mechanisms"); break;
        case "ml": return __("Material Laboratory"); break;
        case "sp": return __("Supplier"); break;
        case "cl": return __("Client"); break;
    }
}

function getSameWithNewLanguage($lang){
    $path = request()->path();
    for ($char = 0; strlen($path); $char++){
        if($path[$char] !== "/")
            $path[$char] = ' ';
        else
            break;
    }
    $path = trim($path, " /");
    return "/" . $lang . "/" . $path;
}


function generateRandomStringAndNumber($length = 16){
    $chars = ['a', 'b', 'c', 'd', 'e', 'f', 'r', 't', 'z', 'm', 'q', 'w', 'n', 'b',
        'A', 'B', 'C', 'D', 'E', 'F', 'R', 'T', 'Z', 'M', 'Q', 'S', 'X', 'V', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
    $string = '';
    for ($i=0; $i < $length; $i++){
        $string .= $chars[rand(0, 37)];
    }
    return $string;
}

function getMySqlTimeStamp($time = 'now'){
    $format = "Y-m-d H:i:s";
    return $time == "now" ? \Illuminate\Support\Carbon::createFromFormat($format, now())->setTimezone("Europe/Amsterdam")->format($format) : date($format, $time);
}

function encriptNumber($value){
    $plaintext = $value;
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, "SahlaEn", $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, "SahlaEn", $as_binary=true);
    return base64_encode( $iv.$hmac.$ciphertext_raw );
}

function decriptNumber($value){
    $c = base64_decode($value);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    return openssl_decrypt($ciphertext_raw, $cipher, "SahlaEn", $options=OPENSSL_RAW_DATA, $iv);
}

function buildTreeView($level, $parentId, $tree){
    $view =  "<ul class='tree'>";
    if(!empty($tree[$level][$parentId]) && isset($tree[$level][$parentId])){
        foreach($tree[$level][$parentId] as $node){
            $hasTree = isset($tree[$level + 1][$node->id]) && !empty($tree[$level + 1][$node->id]);
            $view .= "<li class='" . ($hasTree ? "has-tree" : null) . "'>";

            $view .= "<div class='node " . ($hasTree ? "action-tree" : null) . "'>";
            if($hasTree)
                $view .= '<i class="fas fa-caret-right arrow"></i>';
            $view .= $node->location_text;
            $view .= "<span class='node-details'>Supported: " . ($node->support ? "Yes" : "NO") . "</span>";
            if(hasPermissions(["edit-delivery-price", "delete-delivery-price"])){
                if(hasPermissions("edit-delivery-price"))
                    $view .= '<a href="' . route("admin.locations-support.edit", $node->id) . '?redirect=tree"class="control-link edit node-control"><i class="fas fa-edit"></i></a>';
                if(hasPermissions("delete-delivery-price"))
                    $view .= '<form action="' . route("admin.locations-support.destroy", $node->id) . '?redirect=tree" method="post" id="delete' . $node->id . '" style="display: none" data-swal-title="Delete Location" data-swal-text="Are Your Sure To Delete This Location ?" data-yes="Yes" data-no="No" data-success-msg="the location has been deleted succssfully">
                        <input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="_method" value="delete"></form>' .
                        '<span href="#" class="control-link node-control remove form-confirm" data-form-id="#delete' . $node->id . '"><i class="far fa-trash-alt"></i></span>';
            }
            $view .= "</span>";
            $view .= "</div>";
            if($hasTree)
                $view .= buildTreeView($level + 1, $node->id, $tree);
            $view .= "</li>";
        }
    }
    $view .= "</ul>";
    return $view;
}

function buildTree($trees, $id, $isCheck, $page, $teacherId = Null, $subjectTeacher = Null){
    $view =  "<ul class='tree'>";
        foreach ($trees as $tree){
            $view .= "<li class='has-tree'>";
            $view .= "<input type='checkbox' hidden class='mr-1 remove-all-subject-checkbox group" . $id ."'";
            if($isCheck == "true")
                $view .= "hidden='hidden' checked disabled ";
            else {
                $subjectSub = [];
                if ($subjectTeacher !== Null) {
                    foreach ($tree->subjects as $subject) {
                        $subjectSub[] = $subject->id;
                    }
                    if (count($subjectSub) == count(array_intersect($subjectSub, $subjectTeacher)))
                        $view .= "checked ";
                }
            }

            $view .= "value='" . $tree->id . "' data-sub-url='" . route("ajax.subjects.show", ["sub_id" => $tree->id]) . "'>";
            $view .= "<div class='node action-tree action-tree2' data-id='" . $tree->id .  "' data-url='"  . route("ajax.sub-category.subject");
            if($page == "create")
                $view .= "' data-page='create'";
            else
                $view .= "' data-page='edit' data-teacher-id='" . $teacherId ."'";
            $view .= ">";
            $view .= '<i class="fas fa-caret-right arrow"></i> ' . $tree->name;
            $view .= "</div>";
            $view .= "</li>";
        }
    $view .= "</ul>";
    return $view;
}

function buildTreeSubject($trees,$isCheck, $price_value, $page, $type, $subjectTeacher = Null, $teacherId = Null){
    $Commission=0;
    $view =  "<ul class='tree'>";
    foreach ($trees as $tree){
        $view .= "<li class='has-tree'>";
        $view .= "<input type='checkbox' class='mr-1 subject-check groupSub" . $tree->subCategory->id ." group" . $tree->subCategory->mainCategory->id . "'";
        if($isCheck == "true")
            $view .= "hidden='hidden' checked disabled ";
        else {
//            if($subjectTeacher !== Null)
//                if (in_array($tree->id, $subjectTeacher)){
//                    $view .= "checked";
//                }
        }

        $view .= "value='" . $tree->id . "'>";
        $view .= $tree->name ;
        $view .= "<div class='node action-tree'>";
        $view .= "<div class='form-group' style='display: flex; flex-direction: row'>";
        $teacherPrice = $price_value ?? 0;
        $teacherCommission = $Commission;
        if($page == "edit"){
            $SubjectTeachers = \App\Models\SubjectTeacher::where("subject_id", $tree->id)->where("teacher_id", $teacherId)->first();
            $teacherPrice = $SubjectTeachers ? $SubjectTeachers->price : $price_value;
            $teacherCommission = $SubjectTeachers ? $SubjectTeachers->commission : $Commission;
        }
        if($type) {
            $view .= "<span class='input-group-text'>" . '$' . "</span>";
            $view .= "<input class='form-control' type='number' min='" . $price_value . "' value='" . $teacherPrice . "' placeholder='price' name='subject_price[" . $tree->id . "]'>";
            $view .= "<input class='form-control' type='number' min='0' value='" . $teacherCommission . "' placeholder='Commission' name='subject_Commission_new[" . $tree->id . "]'>";
            $view .= "<div class='input-group-append'><span class='input-group-text'>%</span></div>";
            $view .= "</div>";
            $view .= "</div>";
        }else{
            $view .= "<input class='form-control' type='hidden' min='0' value='0' placeholder='price' name='subject_price[" . $tree->id . "]'>";
            $view .= "<input class='form-control' type='hidden' min='0' value='0' placeholder='Commission' name='subject_Commission_new[" . $tree->id . "]'>";
        }
        $view .= "</li>";
    }
    $view .= "</ul>";
    return $view;
}

function inArray($needle, $haystack){
    foreach ($haystack as $val){
        if($needle === $val)
            return true;
    }
    return false;
}

