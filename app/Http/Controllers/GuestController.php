<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\Autorizaciones;
use App\Models\Notificacion;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenReceived;
use DNS2D;
use Error;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(String $guest )
    {
       $code = $guest;


        $autorizacion = Autorizaciones::where('aut_code','=',$guest)
        ->whereIn('aut_tipo', [1, 2])
        ->first();


        if($autorizacion==null || $autorizacion->aut_email !=null)
               return view("guest.forbidden");

        $coname = User::where("users.id","=",$autorizacion->aut_user_id)

        ->join('lotes','lotes.lot_id','=','users.us_lote_id')
        ->join('countries','co_id','=','lotes.lot_country_id')
        ->select('countries.co_name')
        ->first()  ;

        return view("guest.index",compact('coname','code'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        request()->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'dni' => 'required|integer',
            'tel' => 'required|integer',
            'code' => 'required|string',


        ]);

        Autorizaciones::where('aut_code',"=",$request["code"])->update([

                'aut_nombre' => $request['name'],
                'aut_email' => $request['email'],
                'aut_documento' => $request['dni'],
                'aut_telefono' => $request['tel'],
                'aut_envio_mail'=>0

            ]);

            $autorizacion =  Autorizaciones::where('aut_code',"=",$request["code"])->first();

            Notificacion::create([
                'noti_user_id'=>$autorizacion->aut_user_id,
                'noti_aut_code'=> $request["code"],
                'noti_titulo' =>  $autorizacion->aut_tipo ==1 ? 'Invitacion Confirmada':'Servicio Confirmado',
                'noti_body' => $autorizacion->aut_nombre.' ha completado el registro',
                'noti_to' => 'L:'.$autorizacion->aut_user_id,
                'noti_event' => $autorizacion->aut_tipo ==1 ? 'Invitacion':'Servicio' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0

            ]);




        return view("guest.forbidden");
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
