<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'punch_in',
        'punch_in_description',
        'punch_out',
        'punch_out_description',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

}
