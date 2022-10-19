<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;


    protected $fillable =[
        'dev_id',
        'dev_user_id',
        'dev_token'
    ];
}
