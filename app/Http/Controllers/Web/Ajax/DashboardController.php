<?php

namespace App\Http\Controllers\Web\Ajax;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    public DashboardRepository $repository;
    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(){
        $data["counter"] = $this->repository->getAllCounters();
        return JsonResponse::data($data)->send();
    }
}
