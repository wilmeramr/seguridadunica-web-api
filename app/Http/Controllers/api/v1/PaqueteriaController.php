<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notificacion;
use App\Models\Paqueteria;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class PaqueteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function  index(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $paq = Paqueteria::where('paq_lote_id','=',$request->user()->us_lote_id)
        ->join('lotes as lt','lt.lot_id','=','paqueterias.paq_lote_id')
        ->select('paqueterias.*','lt.lot_name',
        DB::raw('(CASE WHEN pad_empr_envio = 1 THEN "Mercado Libre"
        WHEN pad_empr_envio = 2 THEN "Correo Argentino"
        WHEN pad_empr_envio = 3 THEN "Andreani"
        WHEN pad_empr_envio = 4 THEN "Oca"
        ELSE "Otros" END) AS empresa_envio '))
        ->orderby('paq_id','desc')->paginate(20);


        return response($paq, 200);
    }


    public function uploadImage(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);

        }

        $validator = \Validator::make($request->all(), [
            'imagen' => 'required|mimes:jpeg,png,jpg',
        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }
        if($request->hasFile('imagen')){

            $png_url = Str::uuid().".".$request->imagen->getClientOriginalExtension();
            $path = 'img/paqueteria';
            $file = $request->imagen->storeAs($path, $png_url);


            if($file){

                $url = Storage::url('img/paqueteria/'.$png_url);
                $response =[
                    'link'=> $url
                ];
                return response($response, 201);;
            }else{

                return response('', 500);

            }

        }
            return response('', 500);
    }


}
