<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    protected $fillable = [
        'strand_id', 'subject_code', 'subject_title', 'subject_description'
    ];

    public function strand() 
    {
        return $this->belongsTo(Strand::class, 'strand_id');
    }
}
