<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::put('/view', function () {
//     return view('Products.view');
// });

// Route::post('/generatepdf', function (Request $request) {
//     $data = $request->all(); // Get form data
//     // Load view and pass form data
//     $pdf = Pdf::loadView('Products.pdf_template', compact('data'));

//     // Download PDF
//     return $pdf->download('generated.pdf');
// })->name('generate.pdf');

#Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
require __DIR__.'/auth.php';