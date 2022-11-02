<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Noticia;
use App\Models\Reserva;
use App\Models\TipoReserva;
use App\Models\User;

use Illuminate\Http\Request;

class TipoReservasController extends Controller
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
        $tReserva = TipoReserva::where('tresr_country_id','=',$Lote->lot_country_id)
        ->where('tresr_tipo','=',$request->tipo)
        ->where('tresr_activo','=',1)->get();



        return response($tReserva, 201);
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
            'resr_tipo_id' => 'required|integer',
            'horarioId' => 'required|integer',
            'fecha'=> 'required|date',
            'cant_lugar'=>'required|integer'
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();

        $reserva = Reserva::where('resr_country_id','=',$country->lot_country_id)
        ->where('resr_tipo_id','=', $request->resr_tipo_id)
        ->where('resr_horario_id','=', $request->horarioId)
        ->where('resr_fecha','=', $request->fecha)
        ->where('resr_lugar','=', $request->cant_lugar)
        ->where('resr_cant_personas','=', $request->cant_lugar)->get();



    if($reserva->count() > 0){
        return response(['error' => 'El lugar ya ha sido ocupado'], 500);
    }

        Reserva::create([
            'resr_country_id' =>    $country->lot_country_id,
            'resr_lote_id' => $request->user()->us_lote_id,
            'resr_tipo_id' => $request->resr_tipo_id,
            'resr_horario_id' =>$request->horarioId,
            'resr_fecha' => $request->fecha,
            'resr_lugar' => $request->cant_lugar,
            'resr_cant_personas' => $request->cant_lugar,
            'resr_activo' =>1
        ]);


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

        return response('',201);

    }



    public function destroy(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'resr_id' => 'required|integer'

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();

        $reserva = Reserva::where('resr_id','=',$request->resr_id)
        ->where('resr_country_id','=',$country->lot_country_id)
        ->where('resr_tipo_id','=', $request->resr_tipo_id)
        ->where('resr_horario_id','=', $request->horarioId)
        ->where('resr_fecha','=', $request->fecha)
        ->where('resr_lugar','=', $request->cant_lugar)
        ->where('resr_cant_personas','=', $request->cant_lugar)->delete();


    if($reserva == 0){
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
