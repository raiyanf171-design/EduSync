# EduSync - সম্পূর্ণ আর্কিটেকচার ডকুমেন্টেশন

## 📋 প্রজেক্ট ওভারভিউ

EduSync হলো একটি **মাল্টি-টেন্যান্ট SaaS স্কুল ম্যানেজমেন্ট সিস্টেম** যা Laravel ফ্রেমওয়ার্ক দিয়ে তৈরি। এটি সুপার অ্যাডমিন, স্কুল অ্যাডমিন, শিক্ষক, শিক্ষার্থী এবং অভিভাবকদের জন্য সম্পূর্ণ ডিজিটাল সমাধান প্রদান করে।

---

## 🏗️ সিস্টেম আর্কিটেকচার

```
┌─────────────────────────────────────────────┐
│         Frontend (Blade + Livewire)         │
├─────────────────────────────────────────────┤
│  Super Admin | School Admin | Teacher       │
│  Student     | Parent Panel                 │
├─────────────────────────────────────────────┤
│         API Routes & Controllers            │
├─────────────────────────────────────────────┤
│    Models & Business Logic Layer            │
├─────────────────────────────────────────────┤
│         Database (MySQL)                    │
│  - Multi-tenant Architecture                │
│  - Role-based Access Control                │
└─────────────────────────────────────────────┘
```

---

## 📦 ফেইজ ১: কোর সেটআপ ও ডাটাবেজ স্কিমা

### প্রধান টেবিল:

#### 1. **Schools Table**
```
- id (PK)
- school_name
- subdomain (unique)
- package_id (FK)
- subscription_expire_date
- bkash_phone
- status (active/inactive)
- created_at, updated_at
```

#### 2. **Subscriptions Table**
```
- id (PK)
- school_id (FK)
- package_id (FK)
- transaction_id
- start_date
- expire_date
- amount
- payment_method (bkash)
- status (pending/completed/expired)
- created_at, updated_at
```

#### 3. **Users Table** (সব ধরনের ইউজারের জন্য)
```
- id (PK)
- name
- email
- password
- phone
- photo_path
- school_id (FK) [NULL for Super Admin]
- status (active/inactive)
- created_at, updated_at
```

#### 4. **Roles & Permissions** (Spatie Laravel Permission)
```
Roles:
- Super Admin
- School Admin
- Teacher
- Student
- Parent
```

#### 5. **Academic Structure**
```
- SessionYears (2024-2025, etc.)
- Shifts (Morning, Afternoon, Evening)
- Classes (Class 1 - Class 12)
- Sections (A, B, C, etc.)
```

---

## 👥 ফেইজ ২: প্রশাসনিক ও একাডেমিক মডিউল

### ১. **Student Info (শিক্ষার্থী তথ্য)**
```
Students Table:
- id (PK)
- user_id (FK)
- school_id (FK)
- student_id (auto-generated, unique)
- roll_number
- class_id (FK)
- section_id (FK)
- father_name
- mother_name
- guardian_phone
- photo_path
- admission_date
- status (active/inactive)
```

### २. **Attendance (উপস্থিতি সিস্টেম)**
```
Attendance Table:
- id (PK)
- attendable_id (polymorphic: student/teacher)
- attendable_type
- attendance_date
- status (present/absent/late)
- remark
- created_by (teacher_id)
- school_id (FK)
```

### ३. **Exam & Results (পরীক্ষা ও ফলাফল)**
```
Exams Table:
- id (PK)
- school_id (FK)
- exam_name
- class_id (FK)
- exam_date
- status

ExamMarks Table:
- id (PK)
- exam_id (FK)
- student_id (FK)
- subject_id (FK)
- obtained_marks
- total_marks

ResultCards Table:
- id (PK)
- student_id (FK)
- session_year_id (FK)
- gpa
- grade (A+, A, B+, etc.)
- generated_at
```

