<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Lote;
use Illuminate\Http\Request;

class SelfieController extends Controller
{

    public function getUrlSelfie(Request $request)
    {
        if ($request->user()->tokenCan("Seguridad")) {
            return response(['error' => 'No tienes permisos para esta accion'], 500);
        }

       $lote = Lote::where('lot_id','=',$request->user()->us_lote_id)->first();
       $country = Country::where('co_id','=',$lote->lot_country_id)->first();

       $response = [
        'link' => $country->co_reg_url_propietario

    ];

        return response($response,201);
    }


}
