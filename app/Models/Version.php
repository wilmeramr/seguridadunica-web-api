<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasFactory;
    protected $fillable =[
        'ver_id',
        'app',
        'ios',


     ];

     protected $primaryKey = 'ver_id';
}
