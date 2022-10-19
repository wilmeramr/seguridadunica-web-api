<?php

namespace App\Console\Commands;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenReceived;
use App\Mail\EmailIngresos;
use App\Mail\EmailException;


use App\Models\Autorizaciones;
use App\Models\Country;
use App\Models\Ingreso;
use App\Models\User;
use App\Models\Lote;
use App\Models\Notificacion;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


use DNS2D;
use Illuminate\Support\Str;

class EnviarMailIngreso extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailIngreso:Enviar';

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
                    $ingr =  Ingreso::join('users as usr','usr.id','ingresos.ingr_user_auth')
                    ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
                    ->join('countries as cot','cot.co_id','lt.lot_country_id')
                    ->select('usr.us_lote_id','ingresos.ingr_id','ingresos.ingr_documento','ingresos.ingr_nombre','ingr_observacion','ingresos.ingr_entrada','ingresos.ingr_salida','ingresos.ingr_entrada_envio','ingresos.ingr_salida_envio','cot.co_logo'
                    )
                    ->where('ingr_entrada_envio','=',0)->whereNotNull('ingr_entrada')
                    ->orWhere(function ($q) {
                        $q->Where('ingr_salida_envio', '=',0)->whereNotNull('ingr_salida');
                    })
                    ->get();




                                foreach($ingr as $ingrValue){

                                        $users = User::where('us_lote_id','=',$ingrValue->us_lote_id)->select('email')->get();
                                        //\Log::error($users);

                                   foreach($users as $usr){

                                        if($ingrValue->ingr_entrada_envio==0){
                                            Mail::to( $usr->email)->send(new EmailIngresos($ingrValue,1));
                                        }

                                        if($ingrValue->ingr_salida_envio==0){
                                            Mail::to( $usr->email)->send(new EmailIngresos($ingrValue,0));
                                        }
                                   }




                                    if($ingrValue->ingr_entrada_envio==0){
                                        Ingreso::where('ingr_id','=',$ingrValue->ingr_id)->update(['ingr_entrada_envio'=>1]
                                        );
                                    }

                                    if($ingrValue->ingr_salida_envio==0){
                                        Ingreso::where('ingr_id','=',$ingrValue->ingr_id)->update([ 'ingr_salida_envio'=>1]);
                                    }

                                }

                        } catch (\Throwable $th) {

                            \Log::error($th);
                        }

    }
}
