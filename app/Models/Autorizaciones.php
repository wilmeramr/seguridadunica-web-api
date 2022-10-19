<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AutorizacionTipo;

class Autorizaciones extends Model
{
    use HasFactory;

    protected $fillable =[
        'aut_user_id',
        'aut_code',
        'aut_tipo',
        'aut_nombre',
        'aut_tipo_documento',
        'aut_documento',
        'aut_telefono',
        'aut_email',
        'aut_desde',
        'aut_hasta',
        'aut_tipo_servicio',
        'aut_lunes',
        'aut_martes',
        'aut_miercoles',
        'aut_jueves',
        'aut_viernes',
        'aut_sabado',
        'aut_domingo',
        'aut_comentario',
        'aut_fecha_evento',
        'aut_fecha_evento_hasta',
        'aut_cantidad_invitado',
        'aut_activo',
        'aut_app'

    ];

    public function tipoAutorizacion()

    {
        return $this->belongsTo(AutorizacionTipo::class, 'aut_tipo', 'id');
    }
}
