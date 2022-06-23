<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $data['users'] = User::selectBuilder()->byType('u')->get();
        return view("admin.users.index", $data);
    }

}
