<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;


    protected $fillable =[
        'noti_user_id'
        ,'noti_aut_code'
        ,'noti_titulo'
       ,'noti_body'
       ,'noti_to'
       ,'noti_to_user'
       ,'noti_event'
       ,'noti_priority'
       ,'noti_envio'
       ,'noti_app'

    ];
}
