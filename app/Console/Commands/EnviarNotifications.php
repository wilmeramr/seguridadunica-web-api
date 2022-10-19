<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use App\Mail\EmailException;


use App\Models\Autorizaciones;
use App\Models\Country;
use App\Models\User;
use App\Models\Lote;
use App\Models\Notificacion;
use App\Models\Device;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\RegistrationToken;

class EnviarNotifications extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviarnotifications:enviar';

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

                    $notis =  Notificacion::where('noti_envio','=',0)->get();
                   if( $notis->count()>0)
                   {
                    $factory = (new Factory)->withServiceAccount(base_path(env('GOOGLE_APPLICATION_CREDENTIALS'))); // or use auto-loading of credentials

                   }

                    foreach ($notis as $value) {



                                switch ($value->noti_to) {
                                    case 'T':

                                        $user = User::where('id','=',$value->noti_to_user)->select('us_lote_id')->first();
                                        $lote = Lote::where('lot_id','=',$user->us_lote_id)->select('lot_country_id')->first();
                                        $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                                        $users =User::whereIn('us_lote_id',$lotes)->select('id')->get();
                                        $devices = Device::whereIn('dev_user_id',$users)->where('dev_token','!=','x')->get();

                                        foreach ($devices as $device) {

                                            $messaging = $factory->createMessaging();

                                            $message = CloudMessage::withTarget('token',$device->dev_token)
                                                           ->withNotification(Notification::create($value->noti_titulo, $value->noti_body));
                                           try{

                                           $messaging->send($message);

                                        } catch (\Throwable $th) {
                                            $value->update([
                                                'noti_envio'=>3
                                            ]);
                                            \Log::error($th);
                                        }

                                        }
                                        $value->update([
                                            'noti_envio'=>1
                                        ]);
                                        break;

                                    case 'L':
                                        $user = User::where('id','=',$value->noti_to_user)->select('us_lote_id')->first();
                                        $users =User::where('us_lote_id','=',$user->us_lote_id)->select('id')->get();
                                        $devices = Device::whereIn('dev_user_id',$users)->where('dev_token','!=','x')->get();

                                        foreach ($devices as $device) {
                                            $messaging = $factory->createMessaging();

                                            $message = CloudMessage::withTarget('token',$device->dev_token)
                                                           ->withNotification(Notification::create($value->noti_titulo, $value->noti_body));
                                           try{

                                           $messaging->send($message);



                                        } catch (\Throwable $th) {
                                            $value->update([
                                                'noti_envio'=>3
                                            ]);
                                            \Log::error($th);
                                        }


                                    }
                                    $value->update([
                                        'noti_envio'=>1
                                    ]);
                                        break;
                                    case 'I':

                                        $devices = Device::whereIn('dev_user_id','=',$value->noti_to_user)->where('dev_token','!=','x')->get();
                                        $factory = (new Factory)->withServiceAccount(base_path(env('GOOGLE_APPLICATION_CREDENTIALS')));
                                        foreach ($devices as $device) {

                                            $messaging = $factory->createMessaging();

                                            $message = CloudMessage::withTarget('token',$device->dev_token)
                                                           ->withNotification(Notification::create($value->noti_titulo, $value->noti_body));
                                           try{

                                           $messaging->send($message);



                                        } catch (\Throwable $th) {
                                            $value->update([
                                                'noti_envio'=>3
                                            ]);
                                            \Log::error($th);
                                        }
                                    }
                                    $value->update([
                                        'noti_envio'=>1
                                    ]);

                                        break;
                                    default:
                                        # code...
                                        break;
                                }

                    }

    } catch (\Throwable $th) {

        \Log::error($th);

      //  Mail::to(env('MAIL_TO_EXCEPTION'))->send(new EmailException($th,'Envio de Notificacion'));

    }

    }
}
