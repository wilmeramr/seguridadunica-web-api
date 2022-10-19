<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Noticia;
use App\Models\User;

use Illuminate\Http\Request;

class NoticiaController extends Controller
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
        $Lotes = Lote::where('lot_country_id','=',$Lote->lot_country_id)->select('lot_id')->get();
        $userC = User::whereIn('us_lote_id',$Lotes)->select('id')->get();

       // error_log("".$arr);
       $noti = Noticia::whereIn('notic_to_user', $userC)
       ->orderby('notic_id','desc')->paginate(20);


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
