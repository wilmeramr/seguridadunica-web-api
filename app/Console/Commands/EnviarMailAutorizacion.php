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

class EnviarMailAutorizacion extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailautorizacion:enviar';

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
      //  \Log::error(env('MAIL_TO_EXCEPTION'));
       // Mail::to('wilmeramr@gmail.com')->send(new EmailException('error','envio mail'));
      //  Mail::to(env('MAIL_TO_EXCEPTION'))->send(new EmailException('error','envio mail'));
            try {
                //code...
              //  throw new Exception('Something Went Wrong.');
                    //   \Log::error(env('MAIL_TO_EXCEPTION'));
                    $aut =  Autorizaciones::where('aut_envio_mail','=',0)->where('aut_email', '<>', '')->get();

                   // \Log::error($aut);

                    foreach ($aut as $value) {


                                    $user = User::where("id","=",$value->aut_user_id)->first();
                                    $lote = Lote::where("lot_id","=",$user->us_lote_id)->first();
                                    $country = Country::where('co_id',"=",$lote->lot_country_id)->first();

                                        $png_url = Str::uuid().".png";
                                        $path = 'img/barcode/'.$png_url;

                                     $success =   \Storage::put( $path ,base64_decode(DNS2D::getBarcodePNG($value->aut_documento, "PDF417")));
                                     //   \Log::error($success.$path);

                                        //  $success = file_put_contents($path, base64_decode( DNS2D::getBarcodePNG($value->aut_documento,'PDF417')));

                                        if($success!=false){

                                            try {
                                              $url = Storage::url('img/barcode/'.$png_url);


                                                $value::where('aut_id','=',$value->aut_id)->update([

                                                    "aut_envio_mail"=>1,
                                                    "aut_barcode"=>  $url
                                                ]);
                                                $value->aut_barcode = $url;
                                                //\Log::error($country);
                                                Mail::to($value->aut_email)->send(new EmailAutorizacion($country,$value));


                                               // $value->aut_envio_mail =1;
                                               // $value->aut_barcode = Storage::url('img/barcode/'.$png_url);
                                                //$value->save();

                                            } catch (\Throwable $th) {
                                                    \Log::error($th);
                                            Mail::to(env('MAIL_TO_EXCEPTION'))->send(new EmailException($th,'envio mail autorizacion'));
                                            }

                                        }
                    }

    } catch (\Throwable $th) {

        \Log::error($th);

   //     Mail::to(env('MAIL_TO_EXCEPTION'))->send(new EmailException($th,'Consulta a autorizacion'));

    }

    }
}
