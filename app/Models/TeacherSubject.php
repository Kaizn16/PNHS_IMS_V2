<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $table = 'teacher_subject_handle';
    protected $primaryKey = 'id';

    protected $fillable = [
        'teacher_id',
        'subject_id'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
