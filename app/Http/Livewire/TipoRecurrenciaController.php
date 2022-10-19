<?php

namespace App\Http\Livewire;

use App\Models\ServicioTipo;
use Livewire\Component;
use Livewire\WithPagination;

class TipoRecurrenciaController extends Component
{

    use WithPagination;
    public $stp_descripcion, $search,$selected_id,$pageTitle,$componentName;
    private $pagination =10;


    public function paginateView()
    {
       return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Tipos De Visitas Recurrentes";

    }

    public function render()
    {
        if(strlen($this->search) > 0)
          $servicioTipo = ServicioTipo::where('stp_descripcion','like','%'. $this->search.'%')->paginate($this->pagination);
        else
          $servicioTipo = ServicioTipo::orderBy('stp_descripcion','asc')->paginate($this->pagination);


        return view('livewire.tiporecurrencia.component',[
            'tipos'=> $servicioTipo
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }



    public function CreateTipo()
    {
       $rules = ['stp_descripcion'=>'required|min:3|unique:servicio_tipos,stp_descripcion'];

       $messages =[
           'stp_descripcion.required' => 'La Descripción es requerida',
           'stp_descripcion.unique'=> 'La Descripción ya existe',
           'stp_descripcion.min'=> 'La Descripción debe tener al menos 2 caracteres',

       ];
       $this->validate($rules,$messages);

       ServicioTipo::create(['stp_descripcion'=>$this->stp_descripcion]);

       $this->emit('tiporecurrente-added','Permiso agregado.');
       $this->resetUI();
    }

    public function Edit($id)
    {
        $tipo =  ServicioTipo::where('stp_id','=',$id)->first();

        $this->selected_id= $tipo->stp_id;
        $this->stp_descripcion= $tipo->stp_descripcion;

        $this->emit('show-modal','Show modal');
    }


    public function UpdateTipo()
    {
       $rules = ['stp_descripcion'=>"required|min:3|unique:servicio_tipos,stp_descripcion,{$this->selected_id},stp_id"];

       $messages =[
        'stp_descripcion.required' => 'La Descripción es requerida',
        'stp_descripcion.unique'=> 'La Descripción ya existe',
        'stp_descripcion.min'=> 'La Descripción debe tener al menos 2 caracteres',

    ];

       $this->validate($rules,$messages);

       $tipo =  ServicioTipo::where('stp_id','=',$this->selected_id)->update([

        'stp_descripcion'=>$this->stp_descripcion
    ]);;


       $this->emit('tiporecurrente-updated','Se actualizó el tipo.');
       $this->resetUI();
    }


    protected $listeners =['deleteRow'=>'Destroy'];
    public function Destroy($id){
        $tipo = ServicioTipo::where('stp_id','=',$id)->first();

        $tipo::where('stp_id','=',$tipo->stp_id)->update([

            'stp_activo'=>$tipo->stp_activo==1?0:1
        ]);

        $this->resetUI();
        $this->emit('tiporecurrente-deleted');
    }

    public function resetUI()
    {
        $this->selected_id="";
        $this->search ="";
        $this->stp_descripcion="";
       // $this->resetValidation();
    }
}
