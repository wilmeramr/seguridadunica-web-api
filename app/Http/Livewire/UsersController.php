<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Lote;
use Illuminate\Support\Facades\Hash;



class UsersController extends Component
{
    use WithPagination;
public $us_name, $us_phone, $email, $password, $status,$role, $us_lote_id, $selected_id;
public $pageTitle,$componentName, $search;
private $pagination = 3;


public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Usuarios';
        $this->status ='Elegir';
        $this->us_lote_id ='Elegir';

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
        }

        if(strlen($this->search) > 0){

            if (\Auth::user()->roles[0]->name != 'Administrador') {
             $data = User::join('model_has_roles as mhr','mhr.model_id','users.id')
                ->where('mhr.role_id','<>',1)

             ->whereIn('us_lote_id',$lotes)->where( function($query) {
              $query->where('us_name','like','%'.$this->search.'%')->orWhere('email','like','%'.$this->search.'%');
        })
        ->select('users.*');

    }else{
        $data = User::join('model_has_roles as mhr','mhr.model_id','users.id')

        ->whereIn('us_lote_id',$lotes)->where( function($query) {
                 $query->where('us_name','like','%'.$this->search.'%')->orWhere('email','like','%'.$this->search.'%');
            })
            ->select('users.*');
    }


        }

        else{


            if (\Auth::user()->roles[0]->name != 'Administrador') {
            $data = User::join('model_has_roles as mhr','mhr.model_id','users.id')
            ->where('mhr.role_id','<>',1)

        ->whereIn('us_lote_id',$lotes)
        ->select('users.*');



        }else{
            $data = User::join('model_has_roles as mhr','mhr.model_id','users.id')

        ->whereIn('us_lote_id',$lotes)
        ->select('users.*');
        }
    }



        if (\Auth::user()->roles[0]->name == 'Administrador') {
            $queryRole = Role::where('name', '<>', '');
            $queryLote =Lote::where('lot_country_id','<>','');
        }else{
            $queryRole = Role::where('name', '<>', 'Administrador');
            $queryLote =Lote::where('lot_country_id','=',$lote->lot_country_id);

        }

        return view('livewire.users.component',[
            'data'=>$data->orderBy('us_name','asc')->paginate($this->pagination),
            'roles'=> $queryRole->orderBy('name','asc')->get(),
            'lotes'=>$queryLote->orderBy('lot_name','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function resetUI()
    {
        $this->us_name='';
        $this->email='';
        $this->password='';
        $this->us_phone ='';
        $this->search='';
        $this->status ='Elegir';
        $this->selected_id ='';
        $this->us_lote_id ='Elegir';

        $this->resetValidation();
        $this->resetPage();

    }

    public function Edit(User $user)
    {
            $this->selected_id = $user->id;
            $this->us_name = $user->us_name;
            $this->email = $user->email;
            $this->us_phone = $user->us_phone;
            $this->status = $user->us_active ==0? 'Locked':'Active';
            $this->us_lote_id = $user->us_lote_id;
            $this->password='';
            $this->role=$user->roles[0]->id;
            $this->emit('show-modal', 'open!');



    }

    protected $listeners=[
        'deleteRow'=>'destroy',
        'resetUI'=> 'resetUI'
    ];

    public function Store()
    {
        $rules =[
            'us_name'=>'required|min:3',
            'email'=>'required|unique:users|email',
            'status'=>'required|not_in:Elegir',
            'role'=>'required|not_in:Elegir',
            'us_lote_id'=>'required|not_in:Elegir',
            'password' =>'required|min:8'
        ];

        $messages =[
            'us_name.required'=>'Ingresa el nombre',
            'us_name.min'=>'El nombre del usuario debe tener al menos 3 caracteres',
            'password.required'=>'Ingresa el password',
            'password.min'=>'El password del usuario debe tener al menos 8 caracteres',
            'email.required' =>'Ingresa un correo',
            'email.email' => 'Ingresa un correo valido',
            'email.unique' =>'El emial ya existe un sistema',
            'status.required' =>'Selecciona el estatus del usuario',
            'status.not_in' =>'Selecciona el estatus',

            'role.required' =>'Selecciona el rol del usuario',
            'role.not_in' =>'Selecciona el rol',

            'us_lote_id.required' =>'Selecciona el lote donde pertenece el usuario',
            'us_lote_id.not_in' =>'Selecciona el lote',

        ];

        $this->validate($rules,$messages);

        $user = User::create([
                'us_name' => $this->us_name,
                'us_apellido' => '',

                'email'=>$this->email,
                'us_phone' => $this->us_phone,
                'us_active' => $this->status =='Active' ? 1:0,
                'us_lote_id' => $this->us_lote_id,
                'password'=> Hash::make($this->password),

        ]);

         $user ->assignRole($this->role);

         $this->resetUI();
         $this->emit('user-added','Usuario Registrado');
    }

    public function Update()
    {
            $rules =[
            'email'=>"required|email|unique:users,email,{$this->selected_id}",
            'us_name'=>'required|min:3',
            'status'=>'required|not_in:Elegir',
            'role'=>'required|not_in:Elegir',
            'us_lote_id'=>'required|not_in:Elegir',
          //  'password' =>'required|min:8'
        ];

        $messages =[
            'us_name.required'=>'Ingresa el nombre',
            'us_name.min'=>'El nombre del usuario debe tener al menos 3 caracteres',
          //  'password.required'=>'Ingresa el password',
           // 'password.min'=>'El password del usuario debe tener al menos 8 caracteres',
            'email.required' =>'Ingresa un correo',
            'email.email' => 'Ingresa un correo valido',
            'email.unique' =>'El emial ya existe un sistema',
            'status.required' =>'Selecciona el estatus del usuario',
            'status.not_in' =>'Selecciona el estatus',

            'role.required' =>'Selecciona el rol del usuario',
            'role.not_in' =>'Selecciona el rol',

            'us_lote_id.required' =>'Selecciona el lote donde pertenece el usuario',
            'us_lote_id.not_in' =>'Selecciona el lote',

        ];
        $this->validate($rules,$messages);

                $user = User::find($this->selected_id);
                $user->update([
                    'us_name' => $this->us_name,
                    'email'=>$this->email,
                    'us_phone' => $this->us_phone,
                    'us_active' => $this->status =='Active' ? 1:0,
                    'us_lote_id' => $this->us_lote_id,
                    'password'=> $this->password!=null? Hash::make($this->password) : $user->password,

            ]);
            $user->removeRole($user->roles[0]->name);
            $user ->assignRole($this->role);

               $this->resetUI();
         $this->emit('user-updated','Usuario Actualizado');
    }

    public function destroy()
    {

    }
}
