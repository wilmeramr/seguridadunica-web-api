<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Lote;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;



class CountriesController extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $co_cuit,$co_name,$search,$co_email,$co_logo,$co_logoapp,$co_como_llegar,$co_reg_url_propietario,$co_url_gps,$co_url_video,$selected_id, $pageTitle, $componentName;
    private $pagination =5;

    public function mount(){
        $this->pageTitle ="Listado";
        $this->componentName ="Countries";

    }

    public function paginationView(){
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        if (\Auth::user()->roles[0]->name != 'Administrador') {
            $us_lote_id = \Auth::user()->us_lote_id;
            $lote = Lote::where('lot_id','=',$us_lote_id)->select('lot_country_id')->first();
            $co_id = Country::where('co_id','=',$lote->lot_country_id)->select('co_id')->first();



                }else{
                    $co_id = Country::select('co_id')->get();

                // dd($lotes);
                };

        if(strlen($this->search)>0)
           $data = Country::whereIn('co_id',$co_id)->where( function($query) {
              $query->where('co_name','like','%'.$this->search. '%')
              ->orWhere('co_cuit','like','%'.$this->search. '%')
              ->orWhere('co_email','like','%'.$this->search. '%');
        })

           ->paginate($this->pagination);
        else
        $data = Country::whereIn('co_id',$co_id)->orderBy('co_id','desc')->paginate($this->pagination);

        return view('livewire.country.countries',['countries'=>$data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit($id){

        $record = Country::where('co_id','=',$id)->first();

        $this->co_name = $record->co_name;
       $this->co_logo ='';
        $this->selected_id = $record->co_id;
        $this->co_email = $record->co_email;
        $this->co_cuit =$record->co_cuit;
        $this->co_como_llegar = $record->co_como_llegar;
        $this->co_reg_url_propietario = $record->co_reg_url_propietario;
        $this->co_url_gps = $record->co_url_gps;
        $this->co_url_video = $record->co_url_video;



        $this->emit('show-modal','show modal!');

    }

    public function Store(){

        $rules =[
            'co_cuit'=>'required|unique:countries|numeric',
            'co_name'=>'required|min:3',
            'co_email'=>'required|email',
            'co_como_llegar'=>'required',
            'co_reg_url_propietario'=>'required',
            'co_logo' => 'required|mimes:jpeg,png,jpg|max:2048|dimensions:max_width=1453,max_height=600',

        ];

        $messages =[
            'co_name.required'=> "Nombre del country es requerido",
            'co_name.min'=> 'El nombre del country debe tener al menos 3 caracteres',

            'co_cuit.required'=> "Cuit del country es requerido",
            'co_cuit.unique'=> "Esta cuit ya está registrado",
            'co_cuit.numeric'=> 'El cuit debe contener solo dígitos [0-9].',

            'co_email.required' =>'El email es requerido',

            'co_email.email' =>'Debe ser un email válido',
            'co_como_llegar.required'=>'Como llegar es requerido',
            'co_como_llegar.regex'=>'Como llegar debe ser una URL contener (http) ',
            'co_reg_url_propietario.required'=>'La URL  de registro propietario es requerido',
            'co_reg_url_propietario.regex'=>'La URL de registro propietario debe ser una URL que contenga (http) ',
            'co_logo.required' => 'El logo es requerido',
            'co_logo.mimes' => 'El logo debe ser de formatos [jpeg, png, jpg]',
            'co_logo.dimensions'=> 'El logo debe ser de máximo de 1453x394 y pesar menos 2 MB'
        ];

        $this->validate($rules,$messages);



        $customFileName;
        if($this->co_logo){
            $customFileNamelogo = Str::uuid().".".$this->co_logo->extension();

            $path = 'img/countries';
            $this->co_logo->storeAs($path,$customFileNamelogo);


            $country = Country::create([
                'co_name'=>$this->co_name,
                'co_cuit'=>$this->co_cuit,
                'co_email'=>$this->co_email,
                'co_logo' => Storage::url($path.'/'.$customFileNamelogo),
                'co_como_llegar' => $this->co_como_llegar,
                'co_reg_url_propietario' => $this->co_reg_url_propietario,
                'co_url_gps' => $this->co_url_gps,
                'co_url_video' => $this->co_url_video,
                'co_active'=>1,
            ]);
         /*    $country->co_logo = Storage::url($path.$png_url);
            $country->co_logoapp = Storage::url($path.$png_url);
            $country->save( );*/
        }

        $this->resetUI();
        $this->emit('country-added','Country registrado');

    }

    public function Update(){

        $rules =[
            'co_cuit'=>"required|unique:countries,co_cuit,{$this->selected_id},co_id|numeric",
            'co_name'=>'required|min:3',
            'co_email'=>'required|email',
            'co_como_llegar'=>'required',
            'co_reg_url_propietario'=>'required',

         //   'co_logo' => 'required|mimes:jpeg,png,jpg',
          //  'co_logoapp' => 'required|mimes:jpeg,png,jpg',
        ];

        $messages =[
            'co_name.required'=> "Nombre del country es requerido",
            'co_name.min'=> 'El nombre del country debe tener al menos 3 caracteres',

            'co_cuit.required'=> "Cuit del country es requerido",
            'co_cuit.unique'=> "Esta cuit ya esta registrado",
            'co_cuit.numeric'=> 'El cuit debe contener solo digitos[0-9].',

            'co_email.required' =>'El email es requerido',

            'co_email.email' =>'Debe ser un email valido',
            'co_como_llegar.required'=>'Como llegar es requerido',
            'co_como_llegar.regex'=>'Como llegar debe ser una Url contener (http) ',
            'co_reg_url_propietario.required'=>'La URL  de registro propietario es requerido',
            'co_reg_url_propietario.regex'=>'La URL de registro propietario debe ser una URL que contenga (http) ',


           /*  'co_logo.required' => 'El logo es requerido',
            'co_logo.mimes' => 'El logo debe ser de formatos [jpeg,png,jpg]',

            'co_logoapp.required' => 'El logo app es requerido',
            'co_logoapp.mimes' => 'El logo app debe ser de formatos [jpeg,png,jpg]', */

        ];

        $this->validate($rules,$messages);

        $country = Country::where('co_id','=',$this->selected_id)->first();
        $country::where('co_id','=',$country->co_id)->update([
            'co_name'=>$this->co_name,
            'co_cuit'=>$this->co_cuit,
            'co_email'=>$this->co_email,
            'co_como_llegar' => $this->co_como_llegar,
            'co_reg_url_propietario' => $this->co_reg_url_propietario,
            'co_url_gps' => $this->co_url_gps,
            'co_url_video' => $this->co_url_video,
        ]);

        try {
            if($this->co_logo ){
                $customFileNamelogo = Str::uuid().".".$this->co_logo->getClientOriginalExtension();
                $path = 'img/countries';
                $this->co_logo->storeAs($path,$customFileNamelogo);


                $explo = explode('/',$country->co_logo);
                $imageName = $explo[count($explo)-1];

                $country::where('co_id','=',$country->co_id)->update([

                    'co_logo' => Storage::url($path.'/'.$customFileNamelogo),
                ]);


                if($imageName !=null){


                    if(file_exists('storage/img/countries/'.$imageName)){
                       // dd($imageName);
                        unlink('storage/img/countries/'.$imageName);
                    }
                }

        };

        } catch (\Throwable $th) {
            \Log::error($th);
        }





    $this->resetUI();
    $this->emit('country-updated','Country Actualizada');

}

    public function resetUI(){
        $this->co_name ="";
        $this->co_logo ="";
        $this->search ="";
        $this->co_cuit ="";
        $this->co_email ="";
        $this->co_logoapp ="";
        $this->co_como_llegar ="";
        $this->selected_id ="";
       $this->co_reg_url_propietario="";
       $this->co_url_gps ="";
         $this->co_url_video ="";


    }

        protected $listeners =['deleteRow'=>'Destroy'];
    public function Destroy($id){
        $country = Country::where('co_id','=',$id)->first();

        $country::where('co_id','=',$country->co_id)->update([

            'co_active'=>$country->co_active==1?0:1
        ]);

        $this->resetUI();
        $this->emit('country-deleted');
    }
}
