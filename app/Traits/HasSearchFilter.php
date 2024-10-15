<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait HasSearchFilter
{
    public function scopeSearch($query, $column, $operator = '=', $value = null, $boolean = 'and')
    {
        if (is_array($column)) {
            $value = $this->escapeKeyword($value);

            $query->where(function ($q) use ($column, $operator, $value, $boolean) {
                foreach ($column as $col) {
                    if (! is_null($value)) {
                        $q->where($col, $operator, '%'.$value.'%', $boolean);
                    }
                }
            });

            $this->filterPerWord($query, $column, $operator, $value, $boolean);
        } elseif (! is_null($value)) {
            $query->where($column, $operator, $value, $boolean);
        }
    }

    public function scopeOrSearch($query, $column, $operator = '=', $value = null)
    {
        $query->search($column, $operator, $value, 'or');
    }

    private function filterPerWord($query, $column, $operator, $value, $boolean)
    {
        $arrValue = explode(' ', $value);
        if (count($arrValue) > 1) {
            foreach ($arrValue as $key => $val) {
                $status = $key == 0 ? 'orWhere' : 'where';
                $query->$status(function ($q) use ($column, $operator, $val, $boolean) {
                    foreach ($column as $col) {
                        if (! is_null($val)) {
                            $q->where($col, $operator, '%'.$val.'%', $boolean);
                        }
                    }
                });
            }
        }
    }

    private function escapeKeyword($keyword)
    {
        return str_replace(['\\', "\0", "\n", "\r", "'", '"', "\x1a"], ['\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'], $keyword);
    }
}
