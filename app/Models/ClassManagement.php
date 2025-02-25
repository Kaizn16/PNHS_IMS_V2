<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassManagement extends Model
{
    protected $table = 'class_management';

    protected $primaryKey = 'class_management_id';

    protected $fillable = [
        'class_name',
        'room_id',
        'teacher_id',
        'subject_id',
        'year_level',
        'section',
        'semester',
        'school_year',
        'is_deleted'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_management_id');
    }

    public function students()
    {
        return $this->hasMany(ClassStudent::class, 'class_management_id')
            ->with(['student' => function($query) {
                $query->orderBy('last_name');
            }]);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class, 'class_management_id');
    }

}
