<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\User;
use App\Models\Autorizaciones;
use App\Models\AutorizacionTipo;



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

        $user = User::where('lote_id','=',$request->user()->lote_id)->select('id')->get();
        ;
       // error_log("".$arr);
       $code = Autorizaciones::whereIn('aut_user_id', $user)->where('aut_tipo','=',$id)->select('autorizaciones.*','users.email')->
       join('users','users.id','=','autorizaciones.aut_user_id')->orderby('id','desc')->paginate(40);

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

        $code = Code::getUniqueReferralCode();

        Code::create([
            'referral_code'=>$code
        ]);

        Autorizaciones::create([
            'aut_user_id' => $request->user()->id,
             'aut_code' => $code,
             'aut_tipo' => $request['aut_tipo'],
             'aut_desde' => $request['aut_desde'],
             'aut_hasta' => $request['aut_hasta'],
             'aut_comentario' => $request['aut_comentario'],
             'aut_tipo_servicio' => $request['aut_tipo_servicio'],
             'aut_lunes' => $request['aut_lunes'],
             'aut_martes' => $request['aut_martes'],
             'aut_miercoles' => $request['aut_miercoles'],
             'aut_jueves' => $request['aut_jueves'],
             'aut_viernes' => $request['aut_viernes'],
             'aut_sabado' => $request['aut_sabado'],
             'aut_domingo' => $request['aut_domingo'],
        ]);


        $response = [
            'link' => $code
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