### ४. **Routine (রুটিন ও ক্লাস শিডিউল)**
```
ClassRoutines Table:
- id (PK)
- class_id (FK)
- day_of_week
- period_number
- teacher_id (FK)
- subject_id (FK)
- start_time
- end_time
```

---

## 💰 ফেইজ ३: আর্থিক ও অন্যান্য ব্যবস্থাপনা

### ১. **Fee Management & bKash Integration**
```
FeeTypes Table:
- id (PK)
- school_id (FK)
- fee_name (Admission, Tuition, Exam)
- amount
- is_recurring

StudentFees Table:
- id (PK)
- student_id (FK)
- fee_type_id (FK)
- academic_month
- amount
- due_date
- status (pending/partial/paid)

Invoices Table:
- id (PK)
- student_id (FK)
- school_id (FK)
- total_amount
- paid_amount
- status (unpaid/partial/paid)
- invoice_number (auto-generated)
- generated_date

Payments Table:
- id (PK)
- invoice_id (FK)
- payment_method (bkash)
- transaction_id
- amount
- status (initiated/pending/completed/failed)
- bkash_phone
- reference_number
- receipt_path
- paid_date
```

### २. **HR & Payroll (এইচআর ও পে-রোল)**
```
Staff Table:
- id (PK)
- user_id (FK)
- school_id (FK)
- staff_id (auto-generated)
- designation
- department
- joining_date
- salary_amount
- bank_account
- mfs_account

LeaveApplications Table:
- id (PK)
- staff_id (FK)
- from_date
- to_date
- leave_type
- reason
- status (pending/approved/rejected)

Payroll Table:
- id (PK)
- staff_id (FK)
- month_year
- basic_salary
- allowances
- deductions
- net_salary
- payment_status (pending/disbursed)
- disbursed_date
```

### ३. **Library (লাইব্রেরি)**
```
Books Table:
- id (PK)
- school_id (FK)
- book_name
- isbn
- author
- total_copies
- available_copies
- category

BookIssues Table:
- id (PK)
- student_id (FK)
- book_id (FK)
- issue_date
- due_date
- return_date
- status (issued/returned)
```

### ४. **Transport (পরিবহন)**
```
Routes Table:
- id (PK)
- school_id (FK)
- route_name
- pickup_points
- drop_points
- fare

StudentTransport Table:
- id (PK)
- student_id (FK)
- route_id (FK)
- status (active/inactive)
```

### ५. **Communication (যোগাযোগ)**
```
Notices Table:
- id (PK)
- school_id (FK)
- title
- content
- notice_type (general/class_specific)
- class_id (FK) [nullable]
- published_by (admin_id)
- published_date
- visibility (all/teachers/students/parents)
```

---

## 🎯 ফেইজ ४: আধুনিক ফিচার, পোর্টাল ও ড্যাশবোর্ড

### १. **Events & Calendar (ইভেন্ট ও ক্যালেন্ডার)**
```
Events Table:
- id (PK)
- school_id (FK)
- event_name
- event_date
- event_type (holiday/sports/cultural)
- description
- created_by
```

### २. **Dashboard Components**

#### **Super Admin Dashboard:**
- Total Schools (Active/Inactive)
- Total Revenue (Monthly/Yearly)
- Subscription Status
- Recent Transactions
- System Health

#### **School Admin Dashboard:**
- Total Students/Teachers/Staff
- Daily Attendance
- Daily Collection (Fees)
- Pending Tasks
- Notices

#### **Teacher Dashboard:**
- My Classes
- Routine
- Attendance to Mark
- Exam Schedule
- Students Performance

#### **Student Dashboard:**
- My Routine
- Attendance Record
- Exam Results
- Fees Status
- Notices
- Diary/Assignments

#### **Parent Portal:**
- Child's Attendance
- Exam Results
- Fees Payment History
- School Notices
- Real-time Updates

---

## 🔄 ফেইজ ५: Automated SaaS Subscription Workflow

### Subscription Flow:

