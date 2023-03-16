<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as ModelsRole;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin']);
    }

    public function role(Request $request){
        $roles = ModelsRole::all();


        if ($request->ajax()) {
            $data = ModelsRole::orderBy("id","desc")->get();
            return  Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('edit_role',$id);
                        $delete = route('delete_role',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }


        return view('admin.role.role', compact('roles'));
    }
    public function addRole(){

        return view('admin.role.add_role');
    }

    public function storeRole(Request $request){
        $request->validate([
            'name' => 'required|string',


        ]);
         ModelsRole::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('role')->with('success','Role Add Sucessfully');
    }
    public function editRole($id){
        $role = ModelsRole::findOrFail($id);
        return view('admin.role.edit_role', compact('role'));
    }
    public function updateRole(Request $request){
        $request->validate([
            'name' => 'required|string',


        ]);
       $per_id = $request->id;
       ModelsRole::findOrFail($per_id)->update([
        'name' => $request->name,
        'guard_name' => 'web',
       ]);
        return redirect()->route('role')->with('success','Role Update Sucessfully');
    }
    public function deleteRole($id){

        ModelsRole::findOrFail($id)->delete();

         return redirect()->route('role')->with('success','Role Delete Sucessfully');
     }
     public function getRole(Request $request){
        $search = $request->search;

        if($search == ''){
           $employees = ModelsRole::orderby('name','asc')->select('id','name')->limit(5)->get();
        }else{
           $employees = ModelsRole::orderby('name','asc')->select('id','name')->where('name', 'like', '%' .$search . '%')->limit(5)->get();
        }

        $response = array();
        foreach($employees as $employee){
           $response[] = array(
                "id"=>$employee->id,
                "text"=>$employee->name
           );
        }
        return response()->json($response);
     }

     public function addRolePermission(){

        $permissions = ModelsPermission::all();
        return view('admin.role_in_per.add_role_permi',compact('permissions'));
     }
     public function storeRolePermission(Request $request){
        $request->validate([
            'role' => 'required',


        ]);
        $data = array();

        $role = ModelsRole::findOrFail($request->role);
        $permissions = $request->permission;
        if(empty($permissions)){
            return redirect()->back()->with('success','Please Select Permission');

        }else{
            if(!empty($role)){
                $role->syncPermissions($permissions);

                }else{

                    foreach($permissions as $key => $item){
                        $data['role_id'] = $request->role;
                        $data['permission_id'] = $item;
                        DB::table('role_has_permissions')->insert($data);

                       }
                }

        }


       return redirect()->route('allrolepermission')->with('success','Role in permission add successfully');

     }
     public function allRolePermission(Request $request){
        if ($request->ajax()) {
            $data = ModelsRole::all();
            return  Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('permission', function($row){

                        return view('admin.action.permission', compact('row'));
                    })

                    ->addColumn('action', function($row){
                        $id = $row->id;
                        $edit = route('editrolepermission',$id);
                        $delete = route('deleterolepermission',$id);
                        return view('admin.action.action', compact('id','edit','delete'));
                    })
                    ->rawColumns(['action'])
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.role_in_per.all_role_permi');
     }
     public function editRolePermission($id){
        $role = ModelsRole::findOrFail($id);
        $permissions = ModelsPermission::all();
        return view('admin.role_in_per.edit_role_per',compact('role','permissions'));
     }
     public function updateRolePermission(Request  $request, $id){

        $role = ModelsRole::findOrFail($id);
        $permission = $request->permission;
        if(!empty($permission)){
            $role->syncPermissions($permission);

        }
        return redirect()->route('allrolepermission')->with('success','Update successfully');


     }
     public function deleteRolePermission($id){
        $role = ModelsRole::findOrFail($id);
        if(!is_null($role)){
            $role->delete();
        }
        return redirect()->route('allrolepermission')->with('error','Delete successfully');
     }



}
