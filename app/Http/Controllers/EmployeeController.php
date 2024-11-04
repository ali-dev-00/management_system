<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function employees_list()
    {
        $employees =  Employee::all();
        return view('employees.employee_list', compact('employees'));
    }
    public function create_employee()
    {
        $departments = Department::where('status', true)->get();
        return view('employees.create_employee', compact('departments'));
    }
    public function update_employee($id)
    {
        $employee =  Employee::find($id);
        $departments = Department::where('status', true)->get();
        return view('employees.employee_list', compact('employee', 'departments'));
    }
    public function create_employee_form(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'cnic' => 'required|string|unique:employees,cnic',
            'address' => 'required|string',
        ]);

        // 1. Create the User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Create the Employee using the new user's ID
        Employee::create([
            'user_id' => $user->id, // Link the employee to the user
            'department_id' => $request->department_id,
            'cnic' => $request->cnic,
            'address' => $request->address,
        ]);

        return redirect()->route('employees_list')->with('success', 'Employee created successfully.');
    }
}
