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
        set_time_limit ( 12000 );
        ini_set('memory_limit', '2048M');

        $lastID = Imei::orderBy('id', 'DESC')->first()->id;
        $quantityProcessed = 0;
        Imei::chunk(50000, function ($items) use (&$quantityProcessed, $lastID) {
            $array = [];

            $items->each( function ($item) use (&$array, &$quantityProcessed){
                if ( in_array($item->imei, $array) ) {
                    $item->delete();
                }
                $array[] = $item->imei;
                $quantityProcessed = $item->id;
            });

            (new ConsoleOutput)->writeln(
                "Se ha procesado la cantidad de " . $quantityProcessed . " registros para borrado de duplicados de " . $lastID
            );

            unset($array);
        });
    }
}
