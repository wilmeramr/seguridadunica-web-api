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

class AutorizoController extends Component
{ use WithPagination;

    public $aut_nombre, $aut_dni, $aut_email,$aut_comentarios,  $aut_rangedate,$aut_autoriza, $selected_id, $at_detalle_autorizacion, $autorizacionDetail;
    public $pageTitle,$componentName, $search;
    private $pagination = 10;

public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Autorizo';
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
                ->where('aut_tipo',"=",1)
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
                ->where('aut_tipo',"=",1)

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


        return view('livewire.autorizaciones.autorizo.component',[
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
        'resetUI'=> 'resetUI',
        'echo:emergencias,UserEmergenciaEmit' => 'notifyNewOrder'
    ];

    public function notifyNewOrder()
    {
       // dd(1);
    }
    public function resetUI()
    {
       $this->search='';
       $this->aut_nombre='';
       $this->aut_dni='';
       $this->aut_email='';
       $this->aut_comentarios='';
       $this->aut_rangedate=null;
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
            'aut_email'=>'required|email',
            'aut_dni'=>'required|min:6',
            'aut_rangedate'=>'required',
            'aut_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'aut_nombre.required'=>'Ingresa el nombre completo',
            'aut_nombre.min'=>'El nombre debe tener al menos  5 caracteres',
            'aut_dni.required'=>'Ingresa el DNI',
            'aut_dni.min'=>'El DNI debe tener al menos  6 caracteres',
            'aut_email.required' =>'Ingresa un correo',
            'aut_email.email' => 'Ingresa un correo valido',
            'aut_rangedate.required' =>'Ingresa un rango de fechas',
            'aut_autoriza.required' =>'Selecciona el usuario que autoriza',
            'aut_autoriza.not_in' =>'Selecciona el usuario que autoriza',

        ];

        $this->validate($rules,$messages);

         $code = Code::getUniqueReferralCode();
        Code::create([
            'referral_code'=>$code
        ]);

        if(\Str::contains($this->aut_rangedate, 'a')){

            $fechas = explode(' a ',$this->aut_rangedate);
            $desde = \Carbon\Carbon::parse($fechas[0]);
            $hasta = \Carbon\Carbon::parse($fechas[1]);
        }else{
            $desde = \Carbon\Carbon::parse($this->aut_rangedate);
            $hasta = \Carbon\Carbon::parse($this->aut_rangedate);
        }


        Autorizaciones::create([
            'aut_user_id' => $this->aut_autoriza,
             'aut_code' => $code,
             'aut_tipo' =>1,
             'aut_email' => $this->aut_email,
             'aut_nombre' => $this->aut_nombre,
             'aut_desde' =>$desde,
             'aut_hasta' => $hasta,
             'aut_documento'=> $this->aut_dni,
             'aut_comentario' => $this->aut_comentarios.' Creado desde la web por '.\Auth::user()->us_name,

        ]);

        $noti_aut_code ="AUTORIZACION";
        $noti_titulo ="Creación Autorizacion.";
        $noti_body ="Se ha creado una autorización para ".$this->aut_nombre.' desde '.$desde.' hasta '.$hasta;
        $noti_event ="Autorizacion";

        Notificacion::create([
            'noti_user_id'=> \Auth::user()->id,
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
