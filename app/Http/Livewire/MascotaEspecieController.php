<?php

namespace App\Http\Livewire;

use App\Models\MascotaEspecie;
use App\Models\ServicioTipo;
use Livewire\Component;
use Livewire\WithPagination;

class MascotaEspecieController extends Component
{

    use WithPagination;
    public $masc_esp_name, $search,$selected_id,$pageTitle,$componentName;
    private $pagination =10;


    public function paginateView()
    {
       return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Especies de Macota";

    }

    public function render()
    {
        if(strlen($this->search) > 0)
          $mascotaEspecie = MascotaEspecie::where('masc_esp_name','like','%'. $this->search.'%')->paginate($this->pagination);
        else
          $mascotaEspecie = MascotaEspecie::orderBy('masc_esp_name','asc')->paginate($this->pagination);


        return view('livewire.mascotaespecies.component',[
            'especies'=> $mascotaEspecie
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }



    public function CreateTipo()
    {
       $rules = ['masc_esp_name'=>'required|min:3|unique:mascota_especies,masc_esp_name'];

       $messages =[
           'masc_esp_name.required' => 'La Descripción es requerida',
           'masc_esp_name.unique'=> 'La Descripción ya existe',
           'masc_esp_name.min'=> 'La Descripción debe tener al menos 2 caracteres',

       ];
       $this->validate($rules,$messages);

       MascotaEspecie::create(['masc_esp_name'=>$this->masc_esp_name]);

       $this->emit('especie-added','Especie se ha agregado.');
       $this->resetUI();
    }

    public function Edit($id)
    {
        $tipo =  MascotaEspecie::where('masc_esp_id','=',$id)->first();

        $this->selected_id= $tipo->masc_esp_id;
        $this->masc_esp_name= $tipo->masc_esp_name;

        $this->emit('show-modal','Show modal');
    }


    public function UpdateTipo()
    {
       $rules = ['masc_esp_name'=>"required|min:3|unique:mascota_especies,masc_esp_name,{$this->selected_id},masc_esp_id"];

       $messages =[
        'masc_esp_name.required' => 'La Descripción es requerida',
        'masc_esp_name.unique'=> 'La Descripción ya existe',
        'masc_esp_name.min'=> 'La Descripción debe tener al menos 2 caracteres',

    ];

       $this->validate($rules,$messages);

       $tipo =  MascotaEspecie::where('masc_esp_id','=',$this->selected_id)->update([

        'masc_esp_name'=>$this->masc_esp_name
    ]);;


       $this->emit('especie-updated','Se actualizó el tipo.');
       $this->resetUI();
    }


    protected $listeners =['deleteRow'=>'Destroy','resetUI'=>'resetUI'];
    public function Destroy($id){
        $tipo = MascotaEspecie::where('masc_esp_id','=',$id)->first();

        $tipo::where('masc_esp_id','=',$tipo->masc_esp_id)->update([

            'masc_esp_activo'=>$tipo->masc_esp_activo==1?0:1
        ]);

        $this->resetUI();
        $this->emit('especie-deleted');
    }

    public function resetUI()
    {
        $this->selected_id="";
        $this->search ="";
        $this->masc_esp_name="";
       // $this->resetValidation();
    }
}
