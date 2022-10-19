<?php

namespace App\Models;

use App\Events\UserEmergenciaEmit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencia extends Model
{
    use HasFactory;

    protected $fillable =[
        'eme_country_id',
        'eme_lote_id',
        'eme_user_id',
        'eme_lote_name',
        'eme_user_name',
        'eme_comentario',

    ];

    protected $primaryKey = 'eme_id';

    protected $dispatchesEvents =[

        'created' => UserEmergenciaEmit::class
    ];


}
