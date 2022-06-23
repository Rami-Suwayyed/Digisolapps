<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\AdminNotificationRepository;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    protected AdminNotificationRepository $repository;
    public function __construct(AdminNotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function update(Request $request){
        $result = $this->repository->validation($request);
        if($result["fails"])
            return JsonResponse::validationErrors($result["errors"])->send();
        $this->repository->update($request, $this->repository->getById($request->id));
        return JsonResponse::success()->send();
    }
}
