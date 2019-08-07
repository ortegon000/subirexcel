<?php

namespace App\Jobs;

use App\Imei;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SebastianBergmann\Environment\Console;
use Symfony\Component\Console\Output\ConsoleOutput;

class DeleteDuplicateImeis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeOut = 20000;
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $array = [];
        Imei::chunk(10000, function ($items) use (&$array) {
            $items->each( function ($item) use (&$array) {
                if ( in_array($item->imei, $array) ) {
                    $item->delete();
                }
                $array[] = $item->imei;
            });

            $consoleOutput = new ConsoleOutput;
            $consoleOutput->writeln("Se ha procesado la cantidad de " . count($array) . " registros para borrado de duplicados");
        });
    }
}
