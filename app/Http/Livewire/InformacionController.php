<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Horario;
use App\Models\Informacion;
use App\Models\Lote;
use App\Models\Notificacion;
use App\Models\TipoReserva;
use Illuminate\Validation\Rule;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;



class InformacionController extends Component
{

    use WithPagination;


    public $info_titulo,$search,$selected_id,$pageTitle,$componentName;
    public $info_body;
    private $pagination=10;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle='Listado';
        $this->componentName='Información Útil';

    }
    public function render()
    {

                    if (\Auth::user()->roles[0]->name != 'Administrador') {
                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                    $info = Informacion::where('info_country_id','=',$lote->lot_country_id)->select('info_id')->get();

                        }else{
                            $info = Informacion::select('info_id')->get();

                        // dd($lotes);
                        };

        if(strlen($this->search) > 0)
            $data = Informacion::join('countries as c','c.co_id','informacions.info_country_id')
                        ->select('informacions.*','c.co_name')

                        ->whereIn('informacions.info_id',$info)->where( function($query) {
                            $query->where('informacions.info_titulo','like','%'.$this->search .'%')
                            ->orWhere('informacions.info_body','like','%'.$this->search .'%')
                            ->orWhere('c.co_name','like','%'.$this->search .'%')
                            ;
                      })
                        ->orderBy('informacions.info_titulo','asc')
                        ->paginate($this->pagination);

        else
        $data =Informacion::join('countries as c','c.co_id','informacions.info_country_id')
                        ->select('informacions.*','c.co_name')
                        ->whereIn('informacions.info_id',  $info)
                        ->orderBy('informacions.info_titulo','asc')
                        ->paginate($this->pagination);



        return view('livewire.informacion.component',[
            'data'=> $data

        ])
        ->extends('layouts.theme.app')
    ->section('content');
        }



        public function Store(){

            $rules = ['info_titulo'=>'required|min:5',
            'info_body'=>'required|min:10'
        ];

            $messages =[
                'info_titulo.required' => 'El nombre del permiso es requerido',
                'info_titulo.min'=> 'El nombre debe tener al menos 5 caracteres',
                'info_body.required' => 'El nombre del permiso es requerido',
                'info_body.min'=> 'El nombre debe tener al menos 10 caracteres',
            ];
            $this->validate($rules,$messages);

            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();

            $lote = Informacion::create([
                'info_country_id'=> $c->lot_country_id,
                'info_titulo'=> $this->info_titulo,
                'info_body' =>$this->info_body

            ]);

            Notificacion::create([
                'noti_user_id'=>\Auth::user()->id,
                'noti_aut_code'=> 'INFORMACION',
                'noti_titulo' =>  "Nueva Información Útil.",
                'noti_body' => $this->info_titulo,
                'noti_to' => 'T',
                'noti_to_user' =>\Auth::user()->id,

                'noti_event' => 'Información' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app'=> 1
            ]);

            $this->resetUI();
            $this->emit('info-added','Informacíon Registrada');


        }

        public function Edit($id){

            $info = Informacion::where('info_id','=',$id)->first();

            $this->selected_id = $info->info_id;
             $this->info_titulo = $info->info_titulo;
           $this->info_body = $info->info_body;

              $this->emit('show-modal','Show modal');

        }

        public function Update(){

            $rules = ['info_titulo'=>'required|min:5',
            'info_body'=>'required|min:10'
        ];

            $messages =[
                'info_titulo.required' => 'El nombre del permiso es requerido',
                'info_titulo.min'=> 'El nombre debe tener al menos 5 caracteres',
                'info_body.required' => 'El nombre del permiso es requerido',
                'info_body.min'=> 'El nombre debe tener al menos 10 caracteres',
            ];
            $this->validate($rules,$messages);


            $us_lote_id = \Auth::user()->us_lote_id;
            $c = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();

          Informacion::where('info_id','=', $this->selected_id)->update([

                'info_titulo'=> $this->info_titulo,
                'info_body' =>$this->info_body

            ]);

            Notificacion::create([
                'noti_user_id'=>\Auth::user()->id,
                'noti_aut_code'=> 'INFORMACION',
                'noti_titulo' =>  "Nueva Información Útil.",
                'noti_body' => $this->info_titulo,
                'noti_to' => 'T',
                'noti_to_user' =>\Auth::user()->id,

                'noti_event' => 'Información' ,
                'noti_priority' =>'high',
                'noti_envio'=> 0,
                'noti_app'=> 1
            ]);

            $this->resetUI();
            $this->emit('info-updated','Información Actualizada');


        }


        public function resetUI(){

            $this->selected_id="";

            $this->info_titulo='';
            $this->info_body='';

        }

        protected $listeners =['deleteRow'=>'Destroy','resetUI'=>'resetUI'];
        public function Destroy($id){
            Informacion::where('info_id','=',$id)->delete();
            $this->resetUI();
            $this->emit('info-deleted','Información Eliminada');

        }
}
