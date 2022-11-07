<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Horario;
use App\Models\Informacion;
use App\Models\Lote;
use App\Models\Noticia;
use App\Models\Notificacion;
use App\Models\TipoReserva;
use Illuminate\Validation\Rule;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;



class NoticiaController extends Component
{

    use WithPagination;


    public $notic_titulo,$search,$selected_id,$pageTitle,$componentName;
    public $notic_body;
    private $pagination=10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle='Listado';
        $this->componentName='Noticias';

    }
    public function render()
    {

                    if (\Auth::user()->roles[0]->name != 'Administrador') {
                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                    $notic = Noticia::where('notic_country_id','=',$lote->lot_country_id)->select('notic_id')->get();

                        }else{
                            $notic = Noticia::select('notic_id')->get();

                        // dd($lotes);
                        };

        if(strlen($this->search) > 0)
            $data = Noticia::join('countries as c','c.co_id','noticias.notic_country_id')
                        ->select('noticias.*','c.co_name')

                        ->whereIn('noticias.notic_id',$notic)->where( function($query) {
                            $query->where('noticias.notic_titulo','like','%'.$this->search .'%')
                            ->orWhere('noticias.notic_body','like','%'.$this->search .'%')
                            ->orWhere('c.co_name','like','%'.$this->search .'%')
                            ;
                      })
                        ->orderBy('noticias.notic_titulo','asc')
                        ->paginate($this->pagination);

        else
        $data =Noticia::join('countries as c','c.co_id','noticias.notic_country_id')
                        ->select('noticias.*','c.co_name')
                        ->whereIn('noticias.notic_id',  $notic)
                        ->orderBy('noticias.notic_titulo','asc')
                        ->paginate($this->pagination);



        return view('livewire.noticia.component',[
            'data'=> $data

        ])
        ->extends('layouts.theme.app')
    ->section('content');
        }



        public function Store(){

            $rules = ['notic_titulo'=>'required|min:5',
            'notic_body'=>'required|min:10|max:1500'
        ];

            $messages =[
                'notic_titulo.required' => 'El nombre del permiso es requerido',
                'notic_titulo.min'=> 'El nombre debe tener al menos 5 caracteres',
                'notic_body.required' => 'El nombre del permiso es requerido',
                'notic_body.min'=> 'El nombre debe tener al menos 10 caracteres',
                'notic_body.max'=> 'El nombre debe tener un maximo de 1500 caracteres',

            ];
            $this->validate($rules,$messages);

            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();


            Noticia::create([
                'notic_country_id' => $c->lot_country_id,
                'notic_user_id' => \Auth::user()->id,
                'notic_titulo' =>$this->notic_titulo,
                'notic_body' =>$this->notic_body,
                'notic_to' => 'T',
                'notic_to_user' => \Auth::user()->id,
                'notic_app'=> 1
            ]);


            Notificacion::create([
                'noti_user_id'=>\Auth::user()->id,
                'noti_aut_code'=> 'NOTICIAS',
                'noti_titulo' =>  "Nueva Noticia.",
                'noti_body' => $this->notic_titulo,
                'noti_to' => 'T',
                'noti_to_user' =>\Auth::user()->id,

                'noti_event' => 'Noticias' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app'=> 1
            ]);

            $this->resetUI();
            $this->emit('notic-added','Noticia Registrada');


        }

        public function Edit($id){

            $notic = Noticia::where('notic_id','=',$id)->first();

            $this->selected_id = $notic->notic_id;
             $this->notic_titulo = $notic->notic_titulo;
           $this->notic_body = $notic->notic_body;

              $this->emit('show-modal','Show modal');

        }

        public function Update(){

            $rules = ['notic_titulo'=>'required|min:5',
            'notic_body'=>'required|min:10|max:1500'
        ];

            $messages =[
                'notic_titulo.required' => 'El nombre del permiso es requerido',
                'notic_titulo.min'=> 'El nombre debe tener al menos 5 caracteres',
                'notic_body.required' => 'El nombre del permiso es requerido',
                'notic_body.min'=> 'El nombre debe tener al menos 10 caracteres',
                'notic_body.max'=> 'El nombre debe tener un maximo de 1500 caracteres',

            ];
            $this->validate($rules,$messages);

            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();



          Noticia::where('notic_id','=', $this->selected_id)->update([

                'notic_titulo'=> $this->notic_titulo,
                'notic_body' =>$this->notic_body

            ]);

            Notificacion::create([
                'noti_user_id'=>\Auth::user()->id,
                'noti_aut_code'=> 'NOTICIAS',
                'noti_titulo' =>  "Nueva Noticia.",
                'noti_body' => $this->notic_titulo,
                'noti_to' => 'T',
                'noti_to_user' =>\Auth::user()->id,

                'noti_event' => 'Noticias' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app'=> 1
            ]);

            $this->resetUI();
            $this->emit('notic-updated','Noticia Actualizada');


        }


        public function resetUI(){

            $this->selected_id="";

            $this->notic_titulo='';
            $this->notic_body='';

        }

        protected $listeners =['deleteRow'=>'Destroy','resetUI'=>'resetUI'];
        public function Destroy($id){
            Noticia::where('notic_id','=',$id)->delete();
            $this->resetUI();
            $this->emit('notic-deleted','Noticia Eliminada');

        }
}
