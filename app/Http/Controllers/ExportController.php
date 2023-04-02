<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\User;
use App\Models\Ingreso;
use App\Models\Lote;
use App\Models\Country;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function reportPDF($userId,$reportType,$dateFrom =null,$dateTo = null)
    {
       $data =[];




       if($reportType == 0){

        $from = Carbon::parse(Carbon::now())->format('Y-m-d'). ' 00:00:00';
        $to = Carbon::parse(Carbon::now())->format('Y-m-d'). ' 23:59:59';

    }else{
        $from = Carbon::parse($dateFrom)->format('Y-m-d'). ' 00:00:00';
        $to = Carbon::parse($dateTo)->format('Y-m-d'). ' 23:59:59';
    }


    if($userId == 0){

        $us_lote_id = \Auth::user()->us_lote_id;
        $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
        $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
        $users = User::whereIn('us_lote_id',$lotes)
        ->select('id')
        ->orderBy('us_name','asc')->get();

        $data = Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
        ->join('users as usr','usr.id','ingresos.ingr_user_auth')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
        ,DB::raw('(select us_name from users where users.id = ingresos.ingr_user_c  limit 1) as use_creador'))
        ->whereIn('ingresos.ingr_user_c',$users)
        -> whereBetween('ingresos.ingr_entrada',[$from,$to])
   ->orderBy('ingr_id','desc')
    ->get();


    }else{

       $usersL = User::where('id','=',$userId)->select("us_lote_id")->first();
       $usersL2 = User::where('us_lote_id','=',$usersL->us_lote_id)->select("id")->get();

        $data = Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
        ->join('users as usr','usr.id','ingresos.ingr_user_auth')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
        ,DB::raw('(select us_name from users where users.id = ingresos.ingr_user_c  limit 1) as use_creador'))
        ->whereIn('ingresos.ingr_user_auth',$usersL2)
        -> whereBetween('ingresos.ingr_entrada',[$from,$to])

   ->orderBy('ingr_id','desc')
    ->get();
    };

    $us_lote_id = \Auth::user()->us_lote_id;
    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
    $country = Country::where('co_id','=',$lote->lot_country_id)->select('co_logo')->first();
        // try{

        //     $explo = explode('/',$country->co_logo);
        //     $imageName = $explo[count($explo)-1];

            $logo = $country->co_logo;

        //     if(!file_exists('storage/img/countries/'.$imageName)){
        //         $logo = 'storage/img/noimage.jpg';

        //     }



        // }catch(\Throwable $th){
        //     $logo = 'storage/img/noimage.jpg';
        //     \Log::error($th);
        // }

    $user = $userId == 0? 'Todos': 'Lote - '.$data->first()->lot_name;


    $pdf = PDF::loadView('pdf.reporte', compact('data','reportType','user','dateFrom','dateTo','logo'))->setPaper('a4', 'landscape');


    return $pdf->stream('ingresos.pdf');
    }
}
