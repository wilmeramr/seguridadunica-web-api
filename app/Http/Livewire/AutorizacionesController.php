<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lote;
use App\Models\Autorizaciones;

class AutorizacionesController extends Component
{
    use WithPagination;

    public $us_name, $us_phone, $email, $password, $status,$role, $us_lote_id, $selected_id, $at_detalle_autorizacion, $autorizacionDetail;
    public $pageTitle,$componentName, $search;
    private $pagination = 10;

public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Autorizaciones';
        $this->status ='Elegir';
        $this->us_lote_id ='Elegir';

    }

    public function render()
    {


        if (\Auth::user()->roles[0]->name != 'Administrador') {
                         $us_lote_id = \Auth::user()->us_lote_id;
                        $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                        $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                        $users = User::whereIn('us_lote_id',$lotes)
                        ->select('id')
                        ->orderBy('us_name','asc')->get();

            }else{
                $users = User::select('id')
                ->orderBy('us_name','asc')->get();
               // dd($lotes);
            }

            if(strlen($this->search) > 0){


                $autorizacion = Autorizaciones::join('autorizacion_tipos as at','at.id','autorizaciones.aut_tipo')
                ->join('users as usr','usr.id','autorizaciones.aut_user_id')
                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name')
                ->whereIn('autorizaciones.aut_user_id',$users)
                ->where( function($query) {
                    $query->where('lot_name','like','%'.$this->search.'%')
                    ->orWhere('aut_nombre','like','%'.$this->search.'%')
                    ->orWhere('aut_documento','like','%'.$this->search.'%')
                    ->orWhere('tipo_autorizacion','like','%'.$this->search.'%')
                    ->orWhere('us_name','like','%'.$this->search.'%');
              })
           // ->select('*')
           ->orderBy('aut_desde','desc')
            ->paginate($this->pagination);


            }

            else
            $autorizacion = Autorizaciones::join('autorizacion_tipos as at','at.id','autorizaciones.aut_tipo')
                ->join('users as usr','usr.id','autorizaciones.aut_user_id')
                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name')
                ->whereIn('autorizaciones.aut_user_id',$users)

           ->orderBy('aut_desde','desc')
            ->paginate($this->pagination);


       /*      if (\Auth::user()->roles[0]->name == 'Administrador') {
                $queryRole = Role::where('name', '<>', '');
                $queryLote =Lote::where('lot_country_id','<>','');
            }else{
                $queryRole = Role::where('name', '<>', 'Administrador');
                $queryLote =Lote::where('lot_country_id','=',$lote->lot_country_id);

            } */


       //     $this->autorizacionDetail= $autorizacion[0];
        return view('livewire.autorizaciones.component',[
            'data'=> $autorizacion,
            'autorizacionDetail'=> $this->autorizacionDetail,
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function resetUI()
    {
       // $this->search='';
       // $this->autorizacionDetail ='';
       // $this->resetValidation();
        //$this->resetPage();

    }


    public function show($aut_id)
    {

        $this->autorizacionDetail = Autorizaciones::join('autorizacion_tipos as at','at.id','autorizaciones.aut_tipo')
                ->join('users as usr','usr.id','autorizaciones.aut_user_id')
                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name')
                ->where('autorizaciones.aut_id','=',$aut_id)

           ->orderBy('aut_desde','desc')
           ->first();

           $this->at_detalle_autorizacion = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal', 'open!');



    }
}
