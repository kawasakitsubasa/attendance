<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'clock_in', 'clock_out'];

    protected $casts = [
        'clock_in'  => 'datetime',
        'clock_out' => 'datetime',
        'date'      => 'date',
    ];

    // ユーザーとの関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 合計休憩時間（後で休憩テーブル作ったら実装する）
    public function getTotalBreakAttribute()
    {
        return null;
    }

    // 合計勤務時間
    public function getTotalWorkAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) return null;
        return $this->clock_in->diff($this->clock_out)->format('%H:%I');
    }
    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }
}
