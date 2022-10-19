<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use App\Models\User;
use App\Models\Lote;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Hash;



class NotificacionesController extends Component
{
    use WithPagination,WithFileUploads;

public $us_name, $us_phone, $email, $password, $status,$role, $us_lote_id, $selected_id, $notificacionDetail, $detalle_notificacion;
public $noti_autoriza,$masc_descripcion, $noti_body,$chk,$noti_titulo;
public $pageTitle,$componentName, $search;
private $pagination = 20;


public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Notificaciones';
        $this->masc_autoriza='Elegir';
        $this->chk="T";

    }
    public function render()
    {
        if (\Auth::user()->roles[0]->name != 'Administrador') {
        $us_lote_id = \Auth::user()->us_lote_id;
        $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
        $lotes = Lote::where('lot_country_id','=',$lote->lot_country_id)->select('lot_id')->get();
        $users = User::whereIn('us_lote_id',$lotes)->select('id')->get();
        }else{
            $lotes = Lote::select('lot_id')->get();
            $users = User::whereIn('us_lote_id',$lotes)->select('id')->get();
           // dd($lotes);
        }


        if(strlen($this->search) > 0){
             $data = Notificacion::
             whereIn('noti_user_id',$users)
             ->join('users as usr','usr.id','notificacions.noti_to_user')
             ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
             ->where( function($query) {
                $query->where('lt.lot_name','like','%'.$this->search.'%')
                ->orWhere('us_name','like','%'.$this->search.'%')
                ->orWhere('email','like','%'.$this->search.'%')
                ->orWhere('noti_body','like','%'.$this->search.'%')
                ->orWhere('noti_event','like','%'.$this->search.'%')
                ->orWhere('noti_titulo','like','%'.$this->search.'%');
           })
           ->select('notificacions.*','lt.lot_name','usr.us_name','usr.email')
         ->orderBy('notificacions.id','desc')
         ->paginate($this->pagination);


        }

        else
        $data = Notificacion::whereIn('noti_user_id', $users)
        ->join('users as usr','usr.id','notificacions.noti_to_user')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->orderby('id','desc')
        ->select('notificacions.*','lt.lot_name','usr.us_name','usr.email')
        ->paginate($this->pagination);
        //    $noti = Notificacion::whereIn('noti_user_id', $userL)->orderby('id','desc')->paginate(20);


        if (\Auth::user()->roles[0]->name == 'Administrador') {
            $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
            ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
            ->where('email', '<>', '');

        }else{
            $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                        ->whereIn('us_lote_id',$lotes)
                        ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                    ->orderBy('us_name','asc');

        }

        return view('livewire.notificaciones.component',[
            'notificacions'=>$data,

            'users'=> $queryUsers->get()

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }
    public function show($noti_id)
    {
        $this->notificacionDetail = Notificacion::where('notificacions.id','=', $noti_id)
        ->join('users as usr','usr.id','notificacions.noti_user_id')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
        ->orderby('id','desc')
        ->select('notificacions.*','lt.lot_name','usr.us_name','usr.email')  ->first();

           $this->detalle_notificacion = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal-detalle', 'open!');

    }
    public function resetUI()
    {

       $this->noti_titulo ='';
       $this->noti_body ='';

        $this->selected_id ='';
        $this->noti_autoriza='';
        $this->chk="T";
        $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2','Elegir');


    }

    public function Edit($masc_id)
    {
        $mascota  = Mascota::where('masc_id','=',$masc_id)->first();


            $this->selected_id = $mascota->masc_id;
            $this->masc_name = $mascota->masc_name;
            $this->masc_raza = $mascota->masc_raza;
            $this->masc_peso = $mascota->masc_peso;

            $this->masc_fecha_nacimiento = $mascota->masc_fecha_nacimiento;
            $this->masc_fecha_vacunacion = $mascota->masc_fecha_vacunacion;
            $this->masc_especie_id = $mascota->masc_especie_id;
            $this->masc_genero_id = $mascota->masc_genero_id;
            $this->masc_especie_id = $mascota->masc_especie_id;
            $this->masc_descripcion = $mascota->masc_descripcion;

        $this->masc_autoriza=$mascota->masc_user_id;
        $this->emit('reset-select2',$mascota->masc_user_id);

            $this->emit('show-modal', 'open!');



    }

    protected $listeners=[
        'deleteRow'=>'destroy',
        'resetUI'=> 'resetUI'
    ];

    public function Store()
    {

        $rules =[
            'noti_body' => 'required|string',
            'noti_titulo' => 'required|string',
        ];

        $messages =[
            'noti_titulo.required'=>'Ingresa el titulo del  mensaje a enviar',
            'noti_body.required'=>'Ingresa el mensaje a enviar',
        ];

        if($this->chk=='L'){
                $rules =[
            'noti_body' => 'required|string',
            'noti_titulo' => 'required|string',
            'noti_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'noti_body.required'=>'Ingresa el mensaje a enviar',
            'noti_titulo.required'=>'Ingresa el titulo del  mensaje a enviar',

            'noti_autoriza.required' =>'Selecciona un usuario del lote a enviar',
            'noti_autoriza.not_in' =>'Selecciona un usuario del lote a enviar',
        ];
        }


        $this->validate($rules,$messages);


        $noti_aut_code ="NOTIFICACIONES";
        $noti_titulo = $this->noti_titulo;;
        $noti_body = $this->noti_body;
        $noti_event ="Notificacion";

        Notificacion::create([
            'noti_user_id'=> \Auth::user()->id,
            'noti_aut_code'=> $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => $this->chk,
            'noti_to_user' => $this->chk =='L'?  $this->noti_autoriza : \Auth::user()->id,

            'noti_event' => $noti_event ,
            'noti_priority' =>'high',
            'noti_envio'=> 0

        ]);


         $this->resetUI();
         $this->emit('mastoca-added','Usuario Registrado');
    }

    public function Update()
    {


        $rules =[
            'masc_name' => 'required|string',
            'masc_especie_id' => 'required|not_in:Elegir',
            'masc_raza' => 'required|string',
            'masc_genero_id' => 'required|not_in:Elegir',
            'masc_peso' => 'required',
            'masc_fecha_nacimiento' => 'required|date',
            'masc_fecha_vacunacion' => 'required|date',
            //'masc_descripcion' => 'required|string',
            //'masc_url_foto' => 'required|mimes:jpeg,png,jpg',
            'masc_autoriza'=>'required|not_in:Elegir',
        ];

        $messages =[
            'masc_name.required'=>'Ingresa el nombre',
            'masc_especie_id.required'=>'Seleccione el tipo de mascoca',
            'masc_especie_id.not_in' =>'Seleccione el tipo de mascoca',
            'masc_raza.required' =>'Ingresa la raza',
            'masc_genero_id.required' => 'Seleccione el sexo de mascoca',
            'masc_genero_id.not_in' => 'Seleccione el sexo de mascoca',
            'masc_peso.required' =>'Ingresa la peso',
            'masc_fecha_nacimiento.required' =>'Debe ingresar una fecha de nacimiento',
            'masc_fecha_vacunacion.required' =>'Debe ingresar la fecha de vacunación',
           // 'masc_url_foto.required' => 'La foto es requerida',
            //'masc_url_foto.mimes' => 'La foto debe ser de formatos [jpeg, png, jpg]',
            'masc_autoriza.required' =>'Selecciona el usuario que autoriza',
            'masc_autoriza.not_in' =>'Selecciona el usuario que autoriza',
        ];

        $this->validate($rules,$messages);



        $user = Mascota::where('masc_id','=',$this->selected_id)->update([
                 'masc_user_id' =>$this->masc_autoriza,
                'masc_name' => $this->masc_name,
                'masc_especie_id' => $this->masc_especie_id,

                'masc_raza'=>$this->masc_raza,
                'masc_genero_id' => $this->masc_genero_id,
                'masc_peso' => $this->masc_peso,
                'masc_fecha_nacimiento' => $this->masc_fecha_nacimiento,
                'masc_fecha_vacunacion'=> $this->masc_fecha_vacunacion,
                'masc_descripcion'=> $this->masc_descripcion
    ]);

    if($this->masc_url_foto){
        $customFileNamelogo = \Str::uuid().".".$this->masc_url_foto->extension();

        $path = 'img/mascotas';
        $this->masc_url_foto->storeAs($path,$customFileNamelogo);


        Mascota::where('masc_id','=',$this->selected_id)->update([

            'masc_url_foto'=> \Storage::url($path.'/'.$customFileNamelogo),
    ]);

    }
         $this->resetUI();

         $this->emit('mascota-updated','Mascota Actualizada');
    }

    public function destroy($masc_id)
    {
      $mascota =  Mascota::where('masc_id','=',$masc_id)->first();

      if( \Str::contains($mascota->masc_url_foto,'mascotas')){

        $explo = explode('/',$mascota->masc_url_foto);
        $imageName = $explo[count($explo)-1];
        if($imageName !=null){


            if(file_exists('storage/img/mascotas/'.$imageName)){
               // dd($imageName);
                unlink('storage/img/mascotas/'.$imageName);
            }
        }




      }
      Mascota::where('masc_id','=',$masc_id)->delete();
        $this->resetUI();
     $this->emit('mascota-deleted','Mascota Eliminada');

    }
}
