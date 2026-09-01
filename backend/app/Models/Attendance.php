<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'student_id',
        'attendance_date',
        'status',
        'remarks',
        'marked_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'marked_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
