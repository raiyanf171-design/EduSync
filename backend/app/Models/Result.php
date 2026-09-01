<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'school_id',
        'student_id',
        'exam_subject_id',
        'obtained_marks',
        'grade',
        'gpa',
    ];

    protected $casts = [
        'obtained_marks' => 'integer',
        'gpa' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function examSubject()
    {
        return $this->belongsTo(ExamSubject::class);
    }

    public function calculateGrade()
    {
        if ($this->obtained_marks >= 90) {
            return 'A+';
        } elseif ($this->obtained_marks >= 80) {
            return 'A';
        } elseif ($this->obtained_marks >= 70) {
            return 'B';
        } elseif ($this->obtained_marks >= 60) {
            return 'C';
        } elseif ($this->obtained_marks >= 50) {
            return 'D';
        } else {
            return 'F';
        }
    }

    public function calculateGPA()
    {
        $gradePoints = [
            'A+' => 4.0,
            'A' => 3.7,
            'B' => 3.3,
            'C' => 3.0,
            'D' => 2.0,
            'F' => 0.0,
        ];

        return $gradePoints[$this->grade] ?? 0;
    }
}
