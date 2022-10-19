<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lote;
use App\Models\Code;
use App\Models\Notificacion;
use App\Models\Autorizaciones;
use App\Rules\EventoDateRule;
use Illuminate\Support\Facades\Redirect;
use Stringable;

class EventoController extends Component
{ use WithPagination;

    public $aut_nombre,$aut_comentarios,$aut_autoriza,$aut_desde, $aut_hasta,$aut_cantidad_invitado, $selected_id, $at_detalle_autorizacion, $autorizacionDetail;
    public $pageTitle,$componentName, $search;
    private $pagination = 10;

public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Evento';
        //$this->status ='Elegir';
      //  $this->us_lote_id ='Elegir';
        $this->aut_autoriza='Elegir';
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
                ->where('aut_tipo',"=",4)
                ->where( function($query) {
                    $query->where('lot_name','like','%'.$this->search.'%')
                    ->orWhere('aut_nombre','like','%'.$this->search.'%')
                    ->orWhere('aut_documento','like','%'.$this->search.'%')
                    ->orWhere('tipo_autorizacion','like','%'.$this->search.'%')
                    ->orWhere('us_name','like','%'.$this->search.'%');
              })
           // ->select('*')
           ->orderBy('aut_fecha_evento','desc')
            ->paginate($this->pagination);

            }

            else
            $autorizacion = Autorizaciones::join('autorizacion_tipos as at','at.id','autorizaciones.aut_tipo')
                ->join('users as usr','usr.id','autorizaciones.aut_user_id')
                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name')
                ->whereIn('autorizaciones.aut_user_id',$users)
                ->where('aut_tipo',"=",4)

           ->orderBy('aut_fecha_evento','desc')
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


        return view('livewire.autorizaciones.evento.component',[
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

       $this->aut_comentarios='';
       $this->aut_cantidad_invitado='';
       $this->aut_desde=null;
       $this->aut_hasta=null;
       $this->selected_id='';
     //  $this->aut_autoriza='Elegir';
        $this->autorizacionDetail ='';
         $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2','s');




    }


    public function Store()
    {


        $rules =[
            'aut_nombre'=>'required|min:5',
            'aut_desde'=>['required',  new EventoDateRule(
                $this->aut_desde,
                $this->aut_hasta
            ) ],
            'aut_hasta'=>['required',  new EventoDateRule(
                $this->aut_desde,
                $this->aut_hasta
            )],
            'aut_cantidad_invitado' => 'required|numeric|min:1|max:99',
            'aut_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'aut_nombre.required'=>'Ingresa el nombre completo',
            'aut_nombre.min'=>'El nombre debe tener al menos  5 caracteres',
            'aut_desde.required'=>'Seleccione la fecha desde',
            'aut_hasta.required' =>'Seleccione la fecha desde',
            'aut_autoriza.required' =>'Selecciona el usuario que autoriza',
            'aut_autoriza.not_in' =>'Selecciona el usuario que autoriza',
            'aut_cantidad_invitado.required' =>'Debe ingresar la cantidad',
            'aut_cantidad_invitado.max' =>'Debe de tener un rango 1,99',
            'aut_cantidad_invitado.min' =>'Debe de tener un rango 1,99',


        ];

        $this->validate($rules,$messages);

         $code = Code::getUniqueReferralCode();
        Code::create([
            'referral_code'=>$code
        ]);



        Autorizaciones::create([
            'aut_user_id' => $this->aut_autoriza,
             'aut_code' => $code,
             'aut_tipo' =>4,
             'aut_nombre' => $this->aut_nombre,
             'aut_desde' =>\Carbon\Carbon::now(),
             'aut_hasta' => \Carbon\Carbon::now(),
             'aut_hasta' => \Carbon\Carbon::now(),
            'aut_fecha_evento'=>\Carbon\Carbon::parse($this->aut_desde)->format('Y-m-d H:i'),
            'aut_fecha_evento_hasta'=>\Carbon\Carbon::parse($this->aut_hasta)->format('Y-m-d H:i'),
            'aut_cantidad_invitado' => $this->aut_cantidad_invitado,



             'aut_comentario' => $this->aut_comentarios.' Creado desde la web por '.\Auth::user()->us_name,

        ]);


    $noti_aut_code ="EVENTO";
    $noti_titulo ="Creación Evento.";
    $noti_body ="Se ha creado un evento desde ".\Carbon\Carbon::parse($this->aut_desde)->format('d/m/Y H:i')." hasta  ".\Carbon\Carbon::parse($this->aut_hasta)->format('d/m/Y H:i')." con una cantidad de invitados de ".  $this->aut_cantidad_invitado.".";
    $noti_event ="Evento";


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
