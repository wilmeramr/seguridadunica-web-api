<?php

namespace App\Http\Livewire;

use App\Models\Rol;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesController extends Component
{
    use WithPagination;
    public $roleName, $search,$selected_id,$pageTitle,$componentName;
    private $pagination =5;


    public function paginateView()
    {
       return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName="Roles";

    }

    public function render()
    {
        if(strlen($this->search) > 0)
          $roles = Role::where('name','like','%'. $this->search.'%')->paginate($this->pagination);
        else
          $roles = Role::orderBy('name','asc')->paginate($this->pagination);


        return view('livewire.roles.component',[
            'roles'=> $roles
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function CreateRole()
    {
       $rules = ['roleName'=>'required|min:3|unique:roles,name'];

       $messages =[
           'roleName.required' => 'El nombre del role es requerido',
           'roleName.unique'=> 'El role ya existe',
           'roleName.min'=> 'El nombre del rol debe tener al menos 2 caracteres',

       ];
       $this->validate($rules,$messages);

       Role::create(['name'=>$this->roleName]);

       $this->emit('role-added','Rol agregado.');
       $this->resetUI();
    }

    public function Edit(Role $role)
    {
     //   $role = Role::find($id);

        $this->selected_id= $role->id;
        $this->roleName= $role->name;

        $this->emit('show-modal','Show modal');
    }


    public function UpdateRole()
    {
       $rules = ['roleName'=>"required|min:3|unique:roles,name,{$this->selected_id}"];

       $messages =[
           'roleName.required' => 'El nombre del role es requerido',
           'roleName.unique'=> 'El role ya existe',
           'roleName.min'=> 'El nombre del rol debe tener al menos 2 caracteres',
       ];

       $this->validate($rules,$messages);

       $role = Role::find($this->selected_id);
       $role->name = $this->roleName;
       $role->save();

       $this->emit('role-updated','Se actualizó el role.');
       $this->resetUI();
    }

    protected $listeners =['destroy' => 'Destroy'];

    public function Destroy()
    {
        $this->emit('role-deleted','Se elimino el role.');

    }

    public function resetUI()
    {
        $this->selected_id="";
        $this->search ="";
        $this->roleName="";
    //    $this->resetValidation();
    }

}
