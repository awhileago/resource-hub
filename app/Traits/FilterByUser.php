<?php

namespace App\Traits;

trait FilterByUser
{
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if(auth()->user()) {
                $model->user_id = auth()->id();
            }
        });
    }
}
