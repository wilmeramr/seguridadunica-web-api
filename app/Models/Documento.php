<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;


    protected $fillable =[
        'doc_tipo'
        ,'doc_country_id'
        ,'doc_url'
        ,'doc_app'
        ,'doc_name'
    ];
}
