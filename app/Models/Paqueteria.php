<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Paqueteria extends Model
{
    use HasFactory;


    protected $fillable =[
        'paq_id',
        'paq_user_c',
        'paq_user_auth',
        'paq_lote_id',
        'pad_empr_envio',
        'paq_foto',
        'pad_observacion',
        'created_at'
    ];

    protected $primaryKey = 'paq_id';

    public function getpaqFotoAttribute($value){
        if($value=='' ||$value==null ){
            return Storage::url('img/noimage.jpg');
        }
        $explologo = explode('/',$value);
        $imageNamelogo = $explologo[count($explologo)-1];

        if(file_exists('storage/img/paqueteria/'.$imageNamelogo)){

            return $value;
        }else{
            return Storage::url('img/noimage.jpg');
        }
    }
}
