<?php

namespace App\Models\PSGC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'psgc_10_digit_code', 'geographic_type', 'geographic_id', 'name', 'geo_level', 'income_class', 'population'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'geographic_id'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        $request = app('request');

        // Retrieve the parameter value from the route
        $parameterValue = $request->route()->parameter('municipality');
        //dd($parameterValue);
        // Check if the parameter value is a string and has a length of 10
        if (is_string($parameterValue) && strlen($parameterValue) === 10) {
            return 'psgc_10_digit_code';
        } else {
            // Default to 'slug' if the condition is not met
            return 'code';
        }
    }

    /**
     * Collection of barangays under this municipality.
     */
    public function barangays()
    {
        return $this->morphMany(Barangay::class, 'geographic');
    }

    /**
     * Province or District that this municipality belongs to.
     */
    public function geographic()
    {
        return $this->morphTo();
    }
}
