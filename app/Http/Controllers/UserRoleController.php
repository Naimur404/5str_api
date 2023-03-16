<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HealthOrganization;
use App\Models\Outlet;
use App\Models\User;
use App\Models\UserHasOrganization;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;
use Yajra\DataTables\Facades\DataTables;

use function PHPUnit\Framework\isNull;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Super Admin']);
    }

    public function users(Request $request)
    {
        $users = User::get();
        if ($request->ajax()) {
            $data = User::orderBy("id", "desc")->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return view('admin.action.type', compact('row'));

                })
                ->addColumn('action', function ($row) {

                    $edit = route('edit_user', $row->id);
                    $delete = route('delete_user', $row->id);
                    $btn = '<a href="' . $edit . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editBook">Edit</a>';

                    $btn = $btn . ' <a href="' . $delete . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteBook">Delete</a>';


                    return $btn;
                })
                ->rawColumns(['type'])
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.user_role.user_role', compact('users'));
    }

    public function addUsers()
    {
        $roles = ModelsRole::get();
        $outlets = Outlet::pluck('outlet_name', 'id');
        $warehouses = Warehouse::pluck('warehouse_name', 'id');
        return view('admin.user_role.add_user', compact('roles', 'outlets', 'warehouses'));
    }

    public function addUsersStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',

        ]);
//        $q = DB::select("SHOW TABLE STATUS LIKE 'users'");
//        $user_id = $q[0]->Auto_increment;
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->outlet_id = $request->outlet_id;
        $user->warehouse_id = $request->warehouse_id;
        $user->password = Hash::make($request->password);
        $user->save();
//        $user = User::findOrFail($user_id);
        $user->assignRole($request->role);
        return redirect()->route('user')->with('success', 'Add New User successfully');

    }

    public function editUser($id)
    {
        $data = User::find($id);
        $outlets = Outlet::pluck('outlet_name', 'id');
        $warehouses = Warehouse::pluck('warehouse_name', 'id');
        $roles = ModelsRole::get();
        $roleuser = DB::table('model_has_roles')->where('model_id', $id)->first();
        return view('admin.user_role.edit_user', compact('data', 'roles', 'roleuser', 'outlets', 'warehouses'));
    }

    public function updateUser(Request $request)
    {
        $request->validate([

            'email' => 'email',

        ]);
        $role = DB::table('model_has_roles')->where('model_id', $request->id)->first();
        if (!empty($role)) {
            DB::table('model_has_roles')->where('model_id', $request->id)->delete();
        }
         $model = array(

         'role_id'  => $request->role,
         'model_type' => 'App\Models\User',
         'model_id'   => $request->id,

         );


         DB::table('model_has_roles')->insert($model);


        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->outlet_id = $request->outlet_id;
        $user->warehouse_id = $request->warehouse_id;
        if ($request->password) {
            $request->validate([

                'password' => 'required|confirmed|min:8',

            ]);
            $user->password = Hash::make($request->password);
        }


        $user->update();




        return redirect()->route('user')->with('success', 'Update User successfully');

    }


    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        return redirect()->route('user')->with('success', 'Delete User successfully');

    }


}
