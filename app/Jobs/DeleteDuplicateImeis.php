<?php

namespace App\Jobs;

use App\Imei;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Console\Output\ConsoleOutput;

class DeleteDuplicateImeis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeOut = 20000;
    public $tries = 1;

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

        $lastID = \DB::select(
            \DB::raw('SELECT id FROM `tblcodigos` ORDER BY `tblcodigos`.`id` DESC LIMIT 1')
        )[0]->id;

        $quantityProcessed = 0;

        $bash = \DB::table('config')->where('index', 'bash')->first()->value ?? 0;

        (new ConsoleOutput)->writeln(
            "El utlimo bash fue el " . $bash
        );

        Imei::where('id', '>', $bash)->orderBy('imei', 'DESC')->take(10000)->chunk(3000, function ($items) use (&$quantityProcessed, $lastID) {
            $array = [];

            $items->each( function ($item) use (&$array, &$quantityProcessed){
                if (
                    in_array($item->imei, $array)
                    || ($item->codigo1 === '' && $item->imei === '')
                ){
                    $item->delete();
                }
                $array[] = $item->imei;
                $quantityProcessed = $item->id;
            });

            \DB::table('config')->where('index', 'bash')->update(['value' => $quantityProcessed]);

            (new ConsoleOutput)->writeln(
                "Se ha procesado la cantidad de " . $quantityProcessed . " registros para borrado de duplicados de " . $lastID
            );

            unset($array);
        });
    }
}
