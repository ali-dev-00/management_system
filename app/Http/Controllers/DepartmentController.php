<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function department_list(){
        $departments =  Department::all();
        return view('departments.department_list',compact('departments'));
    }


    public function add_department(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);
        Department::create([
            'name' => $request->name,
            'status' => true,
        ]);
        return redirect()->back()->with('success', 'Department created successfully.');

    }

    public function update_department(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id, // Fixed unique rule
            'status' => 'required|boolean',
        ]);

        $department = Department::find($id);
        if (!$department) {
            return redirect()->back()->withErrors(['Department not found.']);
        }

        $department->name = $request->name;
        $department->status = $request->status;
        $department->save();

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function delete_department($id)
    {
        $department = Department::find($id);
        $department->delete();
        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
