<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $today = Carbon::today();

        // Get today's attendance record for the user
        $attendance = Attendance::where('user_id', $user)
            ->whereDate('punch_in', $today)
            ->first();

        return view('dashboard', compact('attendance'));
    }


    public function punch(Request $request)
    {
        $user = Auth::user()->id;
        $today = Carbon::today();
        $type = $request->input('type');


        $attendance = Attendance::where('user_id', $user)
            ->whereDate('punch_in', $today)
            ->first();

        if ($type === 'in' && !$attendance) {
            Attendance::create([
                'user_id' => $user,
                'punch_in' => now(),
                'punch_in_description' => $request->input('description')
            ]);
            return response()->json(['message' => 'Punch In recorded successfully.']);
        } elseif ($type === 'out' && $attendance && !$attendance->punch_out) {

            $attendance->update([
                'punch_out' => now(),
                'punch_out_description' => $request->input('description')
            ]);
            return response()->json(['message' => 'Punch Out recorded successfully.']);
        }

        return response()->json(['message' => 'Punch action not allowed.'], 403);
    }

    public function attendance_history()
    {
        $userId = Auth::id(); // Get the logged-in user's ID
        $attendanceHistory = Attendance::where('user_id', $userId)
                                        ->orderBy('punch_in', 'desc')
                                        ->get();

        return view('attendance.history', compact('attendanceHistory'));
    }


}
