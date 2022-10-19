<?php

namespace App\Http\Controllers\api\v1;

use App\Events\UserEmergenciaEmit;
use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Documento;
use App\Models\Emergencia;
use App\Models\Noticia;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class EmergenciaController extends Controller
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

        $co_id = $request->user()->lote()->first()->country()->first()->co_id;
       $doc = Documento::where('doc_country_id',"=",$co_id)->get();

        return response($doc, 201);
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


        $co_id = $request->user()->lote()->first()->country()->first()->co_id;
        $lote_id = $request->user()->us_lote_id;
        $user_id = $request->user()->id;


        Emergencia::create([
            'eme_country_id'=>$co_id,
            'eme_lote_id'=> $lote_id,
            'eme_user_id'=>$user_id,
            'eme_lote_name'=>  $request->user()->lote()->first()->lot_name,
            'eme_user_name'=>$request->user()->us_name.' '.$request->user()->us_apellido
        ]);

        Notificacion::create([
            'noti_user_id'=>$request->user()->id,
            'noti_aut_code'=> 'EMERGENCIA',
            'noti_titulo' =>  "EMERGENCIA ACTIVADA",
            'noti_body' =>   'EMERGENCIA ACTIVADA POR '.Str::upper($request->user()->us_name.' '.$request->user()->us_apellido),
            'noti_to' => 'L',
            'noti_to_user' =>$request->user()->id,

            'noti_event' => 'Emergencia' ,
            'noti_priority' =>'high',
            'noti_envio'=> 0,
            'noti_app'=> 1
        ]);



            return response('', 201);



    }

}
