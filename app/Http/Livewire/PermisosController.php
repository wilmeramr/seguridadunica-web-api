<?php

namespace App\Http\Livewire;
use App\Models\Rol;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosController extends Component
{
    use WithPagination;
    public $permissionName, $search,$selected_id,$pageTitle,$componentName;
    private $pagination =10;


    public function paginateView()
    {
       return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Permisos";

    }

    public function render()
    {
        if(strlen($this->search) > 0)
          $permisos = Permission::where('name','like','%'. $this->search.'%')->paginate($this->pagination);
        else
          $permisos = Permission::orderBy('name','asc')->paginate($this->pagination);


        return view('livewire.permisos.component',[
            'permisos'=> $permisos
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }



    public function CreatePermission()
    {
       $rules = ['permissionName'=>'required|min:3|unique:permissions,name'];

       $messages =[
           'permissionName.required' => 'El nombre del permiso es requerido',
           'permissionName.unique'=> 'El permiso ya existe',
           'permissionName.min'=> 'El nombre del permiso debe tener al menos 2 caracteres',

       ];
       $this->validate($rules,$messages);

       Permission::create(['name'=>$this->permissionName]);

       $this->emit('permiso-added','Permiso agregado.');
       $this->resetUI();
    }

    public function Edit(Permission $permission)
    {
     //   $role = Role::find($id);

        $this->selected_id= $permission->id;
        $this->permissionName= $permission->name;

        $this->emit('show-modal','Show modal');
    }


    public function UpdatePermission()
    {
       $rules = ['permissionName'=>"required|min:3|unique:permissions,name,{$this->selected_id}"];

       $messages =[
           'roleName.required' => 'El nombre del permiso es requerido',
           'roleName.unique'=> 'El permiso ya existe',
           'roleName.min'=> 'El nombre del permiso debe tener al menos 2 caracteres',
       ];

       $this->validate($rules,$messages);

       $permiso = Permission::find($this->selected_id);
       $permiso->name = $this->permissionName;
       $permiso->save();

       $this->emit('permiso-updated','Se actualizó el permiso.');
       $this->resetUI();
    }

    protected $listeners =['destroy' => 'Destroy'];

    public function Destroy()
    {
        $this->emit('permiso-deleted','Se elimino el role.');

    }

    public function resetUI()
    {
        $this->selected_id="";
        $this->search ="";
        $this->permissionName="";
       // $this->resetValidation();
    }

}
