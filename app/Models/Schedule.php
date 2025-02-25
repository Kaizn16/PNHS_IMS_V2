<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = "schedules";

    protected $primaryKey = "schedule_id";

    protected $fillable = [
        'class_management_id',
        'day',
        'time_start',
        'time_end',
    ];

    public function manageClass()
    {
        return $this->belongsTo(ClassManagement::class, 'class_management_id');
    }
}
