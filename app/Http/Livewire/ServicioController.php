<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lote;
use App\Models\Code;
use App\Models\Notificacion;
use App\Models\Autorizaciones;
use App\Models\ServicioTipo;
use Stringable;

class ServicioController extends Component
{ use WithPagination;

    public $aut_nombre, $aut_dni, $aut_email,$aut_comentarios, $servicio_id,  $aut_rangedate,$aut_autoriza, $selected_id, $at_detalle_autorizacion, $autorizacionDetail;
    public $aut_lunes_desde,$aut_lunes_hasta, $aut_martes_desde,$aut_martes_hasta, $aut_miercoles_desde,$aut_miercoles_hasta;
    public $aut_jueves_desde,$aut_jueves_hasta, $aut_viernes_desde,$aut_viernes_hasta, $aut_sabados_desde,$aut_sabados_hasta;
    public $aut_domingos_desde,$aut_domingos_hasta;
    public $chk=[];
    public $pageTitle,$componentName, $search;
    private $pagination = 10;

public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Visitas Recurrentes';
        $this->chk=[];
        //$this->status ='Elegir';
      //  $this->us_lote_id ='Elegir';
        $this->aut_autoriza='Elegir';
        $this->servicio_id='Elegir';

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
                ->join('servicio_tipos as st','st.stp_id','autorizaciones.aut_tipo_servicio')


                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name','st.stp_descripcion')
                ->whereIn('autorizaciones.aut_user_id',$users)
                ->where('aut_tipo',"=",2)
                ->where( function($query) {
                    $query->where('lot_name','like','%'.$this->search.'%')
                    ->orWhere('aut_nombre','like','%'.$this->search.'%')
                    ->orWhere('aut_documento','like','%'.$this->search.'%')
                    ->orWhere('tipo_autorizacion','like','%'.$this->search.'%')
                    ->orWhere('stp_descripcion','like','%'.$this->search.'%')
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
                ->join('servicio_tipos as st','st.stp_id','autorizaciones.aut_tipo_servicio')


                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name','st.stp_descripcion')
                ->whereIn('autorizaciones.aut_user_id',$users)
                ->where('aut_tipo',"=",2)

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


        return view('livewire.autorizaciones.servicio.component',[
            'data'=> $autorizacion,
            //'autorizacionDetail'=> $this->autorizacionDetail,
            'users'=> $queryUsers->get(),
            'servicios'=> ServicioTipo::orderBy('stp_descripcion','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function show($aut_id)
    {

        $this->autorizacionDetail = Autorizaciones::join('autorizacion_tipos as at','at.id','autorizaciones.aut_tipo')
                ->join('users as usr','usr.id','autorizaciones.aut_user_id')
                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
                ->join('servicio_tipos as st','st.stp_id','autorizaciones.aut_tipo_servicio')
                ->select('autorizaciones.*','at.tipo_autorizacion','lt.lot_name','usr.us_name','st.stp_descripcion')
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
        'scan-code'=> 'barCode'
    ];

    public function resetUI()
    {
       $this->search='';
       $this->aut_nombre='';
       $this->aut_dni='';
       $this->aut_email='';
       $this->aut_comentarios='';
       $this->aut_rangedate=null;
       $this->selected_id='';
       $this->servicio_id='Elegir';

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
            'aut_email'=>'required|email',
            'aut_dni'=>'required|min:6',
            'aut_rangedate'=>'required',
            'aut_autoriza'=>'required|not_in:Elegir',
            'servicio_id'=>'required|not_in:Elegir',
            'chk'=>"required|array|min:1"
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
            'chk.required' =>'Selecciona el usuario que autoriza',
            'chk.min' =>'Debes seleccionas minimo un dia. ',
            'aut_autoriza.not_in' =>'Selecciona el usuario que autoriza',
            'servicio_id.required' =>'Selecciona el tipo de servicio',
            'servicio_id.not_in' =>'Selecciona el tipo de servicio',
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
             'aut_tipo' =>2,
             'aut_email' => $this->aut_email,
             'aut_nombre' => $this->aut_nombre,
             'aut_desde' =>$desde,
             'aut_hasta' => $hasta,
             'aut_documento'=> $this->aut_dni,
             'aut_comentario' => $this->aut_comentarios.' Creado desde la web por '.\Auth::user()->us_name,
             'aut_tipo_servicio' => $this->servicio_id,
             'aut_lunes' =>  $this->horarios('lunes'),
             'aut_martes' =>$this->horarios('martes'),
             'aut_miercoles' => $this->horarios('mirecoles'),
             'aut_jueves' => $this->horarios('jueves'),
             'aut_viernes' => $this->horarios('viernes'),
             'aut_sabado' => $this->horarios('sabados'),
             'aut_domingo' =>$this->horarios('domingos'),

        ]);

                      $noti_aut_code ="SERVICIO";
                    $noti_titulo ="Creación Servicio.";
                    $noti_body ="Se ha creado un servicio de ". $this->aut_nombre;
                    $noti_event ="Servicio";

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

    public function barCode($barcode,$origen ){
        if(strlen($barcode) == 80){

            $apellidos =  explode('"',$barcode)[1];
            $nombres = explode('"',$barcode)[2];
             $dni = explode('"',$barcode)[4];

            switch($origen) {
                case('search'):
                    $this->search =explode('"',$barcode)[4];
                    break;

                    case('form'):

                      $this->aut_dni =$dni;

                      $us_lote_id = \Auth::user()->us_lote_id;
                      $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                      $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                      $users = User::whereIn('us_lote_id',$lotes)
                      ->select('id')
                      ->orderBy('us_name','asc')->get();

                      $datos = Autorizaciones::where('aut_documento','=',$dni)->whereIn('aut_user_id',$users)->select("aut_nombre","aut_email")->first();

                      if( $datos !=null){
                        $this-> aut_nombre =  $datos->aut_nombre;
                        $this-> aut_email =  $datos->aut_email;

                    }else{
                        $this-> aut_nombre = $nombres.' '.$apellidos;

                    }


                    break;
                default:
                    $msg = 'Something went wrong.';
                    }

        }else{

            switch($origen) {
                case('search'):

                    break;

                    case('form'):
                      $us_lote_id = \Auth::user()->us_lote_id;
                      $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                      $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                      $users = User::whereIn('us_lote_id',$lotes)
                      ->select('id')
                      ->orderBy('us_name','asc')->get();

                      $datos = Autorizaciones::where('aut_documento','=',$barcode)->whereIn('aut_user_id',$users)->select("aut_nombre","aut_email")->first();

                            if( $datos !=null){
                                $this-> aut_nombre =  $datos->aut_nombre;
                                $this-> aut_email =  $datos->aut_email;

                            }else{
                                $this-> aut_nombre="";
                                $this-> aut_email="";

                            }

                    break;
                default:
                    $msg = 'Something went wrong.';
                    }
        }
    }

    private function horarios($dia)
    {
      if(in_array($dia,$this->chk)){


        switch ($dia) {
            case 'lunes' :
                $desde = strlen($this->aut_lunes_desde) > 0? $this->aut_lunes_desde :'09:00';
                $hasta = strlen($this->aut_lunes_hasta) > 0? $this->aut_lunes_hasta: '17:00';
                return $desde.' - '.$hasta;
                break;

                case 'martes' :
                    $desde = strlen($this->aut_martes_desde) > 0? $this->aut_martes_desde :'09:00';
                    $hasta = strlen($this->aut_martes_hasta) > 0? $this->aut_martes_hasta: '17:00';
                    return $desde.' - '.$hasta;
                    break;

                    case 'miercoles' :
                        $desde = strlen($this->aut_miercoles_desde) > 0? $this->aut_miercoles_desde :'09:00';
                        $hasta = strlen($this->aut_miercoles_hasta) > 0? $this->aut_miercoles_hasta: '17:00';
                        return $desde.' - '.$hasta;
                        break;

                        case 'jueves' :
                            $desde = strlen($this->aut_jueves_desde) > 0? $this->aut_jueves_desde :'09:00';
                            $hasta = strlen($this->aut_jueves_hasta) > 0? $this->aut_jueves_hasta: '17:00';
                            return $desde.' - '.$hasta;
                            break;

                            case 'viernes' :
                                $desde = strlen($this->aut_viernes_desde) > 0? $this->aut_viernes_desde :'09:00';
                                $hasta = strlen($this->aut_viernes_hasta) > 0? $this->aut_viernes_hasta: '17:00';
                                return $desde.' - '.$hasta;
                                break;

                                case 'sabados' :
                                    $desde = strlen($this->aut_sabados_desde) > 0? $this->aut_sabados_desde :'09:00';
                                    $hasta = strlen($this->aut_sabados_hasta) > 0? $this->aut_sabados_hasta: '17:00';
                                    return $desde.' - '.$hasta;
                                    break;

                                    case 'domingos' :
                                        $desde = strlen($this->aut_domingos_desde) > 0? $this->aut_domingos_desde :'09:00';
                                        $hasta = strlen($this->aut_domingos_hasta) > 0? $this->aut_domingos_hasta: '17:00';
                                        return $desde.' - '.$hasta;
                                        break;


            }
      }
    }




}
