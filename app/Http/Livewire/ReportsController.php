<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\Ingreso;
use App\Models\Lote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class ReportsController extends Component
{
    public $componentName, $ingresos,$reportType,$userId,$dateFrom,$dateTo;
    public $users;
public function mount()
{
    $this->componentName='Reportes de Ingresos';
   $this->ingresos =[];
    $this->reportType = 0;
    $this-> userId =0;
}
    public function render()
    {
                            if (\Auth::user()->roles[0]->name != 'Administrador') {
                                $us_lote_id = \Auth::user()->us_lote_id;
                                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                                    $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                                    $this->users = User::whereIn('us_lote_id',$lotes)
                                    ->select('id')
                                    ->orderBy('us_name','asc')->get();

                    }else{
                                $this->users = User::select('id')
                                ->orderBy('us_name','asc')->get();
                    };


        $this->IngresosByDate();


        if (\Auth::user()->roles[0]->name == 'Administrador') {
            $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
            ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
            ;

        }else{
            $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                        ->whereIn('us_lote_id',$lotes)
                        ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                    ->orderBy('us_name','asc');

        }

     //   dd( $queryUsers->get());

        return view('livewire.reports.component',[
            'usersQuery' => $queryUsers->get()
        ])  ->extends('layouts.theme.app')
        ->section('content');
    }

    public function IngresosByDate()
    {
      //  dd($this->userId);

        if($this->reportType == 0){

            $from = Carbon::parse(Carbon::now())->format('Y-m-d'). ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d'). ' 23:59:59';

        }else{
            $from = Carbon::parse($this->dateFrom)->format('Y-m-d'). ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d'). ' 23:59:59';
        }

        if($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo == '')){
            return;
        }

        if($this->userId == 0){

            $this->ingresos = Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
            ->join('users as usr','usr.id','ingresos.ingr_user_auth')
            ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
            ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
            ,DB::raw('(select us_name from users where users.id = ingresos.ingr_user_c  limit 1) as use_creador'))
            ->whereIn('ingresos.ingr_user_c',$this->users)
            -> whereBetween('ingresos.ingr_entrada',[$from,$to])
       ->orderBy('ingr_id','desc')
        ->get();
        }else{

           $usersL = User::where('id','=',$this->userId)->select("us_lote_id")->first();
           $usersL2 = User::where('us_lote_id','=',$usersL->us_lote_id)->select("id")->get();

            $this->ingresos = Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
            ->join('users as usr','usr.id','ingresos.ingr_user_auth')
            ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
            ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
            ,DB::raw('(select us_name from users where users.id = ingresos.ingr_user_c  limit 1) as use_creador'))
            ->whereIn('ingresos.ingr_user_auth',$usersL2)
            -> whereBetween('ingresos.ingr_entrada',[$from,$to])

       ->orderBy('ingr_id','desc')
        ->get();
        }
    }
}
