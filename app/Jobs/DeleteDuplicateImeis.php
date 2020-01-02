<?php

namespace App\Jobs;

use App\Imei;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Console\Output\ConsoleOutput;

class DeleteDuplicateImeis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 20000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        set_time_limit ( 12000 );
        ini_set('memory_limit', '2048M');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $quantityDeleted = 0;
        $bash = \DB::table('config')->where('index', 'bash')->first()->value ?? 0;

        (new ConsoleOutput)->writeln(
            "El utlimo bash fue el " . $bash
        );

        Imei::orderBy('imei', 'DESC')
        ->skip($bash)
        ->take(100)
        ->chunk(10, function ($items) use (&$quantityDeleted, &$bash) {
            $array = [];

            $items->each( function ($item) use (&$array, &$quantityDeleted, &$bash){
                if ( in_array($item->imei, $array) ){
                    $item->delete();
                    $quantityDeleted++;
                }

                if ($item->codigo1 <= '' && $item->imei <= '') {
                    $item->delete();
                    $quantityDeleted++;

                    (new ConsoleOutput)->writeln(
                        "Registro borrado por informacion inexistente"
                    );
                }

                $array[] = $item->imei;
                $bash++;
            });

            \DB::table('config')->where('index', 'bash')->update(['value' => $bash]);

            (new ConsoleOutput)->writeln(
                "Se ha borrado la cantidad de " . $quantityDeleted . " registros"
            );

            unset($array);
        });
    }
}
