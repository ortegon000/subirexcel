<?php

namespace App\Jobs;

use App\Imei;
use App\Mail\DeletedDuplicatesMail;
use App\Mail\ImeisImportedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        Imei::chunk(25000, function ($items) use (&$array) {
            $items->each( function ($item) use (&$array) {
                if ( in_array($item->imei, $array) ) {
                    $item->delete();
                }
                $array[] = $item->imei;
            });
        });

        Mail::to('info@liberacionesporimei.com')->send( new DeletedDuplicatesMail() );
        Log::info('Mailed deleted to info@liberacionesporimei.com');
    }
}
