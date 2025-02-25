<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'user_id',
        'lrn',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'province_id',
        'municipality_id',
        'barangay_id',
        'street_address',
        'contact_no',
        'email',
        'father_first_name',
        'father_middle_name',
        'father_last_name',
        'father_occupation',
        'father_contact_no',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'mother_occupation',
        'mother_contact_no',
        'guardian_first_name',
        'guardian_middle_name',
        'guardian_last_name',
        'guardian_occupation',
        'guardian_contact_no',
        'guardian_relation',
        'previous_school_name',
        'birth_certificate',
        'teacher_id',
        'report_card',
        'current_year_level',
        'strand_id',
        'section',
        'school_year',
        'enrollment_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function strand()
    {
        return $this->belongsTo(Strand::class, 'strand_id');
    }

    public function classStudents()
    {
        return $this->hasMany(ClassStudent::class, 'student_id', 'student_id');
    }

}
