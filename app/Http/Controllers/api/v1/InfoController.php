<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Informacion;
use App\Models\Noticia;
use App\Models\Reserva;
use App\Models\TipoReserva;
use App\Models\User;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $Lote = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();
        $info = Informacion::where('info_country_id','=',$Lote->lot_country_id)->orderby('info_id','desc')
       ->get();

$response = [
    'data'=>$info
];

        return response($response, 201);
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
            'info_titulo' => 'required|string',
            'info_body' => 'required|string',

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();

        Informacion::create([
            'info_country_id' =>    $country->lot_country_id,
            'info_titulo' => $request->info_titulo,
            'info_body' => $request->info_body,
        ]);


         Notificacion::create([
            'noti_user_id'=>$request->user()->id,
            'noti_aut_code'=> 'INFORMACION',
            'noti_titulo' =>  "Nueva Información Útil.",
            'noti_body' => $request->info_titulo,
            'noti_to' => 'T',
            'noti_to_user' =>$request->user()->id,

            'noti_event' => 'Información' ,
            'noti_priority' =>'high',
            'noti_envio'=> 0,
            'noti_app'=> 1
        ]);

        return response('',201);

    }



    public function destroy(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'info_id' => 'required|integer'

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();

        $info = Informacion::where('info_id','=',$request->info_id)
      ->delete();


    if($info == 0){
        return response(['error' => 'La reversa no existe'], 500);
    }else{
        return response('',201);
    }


 /*        Notificacion::create([
            'noti_user_id'=>$request->user()->id,
            'noti_aut_code'=> 'NOTICIAS',
            'noti_titulo' =>  "Nueva Noticia.",
            'noti_body' =>  $request['titulo'],
            'noti_to' => 'T',
            'noti_to_user' =>$request->user()->id,

            'noti_event' => 'Noticias' ,
            'noti_priority' =>'high',
            'noti_envio'=> 0,
            'noti_app'=> 1
        ]); */



    }



}
