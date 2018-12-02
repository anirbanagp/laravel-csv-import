<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    public function getCodeAttribute($value)
    {

        return str_contains($value, 'AHC-P-') ? $value : ('AHC-P-'.$value);
    }
    public function getVisibleNameAttribute()
    {
        return $this->name . ' ['. $this->code .  ' ]';
    }

}
