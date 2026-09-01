<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'school_id',
        'session_year_id',
        'exam_name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(ExamSubject::class);
    }
}
