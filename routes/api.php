<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\LoteController;
use App\Http\Controllers\api\v1\AutorizacionController;
use App\Http\Controllers\api\v1\DeviceController;
use App\Http\Controllers\api\v1\DocumentoController;
use App\Http\Controllers\api\v1\EmergenciaController;
use App\Http\Controllers\api\v1\ExpensaController;
use App\Http\Controllers\api\v1\HorarioDeporteController;
use App\Http\Controllers\api\v1\InfoController;
use App\Http\Controllers\api\v1\NotificacionController;
use App\Http\Controllers\api\v1\MascotaController;
use App\Http\Controllers\api\v1\NoticiaController;
use App\Http\Controllers\api\v1\PaqueteriaController;
use App\Http\Controllers\api\v1\ReservasController;
use App\Http\Controllers\api\v1\ServicioTiposController;
use App\Http\Controllers\api\v1\SelfieController;
use App\Http\Controllers\api\v1\TipoReservasController;
use App\Http\Controllers\api\v1\VersionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
Route::post('/login', [AuthController::class, 'login']);
Route::post('/token', [AuthController::class, 'token']);
Route::middleware('Sanctum')->get('/doc', [DocumentoController::class, 'index']);
Route::middleware('Sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
});

Route::get('/storage-link',function(){
    if(file_exists(public_path('storage'))){
        return "Ya existe la carpeta";
    }


    app('files')->link(
        '/home/seguridadunica/apps/acceso/storage/app/public','/home/seguridadunica/public_html/acceso/storage'

    );
    return "carpeta creada";

});

Route::group(['middleware' => ['Sanctum']], function () {

    Route::prefix('v1')->group(function () {

       Route::post('/register', [AuthController::class, 'register']);

    Route::post('/autorizacion', [AutorizacionController::class, 'register']);
    Route::get('/autorizacion/{id}', [AutorizacionController::class, 'index']);
    Route::post('/register-lote', [LoteController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::post('/device', [DeviceController::class, 'store']);

    Route::get('/servicio/tipos', [ServicioTiposController::class, 'index']);

    Route::get('/notificacion', [NotificacionController::class, 'index']);
    Route::post('/notificacion', [NotificacionController::class, 'create']);

    Route::get('/mascota', [MascotaController::class, 'index']);
    Route::get('/mascota/especies', [MascotaController::class, 'indexEsperices']);
    Route::get('/mascota/generos', [MascotaController::class, 'indexgeneros']);
    Route::post('/mascota', [MascotaController::class, 'create']);
    Route::post('/noticias', [NoticiaController::class, 'create']);
    Route::get('/noticias', [NoticiaController::class, 'index']);


    Route::post('/mascota/uploadImg', [MascotaController::class, 'uploadImage']);
    Route::post('/doc/uploadPDF', [DocumentoController::class, 'uploadPDF']);
    Route::post('/doc', [DocumentoController::class, 'create']);
   // Route::get('/doc', [DocumentoController::class, 'index']);

    Route::post('/eme', [EmergenciaController::class, 'create']);

    Route::get('/expensas', [ExpensaController::class, 'index']);
    Route::post('/expensas/uploadPDF', [ExpensaController::class, 'uploadPDF']);



    Route::get('/selfieurl', [SelfieController::class, 'getUrlSelfie']);
    Route::get('/treservas', [TipoReservasController::class, 'index']);
    Route::get('/reservas', [ReservasController::class, 'index']);

    Route::post('/reservas', [ReservasController::class, 'create']);
    Route::delete('/reservas', [ReservasController::class, 'destroy']);
    Route::delete('/reservashorarios', [ReservasController::class, 'destroyRHorarios']);

    Route::get('/horarios', [HorarioDeporteController::class, 'index']);

    Route::get('/info', [InfoController::class, 'index']);
    Route::post('/info', [InfoController::class, 'create']);
    Route::delete('/info', [InfoController::class, 'destroy']);

    Route::get('/version', [VersionController::class, 'index']);

    Route::get('/paqueteria', [PaqueteriaController::class, 'index']);



    });





});

