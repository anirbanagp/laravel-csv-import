<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    protected $guarded = [];

    public function billing()
    {
        return $this->belongsTo('App\Models\Billing');
    }

}
