<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ExportController;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\AutorizacionesController;
use App\Http\Livewire\AutorizoController;
use App\Http\Livewire\CountriesController;
use App\Http\Livewire\Dash;
use App\Http\Livewire\DeliveryController;
use App\Http\Livewire\DeviceController;
use App\Http\Livewire\EventoController;
use App\Http\Livewire\InformacionController;
use App\Http\Livewire\IngresosController;
use App\Http\Livewire\LotesController;
use App\Http\Livewire\MascotaEspecieController;
use App\Http\Livewire\MascotasController;
use App\Http\Livewire\NoticiaController;
use App\Http\Livewire\NotificacionesController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\ReportsController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\ServicioController;
use App\Http\Livewire\TipoRecurrenciaController;
use App\Http\Livewire\TipoReservasController;
use App\Http\Livewire\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/guest/{invitacion}', [App\Http\Controllers\GuestController::class, 'index'])->name('guest');
Route::post('/guest/store', [App\Http\Controllers\GuestController::class, 'store'])->name('guest_store');


Route::get('/getUsers', [App\Http\Controllers\UsuarioController::class, 'getUsers'])->name('get.users');



Route::group(['middleware' => ['auth']],function () {

Route::group(['middleware' => ['role:Administrador|Administración|Seguridad']],function () {
Route::get('/home', Dash::class);

    Route::resource('usuarios', UsuarioController::class);
    Route::get('users',UsersController::class);
   Route::get('lotes',LotesController::class);
   Route::get('countries',CountriesController::class);

   // Route::resource('roles', RolController::class);

   //  Route::resource('countrys', CountryController::class);
    // Route::delete('usuarios/{id}/destroy', CountryController::class);

    Route::get('asignar',AsignarController::class);
    Route::get('autorizaciones',AutorizacionesController::class);
    Route::get('autorizo',AutorizoController::class);
    Route::get('recurrencia',ServicioController::class);
    Route::get('entregas',DeliveryController::class);
    Route::get('evento',EventoController::class);

    Route::get('mascotas',MascotasController::class);
    Route::get('notificacions',NotificacionesController::class);
    Route::get('logout',[LoginController::class,'logout']);
    Route::get('ingresos',IngresosController::class);
    Route::get('rptsIngresos',ReportsController::class);
    Route::get('treservas',TipoReservasController::class);
    Route::get('info',InformacionController::class);
    Route::get('noticias',NoticiaController::class);




    Route::get('report/pdf/{user}/{type}/{f1}/{f2}',[ExportController::class,'reportPDF']);
    Route::get('report/pdf/{user}/{type}',[ExportController::class,'reportPDF']);


});

Route::group(['middleware' => ['role:Administrador']],function () {

    Route::get('mascota/especies',MascotaEspecieController::class);
    Route::get('dispositivos',DeviceController::class);
    Route::get('roles',RolesController::class);
    Route::get('permisos',PermisosController::class);
   Route::get('recurrentes',TipoRecurrenciaController::class);

});




});
