<?php

namespace App\Models;

use App\Models\Pais;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ciudad extends Model
{
    use HasFactory;
    public $table = "ciudades";

    //relation
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'codigo', 'codigo_pais');
    }
}
