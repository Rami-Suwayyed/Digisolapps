<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse\Json\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Dotenv\Validator;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected ?SubjectRepository $repository;
    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;

    }

    public function index(Request $request){
        $data = $this->repository->getSubjectsBySubCategory($request->sub_id);
        return JsonResponse::data($data)->send();
    }

    public function show(Request $request){
        $data = $this->repository->showForApi($request->id);
        return JsonResponse::data($data)->send();
    }
}
