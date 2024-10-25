<?php

namespace App\Models\Info;

use App\Models\User;
use App\Traits\FilterByUser;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Library\LibAverageMonthlyIncome;

class ParentInformation extends Model
{
    /** @use HasFactory<\Database\Factories\Info/ParentInformationFactory> */
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

    public function monthlyIncome()
    {
        return $this->belongsTo(LibAverageMonthlyIncome::class, 'average_monthly_income', 'id');
    }
}
