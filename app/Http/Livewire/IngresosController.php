<?php

namespace App\Http\Livewire;

use App\Models\Ingreso;
use Livewire\WithPagination;

use App\Models\User;
use App\Models\Lote;
use App\Models\ServicioTipo;
use App\Models\Notificacion;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
class IngresosController extends Component
{

    use WithPagination;
    use WithFileUploads;
    public $ingr_autoriza, $search,$selected_id,$pageTitle,$componentName,$ingr_foto,$ingr_foto_base64,$ingr_nombre;
    public  $detalle_ingreso,$ingresoDetail,$ingr_doc,$servicio_id,$ingr_obser,$ingr_patente,$ingr_vto;
    private $pagination =10;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Entradas/Salidas";
        $this->ingr_autoriza ="Elegir";
        $this->servicio_id ="Elegir";

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
                             }

                             if(strlen($this->search) > 0){


                                $ingresos =  Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
                                ->join('users as usr','usr.id','ingresos.ingr_user_auth')
                                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
                                ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
                                ,DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador')
                                , DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END) AS estado'))
                                ->whereIn('ingresos.ingr_user_c',$users)

                                ->where( function($query) {
                                    $query->where('lot_name','like','%'.$this->search.'%')
                                    ->orWhere('ingr_nombre','like','%'.$this->search.'%')
                                    ->orWhere('ingr_documento','like','%'.$this->search.'%')
                                    ->orWhere('ingr_patente','like','%'.$this->search.'%')
                                    ->orWhere('ingr_patente','like','%'.$this->search.'%')
                                    ->orWhere('ingr_observacion','like','%'.$this->search.'%')
                                    ->orWhere(DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1)'),'like','%'.$this->search.'%')
                                    ->orWhere(DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END)'),'like','%'.$this->search.'%')

                                    ->orWhere('us_name','like','%'.$this->search.'%');
                              })
                           // ->select('*')
                           ->orderBy('ingr_id','desc')
                            ->paginate($this->pagination);

                            }
                            else
                            $ingresos = Ingreso::join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
                                ->join('users as usr','usr.id','ingresos.ingr_user_auth')
                                ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
                                ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
                                ,DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador'))
                                ->whereIn('ingresos.ingr_user_c',$users)

                           ->orderBy('ingr_id','desc')
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


        return view('livewire.ingresos.component',[
            'ingresos'=>$ingresos,
            'users'=>$queryUsers->get(),
            'servicios'=> ServicioTipo::orderBy('stp_descripcion','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function show($ingr_id)
    {
        $this->ingresoDetail =  Ingreso::where('ingr_id','=',$ingr_id)
        ->join('servicio_tipos as ts','ts.stp_id','ingresos.ingr_tipo')
        ->join('users as usr','usr.id','ingresos.ingr_user_auth')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->select('ingresos.*','ts.stp_descripcion','lt.lot_name','usr.us_name'
        ,DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador')
        ,DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END) AS estado'))
   ->orderBy('ingr_id','desc')->first();

           $this->detalle_ingreso = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal-detalle', 'open!');

    }
    public function resetUI()
    {
       $this->search='';

       $this->selected_id='';
     //  $this->aut_autoriza='Elegir';
        $this->ingresoDetail ='';
        $this->ingr_doc ='';
        $this->ingr_autoriza ="Elegir";
      $this->ingr_foto_base64= null;
      $this->ingr_foto=null;

        $this->servicio_id ="Elegir";
        $this->ingr_obser='';
        $this->ingr_vto ='';
        $this->ingr_nombre ='';
        $this->ingr_patente='';
         $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2','s');
        $this->emit('desactivar-camara','s');

    }
    protected $listeners =[
        'webcam' => 'Webcam',
     'deleteRow'=>'destroy',
    'resetUI'=> 'resetUI',
'clear_ingr_foto'=>'Clear_Ingr_Foto',
'desactivar_ingr_foto'=>'Desactivar_Ingr_Foto',
'scan-code'=> 'barCode'];

public function Clear_Ingr_Foto($id)
{
    $this->ingr_foto_base64= null;
   $this->ingr_foto=null;
   $this->emit('activar-camara','s');

}
public function Desactivar_Ingr_Foto($id)
{
    $this->ingr_foto_base64= null;
   $this->ingr_foto=null;


}

    public function destroy( $ingr_id)
    {
        Ingreso::where('ingr_id','=',$ingr_id)->update([
            'ingr_salida'=>new DateTime()
        ]);

        $ingreso =  Ingreso::where('ingr_id','=',$ingr_id)->first();
        $noti_aut_code ="EGRESO";
        $noti_titulo ="Egreso de visita.";
        $noti_body ="Ha egresado la persona ".$ingreso->ingr_nombre.", tipo de visita ".ServicioTipo::where('stp_id','=',$ingreso->ingr_tipo)->select('stp_descripcion')->first()->stp_descripcion." ,fecha y hora ". $ingreso->ingr_salida;
        $noti_event ="Egreso";



        Notificacion::create([
            'noti_user_id'=>  \Auth::user()->id,
            'noti_aut_code'=> $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $ingreso->ingr_user_auth,
            'noti_event' => $noti_event ,
            'noti_priority' =>'high',
            'noti_envio'=> 0

        ]);

        $this->resetUI();
     $this->emit('ingr-deleted','Salida procesada');

    }
    public function Webcam($url)
    {
     $this->ingr_foto_base64 =$url;

    }
    public function Store()
    {

        $rules =[
            'ingr_nombre'=>'required|min:5',
            'ingr_doc'=>'required|min:6',
            'servicio_id'=>'required|not_in:Elegir',
            'ingr_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'ingr_nombre.required'=>'Ingresa el nombre completo.',
            'ingr_nombre.min'=>'El nombre debe tener al menos  5 caracteres.',
            'ingr_doc.required'=>'Ingresa un documento.',
            'ingr_doc.min'=>'El DNI debe tener al menos  6 caracteres.',
            'servicio_id.required' =>'Selecciona el tipo de visita.',
            'servicio_id.not_in' =>'Selecciona el tipo de visita.',
            'ingr_autoriza.required' =>'Selecciona el usuario que autoriza.',
            'ingr_autoriza.not_in' =>'Selecciona el usuario que autoriza.',


        ];


        $this->validate($rules,$messages);


        Ingreso::create([
             'ingr_user_c' => \Auth::user()->id,
             'ingr_user_auth' => $this->ingr_autoriza,
             'ingr_documento' => $this->ingr_doc,
             'ingr_nombre' => $this->ingr_nombre,
             'ingr_tipo' => $this->servicio_id,
             'ingr_patente' => $this->ingr_patente,
             'ingr_entrada' =>\Carbon\Carbon::now(),
             'ingr_patente_venc' => \Carbon\Carbon::parse($this->ingr_vto)->format('Y-m-d'),
             'ingr_observacion' => $this->ingr_obser,
        ]);

        $noti_aut_code ="INGRESO";
        $noti_titulo ="Ingreso de visita.";
        $noti_body ="Ha ingresado la persona ".$this->ingr_nombre.", tipo de visita ".ServicioTipo::where('stp_id','=',$this->servicio_id)->select('stp_descripcion')->first()->stp_descripcion." ,fecha y hora ".\Carbon\Carbon::now();
        $noti_event ="Ingreso";



        Notificacion::create([
            'noti_user_id'=>  \Auth::user()->id,
            'noti_aut_code'=> $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $this->ingr_autoriza,
            'noti_event' => $noti_event ,
            'noti_priority' =>'high',
            'noti_envio'=> 0

        ]);


     $this->resetUI();
     $this->emit('ingr-added','Usuario Registrado');


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


                      $us_lote_id = \Auth::user()->us_lote_id;
                      $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                      $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
                      $users = User::whereIn('us_lote_id',$lotes)
                      ->select('id')
                      ->orderBy('us_name','asc')->get();

                      $datos = Ingreso::where('ingr_documento','=',$dni)->whereIn('ingr_user_c',$users)->select("*")->first();

                      if( $datos !=null){
                        $this-> ingr_nombre =  $datos->ingr_nombre;
                        $this->ingr_foto_base64 = $datos->ingr_foto;
                        $this->ingr_doc =$dni;
                      //  $this-> aut_email =  $datos->aut_email;

                    }else{
                        $this-> ingr_nombre = $nombres.' '.$apellidos;
                        $this->ingr_foto_base64 = '';
                        $this->ingr_doc =$dni;
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

                      $datos = Ingreso::where('ingr_documento','=',$barcode)->whereIn('ingr_user_c',$users)->select("*")->first();

                            if( $datos !=null){
                                $this-> ingr_nombre =  $datos->ingr_nombre;
                                $this->ingr_foto_base64 = $datos->ingr_foto;
                               // $this-> aut_email =  $datos->aut_email;

                            }else{
                                $this-> ingr_nombre="";
                                $this->ingr_foto_base64 = '';
                                //$this-> aut_email="";

                            }

                    break;
                default:
                    $msg = 'Something went wrong.';
                    }
        }
    }


}
