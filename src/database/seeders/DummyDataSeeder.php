<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // ユーザー3人作成
        $users = [
            ['name' => '山田 太郎', 'email' => 'taro@example.com'],
            ['name' => '西 伶奈',   'email' => 'reina@example.com'],
            ['name' => '増田 一世', 'email' => 'issei@example.com'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name'              => $userData['name'],
                'email'             => $userData['email'],
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // 今月の勤怠データを作成
            for ($i = 1; $i <= 5; $i++) {
                $date = Carbon::today()->startOfMonth()->addDays($i - 1);

                $attendance = Attendance::create([
                    'user_id'   => $user->id,
                    'date'      => $date->format('Y-m-d'),
                    'clock_in'  => $date->format('Y-m-d') . ' 09:00:00',
                    'clock_out' => $date->format('Y-m-d') . ' 18:00:00',
                ]);

                // 休憩データも作成
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'start_time'    => $date->format('Y-m-d') . ' 12:00:00',
                    'end_time'      => $date->format('Y-m-d') . ' 13:00:00',
                ]);
            }
        }
    }
}
