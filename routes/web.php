<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerManagementController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineDistributeController;
use App\Http\Controllers\MedicinePurchaseController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\OutletInvoiceController;
use App\Http\Controllers\OutletStockController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportController2;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\SupplierController;

use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseReturnController;
use App\Http\Controllers\WarehouseStockController;
use App\Models\Customer;
use App\Models\MedicineDistribute;
use App\Models\OutletInvoice;
use App\Models\OutletStock;
use App\Models\SalesReturn;
use App\Models\StockRequest;
use App\Models\WarehouseReturn;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__.'/auth.php';
Route::get('/', [SettingController::class, 'index']);

Route::get('/dashboard', [DashBoardController::class,'index'])->middleware(['auth'])->name('index');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'],function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/role', 'admin.role.role')->name('role');
    //user route

    Route::get('/user', [UserRoleController::class,'users'])->name('user');
    Route::get('/add_user', [UserRoleController::class,'addUsers'])->name('add_user');
    Route::post('/add-user-store', [UserRoleController::class,'addUsersStore'])->name('add_user_store');
    Route::get('/delete-user/{id}', [UserRoleController::class,'deleteUser'])->name('delete_user');
    Route::get('/edit-user/{id}', [UserRoleController::class,'editUser'])->name('edit_user');
    Route::post('/update/user', [UserRoleController::class,'updateUser'])->name('updateuser');
    Route::get('/add/user/org/{id}', [UserRoleController::class,'addUserOrg'])->name('adduserorg');
    Route::post('/store/user/org', [UserRoleController::class,'storeUserOrg'])->name('storeuserorg');


//permission route

    Route::get('/permission', [PermissionController::class,'permission'])->name('permission');
    Route::get('/add-permission', [PermissionController::class,'addPermission'])->name('add_permission');
    Route::post('/store-permission', [PermissionController::class,'storePermission'])->name('store_permission');
    Route::post('/update-permission', [PermissionController::class,'updatePermission'])->name('update_permission');
    Route::get('/edit-permission/{id}', [PermissionController::class,'editPermission'])->name('edit_permission');
    Route::get('/delete-permission/{id}', [PermissionController::class,'deletePermission'])->name('delete_permission');

    //role route

    Route::get('/role', [RoleController::class,'role'])->name('role');
    Route::get('/add-role', [RoleController::class,'addRole'])->name('add_role');
    Route::post('/store-role', [RoleController::class,'storeRole'])->name('store_role');
    Route::post('/update-role', [RoleController::class,'updateRole'])->name('update_role');
    Route::get('/edit-role/{id}', [RoleController::class,'editRole'])->name('edit_role');
    Route::get('/delete-role/{id}', [RoleController::class,'deleteRole'])->name('delete_role');

    Route::post('/get-role', [RoleController::class,'getRole'])->name('get_role');

    //add role in permission

    Route::get('/add/role/permission', [RoleController::class,'addRolePermission'])->name('rolepermission');
    Route::post('/store/role/permission', [RoleController::class,'storeRolePermission'])->name('add_role_permission');
    Route::get('/all/role/permission', [RoleController::class,'allRolePermission'])->name('allrolepermission');
    Route::get('/edit/role/permission/{id}', [RoleController::class,'editRolePermission'])->name('editrolepermission');
    Route::post('/update/role/permission/{id}', [RoleController::class,'updateRolePermission'])->name('update_role_permission');
    Route::get('/delete/role/permission/{id}', [RoleController::class,'deleteRolePermission'])->name('deleterolepermission');

    //setting route

    Route::get('/site/setting', [SettingController::class,'setting'])->name('setting');
    Route::post('/update/setting', [SettingController::class,'updateSetting'])->name('updatesetting');


});

Route::group(['middleware' => ['auth']],function () {

Route::get('get-user-details/{id}', [Select2Controller::class,'get_user_details']);



//stock route

//profile route
Route::get('/my-profile', [ProfileController::class,'myProfile'])->name('myprofile');
Route::Post('/my-profile/update', [ProfileController::class,'updateMyProfile'])->name('updatemyprofile');





});











