<?php

namespace App\Http\Controllers\Web\Admin;

use App\Helpers\Dialog\Web\Dialog;
use App\Helpers\Dialog\Web\Types\DangerMessage;
use App\Helpers\Dialog\Web\Types\SuccessMessage;
use App\Helpers\Notification\Types\UserNotification;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use App\Models\User;
use App\Models\UserPromocode;
use App\Rules\AfterTime;
use App\Rules\Uppercase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PromocodeController extends Controller
{
    protected function rules(Request $request){
        $rules = [
            "title"         => ["required", "unique:promocodes"],
            "type"          => ["required"],
            "value"         => ["required","numeric","not_in:0"],
            "max"           => ["required","min:1","numeric","not_in:0"],
            "start_time"    => ["required"],
            "End_time"      => ["required", new AfterTime($request->start_time)],
            "max_discount"  => ['required']
        ];
        if($request->use  == 2){
            $rules["user_promocode"] = ['required'];
        }
        if($request->type == "percentage"){
            $rules['value'] = ["required","numeric","not_in:0", "max:100"];
        }
        return $rules;
    }


    public function index()
    {
        $data['promoCodes']= Promocode::all();
        return view('admin.promo_code.index', $data);
    }


    public function create()
    {
        return view("admin.promo_code.create");
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), $this->rules($request), []);
        if($valid->fails())
            return redirect()->route("admin.promocode.create")->withInput($request->all())->withErrors($valid->errors()->messages());

        $promocode =new Promocode();
        $promocode->title = $request->title;
        $promocode->type =$request->type;
        $promocode->value =$request->value;
        $promocode->number_of_used = $request->max;
        $promocode->start_time =$request->start_time;
        $promocode->End_time =$request->End_time;
        $promocode->status =0;
        $promocode->promo_use = $request->use;
        $promocode->max_discount = $request->max_discount;
        $promocode->save();

        if($request->use == 2){
            foreach ($request->user_promocode as $user_promocode) {
                $userPromocode = new UserPromocode();
                $userPromocode->user_id = $user_promocode;
                $userPromocode->promocode_id = $promocode->id;
                $userPromocode->save();
            }
        }

        $message = (new SuccessMessage())->title("Create Successfully")
            ->body("The PromoCode Has Been Create Successfully");
        Dialog::flashing($message);

        return redirect()->route("admin.promocode.index");


    }

    public function edit(Request $request)
    {
        $data['PromoCode'] = $PromoCode = Promocode::findOrFail($request->id);
        if($PromoCode->promo_use == 2) {
            $data['users'] = User::where("type", "u")->get();
            $types = [];
            foreach ($PromoCode->usePromoCode as $user)
                $types[$user->user_id] = $user->user_id;
            $data["userUse"] = $types;
        }
        return view("admin.promo_code.edit", $data);
    }


    public function update(Request $request)
    {
        $promocode = Promocode::findOrFail($request->id);

        $rules = $this->rules($request);
        if($promocode->title == $request->title) {
            $rules["title"] = ["required", "min:6", "max:6", new Uppercase];
        }
        $valid = Validator::make($request->all(), $rules);

        if($valid->fails())
            return redirect()->route("admin.promocode.create")->withInput($request->all())->withErrors($valid->errors()->messages());

        if($promocode->promo_use == 2) {
            if($request->use == 2) {
                $types = $promocode->getTypesRelatedItAsArray();
                $removeTypes = array_diff($types, $request->user_promocode);
                $createTypes = array_diff($request->user_promocode, $types);
                $promocode->usePromoCode()->whereIn("user_id", $removeTypes)->delete();
                foreach ($createTypes as $type) {
                    $typeRelated = new UserPromocode();
                    $typeRelated->user_id = $type;
                    $promocode->usePromoCode()->save($typeRelated);
                }
            }else{
                $types = $promocode->getTypesRelatedItAsArray();
                $promocode->usePromoCode()->whereIn("user_id", $types)->delete();
            }
        }

        $promocode->title = $request->title;
        $promocode->type =$request->type;
        $promocode->value =$request->value;
        $promocode->number_of_used = $request->max;
        $promocode->start_time =$request->start_time;
        $promocode->End_time =$request->End_time;
        $promocode->status =1;
        $promocode->promo_use = $request->use;
        $promocode->max_discount = $request->max_discount;
        $promocode->save();

        $message = (new SuccessMessage())->title("Update Successfully")
            ->body("The PromoCode Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.promocode.index");
    }


    public function destroy(Request $request)
    {
        Promocode::find($request->id)->delete();
        $message = (new DangerMessage())->title("{{__('Delete Successfully')}}")
            ->body("The PromoCode Has Been Deleted Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.promocode.index");

    }

    public function sendNotification(Request $request){
        $promoCode = Promocode::find($request->id);
        if($promoCode->promo_use == 1){
            $users = User::where("type", "u")->get();
            foreach ($users as $user){
                $notification = new UserNotification($user->id, "new_promocode");
                $values = ["title" => $promoCode->title, "date" => $promoCode->End_time, "numberUse" => $promoCode->number_of_used];
                if($promoCode->type == "percentage")
                    $values["value"] = $promoCode->value . "%";
                else
                    $values["value"] = $promoCode->value . "IQ";
                $notification->setBodyArgs($values);
                $notification->send();
            }
        }else{
            $users = UserPromocode::where("promocode_id", $promoCode->id)->get();
            foreach ($users as $user){
                $notification = new UserNotification($user->user_id, "new_promocode");
                $values = ["title" => $promoCode->title, "date" => $promoCode->End_time, "numberUse" => $promoCode->number_of_used];
                if($promoCode->type == "percentage")
                    $values["value"] = $promoCode->value . "%";
                else
                    $values["value"] = $promoCode->value . "IQ";
                $notification->setBodyArgs($values);
                $notification->send();
            }
        }
        $promoCode->status = 1;
        $promoCode->save();
        $message = (new SuccessMessage())->title("Send Successfully")
            ->body("The Send Notifications Has Been Updated Successfully");
        Dialog::flashing($message);
        return redirect()->route("admin.promocode.index");
    }
}
