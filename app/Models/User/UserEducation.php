<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\FilterByUser;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Library\LibEducationLevel;
use App\Models\Library\LibAcademicProgram;
use OwenIt\Auditing\Contracts\Auditable;

class UserEducation extends Model implements Auditable
{
    use HasFactory, HasUlids, FilterByUser, \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel() {
        return $this->belongsTo(LibEducationLevel::class, 'lib_education_level_id', 'id');
    }

    public function academicProgram() {
        return $this->belongsTo(LibAcademicProgram::class, 'lib_academic_program_id', 'id');
    }
}
