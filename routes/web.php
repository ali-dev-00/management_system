<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //departments
    Route::get('departments',[DepartmentController::class,'department_list'])->name('department_list');
    Route::post('departments/create',[DepartmentController::class,'add_department'])->name('add_department');
    Route::put('departments/update/{id}',[DepartmentController::class,'update_department'])->name('update_department');
    Route::delete('departments/delete/{id}',[DepartmentController::class,'delete_department'])->name('delete_department');
});

require __DIR__.'/auth.php';
