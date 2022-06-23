<?php

namespace App\Repositories;
use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Models\Commission;
use App\Models\CommissionTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommissionRepository
{

    public function getAll(){
        return Commission::orderBy("price_from", "asc")->get();
    }

    public function getForPrice($price){
        return Commission::where("price_from", "<=", $price)->orderBy("price_from", "desc")->first();
    }

    public function getForPriceTeacher($price){
        return CommissionTeacher::Where("teacher_id", Auth::user()->id)->where("price_from", "<=", $price)->orderBy("price_from", "desc")->first();
    }

    public function rules(): array
    {
        return [
            "price_from" => ["required"],
            "price_to" => ["required"],
            "commission" => ["required"],
            "price_from.*" => ["required", "numeric"],
            "price_to.*" => ["required", "numeric", "gt:price_from.*"],
            "commission.*" => ["required", "numeric"],
            "price_from_infinity" => ["required", "numeric"],
            "commission_infinity" => ["required", "numeric"],
        ];
    }

    public function columns(): array
    {
        return [
            "price_from.*" => "price from",
            "price_to.*" =>  "price to",
            "commission.*" =>  "commission",
            "price_from_infinity" =>  "price from",
            "commission_infinity" =>  "commission"
        ];
    }

    public function checkIsPricesIsValid(Request $request): array
    {
        $messages = [];
        $prices = [];

        $prices["from"] = $request->price_from;
        $prices["to"] = $request->price_to;
        $validPrice = 1;
        for($i = 0; $i < count($prices["from"]); $i++){
            if($validPrice != (int)$prices["from"][$i]){
                $messages["price_from.$i"][] = __("The Price Must Be {$validPrice}");
            }
            $validPrice = $prices["to"][$i] + 1;
        }

        if($validPrice != $request->price_from_infinity){
            $messages["price_from_infinity"][] = __("The Price Must Be {$validPrice}");
        }

        $result["fails"] = !empty($messages);
        if($result["fails"])
            $result["errors"] = $messages;

        return $result;
    }

    public function validation(Request $request): array
    {
        $result["fails"] = false;
        $valid = Validator::make($request->all(), $this->rules(), [], $this->columns());
        if($valid->fails()){
            $result["fails"] = true;
            $result["errors"] = $valid->errors()->messages();
        }else{
            $result = $this->checkIsPricesIsValid($request);
        }
        return $result;
    }

    public function save(Request $request){
        $commissions = $this->getAll();
        $commissionInfinity = $commissions->pop();
//        dd($commissions, $commissionInfinity);

        //Update Commissions
        $lastCommissionIndex = 0;
        foreach ($commissions as $index => $commission){
            $this->saveCommission($commission, ["price_from" => $request->{"price_from.$index"}, "price_to" => $request->{"price_to.$index"}, "commission" => $request->{"commission.$index"}]);
            $lastCommissionIndex = $index;
        }

        //Create New Commission
        $lastCommissionIndex++;
        for ($i = $lastCommissionIndex; $i < count($request->price_from); $i++)
            $this->saveCommission(new Commission(), ["price_from" => $request->{"price_from.$i"}, "price_to" => $request->{"price_to.$i"}, "commission" => $request->{"commission.$i"}]);

        // Update Infinity Commission
        $this->saveCommission($commissionInfinity, ["price_from" => $request->price_from_infinity, "commission" => $request->commission_infinity]);
    }

    public function saveCommission($commission, $data){
        $commission->price_from = $data["price_from"] ?? $commission->price_from;
        $commission->price_to = $data["price_to"] ?? $commission->price_to;
        $commission->commission = $data["commission"]  ?? $commission->commission;
        $commission->save();
    }

    public function delete($id): bool
    {
        $commissions = $this->getAll();
        if(count($commissions) > 2){
            $commission = Commission::where([['price_to', '!=', -1], ['id', "=", $id]])->first();
            $maxCommission = Commission::where([['price_to', '!=', -1]])
                ->select(DB::raw('max(`price_from`) as max'))->first();
            $maxCommission = $maxCommission ? $maxCommission->max : 1;
            if($commission && $maxCommission == $commission->price_from){
                $commission->delete();
                Commission::where([['price_to', -1]])->update(["price_from" => $commission->price_from]);
                return true;
            }
        }
        return false;
    }

}
