<?php

namespace App\Http\Livewire;

use App\Models\Ingreso;
use App\Models\Expensa;

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
use Illuminate\Support\Facades\Storage;

class ExpensaController extends Component
{

    use WithPagination;
    use WithFileUploads;
    public  $search,$selected_id,$pageTitle,$componentName,$exp_link_pago;
    public  $detalle_expensa,$expensasDetail,$lote_id,$exp_pdf;

    private $pagination =20;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Expensas";
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

                                $expensas =  Expensa::whereIn('exp_lote_id', $lotes)
                                ->join('lotes as lt','lt.lot_id','=','expensas.exp_lote_id')
                                ->select('expensas.*','lt.lot_name'
                             )->where('exp_activo','=',1)
                                ->where( function($query) {
                                    $query->where('lot_name','like','%'.$this->search.'%')
                                    ->orWhere('exp_name','like','%'.$this->search.'%')
                                    ->orWhere('exp_link_pago','like','%'.$this->search.'%');
                              })
                           // ->select('*')
                           ->orderBy('exp_id','desc')
                            ->paginate($this->pagination);

                            }
                            else
                            $expensas =  Expensa::whereIn('exp_lote_id', $lotes)
                            ->join('lotes as lt','lt.lot_id','=','expensas.exp_lote_id')
                            ->select('expensas.*','lt.lot_name'
                            )->where('exp_activo','=',1)

                           ->orderBy('exp_id','desc')
                            ->paginate($this->pagination);


//dd($expensas);
                                $queryLotes = Lote::whereIn('lot_id', $lotes);




        return view('livewire.expensas.component',[
            'expensas'=>$expensas,
            'lotes'=>$queryLotes->get(),

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function show($exp_id)
    {

        $this->expensasDetail =  Expensa::where('exp_id','=', $exp_id)
        ->join('lotes as lt','lt.lot_id','=','expensas.exp_lote_id')
        ->select('expensas.*','lt.lot_name'
        )     ->first();


           $this->detalle_expensa = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal-detalle', 'open!');

    }
    public function resetUI()
    {
       $this->search='';
       $this->selected_id='';
        $this->expensaDetail ='';
        $this->lote_id ="Elegir";
        $this->exp_link_pago='';
        $this->exp_pdf='';
         $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2','s');
        $this->emit('desactivar-camara','s');

    }
    protected $listeners =[
        'webcam' => 'Webcam',
     'deleteRow'=>'Destroy',
    'resetUI'=> 'resetUI',
'clear_ingr_foto'=>'Clear_Ingr_Foto',
'desactivar_ingr_foto'=>'Desactivar_Ingr_Foto',
'scan-code'=> 'barCode'];

public function Destroy($id){
    $expensa = Expensa::where('exp_id','=',$id)->first();

    $expensa::where('exp_id','=',$expensa->exp_id)->update([

        'exp_activo'=>0
    ]);

    $this->resetUI();
    $this->emit('exp-deleted');
}
    public function Store()
    {

        $rules =[

            'lote_id'=>'required|not_in:Elegir',
            'exp_pdf' =>'required|mimes:pdf',
        ];

        $messages =[

            'lote_id.required' =>'Selecciona el lote.',
            'lote_id.not_in' =>'Selecciona el lote.',
            'exp_pdf.mimes' => 'El archivo debe ser de formatos [pdf]',
        ];


        $this->validate($rules,$messages);

        if($this->exp_pdf){

            $co_name = \Auth::user()->lote()->first()->country()->first()->co_name;
            $lot_name = \Auth::user()->lote()->first()->lot_name;
             $pdf_url = pathinfo($this->exp_pdf->getClientOriginalName(), PATHINFO_FILENAME)  ." - ".$lot_name." - ".$co_name.".".$this->exp_pdf->getClientOriginalExtension();
             $path = 'pdf/expensas';
             $file = $this->exp_pdf->storeAs($path, $pdf_url);


             if($file){

                 $url = Storage::url('pdf/expensas/'.$pdf_url);
                 $explo = explode('/', $url);
                 $pdfName = $explo[count($explo)-1];

                 $us_lote_id = \Auth::user()->us_lote_id;
                 $countryid = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                 Expensa::create([
                    'exp_user_id' => \Auth::user()->id
                    ,'exp_lote_id'=> $this->lote_id
                    ,'exp_name' => $pdfName
                    ,'exp_link_pago'=>$this->exp_link_pago
                    ,'exp_country_id' => $countryid->lot_country_id
                    ,'exp_doc_url' =>  $url

                 ]);

                 $user = User::where('us_lote_id','=', $this->lote_id)->first();



                 $noti_aut_code ="EXPENSAS";
                 $noti_titulo ="Expensa recibida.";
                 $noti_body ="Ha recibido la expensa: ".$pdfName;
                 $noti_event ="Expensas";


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
             }

         }




     $this->resetUI();
     $this->emit('exp-added','Expensa Registrada');


    }


}
