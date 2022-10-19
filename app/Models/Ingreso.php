<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'ingr_salida_envio'

    ];

    protected $primaryKey = 'ingr_id';
}
