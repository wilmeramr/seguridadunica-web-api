<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lote;
use App\Models\Code;
use App\Models\Notificacion;
use App\Models\Autorizaciones;
use Stringable;

class DeliveryController extends Component
{ use WithPagination;

    public $aut_nombre, $aut_dni, $aut_email,$aut_vigencia,$aut_comentarios,$aut_autoriza, $selected_id, $at_detalle_autorizacion, $autorizacionDetail;
    public $pageTitle,$componentName, $search;
    private $pagination = 10;

public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Entregas Inmediatas';
        //$this->status ='Elegir';
      //  $this->us_lote_id ='Elegir';
        $this->aut_autoriza='Elegir';
        $this->aut_vigencia='Elegir';
        $this->autorizacionDetail=[];

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
                ->where('aut_tipo',"=",3)
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
                ->where('aut_tipo',"=",3)

           ->orderBy('aut_desde','desc')
            ->paginate($this->pagination);

            if (\Auth::user()->roles[0]->name == 'Administrador') {
                $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                ->where('email', '<>', '');

            }else{
                $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                            ->whereIn('us_lote_id',$lotes)
                            ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                        ->orderBy('us_name','asc');

            }


        return view('livewire.autorizaciones.delivery.component',[
            'data'=> $autorizacion,
            'autorizacionDetail'=> $this->autorizacionDetail,
            'users'=> $queryUsers->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
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
            $this->emit('show-modal-detalle', 'open!');



    }

    protected $listeners=[
        'deleteRow'=>'destroy',
        'resetUI'=> 'resetUI'
    ];

    public function resetUI()
    {
       $this->search='';
       $this->aut_nombre='';
       $this->aut_dni='';
       $this->aut_email='';
       $this->aut_comentarios='';
       $this->aut_vigencia='Elegir';
       $this->selected_id='';
     //  $this->aut_autoriza='Elegir';
        $this->autorizacionDetail ='';
         $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2','s');




    }


    public function Store()
    {

//dd($this->aut_autoriza);
        $rules =[
            'aut_nombre'=>'required|min:5',
            'aut_dni'=>'required|min:6',
            'aut_vigencia'=>'required|not_in:Elegir',
            'aut_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'aut_nombre.required'=>'Ingresa el nombre completo',
            'aut_nombre.min'=>'El nombre debe tener al menos  5 caracteres',
            'aut_dni.required'=>'Ingresa el DNI',
            'aut_dni.min'=>'El DNI debe tener al menos  6 caracteres',
            'aut_rangedate.required' =>'Ingresa un rango de fechas',
            'aut_autoriza.required' =>'Selecciona el usuario que autoriza',
            'aut_autoriza.not_in' =>'Selecciona el usuario que autoriza',
            'aut_vigencia.required' =>'Selecciona la vigencia',
            'aut_vigencia.not_in' =>'Selecciona la vigencia',

        ];


        $this->validate($rules,$messages);

         $code = Code::getUniqueReferralCode();
        Code::create([
            'referral_code'=>$code
        ]);



        Autorizaciones::create([
            'aut_user_id' => $this->aut_autoriza,
             'aut_code' => $code,
             'aut_tipo' =>3,
             'aut_email' => $this->aut_email,
             'aut_nombre' => $this->aut_nombre,
             'aut_desde' =>\Carbon\Carbon::now(),
             'aut_hasta' => \Carbon\Carbon::now(),
             'aut_documento'=> $this->aut_dni,
             'aut_lunes'=>$this->aut_vigencia.'hrs',
             'aut_comentario' => $this->aut_comentarios.' Creado desde la web por '.\Auth::user()->us_name,

        ]);

        $noti_aut_code ="DELIVERY";
        $noti_titulo ="Creación Servicio Delivery.";
        $noti_body ="Se ha creado un servicio delivery para ".$this->aut_nombre." con una vigencia de ". $this->aut_vigencia.'hrs';
    $noti_event ="Delivery";



        Notificacion::create([
            'noti_user_id'=>  \Auth::user()->id,
            'noti_aut_code'=> $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $this->aut_autoriza,
            'noti_event' => $noti_event ,
            'noti_priority' =>'high',
            'noti_envio'=> 0

        ]);


     $this->resetUI();
     $this->emit('aut-added','Usuario Registrado');
    }

    public function destroy( $au_id)
    {
        Autorizaciones::where('aut_id','=',$au_id)->update([
            'aut_activo'=>0
        ]);
        $this->resetUI();
     $this->emit('aut-deleted','Usuario Registrado');

    }
}
