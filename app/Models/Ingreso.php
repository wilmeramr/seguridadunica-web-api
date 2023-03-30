<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Ingreso extends Model
{
    use HasFactory;


    protected $fillable =[
        'ingr_id',
        'ingr_user_c',
        'ingr_user_c',
        'ingr_user_auth',
        'ingr_documento',
        'ingr_nombre',
        'ingr_tipo',
        'ingr_patente',
        'ingr_patente_venc',
        'ingr_entrada',
        'ingr_salida',
        'ingr_observacion',
        'ingr_foto',
        'ingr_entrada_envio',
        'ingr_salida_envio',
        'ingr_art_vto',
        'ingr_licencia_numero',
        'ingr_licencia_vto',
        'ingr_auto_marca',
        'ingr_auto_modelo',
        'ingr_auto_color',
        'ingr_seguro_nombre',
        'ingr_seguro_numero',
        'ingr_seguro_vto'

    ];

    protected $primaryKey = 'ingr_id';

    public function getingrFotoAttribute($value){
        if($value=='' ||$value==null ){
            return Storage::url('img/noimage.jpg');
        }
        $explologo = explode('/',$value);
        $imageNamelogo = $explologo[count($explologo)-1];

        if(file_exists('storage/img/ingresos/'.$imageNamelogo)){

            return $value;
        }else{
            return Storage::url('img/noimage.jpg');
        }
    }
}
