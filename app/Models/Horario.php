<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;



    protected $fillable =[
        'hor_id',
        'hor_range',
        'hor_tipo'

    ];

    protected $primaryKey = 'hor_id';
}
