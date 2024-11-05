<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function task_list(){

        if (Auth::user()->role === 'manager') {
            $user = Auth::user()->id;
            $tasks = Task::with(['project', 'assignedBy', 'assignedTo'])
            ->where('assigned_by', $user)
            ->get();
            $projects = Project::where('manager_id', $user)->get();
        }elseif(Auth::user()->role === 'employee'){
            $user = Auth::user()->id;
            $tasks = Task::with(['project', 'assignedBy', 'assignedTo']) // Eager load relationships
            ->where('assigned_to', $user)
            ->get();
        }else{
            $tasks = Task::with(['project', 'assignedBy', 'assignedTo']) // Eager load relationships
            ->get();
            $projects = Project::all();
        }

        return view('tasks.tasks_list',compact('tasks','projects'));
    }
    public function add_task(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tasks,name',
            'description' => 'required|string',
            'status' => 'required|string|in:completed,in_progress,not_started',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'assigned_by' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = Auth::user()->id;
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->due_date = $request->due_date;
        $project->status = $request->status;
        $project->assigned_to = $request->assigned_to;
        $project->assigned_by = $user ;
        $project->project_id = $request->project_id;
        $project->save();

        return redirect()->back()->with('success', 'Project created successfully.');
    }

    public function update_task(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255|unique:tasks,name',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:completed,in_progress,not_started',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $project = Project::find($id);
        if (!$project) {
            return redirect()->back()->withErrors(['project not found.']);
        }

        $project->name = $request->name;
        $project->description = $request->description;
        $project->due_date = $request->due_date;
        $project->status = $request->status;
        $project->assigned_to = $request->assigned_to;
        $project->project_id = $request->project_id;
        $project->save();

        return redirect()->back()->with('success', 'Project updated successfully.');
    }
}
