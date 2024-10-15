<?php

namespace App\Models\PSGC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'psgc_10_digit_code', 'region_id', 'name', 'income_class', 'population'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'region_id'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        $request = app('request');

        // Retrieve the parameter value from the route
        $parameterValue = $request->route()->parameter('province');
        //dd($parameterValue);
        // Check if the parameter value is a string and has a length of 10
        if (is_string($parameterValue) && strlen($parameterValue) === 10) {
            return 'psgc_10_digit_code';
        } else {
            // Default to 'slug' if the condition is not met
            return 'code';
        }

    }

    public function municipalities()
    {
        return $this->morphMany(Municipality::class, 'geographic');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

}
