<?php

namespace App\Imports;

use App\Models\Collector;

class CollectorImport
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Collector|null
    */
    public static function insert(array $row)
    {
        if(count($row) == 2) {

            $collector  = Collector::firstOrCreate(['name'      => $row[0], 'ph_number'    => $row[1]]);

            $collector->code = $collector->id;
            $collector->save();

            return $collector;
        }
        throwValidationError('csv_file', 'Please choose a valid csv file with proper structure.');

    }
}