```
1. Super Admin Creates Package
   ↓
2. School Admin Selects Package (1 year / 2 years)
   ↓
3. System Generates Subscription Invoice
   ↓
4. School Admin Pays via bKash (Sandbox)
   ↓
5. System Verifies Payment (Execute API)
   ↓
6. Subscription Status → PAID
   ↓
7. School Activation Enabled
   ↓
8. Auto-renewal Notification 30 days before expiry
   ↓
9. Auto-renewal Invoice Generation
```

### bKash Integration Flow:

```
Create Payment Request
    ↓
Get Payment URL (bKash Sandbox)
    ↓
School Admin Redirect to bKash
    ↓
Payment Verification (Callback)
    ↓
Execute Payment
    ↓
Update Subscription Status
    ↓
Generate Digital Receipt
    ↓
Send Confirmation Email
```

---

## 📂 ডিরেক্টরি স্ট্রাকচার

```
EduSync/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SuperAdmin/
│   │   │   ├── SchoolAdmin/
│   │   │   ├── Teacher/
│   │   │   ├── Student/
│   │   │   └── Parent/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── School.php
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   ├── Subscription.php
│   │   ├── Payment.php
│   │   ├── Attendance.php
│   │   ├── Exam.php
│   │   ├── Fee.php
│   │   └── ...
│   ├── Services/
│   │   ├── BKashService.php
│   │   ├── SubscriptionService.php
│   │   ├── PaymentService.php
│   │   └── ...
│   └── Traits/
│       └── MultiTenant.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── super-admin/
│   │   ├── school-admin/
│   │   ├── teacher/
│   │   ├── student/
│   │   ├── parent/
│   │   └── components/
│   └── css/
├── routes/
│   ├── web.php
│   ├── api.php
│   └── channels.php
├── tests/
├── config/
├── storage/
└── public/
```

---

## 🔐 সিকিউরিটি ফিচার

- ✅ Laravel Sanctum (API Authentication)
- ✅ Role-based Access Control (RBAC)
- ✅ Multi-tenant Isolation
- ✅ Password Encryption (bcrypt)
- ✅ CSRF Protection
- ✅ SQL Injection Prevention
- ✅ XSS Protection
- ✅ Rate Limiting

---

## 🚀 ইনস্টলেশন

```bash
# Clone repository
git clone https://github.com/raiyanf171-design/EduSync.git
cd EduSync

# Install dependencies
composer install

# Copy .env file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Install frontend dependencies
npm install && npm run dev

# Start server
php artisan serve
```

---

## 📌 প্রধান ফিচার সামারি

| ফিচার | স্ট্যাটাস | বিবরণ |
|--------|----------|-------|
| মাল্টি-টেন্যান্ট | ✅ | প্রতিটি স্কুল আলাদা ডেটা |
| RBAC | ✅ | ৫টি রোলের জন্য পারমিশন |
| Student Management | ✅ | সম্পূর্ণ শিক্ষার্থী তথ্য ম্যানেজমেন্ট |
| Attendance | ✅ | ডিজিটাল উপস্থিতি ট্র্যাকিং |
| Exam & Results | ✅ | পরীক্ষা, নম্বর এবং গ্রেডিং |
| Fee Management | ✅ | ডিজিটাল ফি জমা ব্যবস্থা |
| bKash Integration | ✅ | পেমেন্ট গেটওয়ে ইন্টিগ্রেশন |
| HR & Payroll | ✅ | কর্মচারী ও বেতন ব্যবস্থাপনা |
| Reporting | ✅ | বিভিন্ন রিপোর্ট জেনারেশন |
| SMS Notification | ✅ | বিজ্ঞপ্তি পাঠানো |

---

## 📞 সাপোর্ট

যেকোনো প্রশ্ন বা সমস্যার জন্য GitHub Issues এ রিপোর্ট করুন।

**Developer:** Raiyan  
**Email:** raiyanf171@gmail.com  
**License:** MIT

---

**Made with ❤️ for Education**
