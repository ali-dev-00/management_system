<?php

namespace App\Http\Controllers;

use App\Models\PerformanceEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceEvluation extends Controller
{
    public function add_performance(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'employee_id' => 'required|exists:users,id',
            'score' => 'required|integer|min:1|max:10',
            'comments' => 'nullable|string',
        ]);

        PerformanceEvaluation::create([
            'task_id' => $request->task_id,
            'employee_id' => $request->employee_id,
            'manager_id' => Auth::user()->id,
            'score' => $request->score,
            'comments' => $request->comments,
        ]);

        return redirect()->back()->with('success', 'Evaluation submitted successfully.');
    }
}
