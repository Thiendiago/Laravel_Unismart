<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    //
    function list(Request $request){
        $status = $request->input('status');
        if($status== 'trash'){
            $users= User::onlyTrashed()->paginate(10);
        }else{
            $keyword = "";
            if($request->input('keyword')){
               $keyword = $request->input('keyword'); 
            }
            $users= User::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        }
        $count_user_active = User::count();
        $count_user_trash = User::onlyTrashed()->count();

        $count = [$count_user_active, $count_user_trash];
        return view('admin.user.list', compact('users', 'count'));
    }
    function add(Request $request){
        if($request->input('btn-add')){
            return $request->input();
        }
        return view('admin.user.add');
    }
    function store(Request $request){
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ],
            [
                'required'=>':attribute ko duoc de trong',
                'min'=>':attribute co do dai it nhat :min ki tu',
                'max'=>':attribute co do dai it nhat :max ki tu',               
                'confirmed'=>'xac nhan mau khau ko dung',

            ],
            [
                'name'=>'ten nguoi dung',
                'password'=>'mat khau',

            ]
            );
            User::create([
                'name'=> $request->input('name'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
            ]);
       
            return redirect('admin/user/list')->with('status', 'da them');    
    }
    function delete($id){
        if(Auth::id()!=$id){
            $user = user::find($id);
            $user->delete();
            return redirect('admin/user/list')->with('status', 'da xoa thanh cong');
        }else{
            return redirect('admin/user/list')->with('status', 'ban ko the tu xoa minh ra khoi he thong');
        }
    }
    
}
