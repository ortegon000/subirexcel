<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Symfony\Component\Console\Output\ConsoleOutput;

class ImeiImport implements ToArray, WithChunkReading, WithHeadingRow, ShouldQueue
{
    use Importable, RegistersEventListeners;

    public $timeout = 20000;
    public $tries = 3;
    public $filename;

    public function __construct($fileName)
    {
        $this->filename = $fileName;
    }

    public function array(array $rows)
    {
        set_time_limit ( 3200 );

        foreach ($rows as $row) {
            $code2 = strpos($row['codigo2'], ',') ? explode(',', $row['codigo2'])[0] : $row['codigo2'];
            DB::table('tblcodigos')->insert([
                'imei'    => $row['imei'] ?? '',
                'marca'   => $row['marca'] ?? '',
                'modelo'  => $row['modelo'] ?? '',
                'codigo1' => $row['codigo'] ?? '',
                'codigo2' => $code2 ?? '',
            ]);
        }

        $console =  new ConsoleOutput;
        $console->writeln("File name: $this->filename");

    }

    public function chunkSize(): int
    {
        return 10000;
    }
}
