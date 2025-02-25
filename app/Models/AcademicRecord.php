<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    protected $table = 'academic_records';

    protected $primaryKey = 'academic_record_id';

    protected $fillable = [
        'class_management_id',
        'semester',
        'school_year',
        'is_deleted'
    ];

    public function classManage()
    {
        return $this->belongsTo(ClassManagement::class, 'class_management_id');
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'academic_record_id', 'academic_record_id');
    }

}
