<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;



    protected $fillable =[
       'resr_id',
        'resr_country_id',
        'resr_lote_id',
        'resr_user_id',
        'resr_tipo_id',
        'resr_horario_id',
        'resr_fecha',
        'resr_lugar',
        'resr_cant_personas',
        'resr_activo',
        'resr_comentarios'

    ];

    protected $primaryKey = 'resr_id';
}
