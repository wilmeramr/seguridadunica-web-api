<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Country extends Model
{
    use HasFactory;

    protected $fillable = ['co_cuit','co_name','co_email','co_logo','co_logoapp','co_active','co_como_llegar','co_reg_url_propietario','co_url_gps','co_url_video'];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'lot_country_id','co_id');
    }


    public function users()
    {
        $lot = Lote::where('lot_country_id','=',$this->co_id)->get();
        $usrs = User::whereIn('us_lote_id',$lot)->get();

        return  $usrs->count();
    }

    public function getcoLogoAttribute($value){
        if($value==''){
            return Storage::url('img/noimage.jpg');
        }
        $explologo = explode('/',$value);
        $imageNamelogo = $explologo[count($explologo)-1];

        if(file_exists('storage/img/countries/'.$imageNamelogo)){

            return $value;
        }else{
            return Storage::url('img/noimage.jpg');
        }
    }

    public function getcoLogoappAttribute($value){
        if($value==''){
            return Storage::url('img/noimage.jpg');
        }
        $explologoapp = explode('/',$value);
        $imageNamelogoapp = $explologoapp[count($explologoapp)-1];

        if(file_exists('storage/img/countries/'.$imageNamelogoapp)){

            return $value;
        }else{
            return Storage::url('img/noimage.jpg');
        }
    }

}
