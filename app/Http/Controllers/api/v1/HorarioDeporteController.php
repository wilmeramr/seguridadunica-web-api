<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Horario;
use App\Models\Noticia;
use App\Models\TipoReserva;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioDeporteController extends Controller
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
        $country_id = Lote::where('lot_id','=',$request->user()->us_lote_id)->select('lot_country_id')->first();

        $horarios = Horario::where('hor_tipo','=',$request->tresr_tipo_horarios)
        ->select('horarios.*',
         DB::raw("(SELECT count(1) FROM reservas
         inner join tipo_reservas treserva on treserva.tresr_id = reservas.resr_tipo_id
         WHERE
          resr_horario_id= hor_id and resr_country_id=".$country_id->lot_country_id." and treserva.tresr_tipo=1
          and resr_lugar=".$request->lugar." and 	resr_fecha='".$request->fecha."') as no_habilitado"),
          DB::raw("(CASE WHEN (SELECT resr_lote_id FROM reservas
          inner join tipo_reservas treserva on treserva.tresr_id = reservas.resr_tipo_id
          WHERE
          resr_horario_id= hor_id and resr_country_id=".$country_id->lot_country_id." and treserva.tresr_tipo=1
          and resr_lugar=".$request->lugar." and 	resr_fecha='".$request->fecha."') IS NOT NULL  THEN (SELECT resr_lote_id FROM reservas
          inner join tipo_reservas treserva on treserva.tresr_id = reservas.resr_tipo_id
          WHERE
          resr_horario_id= hor_id and resr_country_id=".$country_id->lot_country_id." and treserva.tresr_tipo=1
          and resr_lugar=".$request->lugar." and 	resr_fecha='".$request->fecha."') ELSE 0 END) as lote"))
        ->orderby('hor_id','asc')->get();


$response = [
    'data'=>$horarios
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
            'titulo' => 'required|string',
            'body' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }

        Noticia::create([
            'notic_user_id' => $request->user()->id,
            'notic_titulo' => $request['titulo'],
            'notic_body' =>$request['body'],
            'notic_to' => 'T',
            'notic_to_user' => $request->user()->id,
            'notic_app'=> 1
        ]);


        Notificacion::create([
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
        ]);

        return response('',201);

    }


}
