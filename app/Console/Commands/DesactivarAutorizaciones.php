<?php

namespace App\Console\Commands;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenReceived;
use App\Mail\EmailAutorizacion;
use App\Mail\EmailException;


use App\Models\Autorizaciones;
use App\Models\Country;
use App\Models\User;
use App\Models\Lote;
use App\Models\Notificacion;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


use DNS2D;
use Illuminate\Support\Str;

class DesactivarAutorizaciones extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Autorizaciones:Desactivar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

            try {
                $DateAndTime = date('Y-m-d');

             //   \Log::error($DateAndTime);
                    $aut =  Autorizaciones::where('aut_activo','=',1)->where('aut_hasta', '<',  $DateAndTime)->update([
                        'aut_activo'=>0
                    ]);
                    \Log::info("Se ejecuto DesactivarAutorizaciones afecto: ".$aut);


    } catch (\Throwable $th) {

        \Log::error($th);

    }

    }
}
