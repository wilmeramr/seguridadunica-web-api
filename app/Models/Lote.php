<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $fillable =[
        'lot_id',
        'lot_name',
        'lot_country_id',
        'lot_activo'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class,  'lot_country_id','co_id');
    }
}
