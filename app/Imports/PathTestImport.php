<?php

namespace App\Imports;

use App\Models\PathTest;

class PathTestImport
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public static function insert(array $row)
    {
        if(count($row) == 3) {
            return PathTest::updateOrCreate(
                ['name'      => $row[0], 'sample'    => $row[1]],
                ['price'     => $row[2]]
            );
        }
        throwValidationError('csv_file', 'Please choose a valid csv file with proper structure.');

    }
}
