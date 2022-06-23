<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Helpers\ApiResponse\Json\Senders\SendData;
use App\Helpers\ApiResponse\Json\Senders\SendError;
use App\Helpers\ApiResponse\Json\Senders\SendValidationError;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserLocationRepository;
use App\Rules\BeforeTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    protected AuthRepository $repository;
    public function __construct(AuthRepository $repo)
    {
        $this->repository = $repo;
    }



    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $result = $this->repository->registerValidation($request);
            if($result["fails"])
                return JsonResponse::validationErrors($result["messages"])->initAjaxRequest()->send();

            $data = $this->repository->register($request);
            return JsonResponse::data($data)->message("created user success")->send();
        }catch (\Exception $e){
            return JsonResponse::error()->message($e->getMessage())->send();
        }
    }
}
