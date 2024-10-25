<?php

namespace App\Models\User;

use App\Models\User;
use App\Traits\FilterByUser;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    /** @use HasFactory<\Database\Factories\User/UserSkillFactory> */
    use HasFactory, HasUlids, FilterByUser;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = ucwords(strtolower($value));
    }
}
