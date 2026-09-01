<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'user_id',
        'class_id',
        'section_id',
        'student_id',
        'roll_number',
        'admission_date',
        'admission_number',
        'guardians_info',
        'status',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'guardians_info' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
