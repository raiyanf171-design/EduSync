<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo_path',
        'school_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the school this user belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get student record if user is a student
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get staff record if user is staff
     */
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Check if user is Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Check if user is School Admin
     */
    public function isSchoolAdmin(): bool
    {
        return $this->hasRole('School Admin');
    }

    /**
     * Check if user is Teacher
     */
    public function isTeacher(): bool
    {
        return $this->hasRole('Teacher');
    }

    /**
     * Check if user is Student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('Student');
    }

    /**
     * Check if user is Parent
     */
    public function isParent(): bool
    {
        return $this->hasRole('Parent');
    }

    /**
     * Get user's dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        if ($this->isSuperAdmin()) {
            return route('super-admin.dashboard');
        } elseif ($this->isSchoolAdmin()) {
            return route('school-admin.dashboard');
        } elseif ($this->isTeacher()) {
            return route('teacher.dashboard');
        } elseif ($this->isStudent()) {
            return route('student.dashboard');
        } elseif ($this->isParent()) {
            return route('parent.dashboard');
        }

        return route('login');
    }

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter by school
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}
