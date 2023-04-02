<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Horario;
use App\Models\Lote;
use App\Models\TipoReserva;
use Illuminate\Validation\Rule;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;



class TipoReservasController extends Component
{

    use WithPagination;


    public $tresr_description,$search,$selected_id,$pageTitle,$componentName;
    public $tresr_tipo,$tresr_tipo_horarios,$tresr_cant_lugares,$tresr_email,$tresr_url;
    private $pagination=10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle='Listado';
        $this->componentName='Tipo de Reservas';
        $this->tresr_tipo='Elegir';
        $this->tresr_tipo_horarios='Elegir';

    }
    public function render()
    {

                    if (\Auth::user()->roles[0]->name != 'Administrador') {
                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                    $tipoReserva = TipoReserva::where('tresr_country_id','=',$lote->lot_country_id)->select('tresr_id')->get();

                        }else{
                            $tipoReserva = TipoReserva::select('tresr_id')->get();

                        // dd($lotes);
                        };

        if(strlen($this->search) > 0)
            $data = TipoReserva::join('countries as c','c.co_id','tipo_reservas.tresr_country_id')
                        ->select('tipo_reservas.*','c.co_name',
                        DB::raw("(CASE WHEN tresr_tipo = 1 THEN 'Deportivas'
                        WHEN tresr_tipo = 2 THEN 'Gastronómicos'
                        WHEN tresr_tipo = 3 THEN 'Amenitis'
                        ELSE 'Otros' END) as tipo"))

                        ->whereIn('tipo_reservas.tresr_id',$tipoReserva)->where( function($query) {
                            $query->where('tipo_reservas.tresr_description','like','%'.$this->search .'%')
                            ->orWhere('c.co_name','like','%'.$this->search .'%')
                            ;
                      })
                        ->orderBy('tipo_reservas.tresr_description','asc')
                        ->paginate($this->pagination);

        else
        $data =TipoReserva::join('countries as c','c.co_id','tipo_reservas.tresr_country_id')
                        ->select('tipo_reservas.*','c.co_name',    DB::raw("(CASE WHEN tresr_tipo = 1 THEN 'Deportivas'
                        WHEN tresr_tipo = 2 THEN 'Gastronómicos'
                        WHEN tresr_tipo = 3 THEN 'Amenitis'
                        ELSE 'Otros' END) as tipo"))
                        ->whereIn('tipo_reservas.tresr_id',  $tipoReserva)
                        ->orderBy('tipo_reservas.tresr_description','asc')
                        ->paginate($this->pagination);



        return view('livewire.tiporeservas.component',[
            'data'=> $data,
            'horas'=>  Horario::SELECT('hor_tipo')->groupby('hor_tipo')->get()
        ])
        ->extends('layouts.theme.app')
    ->section('content');
        }



        public function Store(){

            $rules = ['tresr_description'=>'required|min:5'
            ,'tresr_tipo'=>'not_in:Elegir'
            ,'tresr_tipo_horarios'=>'not_in:Elegir'
           ,'tresr_cant_lugares'=>'required|numeric|min:1'

        ];

            $messages =[
                'tresr_description.required' => 'El nombre del permiso es requerido',

                'tresr_description.min'=> 'El nombre debe tener al menos 5 caracteres',
                'tresr_tipo.not_in'=> 'El tipo seleccionado debe ser distinto a Elegir',
                'tresr_tipo_horarios.not_in'=> 'El rango horario seleccionado debe ser distinto a Elegir',
                'tresr_cant_lugares.min'=> 'La cantidad debe mayor o igual a 1',


            ];
            $this->validate($rules,$messages);

            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();

            $lote = TipoReserva::create([
                'tresr_country_id'=> $c->lot_country_id,
                'tresr_description'=> $this->tresr_description,
                'tresr_tipo' =>$this->tresr_tipo,
                'tresr_tipo_horarios'=>$this->tresr_tipo_horarios,
                'tresr_cant_lugares'=>$this->tresr_cant_lugares,
                'tresr_email'=>$this->tresr_email,
                'tresr_url' =>$this->tresr_url,

            ]);


            $this->resetUI();
            $this->emit('treservas-added','Tipo Registrado');


        }

        public function Edit($id){

            $treservas = TipoReserva::where('tresr_id','=',$id)->first();

            $this->selected_id = $treservas->tresr_id;
         $this->tresr_description = $treservas->tresr_description;
           $this->tresr_tipo = $treservas->tresr_tipo;
             $this->tresr_tipo_horarios = $treservas->tresr_tipo_horarios;
             $this->tresr_cant_lugares= $treservas->tresr_cant_lugares;
             $this->tresr_email = $treservas->tresr_email;
              $this->tresr_url = $treservas->tresr_email;

              $this->emit('show-modal','Show modal');

        }

        public function Update(){

            $rules = ['tresr_description'=>'required|min:5'
            ,'tresr_tipo'=>'not_in:Elegir'
            ,'tresr_tipo_horarios'=>'not_in:Elegir'
           ,'tresr_cant_lugares'=>'required|numeric|min:1'

        ];

            $messages =[
                'tresr_description.required' => 'El nombre del permiso es requerido',

                'tresr_description.min'=> 'El nombre debe tener al menos 5 caracteres',
                'tresr_tipo.not_in'=> 'El tipo seleccionado debe ser distinto a Elegir',
                'tresr_tipo_horarios.not_in'=> 'El rango horario seleccionado debe ser distinto a Elegir',
                'tresr_cant_lugares.min'=> 'La cantidad debe mayor o igual a 1',


            ];
            $this->validate($rules,$messages);

            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();

            $lote = TipoReserva::where('tresr_id','=',$this->selected_id)->update([
                'tresr_country_id'=> $c->lot_country_id,
                'tresr_description'=> $this->tresr_description,
                'tresr_tipo' =>$this->tresr_tipo,
                'tresr_tipo_horarios'=>$this->tresr_tipo_horarios,
                'tresr_cant_lugares'=>$this->tresr_cant_lugares,
                'tresr_email'=>$this->tresr_email,
                'tresr_url' =>$this->tresr_url,

            ]);

            $this->resetUI();
            $this->emit('treservas-updated','Tipo Actualizado');


        }


        public function resetUI(){

            $this->selected_id="";
            $this->tresr_tipo='Elegir';
            $this->tresr_tipo_horarios='Elegir';
           $this->tresr_email = '';
           $this->tresr_url='';
            $this->tresr_description='';
            $this->tresr_tipo='';
            $this->tresr_cant_lugares='';
        }

        protected $listeners =['deleteRow'=>'Destroy','resetUI'=>'resetUI'];
        public function Destroy($id){
            TipoReserva::where('tresr_id','=',$id)->delete();
            $this->resetUI();
            $this->emit('treservas-deleted','Tipo Eliminado');

        }
}
