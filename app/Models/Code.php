<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Code extends Model
{
    use HasFactory;

    protected $fillable =[
        'referral_code'
    ];

    public static function getUniqueReferralCode(){

        do{
            $code = Str::random(11);

        }while(Code::where('referral_code',$code)->exists());

   
        return $code;
    }
}
