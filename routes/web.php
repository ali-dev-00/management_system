<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PerformanceEvluation;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/attendance/punch', [DashboardController::class, 'punch'])->name('attendance.punch');
    Route::get('/attendance/history',[DashboardController::class,'attendance_history'])->name('attendance_history');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //departments
    Route::get('departments', [DepartmentController::class, 'department_list'])->name('department_list');
    Route::post('departments/create', [DepartmentController::class, 'add_department'])->name('add_department');
    Route::put('departments/update/{id}', [DepartmentController::class, 'update_department'])->name('update_department');
    Route::delete('departments/delete/{id}', [DepartmentController::class, 'delete_department'])->name('delete_department');

    //documents
    Route::get('documents', [DocumentController::class, 'document_list'])->name('document_list');
    Route::post('documents/create', [DocumentController::class, 'add_document'])->name('add_document');
    Route::put('documents/update/{id}', [DocumentController::class, 'update_document'])->name('update_document');
    Route::delete('documents/delete/{id}', [DocumentController::class, 'delete_document'])->name('delete_document');


    //projects
    Route::get('projects', [ProjectController::class, 'project_list'])->name('project_list');
    Route::post('projects/create', [ProjectController::class, 'add_project'])->name('add_project');
    Route::put('projects/update/{id}', [ProjectController::class, 'update_project'])->name('update_project');
    Route::put('projects/update/status/{id}', [ProjectController::class, 'update_project_status'])->name('update_project_status');
    Route::delete('projects/delete/{id}', [ProjectController::class, 'delete_project'])->name('delete_project');
    Route::get('/projects/{id}/tasks', [ProjectController::class, 'show_project_tasks'])->name('show_project_tasks');


    //tasks
    Route::post('/tasks/performance',[PerformanceEvluation::class,'add_performance'])->name('add_performance');
    Route::get('tasks', [TaskController::class, 'task_list'])->name('task_list');
    Route::post('tasks/create', [TaskController::class, 'add_task'])->name('add_task');
    Route::put('tasks/update/{id}', [TaskController::class, 'update_task'])->name('update_task');
    Route::put('tasks/update/status/{id}', [TaskController::class, 'update_task_status'])->name('update_task_status');
    Route::delete('tasks/delete/{id}', [TaskController::class, 'delete_task'])->name('delete_task');


    //employees
    Route::get('employees', [EmployeeController::class, 'employees_list'])->name('employees_list');
    Route::get('employees/create', [EmployeeController::class, 'create_employee'])->name('create_employee');
    Route::get('employees/update/{id}', [EmployeeController::class, 'update_employee'])->name('update_employee');
    Route::post('employees/create', [EmployeeController::class, 'create_employee_form'])->name('create_employee_form');
    Route::put('employees/update/{id}', [EmployeeController::class, 'update_employee_form'])->name('update_employee_form');
    Route::delete('employees/delete/{id}', [EmployeeController::class, 'delete_employee'])->name('delete_employee');

    Route::get('/employee/attendance/{user_id}',[EmployeeController::class,'view_attendance_history'])->name('view_attendance_history');
    Route::get('/employee/documents/{user_id}',[EmployeeController::class,'documents_history'])->name('documents_history');


});

require __DIR__ . '/auth.php';
