<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;


    protected $fillable =[
        'notic_user_id'
        ,'notic_country_id'
        ,'notic_titulo'
       ,'notic_body'
       ,'notic_to'
       ,'notic_to_user'
       ,'notic_envio'
       ,'notic_app'

    ];
    protected $primaryKey = 'notic_id';
}
