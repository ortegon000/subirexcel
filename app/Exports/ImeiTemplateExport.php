<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ImeiTemplateExport implements FromArray
{

    /**
     * @return array
     */
    public function array(): array
    {
       return [
           ['marca', 'modelo', 'imei', 'codigo1', 'codigo2']
       ];
    }
}
