<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectorsPathTest extends Model
{
    protected $guarded  =   [];

    public $timestamps = false;

    public function path_test()
    {
        return $this->belongsTo('App\Models\PathTest');
    }

}
