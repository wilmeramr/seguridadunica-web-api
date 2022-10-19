<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\User;
use App\Models\Autorizaciones;
use App\Models\AutorizacionTipo;
use App\Models\Lote;
use App\Models\Country;
use App\Models\Notificacion;

class AutorizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {

        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $user = User::where('us_lote_id','=',$request->user()->us_lote_id)->select('id')->get();

       // error_log("".$arr);
       $code = Autorizaciones::whereIn('aut_user_id', $user)->where('aut_tipo','=',$id)->select('autorizaciones.*','users.email')->
       join('users','users.id','=','autorizaciones.aut_user_id')->orderby('aut_id','desc')->paginate(20);

        return response($code, 201);

    }


    public function Register(Request $request)
    {


        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            //'aut_user_id' => 'required|integer',
           // 'aut_code' => 'required|string',
            'aut_tipo' => 'required|integer',
            'aut_desde' => 'required|date',
            'aut_hasta' => 'required|date',

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }

//Validar fechas eventos

if($request['aut_tipo']=='4'){
    $aut_evento = Autorizaciones::where('aut_tipo','=',$request['aut_tipo'])
    ->where('aut_fecha_evento',"<",$request['aut_fecha_evento_hasta'])
    ->where('aut_fecha_evento_hasta',">",$request['aut_fecha_evento'])
    ->get();
    if($aut_evento->count()>0){
        return response( [
            'error' => 'El rango de fechas no deben cruzarce'

        ],402);
    }

}



        $code = Code::getUniqueReferralCode();

        $country = Country::select('countries.co_name')
        -> join('lotes','lotes.lot_country_id','=','countries.co_id')
        ->where('lotes.lot_id','=',$request->user()->us_lote_id)
        ->first();

        $msg = $request->user()->us_name.' '.$request->user()->us_apellido
        .' te envió esta invitación a '. $country->co_name
        .' para acceder él dia '.\Carbon\Carbon::parse($request['aut_desde'])->format('d/m/Y').'.'
        .' Completa tus datos en '.env('APP_URL').'/guest/'.$code
        .'Obtendrás un Código QR vía email el cual debe mostrar en el momento del ingreso.';

        Code::create([
            'referral_code'=>$code
        ]);

       Autorizaciones::create([
            'aut_user_id' => $request->user()->id,
             'aut_code' => $code,
             'aut_tipo' => $request['aut_tipo'],
             'aut_email' => $request['aut_email'],
             'aut_nombre' => $request['aut_nombre'],
             'aut_desde' =>$request['aut_tipo']==3? \Carbon\Carbon::now(): $request['aut_desde'],
             'aut_hasta' => $request['aut_tipo']==3? \Carbon\Carbon::now(): $request['aut_hasta'],
             'aut_documento'=> $request['aut_tipo']==3? $request['aut_email']: null,
             'aut_comentario' => $request['aut_comentario'],
             'aut_tipo_servicio' => $request['aut_tipo_servicio'],
             'aut_lunes' => $request['aut_lunes'],
             'aut_martes' => $request['aut_martes'],
             'aut_miercoles' => $request['aut_miercoles'],
             'aut_jueves' => $request['aut_jueves'],
             'aut_viernes' => $request['aut_viernes'],
             'aut_sabado' => $request['aut_sabado'],
             'aut_domingo' => $request['aut_domingo'],
             'aut_fecha_evento' => $request['aut_fecha_evento'],
             'aut_fecha_evento_hasta' => $request['aut_fecha_evento_hasta'],
             'aut_cantidad_invitado' =>$request['aut_cantidad_invitado'] ==null? 0: $request['aut_cantidad_invitado'],
             'aut_app'=>1


        ]);

        $noti_aut_code ="";
        $noti_titulo ="";
        $noti_body ="";
        $noti_event ="";



        switch ($request['aut_tipo']) {
            case 1:
                $noti_aut_code ="AUTORIZACION";
                $noti_titulo ="Creación Autorizacion.";
                $noti_body ="Se ha creado una autorización espere a su confirmación.";
                $noti_event ="Autorizacion";

                break;
                case 2:
                    $noti_aut_code ="SERVICIO";
                    $noti_titulo ="Creación Servicio.";
                    $noti_body ="Se ha creado un servicio espere a su confirmación.";
                $noti_event ="Servicio";
                    break;
                    case 3:
                        $noti_aut_code ="DELIVERY";
                        $noti_titulo ="Creación Servicio Delivery.";
                        $noti_body ="Se ha creado un servicio delivery con una vigencia de ". $request['aut_lunes'].".";
                    $noti_event ="Delivery";
                        break;
                        case 4:
                            $noti_aut_code ="EVENTO";
                            $noti_titulo ="Creación Evento.";
                            $noti_body ="Se ha creado un evento para el dia ".\Carbon\Carbon::parse($request['aut_fecha_evento'])->format('d/m/Y H:i')." con una cantidad de invitados de ". $request['aut_cantidad_invitado'].".";
                        $noti_event ="Evento";
                            break;
            default:
                # code...
                break;
        }

           Notificacion::create([
                'noti_user_id'=>$request->user()->id,
                'noti_aut_code'=> $noti_aut_code,
                'noti_titulo' =>   $noti_titulo,
                'noti_body' => $noti_body,
                'noti_to' => 'L',
                'noti_to_user' =>$request->user()->id,

                'noti_event' => $noti_event ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app'=> 1


            ]);


        $response = [
            'link' => $msg

        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
