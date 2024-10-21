<?php

namespace App\Models\Posting;

use App\Models\User;
use App\Traits\FilterByUser;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostingApplication extends Model
{
    /** @use HasFactory<\Database\Factories\Posting/PostingApplicationFactory> */
    use HasFactory, FilterByUser, HasUlids;

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
}
