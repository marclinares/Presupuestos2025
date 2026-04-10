<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoHistorial extends Model
{
    use HasFactory;

    protected $table = 'gastos_historial';

    protected $fillable = [
        'gasto_id',
        'importe_anterior',
        'importe_nuevo',
        'diferencia',
        'usuario',
        'fecha_cambio'
    ];

    //  Relación con el gasto principal
    public function gasto()
    {
        return $this->belongsTo(GastoPresupuesto::class, 'gasto_id');
    }
}