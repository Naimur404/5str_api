<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class UserRole extends Controller
{
    public function users(){
        $users = User::get();
        return view('admin.user_role.user_role', compact('users'));
    }
    public function addUsers(){
        $roles = ModelsRole::get();
        return view('admin.user_role.add_user',compact('roles'));
    }
    public function addUsersStore(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required'
           ]);
           $q = DB::select("SHOW TABLE STATUS LIKE 'users'");
           $user_id = $q[0]->Auto_increment;
           $user = new User();
           $user->name = $request->name;
           $user->email = $request->email;
           $user->password = Hash::make($request->password);
           $user->save();
           $user = User::findOrFail($user_id);
           $user->assignRole($request->role);
           return redirect()->back();

    }
}
