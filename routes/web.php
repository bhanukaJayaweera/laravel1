<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RolePermissionController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/product',[ProductController::class,'index'])->name('product.index');
    Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
    Route::post('/product',[ProductController::class,'store'])->name('product.store');
    Route::get('/product/{product}/edit',[ProductController::class,'edit'])->name('product.edit');
    Route::put('/product/{product}/update',[ProductController::class,'update'])->name('product.update');
    Route::delete('/product/{product}/destroy',[ProductController::class,'destroy'])->name('product.destroy');
    Route::get('/product/{product}/view',[ProductController::class,'view'])->name('product.view');
    // Route::post('/generatepdf',[ProductController::class,'generatepdf'])->name('generate.pdf');
    Route::get('product/upload', [ProductController::class, 'showUploadForm'])->name('product.upload');
    Route::post('/import', [ProductController::class, 'import'])->name('import');
    Route::post('/product/select', [ProductController::class, 'generatepdfSelect'])->name('product.select');

    Route::get('/customer',[CustomerController::class,'index'])->name('customer.index');
    Route::post('/customer/select', [CustomerController::class, 'generatepdfSelect'])->name('customer.select');
    Route::get('/customer/{customer}/view',[CustomerController::class,'view'])->name('customer.view');
    Route::get('/customer/{customer}/edit',[CustomerController::class,'edit'])->name('customer.edit');
    Route::put('/customer/{customer}/update',[CustomerController::class,'update'])->name('customer.update');
    Route::delete('/customer/{customer}/destroy',[CustomerController::class,'destroy'])->name('customer.destroy');
    Route::get('/customer/create',[CustomerController::class,'create'])->name('customer.create');
    Route::post('/customer',[CustomerController::class,'store'])->name('customer.store');

    //AJAX
    Route::get('/customer/{id}/change', [CustomerController::class, 'ajaxedit']);
    Route::post('/customer/store', [CustomerController::class, 'ajaxstore']);
    Route::post('/customer/new', [CustomerController::class, 'ajaxstorenew']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/order',[OrderController::class,'index'])->name('order.index');
    Route::post('/order/select', [OrderController::class, 'generatepdfSelect'])->name('order.select');
    //Route::delete('/order/deletemultiple', [OrderController::class, 'deleteMultiple'])->name('order.deletemultiple');
    // Route::get('/order/{order}',[OrderController::class,'view'])->name('order.view');
    Route::get('/order/{order}/view',[OrderController::class,'view'])->name('order.view');
    Route::get('/order/{order}/edit',[OrderController::class,'edit'])->name('order.edit');
    Route::put('/order/{order}/update',[OrderController::class,'update'])->name('order.update'); 
    
    Route::get('/order/create',[OrderController::class,'create'])->name('order.create');
    Route::post('/order',[OrderController::class,'store'])->name('order.store');
    Route::post('/importorder', [OrderController::class, 'importorder'])->name('importorder');
    Route::get('order/upload', [OrderController::class, 'showUploadForm'])->name('order.upload');
    Route::get('/order/productsearch',[OrderController::class,'showSearch'])->name('order.productsearch');

    //AJAX
    Route::get('/order/{id}/change', [OrderController::class, 'orderedit']);
    Route::post('/order/store', [OrderController::class, 'orderstore']);
    Route::get('/order/newfetch', [OrderController::class, 'newfetch']);
    //Route::post('/order/new', [OrderController::class, 'ordernew']);
    Route::delete('/order/{orderId}',[OrderController::class,'destroy']);
    Route::delete('/orders/delete-multiple', [OrderController::class, 'deleteMultiple'])->name('orders.delete-multiple');

    //orderproduct
    Route::post('/orderproduct/store', [OrderController::class, 'storeOrder']);
    Route::post('/orderproduct/checkInvent', [OrderController::class, 'checkInvent']);
    Route::post('/orderproduct/edit', [OrderController::class, 'editOrder']);

    //productsearch
    Route::get('/order/search', [OrderController::class, 'search'])->name('order.search');
    // });

    Route::get('/user-role', [UserRoleController::class, 'index'])->name('user-role.index');
    Route::post('/user-role/{user}', [UserRoleController::class, 'update'])->name('user-role.update');

    Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
    Route::post('/roles-permissions/{role}', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

});
require __DIR__.'/auth.php';