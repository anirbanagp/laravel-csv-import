<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $guarded = [];

    public function billing_details()
    {
        return $this->hasMany('App\Models\BillingDetail');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }
    public function collector()
    {
        return $this->belongsTo('App\Models\Collector');
    }

    public function setCodeAttribute()
    {
        $this->attributes['code']   =   'AHC-B-'.($this->id + 1005);
    }
    public function getNetAmountAttribute()
    {
        return $this->total_amount - $this->discount_or_commission;
    }
    public function getDueAmountAttribute()
    {
        return $this->net_amount - $this->paid_amount;
    }
    public function getBillDateAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }
}
