<?php

namespace App\Models\Posting;

use App\Models\Library\LibPostingCategory;
use App\Models\PSGC\Barangay;
use App\Models\User;
use App\Traits\FilterByUser;
use App\Traits\HasSearchFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Posting extends Model
{
    /** @use HasFactory<\Database\Factories\Posting/PostingFactory> */
    use HasFactory, FilterByUser, HasUlids, HasSpatial, HasSearchFilter;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'date_published' => 'datetime:Y-m-d',
            'date_end' => 'datetime:Y-m-d',
            'coordinates' => Point::class
        ];
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = ucwords(strtolower($value));
    }

    public function setCoordinatesAttribute($value)
    {
        // Handle string input or array input for coordinates
        if (is_string($value)) {
            // Parse the string into latitude and longitude if needed
            [$latitude, $longitude] = explode(',', $value);
            $this->attributes['coordinates'] = (new Point(trim($latitude), trim($longitude), Srid::WGS84->value))->toWkb();
        } elseif (is_array($value) && isset($value['latitude'], $value['longitude'])) {
            // Create a Point object from the array and convert it to WKB
            $this->attributes['coordinates'] = (new Point($value['latitude'], $value['longitude'], Srid::WGS84->value))->toWkb();
        } else {
            throw new \InvalidArgumentException("Invalid coordinates provided");
        }
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_code', 'psgc_10_digit_code');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(LibPostingCategory::class, 'lib_posting_category_id', 'id');
    }

    public function applicants()
    {
        return $this->hasMany(PostingApplication::class);
    }
}
