<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'manager_id',
        'employee_id',
        'score',
        'comments',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
