<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMail extends Model
{
    use HasFactory;


    protected $fillable =[
        'ml_id',
        'ml_user_id',
        'ml_tipo',
        'ml_user_email',
        'ml_user_dni',
        'ml_envio'
    ];
}
