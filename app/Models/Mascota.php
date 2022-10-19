<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mascota extends Model
{
    use HasFactory;


    protected $fillable =[
        'masc_id'
        ,'masc_user_id'
        ,'masc_name'
        ,'masc_especie_id'
       ,'masc_raza'
       ,'masc_genero_id'
       ,'masc_peso'
       ,'masc_fecha_nacimiento'
       ,'masc_fecha_vacunacion'
       ,'masc_descripcion'
       ,'masc_url_foto'
       ,'masc_app'
    ];


    public function getmascUrlFotoAttribute($value){
        if($value==''|| $value==null){
            return Storage::url('img/noimage.jpg');
        }
        $explologo = explode('/',$value);
        $imageNamelogo = $explologo[count($explologo)-1];

        if(file_exists('storage/img/mascotas/'.$imageNamelogo)){

            return Storage::url('img/mascotas/'.$imageNamelogo);
        }else{
            return Storage::url('img/noimage.jpg');
        }
    }
}
