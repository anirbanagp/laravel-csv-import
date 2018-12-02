<?php

namespace App\Imports;

use App\Models\PathTest;
use App\Models\CollectorsPathTest;

class CollectorPathTestImport
{
    /**
    * @param array $row
    *
    * @return 
    */
    public static function insert(array $row, int $collectorId)
    {
        if(count($row) == 3) {
            $pathTest   =   PathTest::select('id')->whereName($row[0])->whereSample($row[1])->first();
            if(isset($pathTest->id)) {
                $collector  =   CollectorsPathTest::updateOrCreate(
                    ['path_test_id' => $pathTest->id, 'collector_id'    => $collectorId],
                    ['commission'   =>  $row[2]]
                );
                return $collector;
            } else {
                return false;
            }
        }
        throwValidationError('csv_file', 'Please choose a valid csv file with proper structure.');

    }
}
