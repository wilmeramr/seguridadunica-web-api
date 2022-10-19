<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioTipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'stp_id',
        'stp_descripcion'
    ];

}
