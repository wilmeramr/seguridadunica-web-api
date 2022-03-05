<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\TokenReceived;

use App\Models\Country;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad") || $request->user()->tokenCan("Propietario")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'loteid' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response(['error' => 'El email ya esta registrado'], 500);
        }


        $user = User::create([
            'name' => $request['name'],
           // 'apellido' => $request['apellido'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'lote_id' => $request['loteid']

        ]);



        $lote = Lote::where('id','=',$request['loteid'])->select(["lotes.lote_name","lotes.lote_countryid"])->first();
        $country= Country::where('id','=',$lote->lote_countryid)->select(['countries.id','countries.countryname'])->first();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $user->roles->first();

        $userDto = [
            'id'=> $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'apellido' => $user->apellido,
            'rol' => $user->roles[0]->name,
            'lote'=> $lote->lote_name,
            'country'=>$country->countryname


        ];

        $response = [
            'user' => $userDto,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        if(!$user || $user->roles[0]->name=='Seguridad'){

            return response(['error' => 'No tienes permisos para esta accion.'], 500);
        }

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        $lote = Lote::where('id','=',$user->lote_id)->select(["lotes.lote_name","lotes.lote_countryid"])->first();
        $country= Country::where('id','=',$lote->lote_countryid)->select(['countries.id','countries.countryname'])->first();
        $token = $user->createToken('myapptoken', [$user->roles[0]->name])->plainTextToken;
        $user->roles->first();

        $userDto = [
            'id'=> $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'apellido' => 'molina',
            'rol' => $user->roles[0]->name,
            'lote'=> $lote->lote_name,
            'country'=>$country->countryname


        ];


        $response = [
            'user' => $userDto,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function token(Request $request)
    {

        $now =\Carbon\Carbon::now('America/Argentina/Buenos_Aires');

        $validator = \Validator::make($request->all(), [

            'email' => 'required|string|unique:users,email',

        ]);

        if (!$validator->fails()) {
            return response(['error' => 'El email no esta registrado'], 500);
        }

        $auth_token = User::geToken();
        $input = [
        'password' =>Hash::make($auth_token)
        ,'updated_token'=>$now->addHour(1)];

        $user = User::where('email','=',$request['email'])->first();

        $updated_token = \Carbon\Carbon::parse($user['updated_token'],'America/Argentina/Buenos_Aires');
        error_log('hola '.$updated_token);
        error_log('now '.\Carbon\Carbon::now('America/Argentina/Buenos_Aires'));


        if( $updated_token > \Carbon\Carbon::now('America/Argentina/Buenos_Aires')){
            return response([
                'message' => 'Espere solo puede generar token cada hora',

            ],401);
        }
        $user->update($input);

        Mail::to($user->email)->send(new TokenReceived($auth_token));

        return response([
            'message' => 'Token enviado a su direccion de correo.',

        ],201);
    }
}
