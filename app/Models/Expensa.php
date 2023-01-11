<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensa extends Model
{
    use HasFactory;

    protected $fillable =[
        'exp_id'
        ,'exp_user_id'
        ,'exp_lote_id'
        ,'exp_name'
        ,'exp_link_pago'

        ,'exp_country_id'
        ,'exp_doc_url'
        ,'exp_activo'
    ];


}
