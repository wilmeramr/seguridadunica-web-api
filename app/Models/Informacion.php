<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informacion extends Model
{
    use HasFactory;


    protected $fillable =[
        'info_id',
        'info_country_id',
        'info_titulo',
        'info_body'

    ];

    protected $primaryKey = 'info_id';

}
