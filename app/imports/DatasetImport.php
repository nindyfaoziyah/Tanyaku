<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class DatasetImport implements ToArray
{
    public array $rows = [];

    public function array(array $array): void
    {
        $this->rows = $array;
    }
}