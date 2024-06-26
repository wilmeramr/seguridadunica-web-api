<?php

namespace App\Http\Livewire;

use App\Models\Ingreso;
use Livewire\WithPagination;

use App\Models\User;
use App\Models\Lote;
use App\Models\Autorizaciones;

use App\Models\ServicioTipo;
use App\Models\Notificacion;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class IngresosController extends Component
{

    use WithPagination;
    use WithFileUploads;
    public $ingr_autoriza = "Elegir", $search, $selected_id, $pageTitle, $componentName, $ingr_foto, $ingr_foto_base64, $ingr_nombre;
    public  $detalle_ingreso, $ingresoDetail, $ingr_doc, $servicio_id, $ingr_obser, $ingr_patente, $ingr_vto;
    public $ingr_art_vto, $ingr_licencia_numero, $ingr_licencia_vto, $ingr_auto_marca, $ingr_auto_modelo, $ingr_auto_color;
    public $ingr_seguro_nombre, $ingr_seguro_numero, $ingr_seguro_vto;
    public $ingr_autoriza_info;

    public $searchauth, $Users = [];
    public $urlFotoFctual;
    private $pagination = 10;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = "Listado";
        $this->componentName = "Entradas/Salidas";
        $this->ingr_autoriza = "Elegir";
        $this->servicio_id = "Elegir";
        // $this->ingr_vto ="2023-01-01";
    }



    public function render()
    {

        if (\Auth::user()->roles[0]->name != 'Administrador') {
            $us_lote_id = \Auth::user()->us_lote_id;
            $lote = Lote::where('lot_id', '=', $us_lote_id)->select('lot_country_id')->first();
            $lotes = Lote::where('lot_country_id', '=', $lote->lot_country_id)->select('lot_id')->get();
            $users = User::whereIn('us_lote_id', $lotes)
                ->select('id')
                ->orderBy('us_name', 'asc')->get();
        } else {
            $users = User::select('id')
                ->orderBy('us_name', 'asc')->get();
        }

        if (strlen($this->search) > 0) {


            $ingresos =  Ingreso::join('servicio_tipos as ts', 'ts.stp_id', 'ingresos.ingr_tipo')
                ->join('users as usr', 'usr.id', 'ingresos.ingr_user_auth')
                ->join('lotes as lt', 'lt.lot_id', 'usr.us_lote_id')
                ->select(
                    'ingresos.*',
                    'ts.stp_descripcion',
                    'lt.lot_name',
                    'usr.us_name',
                    DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador'),
                    DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END) AS estado')
                )
                ->whereIn('ingresos.ingr_user_c', $users)

                ->where(function ($query) {
                    $query->where('lot_name', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_documento', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_patente', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_patente', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_observacion', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_art_vto', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_licencia_numero', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_auto_marca', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_auto_modelo', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_auto_color', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_seguro_nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('ingr_seguro_numero', 'like', '%' . $this->search . '%')

                        ->orWhere(DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1)'), 'like', '%' . $this->search . '%')
                        ->orWhere(DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END)'), 'like', '%' . $this->search . '%')

                        ->orWhere('us_name', 'like', '%' . $this->search . '%');
                })

                ->orderBy('ingr_id', 'desc')
                ->paginate($this->pagination);
        } else
            $ingresos = Ingreso::join('servicio_tipos as ts', 'ts.stp_id', 'ingresos.ingr_tipo')
                ->join('users as usr', 'usr.id', 'ingresos.ingr_user_auth')
                ->join('lotes as lt', 'lt.lot_id', 'usr.us_lote_id')
                ->select(
                    'ingresos.*',
                    'ts.stp_descripcion',
                    'lt.lot_name',
                    'usr.us_name',
                    DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador')
                )
                ->whereIn('ingresos.ingr_user_c', $users)

                ->orderBy('ingr_id', 'desc')
                ->paginate($this->pagination);


        /*       if (\Auth::user()->roles[0]->name == 'Administrador') {
                                $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                                ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                                ->where('email', '<>', '');

                            }else{
                                $queryUsers = User::join('lotes as lt','lt.lot_id','users.us_lote_id')
                                            ->whereIn('us_lote_id',$lotes)
                                            ->select('users.id','users.us_name','lt.lot_name','lt.lot_country_id')
                                        ->orderBy('us_name','asc');

                            } */

        $this->liveSearch();
        return view('livewire.ingresos.component', [
            'ingresos' => $ingresos,
            // 'users'=>$queryUsers->get(),
            'servicios' => ServicioTipo::orderBy('stp_descripcion', 'asc')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function show($ingr_id)
    {
        $this->ingresoDetail =  Ingreso::where('ingr_id', '=', $ingr_id)
            ->join('servicio_tipos as ts', 'ts.stp_id', 'ingresos.ingr_tipo')
            ->join('users as usr', 'usr.id', 'ingresos.ingr_user_auth')
            ->join('lotes as lt', 'lt.lot_id', 'usr.us_lote_id')
            ->select(
                'ingresos.*',
                'ts.stp_descripcion',
                'lt.lot_name',
                'usr.us_name',
                DB::raw('(select us_name from users where id = ingresos.ingr_user_c  limit 1) as use_creador'),
                DB::raw('(CASE WHEN ingresos.ingr_salida IS NULL THEN "SIN SALIDA" ELSE "COMPLETADO" END) AS estado')
            )
            ->orderBy('ingr_id', 'desc')->first();

        $this->detalle_ingreso = 'Detalle';
        // dd($this->autorizacionDetail->aut_nombre);
        $this->emit('show-modal-detalle', 'open!');
    }
    public function resetUI()
    {
        $this->search = '';

        $this->selected_id = '';
        //  $this->aut_autoriza='Elegir';
        $this->ingresoDetail = '';
        $this->ingr_doc = '';
        $this->ingr_autoriza = "Elegir";
        $this->ingr_foto_base64 = null;
        $this->ingr_foto = null;

        $this->servicio_id = "Elegir";
        $this->ingr_obser = '';
        $this->ingr_vto = '';
        $this->ingr_nombre = '';
        $this->ingr_patente = '';


        $this->ingr_auto_color = '';
        $this->ingr_licencia_numero = 0;
        $this->ingr_licencia_vto = '';
        $this->ingr_auto_marca = '';
        $this->ingr_auto_modelo = '';
        $this->ingr_seguro_nombre = '';
        $this->ingr_seguro_numero = '';
        $this->searchauth = '';
        $this->ingr_autoriza_info = '';

        $this->ingr_vto = '';
        $this->ingr_art_vto = '';
        $this->resetValidation();
        $this->resetPage();
        $this->emit('reset-select2', 's');
        $this->emit('desactivar-camara', 's');
    }
    protected $listeners = [
        'webcam' => 'Webcam',
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI',
        'clear_ingr_foto' => 'Clear_Ingr_Foto',
        'desactivar_ingr_foto' => 'Desactivar_Ingr_Foto',
        'scan-code' => 'barCode',
        'scan-code-byid' => 'ScanCodebyId'
    ];
    public function liveSearch()
    {

        if (strlen($this->searchauth) > 0) {


            $us_lote_id = \Auth::user()->us_lote_id;
            $lote = Lote::where('lot_id', '=', $us_lote_id)->select('lot_country_id')->first();
            $lotes = Lote::where('lot_country_id', '=', $lote->lot_country_id)->select('lot_id')->get();

            $this->Users = User::join('lotes as lt', 'lt.lot_id', 'users.us_lote_id')
                ->select('users.id', 'users.us_name', 'lt.lot_name', 'lt.lot_country_id')
                ->whereIn('us_lote_id', $lotes)
                ->where('email', '<>', '')
                ->where(function ($query) {
                    $query->where('us_name', 'like', "%{$this->searchauth}%")
                        ->orWhere('lot_name', 'like', "%{$this->searchauth}%");
                })
                ->orderBy('us_name', 'asc')
                ->get()->take(10);
        } else {
            return $this->Users = [];
        }
    }
    public function ScanCodebyId(User $user)
    {
        $this->ingr_autoriza_info = $user->us_name;
        $this->ingr_autoriza = $user->id;
    }
    public function Clear_Ingr_Foto($id)
    {
        $this->ingr_foto_base64 = null;
        $this->ingr_foto = null;
        $this->emit('activar-camara', 's');
    }
    public function Desactivar_Ingr_Foto($id)
    {
        $this->ingr_foto_base64 = null;
        $this->ingr_foto = null;
    }

    public function destroy($ingr_id)
    {
        Ingreso::where('ingr_id', '=', $ingr_id)->update([
            'ingr_salida' => new DateTime()
        ]);

        $ingreso =  Ingreso::where('ingr_id', '=', $ingr_id)->first();
        $noti_aut_code = "EGRESO";
        $noti_titulo = "Egreso de visita.";
        $noti_body = "Ha egresado la persona " . $ingreso->ingr_nombre . ", tipo de visita " . ServicioTipo::where('stp_id', '=', $ingreso->ingr_tipo)->select('stp_descripcion')->first()->stp_descripcion . " ,fecha y hora " . $ingreso->ingr_salida;
        $noti_event = "Egreso";



        Notificacion::create([
            'noti_user_id' =>  \Auth::user()->id,
            'noti_aut_code' => $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $ingreso->ingr_user_auth,
            'noti_event' => $noti_event,
            'noti_priority' => 'high',
            'noti_envio' => 0

        ]);

        $this->resetUI();
        $this->emit('ingr-deleted', 'Salida procesada');
    }
    public function Webcam($url)
    {
        $this->ingr_foto_base64 = $url;
    }
    public function Store()
    {

        $rules = [
            'ingr_nombre' => 'required|min:5',
            'ingr_doc' => 'required|min:6',
            'servicio_id' => 'required|not_in:Elegir',
            'ingr_autoriza' => 'required|not_in:Elegir',
        ];

        $messages = [
            'ingr_nombre.required' => 'Ingresa el nombre completo.',
            'ingr_nombre.min' => 'El nombre debe tener al menos  5 caracteres.',
            'ingr_doc.required' => 'Ingresa un documento.',
            'ingr_doc.min' => 'El DNI debe tener al menos  6 caracteres.',
            'servicio_id.required' => 'Selecciona el tipo de visita.',
            'servicio_id.not_in' => 'Selecciona el tipo de visita.',
            'ingr_autoriza.required' => 'Selecciona el usuario que autoriza.',
            'ingr_autoriza.not_in' => 'Selecciona el usuario que autoriza.',


        ];


        $this->validate($rules, $messages);


        $png_url = Str::uuid() . ".png";
        $path = 'img/ingresos/' . $png_url;
        $url = null;
        if ($this->ingr_foto_base64 != null && !Str::contains($this->ingr_foto_base64, 'http') && $this->ingr_foto == null) {

            $image_parts = explode(";base64,", $this->ingr_foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $success =   \Storage::put($path,  $image_base64);
            $url = \Storage::url('img/ingresos/' . $png_url);
        } else

            if (Str::contains($this->ingr_foto_base64, 'http')) {

            $url = $this->ingr_foto_base64;
        } else
            if ($this->ingr_foto != null) {
            $customFileNamelogo = Str::uuid() . "." . $this->ingr_foto->extension();

            $path = 'img/ingresos';
            $this->ingr_foto->storeAs($path, $customFileNamelogo);

            $url = \Storage::url($path . '/' . $customFileNamelogo);
        }




        Ingreso::create([
            'ingr_user_c' => \Auth::user()->id,
            'ingr_user_auth' => $this->ingr_autoriza,
            'ingr_documento' => $this->ingr_doc,
            'ingr_nombre' => $this->ingr_nombre,
            'ingr_tipo' => $this->servicio_id,
            'ingr_patente' => $this->ingr_patente,
            'ingr_foto' => $url,
            'ingr_entrada' => \Carbon\Carbon::now(),
            'ingr_patente_venc' => \Carbon\Carbon::parse($this->ingr_vto)->format('Y-m-d'),
            'ingr_observacion' => $this->ingr_obser,
            'ingr_art_vto' => \Carbon\Carbon::parse($this->ingr_art_vto)->format('Y-m-d'),
            'ingr_licencia_numero' => $this->ingr_licencia_numero,
            'ingr_licencia_vto' =>  \Carbon\Carbon::parse($this->ingr_licencia_vto)->format('Y-m-d'),
            'ingr_auto_marca' => $this->ingr_auto_marca,
            'ingr_auto_modelo' => $this->ingr_auto_modelo,
            'ingr_auto_color' => $this->ingr_auto_color,
            'ingr_seguro_nombre' => $this->ingr_seguro_nombre,
            'ingr_seguro_numero' => $this->ingr_seguro_numero,
            'ingr_seguro_vto' => \Carbon\Carbon::parse($this->ingr_seguro_vto)->format('Y-m-d')
        ]);

        $noti_aut_code = "INGRESO";
        $noti_titulo = "Ingreso de visita.";
        $noti_body = "Ha ingresado la persona " . $this->ingr_nombre . ", tipo de visita " . ServicioTipo::where('stp_id', '=', $this->servicio_id)->select('stp_descripcion')->first()->stp_descripcion . " ,fecha y hora " . \Carbon\Carbon::now();
        $noti_event = "Ingreso";



        Notificacion::create([
            'noti_user_id' =>  \Auth::user()->id,
            'noti_aut_code' => $noti_aut_code,
            'noti_titulo' =>   $noti_titulo,
            'noti_body' => $noti_body,
            'noti_to' => 'L',
            'noti_to_user' => $this->ingr_autoriza,
            'noti_event' => $noti_event,
            'noti_priority' => 'high',
            'noti_envio' => 0

        ]);


        $this->resetUI();
        $this->emit('ingr-added', 'Usuario Registrado');
    }
    public function barCode($barcode, $origen)
    {
        // dd($barcode);
        if (count(explode('"', $barcode)) == 9) {

            $apellidos =  explode('"', $barcode)[1];
            $nombres = explode('"', $barcode)[2];
            $dni = explode('"', $barcode)[4];
            $this->ingr_doc = $dni;
            switch ($origen) {

                case ('search'):
                    $this->search = explode('"', $barcode)[4];
                    break;

                case ('form'):


                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id', '=', $us_lote_id)->select('lot_country_id')->first();
                    $lotes = Lote::where('lot_country_id', '=', $lote->lot_country_id)->select('lot_id')->get();
                    $users = User::whereIn('us_lote_id', $lotes)
                        ->select('id')
                        ->orderBy('us_name', 'asc')->get();

                    $datos = Ingreso::where('ingr_documento', '=', $dni)->whereIn('ingr_user_c', $users)->select("*")->orderby('ingr_id', 'desc')->first();


                    if ($datos != null) {
                        //  dd($datos);

                        $this->ingr_nombre =  $datos->ingr_nombre;
                        $this->ingr_foto_base64 = $datos->ingr_foto;
                        $this->ingr_patente = $datos->ingr_patente;
                        $this->ingr_auto_color = $datos->ingr_auto_color;
                        $this->ingr_licencia_numero = $datos->ingr_licencia_numero;
                        $this->ingr_licencia_vto = $datos->ingr_licencia_vto;
                        $this->ingr_auto_marca = $datos->ingr_auto_marca;
                        $this->ingr_auto_modelo = $datos->ingr_auto_modelo;
                        $this->ingr_auto_color = $datos->ingr_auto_color;
                        $this->ingr_seguro_nombre = $datos->ingr_seguro_nombre;
                        $this->ingr_seguro_numero = $datos->ingr_seguro_numero;



                        $this->ingr_vto = $datos->ingr_patente_venc;
                        $this->ingr_art_vto = $datos->ingr_art_vto;

                        $mensaje = '';
                        if ($datos->ingr_art_vto != null && \Carbon\Carbon::parse($datos->ingr_art_vto) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Art Vencida';
                            //  $this->emit('vencimientos','Art Vencida');
                        }

                        if ($datos->ingr_patente_venc != null && \Carbon\Carbon::parse($datos->ingr_patente_venc) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Patente Vencida';
                            //  $this->emit('vencimientos','Patente Vencida');
                        }
                        if ($datos->ingr_licencia_vto != null && \Carbon\Carbon::parse($datos->ingr_licencia_vto) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Licencia Vencida';

                            //$this->emit('vencimientos','Licencia Vencida');
                        }
                        $mensajeCurrente = '';
                        $visitaconcurrente =  Autorizaciones::where('aut_tipo', "=", 2)
                            ->where('aut_activo', '=', 1)
                            ->where('aut_documento', '=', $dni)
                            ->whereIn('aut_user_id', $users)
                            ->whereDate('aut_hasta', '>=', \Carbon\Carbon::now()->toDateString())
                            ->first();

                        // dd($visitaconcurrente);
                        if ($visitaconcurrente)
                            $mensajeCurrente = 'Es un visitante Recurrentes';

                        if ($mensaje != '' || $mensajeCurrente != '') {
                            if ($mensaje != '')
                                $mensaje = 'Alertas Importantes:' . $mensaje;
                            if ($mensajeCurrente != '')
                                $mensajeCurrente = 'Informacion del Visitante:' . $mensajeCurrente;

                            // dd($mensaje.'|'.$mensajeCurrente);
                            $this->emit('vencimientos', $mensajeCurrente . '|' . $mensaje);
                        }

                        //    $this->emit('visitanterecurrente','pruebas');
                        //   $this->emit('basicFlatpickr',$datos->ingr_patente_venc);

                        //$this->urlFotoFctual = $datos->ingr_foto;
                        // $this-> aut_email =  $datos->aut_email;

                    } else {
                        $this->ingr_nombre = "";
                        $this->ingr_foto_base64 = '';
                        $this->ingr_patente = '';
                        $this->ingr_vto = null;
                        $this->ingr_auto_color = '';
                        $this->ingr_auto_modelo = '';
                        $this->ingr_auto_color = '';
                        $this->ingr_seguro_nombre = '';
                        $this->ingr_seguro_numero = '';
                        $this->ingr_art_vto = '';
                        $this->ingr_licencia_numero = '';
                        $this->ingr_licencia_vto = '';
                        //  $this->urlFotoFctual='';
                        //$this-> aut_email="";

                    }

                    break;
                default:
                    $msg = 'Something went wrong.';
            }
        } else {

            switch ($origen) {
                case ('search'):

                    break;

                case ('form'):
                    $us_lote_id = \Auth::user()->us_lote_id;
                    $lote = Lote::where('lot_id', '=', $us_lote_id)->select('lot_country_id')->first();
                    $lotes = Lote::where('lot_country_id', '=', $lote->lot_country_id)->select('lot_id')->get();
                    $users = User::whereIn('us_lote_id', $lotes)
                        ->select('id')
                        ->orderBy('us_name', 'asc')->get();

                    $datos = Ingreso::where('ingr_documento', '=', $barcode)->whereIn('ingr_user_c', $users)->select("*")->orderby('ingr_id', 'desc')->first();

                    if ($datos != null) {
                        //  dd($datos);

                        $this->ingr_nombre =  $datos->ingr_nombre;
                        $this->ingr_foto_base64 = $datos->ingr_foto;
                        $this->ingr_patente = $datos->ingr_patente;
                        $this->ingr_auto_color = $datos->ingr_auto_color;
                        $this->ingr_licencia_numero = $datos->ingr_licencia_numero;
                        $this->ingr_licencia_vto = $datos->ingr_licencia_vto;
                        $this->ingr_auto_marca = $datos->ingr_auto_marca;
                        $this->ingr_auto_modelo = $datos->ingr_auto_modelo;
                        $this->ingr_auto_color = $datos->ingr_auto_color;
                        $this->ingr_seguro_nombre = $datos->ingr_seguro_nombre;
                        $this->ingr_seguro_numero = $datos->ingr_seguro_numero;



                        $this->ingr_vto = $datos->ingr_patente_venc;
                        $this->ingr_art_vto = $datos->ingr_art_vto;

                        $mensaje = '';
                        if ($datos->ingr_art_vto != null && \Carbon\Carbon::parse($datos->ingr_art_vto) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Art Vencida';
                            //  $this->emit('vencimientos','Art Vencida');
                        }

                        if ($datos->ingr_patente_venc != null && \Carbon\Carbon::parse($datos->ingr_patente_venc) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Patente Vencida';
                            //  $this->emit('vencimientos','Patente Vencida');
                        }
                        if ($datos->ingr_licencia_vto != null && \Carbon\Carbon::parse($datos->ingr_licencia_vto) <= \Carbon\Carbon::now()) {
                            $mensaje = $mensaje . ' - ' . 'Licencia Vencida';

                            //$this->emit('vencimientos','Licencia Vencida');
                        }
                        $mensajeCurrente = '';
                        $visitaconcurrente =  Autorizaciones::where('aut_tipo', "=", 2)
                            ->where('aut_activo', '=', 1)
                            ->where('aut_documento', '=', $barcode)
                            ->whereIn('aut_user_id', $users)
                            ->whereDate('aut_hasta', '>=', \Carbon\Carbon::now()->toDateString())
                            ->first();

                        // dd($visitaconcurrente);
                        if ($visitaconcurrente)
                            $mensajeCurrente = 'Es un visitante Recurrentes';

                        if ($mensaje != '' || $mensajeCurrente != '') {
                            if ($mensaje != '')
                                $mensaje = 'Alertas Importantes:' . $mensaje;
                            if ($mensajeCurrente != '')
                                $mensajeCurrente = 'Informacion del Visitante:' . $mensajeCurrente;

                            // dd($mensaje.'|'.$mensajeCurrente);
                            $this->emit('vencimientos', $mensajeCurrente . '|' . $mensaje);
                        }

                        //    $this->emit('visitanterecurrente','pruebas');
                        //   $this->emit('basicFlatpickr',$datos->ingr_patente_venc);

                        //$this->urlFotoFctual = $datos->ingr_foto;
                        // $this-> aut_email =  $datos->aut_email;

                    } else {
                        $this->ingr_nombre = "";
                        $this->ingr_foto_base64 = '';
                        $this->ingr_patente = '';
                        $this->ingr_vto = null;
                        $this->ingr_auto_color = '';
                        $this->ingr_auto_modelo = '';
                        $this->ingr_auto_color = '';
                        $this->ingr_seguro_nombre = '';
                        $this->ingr_seguro_numero = '';
                        $this->ingr_art_vto = '';
                        $this->ingr_licencia_numero = '';
                        $this->ingr_licencia_vto = '';
                        //  $this->urlFotoFctual='';
                        //$this-> aut_email="";

                    }

                    break;
                default:
                    $msg = 'Something went wrong.';
            }
        }
    }
}
