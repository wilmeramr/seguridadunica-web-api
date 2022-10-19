<?php

namespace App\Http\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\WithPagination;

class DeviceController extends Component
{

    use WithPagination;
    public $dev_token, $search,$selected_id,$pageTitle,$componentName;
    private $pagination =10;


    public function paginateView()
    {
       return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Dispositivos";

    }

    public function render()
    {

        if(strlen($this->search) > 0)
          $devices = Device::join('users as usr','usr.id','devices.dev_id')
          ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
          ->join('countries as c','c.co_id','lt.lot_country_id')
          ->select('devices.*','lt.lot_name','usr.us_name','usr.email','c.co_name')
          ->where( function($query) {
            $query->where('lot_name','like','%'.$this->search.'%')
            ->orWhere('co_name','like','%'.$this->search.'%')
            ->orWhere('us_name','like','%'.$this->search.'%')
            ->orWhere('email','like','%'.$this->search.'%');

      })
      ->orderBy('dev_id','desc')
          ->paginate($this->pagination);
        else
        $devices = Device::join('users as usr','usr.id','devices.dev_id')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->join('countries as c','c.co_id','lt.lot_country_id')
        ->select('devices.*','lt.lot_name','usr.us_name','usr.email','c.co_name')
        ->orderBy('dev_id','desc')
        ->paginate($this->pagination);



        return view('livewire.device.component',[
            'devices'=> $devices
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
        $device =  Device::where('dev_id','=',$id)->first();

        $this->selected_id= $device->dev_id;
        $this->dev_token= $device->dev_token;

        $this->emit('show-modal','Show modal');
    }


    public function UpdateTipo()
    {

       $tipo =  Device::where('dev_id','=',$this->selected_id)->update([

        'dev_token'=>$this->dev_token
    ]);;


       $this->emit('device-updated','Se actualizó el TOKEN.');
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
        $this->dev_token="";
       // $this->resetValidation();
    }
}
