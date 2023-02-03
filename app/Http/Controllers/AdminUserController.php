<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    //
    function list(){
        $users= User::paginate(10);

        
        return view('admin.user.list', compact('users'));
    }
    
}
