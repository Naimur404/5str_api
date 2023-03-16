<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends Controller
{
    public function permission(){
        $permissions = ModelsPermission::all();
        return view('admin.permission.permission', compact('permissions'));
    }
    public function addPermission(){

        return view('admin.permission.add_permission');
    }

    public function storePermission(Request $request){
        $permission = ModelsPermission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return redirect()->back();
    }
    public function editPermission($id){
        $permission = ModelsPermission::findOrFail($id);
        return view('admin.permission.edit_permission', compact('permission'));
    }
    public function updatePermission(Request $request){
       $per_id = $request->id;
       ModelsPermission::findOrFail($per_id)->update([
        'name' => $request->name,
        'guard_name' => $request->guard_name,
       ]);
        return redirect()->route('permission');
    }
    public function deletePermission($id){

        ModelsPermission::findOrFail($id)->delete();

         return redirect()->route('permission');
     }
}
