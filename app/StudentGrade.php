<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    protected $table = 'student_grades';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['student_grades'];
    // protected $hidden = [];
    // protected $dates = [];
}
