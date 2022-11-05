<?php

namespace App\Http\Controllers\api\v1;

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
            'email' => 'required|string|unique:users,us_email',
            'loteid' => 'required|integer',
            'password' => 'required|string|confirmed',
            'rol' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return response(['error' => 'El email ya esta registrado'], 500);
        }


        $user = User::create([
            'us_name' => $request['name'],
           'us_apellido' => $request['apellido'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'us_lote_id' => $request['loteid']

        ]);

        $user->assignRole($request->input('rol'));

        $lote = Lote::where('lot_id','=',$request['loteid'])->select(["lotes.lot_name","lotes.lot_country_id"])->first();
        $country= Country::where('co_id','=',$lote->lot_country_id)->select(['countries.co_id','countries.co_name'])->first();
        $token = $user->createToken(env('TOKEN_SECRET'))->plainTextToken;
        $user->roles->first();

        $userDto = [
            'id'=> $user->id,
            'name' => $user->us_name,
            'email' => $user->email,
            'apellido' => $user->us_apellido,
            'rol' => $user->roles[0]->name,
            'lote'=> $lote->lot_name,
            'country'=>$country->co_name


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

        $user = User::where('email', $fields['email'])->where('us_active','=', 1)->first();
        if(!$user || $user->roles[0]->name=='Seguridad'){

            return response(['error' => 'No tienes permisos para esta accion.'], 500);
        }
        $user->roles->first();

        // Check password
        if($fields['email']!='wil_mw_ab@hotmail.com' && $fields['email']!='eventos@seguridadunica.com')
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        $lote = Lote::where('lot_id','=',$user->us_lote_id)->select(["lotes.lot_name","lotes.lot_country_id"])->first();
        $country= Country::where('co_id','=',$lote->lot_country_id)->select(['countries.co_id','countries.co_name'])->first();
        $token = $user->createToken(env('TOKEN_SECRET'), [$user->roles[0]->name])->plainTextToken;
        $user->roles->first();

        $userDto = [
            'id'=> $user->id,
            'name' => $user->us_name,
            'email' => $user->email,
            'apellido' => $user->us_apellido,
            'rol' => $user->roles[0]->name,
            'lote'=> $lote->lot_name,
            'loteId'=>$user->us_lote_id,
            'country'=>$country->co_name,
            'countryId'=>$lote->lot_country_id,



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

        $user = User::where('email','=',$request['email'])->where('us_active',"=",1) ->first();
        if(!$user || $user->roles[0]->name=='Seguridad'){

            return response(['error' => 'No tienes permisos para esta accion.'], 500);
        }

        $auth_token = User::geToken();
        \Log::info($auth_token);
        $input = [
        'password' =>Hash::make($auth_token)
        ,'us_updated_token'=>$now];




        $updated_token = \Carbon\Carbon::parse($user['us_updated_token'],'America/Argentina/Buenos_Aires');



        if(  $updated_token > \Carbon\Carbon::now('America/Argentina/Buenos_Aires')){
            return response([
                'message' => 'Espere solo puede generar token cada hora',

            ],401);
        }
        $lote = Lote::where("lot_id","=",$user->us_lote_id)->first();

        $country = Country::where('co_id',"=",$lote->lot_country_id)->first();

        $user->update($input);

        Mail::to($user->email)->send(new TokenReceived($country,$auth_token));

        return response([
            'message' => 'Token enviado a su direccion de correo.',

        ],201);
    }

    public function getUsers(Request $request)
    {

        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [

            'query' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response(['error' => 'El debe enviar un valor'], 500);
        }

        $search = $request['query'];
        $us_lote_id = \Auth::user()->us_lote_id;
        $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
        $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();

        $data = User::whereIn('us_lote_id',$lotes)
        ->join('lotes as lt','lt.lot_id','users.us_lote_id')
        ->where( function($query) use($search) {
            $query->where('us_name','like','%'.$search.'%')
            ->orWhere('us_apellido','like','%'.$search.'%')
            ->orWhere('email','like','%'.$search.'%')
            ->where('lt.lot_name','like','%'.$search.'%');
      })

      ->select('users.id as us_id','users.us_name','users.us_apellido','users.email as us_email','lt.lot_name')
      ->orderBy('us_name','asc')
      ->get();
      //->paginate($this->pagination);


        return response([
            'data' => $data,

        ],201);
    }
}
