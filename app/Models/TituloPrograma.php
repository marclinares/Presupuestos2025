<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TituloPrograma extends Model
{
    protected $table = 'titulos_programa';

    protected $fillable = [
        'titulo'
    ];

    public function gastos()
    {
        return $this->hasMany(GastoPresupuesto::class, 'titulo_programa_id');
    }
}
