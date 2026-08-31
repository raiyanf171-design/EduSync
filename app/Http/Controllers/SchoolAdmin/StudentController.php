<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display all students
     */
    public function index()
    {
        $students = Student::with('user', 'schoolClass', 'section')
            ->where('school_id', auth()->user()->school_id)
            ->paginate(15);
        return view('school-admin.students.index', compact('students'));
    }

    /**
     * Show student creation form
     */
    public function create()
    {
        $classes = SchoolClass::where('school_id', auth()->user()->school_id)->get();
        return view('school-admin.students.create', compact('classes'));
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^\d{11}$/',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'roll_number' => 'required|integer',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
        ]);

        // Create user
        $user = $this->createUser($validated);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'school_id' => auth()->user()->school_id,
            'student_id' => $this->generateStudentId(),
            'roll_number' => $validated['roll_number'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'],
            'father_name' => $validated['father_name'],
            'mother_name' => $validated['mother_name'],
            'admission_date' => now(),
        ]);

        return redirect()->route('school-admin.students.index')
            ->with('success', 'Student created successfully');
    }

    /**
     * Create user account
     */
    private function createUser($data)
    {
        return \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt('password123'),
            'school_id' => auth()->user()->school_id,
            'status' => 'active',
        ]);
    }

    /**
     * Generate unique student ID
     */
    private function generateStudentId()
    {
        return 'STU' . date('Y') . str_pad(Student::count() + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Show student details
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);
        return view('school-admin.students.show', compact('student'));
    }
}
