<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function project_list()
    {

        if (Auth::user()->role === 'admin') {
            $projects = Project::all();
            $users = User::where('role', 'manager')->get();
        } else {
            $managerId = Auth::user()->id;
            $projects = Project::where('manager_id', $managerId)->get();
            $users = User::where('role', 'manager')->get();
        }


        return view('projects.project_list', compact('projects', 'users'));
    }

    public function add_project(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|in:completed,in_progress,not_started',
            'manager_id' => 'required|exists:users,id', // Validate manager_id exists in users table
        ]);


        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->status = $request->status;
        $project->manager_id = $request->manager_id;
        $project->save();

        return redirect()->back()->with('success', 'Project created successfully.');
    }

    public function update_project(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:completed,in_progress,not_started'
        ]);

        $project = Project::find($id);
        if (!$project) {
            return redirect()->back()->withErrors(['project not found.']);
        }

        $project->name = $request->name;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->status = $request->status;
        $project->save();

        return redirect()->back()->with('success', 'Project updated successfully.');
    }
    public function update_project_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:completed,in_progress,not_started'
        ]);

        $project = Project::find($id);
        if (!$project) {
            return redirect()->back()->withErrors(['project not found.']);
        }

        // Update only the status
        $project->status = $request->status;
        $project->save();

        return redirect()->back()->with('success', 'Project status updated successfully.');
    }


    public function delete_project($id)
    {
        $project = Project::find($id);
        $project->delete();
        return redirect()->back()->with('success', 'project deleted successfully.');
    }
    public function show_project_tasks($id)
    {
        $project = Project::with('tasks')->findOrFail($id);
        return view('projects.project_tasks', compact('project'));
    }
}
