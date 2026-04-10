<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoPresupuesto extends Model
{
    protected $table = 'gastos_presupuesto_2025'; // nombre real de la tabla

    protected $primaryKey = 'id'; // clave primaria

    public $timestamps = false; // desactiva created_at y updated_at

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'CODI_PROG',
        'CODI_ECON',
        'APLICACION_PRESUPUESTARIA',
        'CR_INIC_2024',
        'CR_INIC_2025',
        'CR_INIC_2026',
        'VARIACION',
    ];
}
