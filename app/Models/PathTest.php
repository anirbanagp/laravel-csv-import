<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PathTest extends Model
{
    protected $guarded  =   [];

    public function getVisibleNameAttribute()
    {
        return $this->name . ' - '. $this->sample .  ' ('. priceFormat($this->price) .')';
    }
    public function getVisibleNameWithMRPAttribute()
    {
        return $this->name . ' - '. $this->sample .  ' (MRP : '. priceFormat($this->price) .')';
    }
}
