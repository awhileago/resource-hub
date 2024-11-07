<?php

namespace App\Models\Info;

use App\Models\User;
use App\Traits\FilterByUser;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Library\LibAverageMonthlyIncome;
use OwenIt\Auditing\Contracts\Auditable;

class ParentInformation extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\Info/ParentInformationFactory> */
    use HasFactory, HasUlids, FilterByUser, \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'ofw_flag',
        'fathers_name',
        'fathers_occupation',
        'fathers_company',
        'mothers_name',
        'mothers_occupation',
        'mothers_company',
        'average_monthly_income',
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
