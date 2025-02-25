<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = "class_student";

    protected $primaryKey = "class_student_id";

    protected $fillable = [
        'class_management_id',
        'student_id',
    ];

    public function manageClass()
    {
        return $this->belongsTo(ClassManagement::class, 'class_management_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
