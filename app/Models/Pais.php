<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;
    public $table = "paises";

    //relation
    public function ciudades()
    {
        return $this->hasMany('App\Models\Ciudad', 'codigo_pais', 'codigo');
    }

}
