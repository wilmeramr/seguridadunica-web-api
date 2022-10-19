<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    public function store(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

        $validator = \Validator::make($request->all(), [
            'token_device' => 'required|string',

        ]);


        if ($validator->fails()) {
            return response(['error' => 'Debe enviar todos los campos'], 500);
        }

       $device = Device::where('dev_user_id','=',$request->user()->id)->first();

        if($device!=null){
          $device::where('dev_user_id','=',$request->user()->id)->update([

            'dev_token' => $request['token_device'],

        ]);
            return response('',201);
        }

        Device::create([
            'dev_user_id' => $request->user()->id,
            'dev_token' => $request['token_device'],

        ]);

        return response('',201);
    }


}
