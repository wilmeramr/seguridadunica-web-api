<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use App\Models\User;
use App\Models\MascotaEspecie;
use App\Models\Notificacion;
use App\Models\MascotaGenero;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;





use Illuminate\Http\Request;

class MascotaController extends Controller
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

        $users = User::where('us_lote_id','=',$request->user()->us_lote_id)->select('id')->get();

        $mascotas = Mascota::whereIn('masc_user_id',$users)->paginate(20);

        return response($mascotas, 200);
    }

    public function  indexEsperices(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);

        }
        $Especies = MascotaEspecie::where('masc_esp_activo','=',1)->select(['masc_esp_id','masc_esp_name','masc_esp_activo'])->get();

        $response =[
            'especies'=> $Especies
        ];
        return response($response, 200);
    }


    public function  indexGeneros(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);

        }
        $Generos = MascotaGenero::where('masc_gene_activo','=',1)->select(['masc_gene_id','masc_gene_name','masc_gene_activo'])->get();

        $response =[
            'generos'=> $Generos
        ];
        return response($response, 200);
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
            'masc_id' => 'nullable|integer',
            'masc_name' => 'required|string',
            'masc_especie_id' => 'required|integer',
            'masc_raza' => 'required|string',
            'masc_genero_id' => 'required|integer',
            'masc_peso' => 'required',
            'masc_fecha_nacimiento' => 'required|date',
            'masc_fecha_vacunacion' => 'required|date'


        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }

        if($request['masc_id']==null){

       $mascota =  Mascota::create([
                'masc_user_id' => $request->user()->id,
                'masc_name' => $request['masc_name'],
                'masc_especie_id' =>  $request['masc_especie_id'],
                'masc_raza' =>  $request['masc_raza'],
                'masc_genero_id' =>  $request['masc_genero_id'],
                'masc_peso' =>  $request['masc_peso'],
                'masc_fecha_nacimiento' => $request['masc_fecha_nacimiento'],
                'masc_fecha_vacunacion' =>  $request['masc_fecha_vacunacion'],
                'masc_descripcion' =>  $request['masc_descripcion'],
                'masc_url_foto' =>  $request['masc_url_foto'],
                'masc_app' => 1
            ]);
            $response =[
                'msg'=> 'Se creo correctamente.',
                'id'=>$mascota->id
            ];

            Notificacion::create([
                'noti_user_id'=>$request->user()->id,
                'noti_aut_code'=> 'MASCOTAS',
                'noti_titulo' =>  'Creacion de una mascota.',
                'noti_body' => 'Bienvenido '.$request['masc_name'].'',
                'noti_to' => 'L',
                'noti_to_user' => $request->user()->id,
                'noti_event' => 'Mascota' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app' => 1

            ]);
            return response($response, 201);
        }else{

            Mascota::where('masc_id','=',$request['masc_id'])->update([

                'masc_user_id' => $request->user()->id,
                'masc_name' => $request['masc_name'],
                'masc_especie_id' =>  $request['masc_especie_id'],
                'masc_raza' =>  $request['masc_raza'],
                'masc_genero_id' =>  $request['masc_genero_id'],
                'masc_peso' =>  $request['masc_peso'],
                'masc_fecha_nacimiento' => $request['masc_fecha_nacimiento'],
                'masc_fecha_vacunacion' =>  $request['masc_fecha_vacunacion'],
                'masc_descripcion' =>  $request['masc_descripcion'],
                'masc_url_foto' =>  $request['masc_url_foto'],
                'masc_app' => 1
            ]);

              $response =[
                'msg'=> 'Se edito correctamente.'
            ];

            Notificacion::create([
                'noti_user_id'=>$request->user()->id,
                'noti_aut_code'=> 'MASCOTAS',
                'noti_titulo' =>  'Actualizacion de datos.',
                'noti_body' => 'Los datos de '.$request['masc_name'].' se han actualizado',
                'noti_to' => 'L',
                'noti_to_user' => $request->user()->id,
                'noti_event' => 'Mascota' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app' => 1

            ]);
            return response($response, 201);

        }

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
            $path = 'img/mascotas';
            $file = $request->imagen->storeAs($path, $png_url);


            if($file){

                $url = Storage::url('img/mascotas/'.$png_url);
                $response =[
                    'link'=> $url
                ];
                return response($response, 201);;
            }else{

                return response('', 500);

            }

        }
            return response('hola', 500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function show(Mascota $mascota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function edit(Mascota $mascota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mascota $mascota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mascota  $mascota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mascota $mascota)
    {
        //
    }
}
