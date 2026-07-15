<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'roll_number', 'class_id', 'department_id', 'date_of_birth',
        'guardian_name', 'guardian_phone', 'address', 'admission_date',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    /**
     * Attendance percentage across all recorded sessions.
     */
    public function attendancePercentage(): float
    {
        $total = $this->attendanceDetails()->count();
        if ($total === 0) {
            return 0;
        }
        $present = $this->attendanceDetails()->whereIn('status', ['present', 'late'])->count();

        return round(($present / $total) * 100, 2);
    }
}
