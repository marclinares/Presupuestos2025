<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GastoPresupuesto;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\GastoHistorial;
use Illuminate\Support\Facades\Auth;
use App\Models\TituloPrograma;

class GastoPresupuestoController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = GastoPresupuesto::with('tituloPrograma');

        if ($search) {
            $query->where('CODI_PROG', 'like', "%{$search}%")
                ->orWhere('CODI_ECON', 'like', "%{$search}%")
                ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$search}%");
        }

        $resultados = $query->paginate(20);

        $titulos = TituloPrograma::orderBy('titulo')->get();

        return view('index', compact('resultados', 'search', 'titulos'));
    }


    public function search(Request $request)
    {
        $q    = $request->input('q');
        $sort = $request->input('sort', 'id');
        $dir  = $request->input('dir', 'asc');

        // FIX 1: una sola query con with(), sin sobreescribir
        $query = GastoPresupuesto::with('historial');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('CODI_PROG', 'like', "%{$q}%")
                    ->orWhere('CODI_ECON', 'like', "%{$q}%")
                    ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
            });
        }

        $camposOrdenables = [
            'CODI_PROG',
            'CODI_ECON',
            'APLICACION_PRESUPUESTARIA',
            'CR_INIC_2024',
            'CR_INIC_2025',
            'CR_INIC_2026',
            'VARIACION',
        ];

        if (in_array($sort, $camposOrdenables)) {
            $query->orderBy($sort, $dir);
        }

        return response()->json($query->paginate(15));
    }


    public function store(Request $request)
    {
        $nuevo = GastoPresupuesto::create($request->only([
            'CODI_PROG',
            'CODI_ECON',
            'APLICACION_PRESUPUESTARIA',
            'CR_INIC_2024',
            'CR_INIC_2025',
            'CR_INIC_2026',
            'VARIACION',
            'titulo_programa_id',
        ]));

        return response()->json(['message' => 'Creado', 'id' => $nuevo->id]);
    }


    public function update(Request $request, $id)
    {
        $gasto = GastoPresupuesto::findOrFail($id);

        // Valor anterior redondeado a 2 decimales para comparación fiable
        $anterior = round((float) $gasto->CR_INIC_2026, 2);
        $nuevo    = round((float) $request->CR_INIC_2026, 2);

        $gasto->CODI_PROG                 = $request->CODI_PROG;
        $gasto->CODI_ECON                 = $request->CODI_ECON;
        $gasto->APLICACION_PRESUPUESTARIA = $request->APLICACION_PRESUPUESTARIA;
        $gasto->CR_INIC_2024              = $request->CR_INIC_2024;
        $gasto->CR_INIC_2025              = $request->CR_INIC_2025;
        $gasto->CR_INIC_2026              = $nuevo;
        $gasto->VARIACION                 = $request->VARIACION;
        $gasto->titulo_programa_id       = $request->titulo_programa_id;    

        $gasto->save();

        // FIX 2: comparación segura entre floats redondeados
        if ($anterior !== $nuevo) {
            GastoHistorial::create([
                'gasto_id'         => $gasto->id,
                'importe_anterior' => $anterior,
                'importe_nuevo'    => $nuevo,
                'diferencia'       => round($nuevo - $anterior, 2),
                'usuario'          => Auth::user()->name ?? 'system',
                'fecha_cambio'     => now(),
            ]);
        }

        return response()->json(['message' => 'Actualizado']);
    }


    public function destroy($id)
    {
        $gasto = GastoPresupuesto::findOrFail($id);
        $gasto->delete();

        return response()->json(['message' => 'Eliminado']);
    }


    public function exportarPDF(Request $request)
    {
        $q    = $request->input('q', '');
        $sort = $request->input('sort') ?: 'CODI_PROG';
        $dir  = $request->input('dir')  ?: 'asc';

        $camposOrdenables = [
            'CODI_PROG',
            'CODI_ECON',
            'APLICACION_PRESUPUESTARIA',
            'CR_INIC_2024',
            'CR_INIC_2025',
            'CR_INIC_2026',
            'VARIACION',
        ];

        if (!in_array($sort, $camposOrdenables)) {
            $sort = 'CODI_PROG';
        }

        if (!in_array(strtolower($dir), ['asc', 'desc'])) {
            $dir = 'asc';
        }

        $query = GastoPresupuesto::query();

        if ($q) {
            $query->where(function ($subquery) use ($q) {
                $subquery->where('CODI_PROG', 'like', "%{$q}%")
                         ->orWhere('CODI_ECON', 'like', "%{$q}%")
                         ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
            });
        }

        $gastos = $query->orderBy($sort, $dir)->get();

        $graficoPrograma  = $request->input('grafico_programa', null);
        $graficoEconomico = $request->input('grafico_economico', null);

        $pdf = PDF::loadView('gastos.pdf', compact(
            'gastos',
            'graficoPrograma',
            'graficoEconomico'
        ));

        return $pdf->download('gastos_presupuestarios.pdf');
    }


    public function resumenGlobal(Request $request)
    {
        $q = $request->query('q');

        $query = GastoPresupuesto::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('CODI_PROG', 'like', "%{$q}%")
                    ->orWhere('CODI_ECON', 'like', "%{$q}%")
                    ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
            });
        }

        $porPrograma = (clone $query)
            ->select(
                'CODI_PROG as codigo_programa',
                DB::raw('SUM(CR_INIC_2024) as total_2024'),
                DB::raw('SUM(CR_INIC_2025) as total_2025'),
                DB::raw('SUM(CR_INIC_2026) as total_2026')
            )
            ->groupBy('CODI_PROG')
            ->orderBy('total_2026', 'desc')
            ->get();

        $porEconomico = (clone $query)
            ->select(
                'CODI_ECON as codigo_economico',
                DB::raw('SUM(CR_INIC_2024) as total_2024'),
                DB::raw('SUM(CR_INIC_2025) as total_2025'),
                DB::raw('SUM(CR_INIC_2026) as total_2026')
            )
            ->groupBy('CODI_ECON')
            ->orderBy('total_2026', 'desc')
            ->get();

        return response()->json([
            'porPrograma'  => $porPrograma,
            'porEconomico' => $porEconomico,
        ]);
    }


    public function chartDataEconomico(Request $request)
    {
        $query = GastoPresupuesto::query();

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%")
                    ->orWhere('CODI_ECON', 'like', "%{$q}%")
                    ->orWhere('CODI_PROG', 'like', "%{$q}%");
            });
        }

        $datos = $query
            ->select(
                'CODI_ECON as codigo_economico',
                DB::raw('SUM(CR_INIC_2024) as total_2024'),
                DB::raw('SUM(CR_INIC_2025) as total_2025'),
                DB::raw('SUM(CR_INIC_2026) as total_2026')
            )
            ->groupBy('CODI_ECON')
            ->orderBy('total_2026', 'desc')
            ->get();

        return response()->json($datos);
    }


    public function chartDataPrograma(Request $request)
    {
        $query = GastoPresupuesto::query();

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%")
                    ->orWhere('CODI_ECON', 'like', "%{$q}%")
                    ->orWhere('CODI_PROG', 'like', "%{$q}%");
            });
        }

        $datos = $query
            ->select(
                'CODI_PROG as codigo_programa',
                DB::raw('SUM(CR_INIC_2024) as total_2024'),
                DB::raw('SUM(CR_INIC_2025) as total_2025'),
                DB::raw('SUM(CR_INIC_2026) as total_2026')
            )
            ->groupBy('CODI_PROG')
            ->orderBy('total_2026', 'desc')
            ->get();

        return response()->json($datos);
    }


    public function getHistorial($id)
    {
        $historial = GastoHistorial::where('gasto_id', $id)
                                   ->orderBy('fecha_cambio', 'desc')
                                   ->get();

        return response()->json($historial);
    }
}
