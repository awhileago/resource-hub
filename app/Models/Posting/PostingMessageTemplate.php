<?php

namespace App\Models\Posting;

use App\Models\User;
use App\Traits\FilterByUser;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostingMessageTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\Posting/PostingMessageTemplateFactory> */
    use HasFactory, FilterByUser, HasUlids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posting()
    {
        return $this->belongsTo(Posting::class);
    }
}
