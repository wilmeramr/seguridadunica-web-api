<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;


use App\Models\User;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $userC;
    public function index(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $Lote = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();
        $Lotes = Lote::where('lot_country_id','=',$Lote->lot_country_id)->select('lot_id')->get();
        $userL = User::where('us_lote_id','=',$request->user()->us_lote_id)->select('id')->get();
        $this->userC = User::whereIn('us_lote_id',$Lotes)->select('id')->get();

       // error_log("".$arr);
       $noti = Notificacion::whereIn('noti_to_user', $userL)->OrWhere( function($query) {
        $query->whereIn('noti_to_user', $this->userC)
        ->Where('noti_to','like','%A%');
                })->orderby('id','desc')->paginate(20);
    //    $noti = Notificacion::whereIn('noti_user_id', $userL)->orderby('id','desc')->paginate(20);
       foreach ( $noti as  $value) {



           switch ($value->noti_to) {
               case 'I':
                $users = User::where('id','=',$value->noti_to_user)->select('us_name','us_apellido')->first();
                $value->noti_to='I:'.$users->us_name.' '.$users->us_apellido ;
                   # code...
                   break;
                   case 'L':
                    $users = User::where('id','=',$value->noti_to_user)->select('us_name')->first();
                    $value->noti_to='L:'.$users->us_name ;
                       # code...
                       break;
                       case 'T':
                        $users = User::where('id','=',$value->noti_to_user)->select('us_lote_id')->first();
                        $lotes= Lote::where('lot_id','=', $users->us_lote_id)->select('lot_country_id')->first();
                        $country = Country::where('co_id','=',$lotes->lot_country_id)->select('co_name')->first();
                        $value->noti_to='T:'.$country->co_name ;
                           # code...
                           break;
               default:
                   # code...
                   break;
           }
       }

        return response($noti, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'to' => 'required|string',
            'to_user' => 'required|string',
            'titulo' => 'required|string',
            'body' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }

        Notificacion::create([
            'noti_user_id' => $request->user()->id,
            'noti_aut_code'=> 'NOTIFICACIONES',
            'noti_titulo' => $request['titulo'],
            'noti_body' =>$request['body'],
            'noti_to' =>$request['to'],
            'noti_to_user' =>$request['to'] =='T'? $request->user()->id : $request['to_user'] ,
            'noti_event' =>'Notificacion',
            'noti_priority' =>'hight',
            'noti_envio' =>0,
            'noti_app'=> 1
        ]);

        return response('',201);

    }


}
