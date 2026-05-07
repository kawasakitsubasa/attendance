<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceCorrectRequest extends Model
{
    protected $fillable = [
        'attendance_id', 'user_id', 'target_date',
        'reason', 'is_approved', 'clock_in', 'clock_out'
    ];

    protected $casts = [
        'target_date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
