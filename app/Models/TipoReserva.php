<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReserva extends Model
{
    use HasFactory;


    protected $fillable =[
        'tresr_id',
        'tresr_country_id',
        'tresr_description',
        'tresr_tipo',
        'tresr_tipo_horarios',
        'tresr_cant_lugares',
        'tresr_email',
        'tresr_url',
        'tresr_activo',

     ];

     protected $primaryKey = 'tresr_id';
}
