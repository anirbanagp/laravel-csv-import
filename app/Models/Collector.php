<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    protected $guarded  =   [];

    public function getVisibleNameAttribute()
    {
        return $this->name.' ['.$this->code.']';
    }

    // public function setCodeAttribute()
    // {
    //     $this->attributes['code']   =   'AHC-C-'.($this->id + 1005);
    // }

    public function collector_path_tests()
    {
        return $this->hasMany('App\Models\CollectorsPathTest');
    }

    public function getAssocPriceOf($testId, $originalPrice)
    {
        $assoc_price    =   $this->collector_path_tests()->select('price')->wherePathTestId($testId)->first();

        return optional($assoc_price)->price ?? $originalPrice;
    }
}
