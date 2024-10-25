<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contracts\MustVerifyMobileNumber;
use App\Models\Info\ParentInformation;
use App\Models\Library\LibSuffixName;
use App\Models\Library\LibSchool;
use App\Models\Library\LibAcademicProgram;
use App\Models\Library\LibYearLevel;
use App\Models\User\UserEducation;
use App\Models\SMS\Otp;
use App\Models\User\UserEmployment;
use App\Models\User\UserReference;
use App\Traits\HasSearchFilter;
use App\Traits\VerifiesMobileNumber;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, MustVerifyMobileNumber
{
    use HasFactory, Notifiable, HasUlids, HasApiTokens, HasSearchFilter, VerifiesMobileNumber;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
        'mobile_verified_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }

    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = ucwords(strtolower($value));
    }

    public function suffixName()
    {
        return $this->belongsTo(LibSuffixName::class, 'suffix_name', 'code');
    }

    public function parents()
    {
        return $this->hasOne(ParentInformation::class);
    }

    public function school()
    {
        return $this->belongsTo(LibSchool::class, 'lib_school_id', 'id');
    }

    public function academicProgram()
    {
        return $this->belongsTo(LibAcademicProgram::class, 'lib_academic_program_id', 'id');
    }

    public function yearLevel()
    {
        return $this->belongsTo(LibYearLevel::class, 'lib_year_level_id', 'id');
    }

    public function otp()
    {
        return $this->hasOne(Otp::class)->latest('created_at');
    }

    public function userEducation() {
        return $this->hasMany(UserEducation::class, 'user_id', 'id');
    }

    public function education()
    {
        return $this->hasMany(UserEducation::class);
    }

    public function employment()
    {
        return $this->hasMany(UserEmployment::class)->orderByRaw('ISNULL(end_date) DESC, end_date DESC');
    }

    public function reference()
    {
        return $this->hasMany(UserReference::class)->orderBy('full_name');
    }

    public function skill()
    {
        return $this->hasMany(UserReference::class)->orderBy('description');
    }

}
