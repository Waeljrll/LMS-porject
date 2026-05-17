<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseObjective extends Model
{
    protected $fillable = ['course_id', 'objective'];
    protected $table = 'course_objectives';
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
