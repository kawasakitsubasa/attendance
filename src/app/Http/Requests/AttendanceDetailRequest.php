<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clock_in'  => ['required'],
            'clock_out' => ['required'],
            'note'      => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $clockIn  = $this->clock_in;
            $clockOut = $this->clock_out;
            $breaks   = $this->breaks ?? [];

            // 出勤・退勤チェック
            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            // 休憩チェック
            foreach ($breaks as $break) {
                $start = $break['start'] ?? null;
                $end   = $break['end'] ?? null;

                if ($start && $clockIn && $start < $clockIn) {
                    $validator->errors()->add('breaks', '休憩時間が不適切な値です');
                    break;
                }
                if ($start && $clockOut && $start > $clockOut) {
                    $validator->errors()->add('breaks', '休憩時間が不適切な値です');
                    break;
                }
                if ($end && $clockOut && $end > $clockOut) {
                    $validator->errors()->add('breaks', '休憩時間もしくは退勤時間が不適切な値です');
                    break;
                }
            }
        });
    }
}
