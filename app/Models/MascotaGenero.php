<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MascotaGenero extends Model
{
    use HasFactory;
    protected $fillable =[
        'masc_gene_name'
        ,'masc_gene_activo'

    ];
}
