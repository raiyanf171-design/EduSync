<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
        'student_id',
        'roll_number',
        'class_id',
        'section_id',
        'session_year_id',
        'father_name',
        'mother_name',
        'guardian_phone',
        'guardian_email',
        'date_of_birth',
        'gender',
        'address',
        'admission_date',
        'photo_path',
        'status',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this student
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school associated with this student
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the class associated with this student
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the section associated with this student
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the session year associated with this student
     */
    public function sessionYear(): BelongsTo
    {
        return $this->belongsTo(SessionYear::class, 'session_year_id');
    }

    /**
     * Get all attendance records for this student
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all exam marks for this student
     */
    public function examMarks(): HasMany
    {
        return $this->hasMany(ExamMark::class);
    }

    /**
     * Get all result cards for this student
     */
    public function resultCards(): HasMany
    {
        return $this->hasMany(ResultCard::class);
    }

    /**
     * Get all fees for this student
     */
    public function studentFees(): HasMany
    {
        return $this->hasMany(StudentFee::class);
    }

    /**
     * Get all invoices for this student
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Generate unique student ID if not exists
     */
    public static function generateStudentId(School $school)
    {
        $prefix = strtoupper(substr($school->school_name, 0, 3));
        $count = self::where('school_id', $school->id)->count();
        
        return $prefix . '-' . date('Y') . '-' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get attendance percentage for a month
     */
    public function getAttendancePercentage($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $query = $this->attendances()
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month);

        $totalDays = $query->count();
        $presentDays = $query->where('status', 'present')->count();

        if ($totalDays == 0) {
            return 0;
        }

        return round(($presentDays / $totalDays) * 100, 2);
    }

    /**
     * Scope to filter active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope to filter by section
     */
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }
}
