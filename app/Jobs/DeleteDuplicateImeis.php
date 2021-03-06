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
        $max = Imei::count();

        (new ConsoleOutput)->writeln(
            "El utlimo bash fue el " . $bash
        );

        Imei::where('imei', '')
            ->where('marca', '')
            ->where('modelo', '')
            ->delete();

        Imei::orderBy('imei', 'DESC')
        ->skip($bash)
        ->chunk(2000, function ($items) use (&$quantityDeleted, &$bash) {
            $array = [];

            foreach ($items as $item) {
                if ( in_array($item->imei, $array) ){
                    $item->delete();
                    $quantityDeleted++;
                }

                $array[] = $item->imei;
                $bash++;
            }

            \DB::table('config')->where('index', 'bash')->update(['value' => $bash]);

            (new ConsoleOutput)->writeln(
                "Se ha borrado la cantidad de " . $quantityDeleted . " registros de " . $bash . " procesados"
            );

            unset($array);
        });

        (new ConsoleOutput)->writeln(
            "El utlimo bash procesado fue el " . $bash
        );
    }
}
