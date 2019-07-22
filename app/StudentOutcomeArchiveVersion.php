<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentOutcomeArchiveVersion extends Model
{
    public $guarded = [];

    public function student_outcome_archives() {
        return StudentOutcomeArchive::where('revision_no', $this->revision_no)->orderBy('so_code', 'ASC')->get();
    }
}
