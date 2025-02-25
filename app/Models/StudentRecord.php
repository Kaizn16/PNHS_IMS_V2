<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    protected $table = "student_records";

    protected $primaryKey = "student_record_id";

    protected $fillable = [
        'academic_record_id',
        'student_id',
        'exam_type',
        'grade'
    ];

    public function academicRecords()
    {
        return $this->belongsTo(AcademicRecord::class, 'academic_record_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
