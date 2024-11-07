<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\FilterByUser;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserEmployment extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\User/UserEmploymentFactory> */
    use HasFactory, HasUlids, FilterByUser, \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m',
            'end_date' => 'date:Y-m',
        ];
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::createFromFormat('Y-m', $value)->startOfMonth();
    }

    public function setEndDateAttribute($value)
    {
        if($value) {
            $this->attributes['end_date'] = Carbon::createFromFormat('Y-m', $value)->startOfMonth();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setEmployerNameAttribute($value)
    {
        $this->attributes['employer_name'] = ucwords(strtolower($value));
    }

    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = ucwords(strtolower($value));
    }
}
