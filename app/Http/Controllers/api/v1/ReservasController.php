<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Horario;
use App\Models\Noticia;
use App\Models\Reserva;
use App\Models\TipoReserva;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasController extends Controller
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
        $Reserva = Reserva::where('resr_lote_id','=',$request->user()->us_lote_id)
        ->join('users','users.id','=','reservas.resr_user_id')
        ->join('horarios','horarios.hor_id','=','reservas.resr_horario_id')
        ->join('tipo_reservas','tipo_reservas.tresr_id','=','reservas.resr_tipo_id')


        ->select('reservas.resr_id','reservas.resr_fecha','tipo_reservas.tresr_description',
        DB::raw('(CASE WHEN resr_comentarios IS NULL THEN ""
         ELSE resr_comentarios END) AS resr_comentarios '),

        DB::raw('(CASE WHEN tipo_reservas.tresr_tipo = 1 THEN CONCAT("CANCHA: ",reservas.resr_lugar)
        WHEN resr_tipo_id = 2 THEN  CONCAT( "Cantidad de Asistentes: ",reservas.resr_lugar) ELSE "Otros" END) AS ubicacion ')
        ,'users.us_name','users.us_apellido',
        DB::raw('(CASE WHEN tipo_reservas.tresr_tipo = 1 THEN "Deportes"
        WHEN tipo_reservas.tresr_tipo = 2 THEN "Comida" ELSE "Otros" END) AS resr_tipo '), 'horarios.hor_range','reservas.created_at')
        ->orderby('resr_id','desc')->paginate(20);


        return response($Reserva, 201);
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
            'resr_user_id' => $request->user()->id,
            'resr_tipo_id' => $request->resr_tipo_id,
            'resr_horario_id' =>$request->horarioId,
            'resr_fecha' => $request->fecha,
            'resr_lugar' => $request->cant_lugar,
            'resr_cant_personas' => $request->cant_lugar,
            'resr_activo' =>1
        ]);

       $treserva = TipoReserva::where('tresr_id','=',$request->resr_tipo_id)->first();
        $horario = Horario::where('hor_id','=',$request->horarioId)->first();
         Notificacion::create([
            'noti_user_id'=>$request->user()->id,
            'noti_aut_code'=> 'RESERVA',
            'noti_titulo' =>  "Reservación.",
            'noti_body' => 'Se realizo una reserva en '.$treserva->tresr_description.' para la fecha '.\Carbon\Carbon::parse($request->fecha)->format('d/m/Y H:i').' en el horario '. $horario->hor_range ,
            'noti_to' => 'L',
            'noti_to_user' =>$request->user()->id,

            'noti_event' => 'Reserva' ,
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
            'resr_id' => 'required|integer'

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();


        $reservaData = Reserva::where('resr_id','=',$request->resr_id)
        ->where('resr_country_id','=',$country->lot_country_id)
       // ->where('resr_tipo_id','=', $request->resr_tipo_id)
       // ->where('resr_horario_id','=', $request->horarioId)
       // ->where('resr_fecha','=', $request->fecha)
       // ->where('resr_lugar','=', $request->cant_lugar)
       // ->where('resr_cant_personas','=', $request->cant_lugar)
       ->first();

    $reserva = Reserva::where('resr_id','=',$request->resr_id)
    ->where('resr_country_id','=',$country->lot_country_id)
   // ->where('resr_tipo_id','=', $request->resr_tipo_id)
   // ->where('resr_horario_id','=', $request->horarioId)
   // ->where('resr_fecha','=', $request->fecha)
   // ->where('resr_lugar','=', $request->cant_lugar)
   // ->where('resr_cant_personas','=', $request->cant_lugar)
   ->delete();
    if($reserva == 0){
        return response(['error' => 'La reversa no existe'], 500);
    }else{
    $treserva = TipoReserva::where('tresr_id','=',$reservaData->resr_tipo_id)->first();
    $horario = Horario::where('hor_id','=',$reservaData->resr_horario_id)->first();
     Notificacion::create([
        'noti_user_id'=>$request->user()->id,
        'noti_aut_code'=> 'RESERVA',
        'noti_titulo' =>  "Eliminacion de Reservación.",
        'noti_body' => 'Se Elimino la reserva en '.$treserva->tresr_description.' para la fecha '.\Carbon\Carbon::parse($reservaData->resr_fecha)->format('d/m/Y H:i').' en el horario '. $horario->hor_range ,
        'noti_to' => 'L',
        'noti_to_user' =>$request->user()->id,

        'noti_event' => 'Reserva' ,
        'noti_priority' =>'high',
        'noti_envio'=> 0,
        'noti_app'=> 1
    ]);

        return response('',201);
    }


    }

    public function destroyRHorarios(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'resr_tipo_id' => 'required|integer',
            'horarioId' => 'required|integer',
            'cant_lugar' =>'required|integer',
            'fecha'=>'required|date'


        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        $country = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();


        $reservaData = Reserva::where('resr_country_id','=',$country->lot_country_id)
        ->where('resr_tipo_id','=', $request->resr_tipo_id)
        ->where('resr_horario_id','=', $request->horarioId)
        ->where('resr_fecha','=', $request->fecha)
        ->where('resr_lugar','=', $request->cant_lugar)
       // ->where('resr_cant_personas','=', $request->cant_lugar)
       ->first();

    $reserva = Reserva::where('resr_country_id','=',$country->lot_country_id)
    ->where('resr_tipo_id','=', $request->resr_tipo_id)
    ->where('resr_horario_id','=', $request->horarioId)
    ->where('resr_fecha','=', $request->fecha)
    ->where('resr_lugar','=', $request->cant_lugar)
   // ->where('resr_cant_personas','=', $request->cant_lugar)
   ->delete();
    if($reserva == 0){
        return response(['error' => 'La reversa no existe'], 500);
    }else{
    $treserva = TipoReserva::where('tresr_id','=',$reservaData->resr_tipo_id)->first();
    $horario = Horario::where('hor_id','=',$reservaData->resr_horario_id)->first();
     Notificacion::create([
        'noti_user_id'=>$request->user()->id,
        'noti_aut_code'=> 'RESERVA',
        'noti_titulo' =>  "Eliminacion de Reservación.",
        'noti_body' => 'Se Elimino la reserva en '.$treserva->tresr_description.' para la fecha '.\Carbon\Carbon::parse($reservaData->resr_fecha)->format('d/m/Y H:i').' en el horario '. $horario->hor_range ,
        'noti_to' => 'L',
        'noti_to_user' =>$request->user()->id,

        'noti_event' => 'Reserva' ,
        'noti_priority' =>'high',
        'noti_envio'=> 0,
        'noti_app'=> 1
    ]);

        return response('',201);
    }


    }



}
