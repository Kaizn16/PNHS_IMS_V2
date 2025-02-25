<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'sex',
        'date_of_birth',
        'civil_status',
        'nationality',
        'province_id',
        'municipality_id',
        'barangay_id',
        'street_address',
        'designation',
        'employment_type',
        'date_hired',
        'employment_status',
        'is_deleted',
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

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject_handle', 'teacher_id', 'subject_id');
    }
}
