<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermsController extends Controller
{

    public function getAllTerms(){

        $query=DB::select("select * from terms");

        return response($query);
    }

}
