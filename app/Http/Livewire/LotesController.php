<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Lote;
use Illuminate\Validation\Rule;

use Livewire\Component;
use Livewire\WithPagination;



class LotesController extends Component
{

    use WithPagination;


    public $lot_name, $co_id, $search,$selected_id,$pageTitle,$componentName;

    private $pagination=5;

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle='Listado';
        $this->componentName='Lotes';
        $this->co_id='Elegir';
    }
    public function render()
    {

                    if (\Auth::user()->roles[0]->name != 'Administrador') {
                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
                    $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();

                        }else{
                            $lotes = Lote::select('lot_id')->get();

                        // dd($lotes);
                        };

        if(strlen($this->search) > 0)
        $lotes = Lote::join('countries as c','c.co_id','lotes.lot_country_id')
                        ->select('lotes.lot_id','lotes.lot_name','c.co_name')

                        ->whereIn('lotes.lot_id',$lotes)->where( function($query) {
                            $query->where('lotes.lot_name','like','%'.$this->search .'%')
                            ->orWhere('c.co_name','like','%'.$this->search .'%')
                            ;
                      })
                        ->orderBy('lotes.lot_name','asc')
                        ->paginate($this->pagination);

        else
        $lotes =Lote::join('countries as c','c.co_id','lotes.lot_country_id')
                        ->select('lotes.lot_id','lotes.lot_name','c.co_name')
                        ->whereIn('lotes.lot_id',$lotes)
                        ->orderBy('lotes.lot_name','asc')
                        ->paginate($this->pagination);


        if (\Auth::user()->roles[0]->name == 'Administrador') {
            $queryCo = Country::orderBy('co_name','asc');
        }else{
            $us_lote_id = \Auth::user()->us_lote_id;
            $lot_country_id = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
            $queryCo = Country::where('co_id', '=', $lot_country_id->lot_country_id);
        }


        return view('livewire.lotes.lotes',[
            'data'=> $lotes,
            'countries'=>$queryCo->get()
        ])
        ->extends('layouts.theme.app')
    ->section('content');
        }



        public function Store(){

            $rules =[

                'lot_name' => [
                    'required', Rule::unique('lotes', 'lot_name')
                    ->where('lot_country_id', $this->co_id),'min:3'
            ],

            'co_id' => [
                'required', Rule::unique('lotes', 'lot_country_id')
                    ->where('lot_name', $this->lot_name),'not_in:Elegir'
            ]
            ];

            $messages =[
                'lot_name.required'=> "Nombre del lote es requerido",
                'lot_name.min'=> 'El nombre del lote debe tener al menos 3 caracteres',
                'lot_name.unique'=> 'El nombre del lote ya existe en el country seleccionado',

                'co_id.required'=> "El country es requerido",
                'co_id.unique'=> 'El country seleccionado ya tiene un lote con el mismo nombre',
                'co_id.not_in'=> 'El country seleccionado debe ser distinto a Elegir',


            ];

            $this->validate($rules,$messages);

            $lote = Lote::create([
                'lot_name'=>$this->lot_name,
                'lot_country_id'=>$this->co_id,
                'lot_activo'=>0,

            ]);


            $this->resetUI();
            $this->emit('lote_added','Lote Registrado');


        }

        public function Edit($id){

            $lote = Lote::where('lot_id','=',$id)->first();

            $this->selected_id = $lote->lot_id;
             $this->lot_name = $lote->lot_name;
              $this->co_id = $lote->lot_country_id;

              $this->emit('show-modal','Show modal');

        }

        public function Update(){

            $rules =[

                'lot_name' => [
                    'required', Rule::unique('lotes', 'lot_name')
                    ->where('lot_country_id', $this->co_id),'min:3'
            ],

            'co_id' => [
                'required', Rule::unique('lotes', 'lot_country_id')
                    ->where('lot_name', $this->lot_name),'not_in:Elegir'
            ]
            ];

            $messages =[
                'lot_name.required'=> "Nombre del lote es requerido",
                'lot_name.min'=> 'El nombre del lote debe tener al menos 3 caracteres',
                'lot_name.unique'=> 'El nombre del lote ya existe en el country seleccionado',

                'co_id.required'=> "El country es requerido",
                'co_id.unique'=> 'El country seleccionado ya tiene un lote con el mismo nombre',
                'co_id.not_in'=> 'El country seleccionado debe ser distinto a Elegir',
            ];

            $this->validate($rules,$messages);

            Lote::where('lot_id','=',$this->selected_id)->update([
                'lot_name'=>$this->lot_name,
                'lot_country_id'=>$this->co_id,
                'lot_activo'=>0,

            ]);


            $this->resetUI();
            $this->emit('lote-updated','Lote Actualizado');


        }


        public function resetUI(){

            $this->selected_id="";
            $this->lot_name="";
            $this->co_id="Elegir";


        }

        protected $listeners =['deleteRow'=>'Destroy','resetUI'=>'resetUI'];
        public function Destroy($id){

            $this->resetUI();
            $this->emit('lote-deleted','Lote Eliminado');

        }
}
