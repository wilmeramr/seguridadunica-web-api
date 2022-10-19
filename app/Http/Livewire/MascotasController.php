<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use App\Models\User;
use App\Models\Lote;
use App\Models\Mascota;
use App\Models\Notificacion;
use App\Models\MascotaEspecie;
use App\Models\MascotaGenero;
use Illuminate\Support\Facades\Hash;



class MascotasController extends Component
{
    use WithPagination,WithFileUploads;

public $us_name, $us_phone, $email, $password, $status,$role, $us_lote_id, $selected_id, $mascotaDetail, $detalle_mascota;
public $masc_name,$masc_especie_id,$masc_raza,$masc_genero_id,$masc_peso,$masc_fecha_nacimiento,$masc_fecha_vacunacion,$masc_url_foto;
public $masc_autoriza,$masc_descripcion;
public $pageTitle,$componentName, $search;
private $pagination = 10;


public function paginationView()
{
    return 'vendor.livewire.bootstrap';
}

    public function mount()
    {
        $this->pageTitle="Listado";
        $this->componentName='Mascotas';
        $this->masc_genero_id ='Elegir';
        $this->masc_especie_id ='Elegir';
        $this->masc_autoriza='Elegir';

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
             $data = Mascota::
             whereIn('mascotas.masc_user_id',$users)
             ->join('users as usr','usr.id','mascotas.masc_user_id')
             ->join('mascota_especies as me','me.masc_esp_id','mascotas.masc_especie_id')
             ->join('lotes as lt','lt.lot_id','usr.us_lote_id')
             ->where( function($query) {
                $query->where('lt.lot_name','like','%'.$this->search.'%')
                ->orWhere('masc_name','like','%'.$this->search.'%')
                ->orWhere('us_name','like','%'.$this->search.'%')
                ->orWhere('email','like','%'.$this->search.'%')
                ->orWhere('masc_raza','like','%'.$this->search.'%')
                ->orWhere('masc_esp_name','like','%'.$this->search.'%');
           })
           ->select('mascotas.*','lt.lot_name','me.masc_esp_name','usr.us_name','usr.email')
         ->orderBy('masc_name','desc')
         ->paginate($this->pagination);


        }

        else
        $data = Mascota::whereIn('masc_user_id',$users)
        ->join('users as usr','usr.id','mascotas.masc_user_id')
        ->join('mascota_especies as me','me.masc_esp_id','mascotas.masc_especie_id')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

        ->select('mascotas.*','lt.lot_name','me.masc_esp_name','usr.us_name','usr.email')

    ->orderBy('masc_name','desc')
        ->paginate($this->pagination);


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

        return view('livewire.mascotas.component',[
            'mascotas'=>$data,
            'tipos'=> MascotaEspecie::all(),
            'generos'=> MascotaGenero::all(),
            'users'=> $queryUsers->get()

        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }
    public function show($masc_id)
    {
        $this->mascotaDetail = Mascota::where('masc_id','=',$masc_id)
        ->join('users as usr','usr.id','mascotas.masc_user_id')
        ->join('mascota_especies as me','me.masc_esp_id','mascotas.masc_especie_id')
        ->join('lotes as lt','lt.lot_id','usr.us_lote_id')

        ->select('mascotas.*','lt.lot_name','me.masc_esp_name','usr.us_name','usr.email')

    ->orderBy('masc_name','desc')  ->first();

           $this->detalle_mascota = 'Detalle';
       // dd($this->autorizacionDetail->aut_nombre);
            $this->emit('show-modal-detalle', 'open!');

    }
    public function resetUI()
    {

        $this->masc_name='';
        $this->masc_raza='';
        $this->masc_peso='';
        $this->masc_fecha_nacimiento ='';
        $this->masc_fecha_vacunacion='';
        $this->masc_especie_id ='Elegir';
        $this->masc_descripcion ='';
        $this->selected_id ='';
        $this->masc_autoriza='';
        $this->masc_genero_id ='Elegir';
        $this->masc_url_foto ='';
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
            'masc_name' => 'required|string',
            'masc_especie_id' => 'required|not_in:Elegir',
            'masc_raza' => 'required|string',
            'masc_genero_id' => 'required|not_in:Elegir',
            'masc_peso' => 'required',
            'masc_fecha_nacimiento' => 'required|date',
            'masc_fecha_vacunacion' => 'required|date',
            //'masc_descripcion' => 'required|string',
            'masc_url_foto' => 'required|mimes:jpeg,png,jpg',
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
            'masc_url_foto.required' => 'La foto es requerida',
            'masc_url_foto.mimes' => 'La foto debe ser de formatos [jpeg, png, jpg]',
            'masc_autoriza.required' =>'Selecciona el usuario que autoriza',
            'masc_autoriza.not_in' =>'Selecciona el usuario que autoriza',
        ];

        $this->validate($rules,$messages);


        if($this->masc_url_foto){
            $customFileNamelogo = \Str::uuid().".".$this->masc_url_foto->extension();

            $path = 'img/mascotas';
            $this->masc_url_foto->storeAs($path,$customFileNamelogo);


            $mascota = Mascota::create([
                'masc_user_id' =>$this->masc_autoriza,
                'masc_name' => $this->masc_name,
                'masc_especie_id' => $this->masc_especie_id,

                'masc_raza'=>$this->masc_raza,
                'masc_genero_id' => $this->masc_genero_id,
                'masc_peso' => $this->masc_peso,
                'masc_fecha_nacimiento' => $this->masc_fecha_nacimiento,
                'masc_fecha_vacunacion'=> $this->masc_fecha_vacunacion,
                'masc_descripcion'=> $this->masc_descripcion,
                'masc_url_foto'=> \Storage::url($path.'/'.$customFileNamelogo),

        ]);


        }

        Notificacion::create([
            'noti_user_id'=> \Auth::user()->id,
            'noti_aut_code'=> 'MASCOTAS',
            'noti_titulo' =>  'Creacion de una mascota.',
            'noti_body' => 'Bienvenido '.$this->masc_name.'',
            'noti_to' => 'L',
            'noti_to_user' => $this->masc_autoriza,
            'noti_event' => 'Mascota' ,
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

    Notificacion::create([
        'noti_user_id'=> \Auth::user()->id,
        'noti_aut_code'=> 'MASCOTAS',
        'noti_titulo' =>  'Actualizacion de datos.',
        'noti_body' => 'Los datos de '. $this->masc_name.' se han actualizado',
        'noti_to' => 'L',
        'noti_to_user' => $this->masc_autoriza,
        'noti_event' => 'Mascota' ,
        'noti_priority' =>'high',
        'noti_envio'=> 0

    ]);

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
