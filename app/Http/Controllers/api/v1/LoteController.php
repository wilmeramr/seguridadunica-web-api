<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class LoteController extends Controller
{

    public function register(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")||$request->user()->tokenCan("Residente")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'lote' => [
                'required', Rule::unique('lotes', 'lote_name')
                    ->where('lote_countryid', $request->countryid)
            ],

            'countryid' => [
                'required', Rule::unique('lotes', 'lote_countryid')
                    ->where('lote_name', $request->lote)
            ],
        ]);
        //


        if ($validator->fails()) {
            return response(['error' => 'El lote ya esta registrado o debe enviar todos los parametros'], 500);
        }

        Lote::create([
            'lote_name' => Str::upper($request['lote'])  ,
            'lote_countryid' => $request['countryid'],
            'activo' => $request['activo'] == null ? 0 : $request['activo']
        ]);

        $response = [
            'msg' => 'Lote creado con exito '
        ];

        return response($response, 201);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
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
