<?php

namespace App\Models\Posting;

use App\Models\SMS\SmsLog;
use App\Models\User;
use App\Traits\FilterByUser;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PostingApplication extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\Posting/PostingApplicationFactory> */
    use HasFactory, FilterByUser, HasUlids, \OwenIt\Auditing\Auditable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'date_applied' => 'datetime:Y-m-d',
            'status_date' => 'datetime:Y-m-d',
        ];
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }

    public function posting()
    {
        return $this->belongsTo(Posting::class);
    }

    public function smsLogs()
    {
        return $this->hasMany(SmsLog::class);
    }
}
