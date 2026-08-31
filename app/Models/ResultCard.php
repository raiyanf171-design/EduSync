<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'session_year_id',
        'gpa',
        'grade',
        'total_obtained_marks',
        'total_marks',
        'position',
        'generated_date',
    ];

    protected $casts = [
        'generated_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the student associated with this result card
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the session year associated with this result card
     */
    public function sessionYear(): BelongsTo
    {
        return $this->belongsTo(SessionYear::class, 'session_year_id');
    }

    /**
     * Calculate GPA from marks
     */
    public static function calculateGPA($marks): float
    {
        if (count($marks) == 0) return 0;
        
        $totalGPA = 0;
        foreach ($marks as $mark) {
            $percentage = ($mark->obtained_marks / $mark->total_marks) * 100;
            if ($percentage >= 90) $gpa = 4.0;
            elseif ($percentage >= 80) $gpa = 3.5;
            elseif ($percentage >= 70) $gpa = 3.0;
            elseif ($percentage >= 60) $gpa = 2.5;
            elseif ($percentage >= 50) $gpa = 2.0;
            else $gpa = 0;
            
            $totalGPA += $gpa;
        }
        
        return round($totalGPA / count($marks), 2);
    }
}
