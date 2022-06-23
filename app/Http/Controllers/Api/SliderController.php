<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SliderRepository;
use Illuminate\Http\Request;
class SliderController extends Controller
{
    protected SliderRepository $repository;
    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data["sliders"] = $this->repository->getAll();
    }
}
