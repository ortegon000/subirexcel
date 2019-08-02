<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Symfony\Component\Console\Output\ConsoleOutput;

class ImeiImport implements ToArray, WithChunkReading, WithHeadingRow, ShouldQueue
{
    use Importable;

    public $filename;
    public $timeOut = 20000;
    public $tries = 3;

    public $sum = 0;

    public function __construct($fileName)
    {
        set_time_limit ( 3200 );
        ini_set('memory_limit', '2048M');
        $this->filename = $fileName;
    }

    public function array(array $rows)
    {
        foreach ($rows as $row) {
            $code2 = strpos($row['codigo2'], ',') ? explode(',', $row['codigo2'])[0] : $row['codigo2'];
            DB::table('tblcodigos')->insert([
                'imei'    => $row['imei'] ?? '',
                'marca'   => $row['marca'] ?? '',
                'modelo'  => $row['modelo'] ?? '',
                'codigo1' => $row['codigo'] ?? '',
                'codigo2' => $code2 ?? '',
            ]);

            $this->sum++;
        }

        (new ConsoleOutput)->writeln("Number of rows inserted $this->sum on file $this->filename");
    }

    public function chunkSize(): int
    {
        return 8000;
    }
}
