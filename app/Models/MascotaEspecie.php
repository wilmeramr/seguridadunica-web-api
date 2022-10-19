<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MascotaEspecie extends Model
{
    use HasFactory;

    protected $fillable =[
        'masc_esp_name'
        ,'masc_esp_activo'

    ];

}
