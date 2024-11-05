<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
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


    //employees
    Route::get('employees',[EmployeeController::class,'employees_list'])->name('employees_list');
    Route::get('employees/create',[EmployeeController::class,'create_employee'])->name('create_employee');
    Route::get('employees/update/{id}',[EmployeeController::class,'update_employee'])->name('update_employee');
    Route::post('employees/create',[EmployeeController::class,'create_employee_form'])->name('create_employee_form');
    Route::put('employees/update/{id}',[EmployeeController::class,'update_employee_form'])->name('update_employee_form');
    Route::delete('employees/delete/{id}',[EmployeeController::class,'delete_employee'])->name('delete_employee');
});

require __DIR__.'/auth.php';
