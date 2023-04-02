<?php

namespace App\Http\Livewire;

use App\Models\Ingreso;
use Livewire\WithPagination;

use App\Models\User;
use App\Models\Lote;
use App\Models\Paqueteria;

use App\Models\ServicioTipo;
use App\Models\Notificacion;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class PaqueteController extends Component
{

    use WithPagination;
    use WithFileUploads;
    public  $search,$selected_id,$pageTitle,$componentName,$paq_foto,$paq_foto_base64;
    public  $detalle_paquete,$paqueteDetail,$empresa_id,$paq_obser,$lote_id;
    public $urlFotoFctual;
    private $pagination =20;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Paqueteria";
        $this->empresa_id ="Elegir";
        $this->lote_id ="Elegir";

    }



    public function render()
    {

                        if (\Auth::user()->roles[0]->name != 'Administrador') {
                                    $us_lote_id = \Auth::user()->us_lote_id;
                                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                                    $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();


                            }else{
                                $lotes = Lote::select('lot_id')->get();
                             }




                             if(strlen($this->search) > 0){


                                $paquetes =  Paqueteria::whereIn('paq_lote_id', $lotes)
                                ->join('lotes as lt','lt.lot_id','=','paqueterias.paq_lote_id')
                                ->select('paqueterias.*','lt.lot_name',
                                DB::raw('(CASE WHEN pad_empr_envio = 1 THEN "Mercado Libre"
                                WHEN pad_empr_envio = 2 THEN "Correo Argentino"
                                WHEN pad_empr_envio = 3 THEN "Andreani"
                                WHEN pad_empr_envio = 4 THEN "Oca"
                                ELSE "Otros" END) AS empresa_envio '))
                                ->where( function($query) {
                                    $query->where('lot_name','like','%'.$this->search.'%')
                                    ->orWhere('pad_observacion','like','%'.$this->search.'%')
                                    ->orWhere(DB::raw('(CASE WHEN pad_empr_envio = 1 THEN "Mercado Libre"
                                    WHEN pad_empr_envio = 2 THEN "Correo Argentino"
                                    WHEN pad_empr_envio = 3 THEN "Andreani"
                                    WHEN pad_empr_envio = 4 THEN "Oca"
                                    ELSE "Otros" END)'),'like','%'.$this->search.'%');
                              })
                           // ->select('*')
                           ->orderBy('paq_id','desc')
                            ->paginate($this->pagination);

                            }
                            else
                            $paquetes =  Paqueteria::whereIn('paq_lote_id', $lotes)
                            ->join('lotes as lt','lt.lot_id','=','paqueterias.paq_lote_id')
                            ->select('paqueterias.*','lt.lot_name',
                            DB::raw('(CASE WHEN pad_empr_envio = 1 THEN "Mercado Libre"
                            WHEN pad_empr_envio = 2 THEN "Correo Argentino"
                            WHEN pad_empr_envio = 3 THEN "Andreani"
                            WHEN pad_empr_envio = 4 THEN "Oca"
                            ELSE "Otros" END) AS empresa_envio '))

                           ->orderBy('paq_id','desc')
                            ->paginate($this->pagination);



                                $queryLotes = Lote::whereIn('lot_id', $lotes);




        return view('livewire.paquete.component',[
            'paquetes'=>$paquetes,
            'lotes'=>$queryLotes->get(),

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function show($paq_id)
    {
        $this->paqueteDetail =  Paqueteria::where('paq_id', '=',$paq_id)
        ->join('lotes as lt','lt.lot_id','=','paqueterias.paq_lote_id')
        ->select('paqueterias.*','lt.lot_name',
        DB::raw('(CASE WHEN pad_empr_envio = 1 THEN "Mercado Libre"
        WHEN pad_empr_envio = 2 THEN "Correo Argentino"
        WHEN pad_empr_envio = 3 THEN "Andreani"
        WHEN pad_empr_envio = 4 THEN "Oca"
        ELSE "Otros" END) AS empresa_envio '))

       ->orderBy('paq_id','desc')
        ->first();

           $this->detalle_paquete = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal-detalle', 'open!');

    }
    public function resetUI()
    {
       $this->search='';
       $this->selected_id='';
        $this->paqueteDetail ='';
        $this->empresa_id ="Elegir";
      $this->paq_foto_base64= null;
      $this->paq_foto=null;
        $this->lote_id ="Elegir";
        $this->paq_obser='';
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
    $this->paq_foto_base64= null;
   $this->paq_foto=null;
   $this->emit('activar-camara','s');

}
public function Desactivar_Ingr_Foto($id)
{
    $this->paq_foto_base64= null;
   $this->paq_foto=null;


}


    public function Webcam($url)
    {
     $this->paq_foto_base64 =$url;

    }
    public function Store()
    {

        $rules =[

            'lote_id'=>'required|not_in:Elegir',
            'empresa_id'=>'required|not_in:Elegir',
        ];

        $messages =[

            'lote_id.required' =>'Selecciona el lote.',
            'lote_id.not_in' =>'Selecciona el lote.',
            'empresa_id.required' =>'Selecciona la empresa de envíos.',
            'empresa_id.not_in' =>'Selecciona la empresa de envíos.',


        ];


        $this->validate($rules,$messages);


        $png_url = Str::uuid().".png";
        $path = 'img/paqueteria/'.$png_url;
        $url = null;
            if($this->paq_foto_base64 !=null && !Str::contains($this->paq_foto_base64, 'http') && $this->paq_foto== null){

                $image_parts = explode(";base64,", $this->paq_foto_base64);
                $image_base64 = base64_decode($image_parts[1]);
             $success =   \Storage::put( $path ,  $image_base64);
                 $url = \Storage::url('img/paqueteria/'.$png_url);


            }else

            if(Str::contains($this->paq_foto_base64, 'http')){

                $url = $this->paq_foto_base64;


            }else
            if($this->paq_foto!= null){
                $customFileNamelogo = Str::uuid().".".$this->paq_foto->extension();

                $path = 'img/paqueteria';
                $this->paq_foto->storeAs($path,$customFileNamelogo);

                $url = \Storage::url($path.'/'.$customFileNamelogo);

            }



        Paqueteria::create([
        'paq_user_c' => \Auth::user()->id,
        'paq_user_auth' => \Auth::user()->id,
        'paq_lote_id' => $this->lote_id,
        'pad_empr_envio' => $this->empresa_id,
        'paq_foto'  => $url,
        'pad_observacion' => $this->paq_obser,
        ]);

        $noti_aut_code ="PAQUETERIA";
        $noti_titulo ="Paquete recibido.";
        $noti_body ="Ha recibido un paquete en la fecha y hora ".\Carbon\Carbon::now();
        $noti_event ="Paqueteria";


        $user = User::where('us_lote_id','=', $this->lote_id)->first();

        Notificacion::create([
            'noti_user_id'=>  \Auth::user()->id,
            'noti_aut_code'=> $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $user ->id,
            'noti_event' => $noti_event ,
            'noti_priority' =>'high',
            'noti_envio'=> 0

        ]);


     $this->resetUI();
     $this->emit('paq-added','Usuario Registrado');


    }


}
