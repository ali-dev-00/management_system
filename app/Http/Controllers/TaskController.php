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
            $users = User::where('role', 'employee')->get();
        }elseif(Auth::user()->role === 'employee'){
            $user = Auth::user()->id;
            $tasks = Task::with(['project', 'assignedBy', 'assignedTo'])
            ->where('assigned_to', $user)
            ->get();
            $projects = Project::where('manager_id', $user)->get();
            $users = User::where('role', 'employee')->get();
        }else{
            $tasks = Task::with(['project', 'assignedBy', 'assignedTo'])

            ->get();
            $projects = Project::all();
            $users = User::where('role', 'employee')->get();
        }

        return view('tasks.tasks_list',compact('tasks','projects' , 'users'));
    }
    public function add_task(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tasks,name',
            'description' => 'required|string',
            'status' => 'required|string|in:completed,in_progress,not_started',
            'due_date' => 'required|date',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = Auth::user()->id;
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->assigned_to = $request->assigned_to;
        $task->assigned_by = $user;
        $task->project_id = $request->project_id;
        $task->save();

        return redirect()->back()->with('success', 'Task created successfully.');
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

        $task = Task::find($id);
        if (!$task) {
            return redirect()->back()->withErrors(['task not found.']);
        }

        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->assigned_to = $request->assigned_to;
        $task->project_id = $request->project_id;
        $task->save();

        return redirect()->back()->with('success', 'Task updated successfully.');
    }
    public function update_task_status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:completed,in_progress,not_started'
        ]);

        $task = Task::find($id);
        if (!$task) {
            return redirect()->back()->withErrors(['task not found.']);
        }

        // Update only the status
        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Task status updated successfully.');
    }


    public function delete_task($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
}
