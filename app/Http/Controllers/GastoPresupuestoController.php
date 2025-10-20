<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GastoPresupuesto;
use PDF; // Importar la fachada de DOMPDF
use Illuminate\Support\Facades\DB;


class GastoPresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $query = GastoPresupuesto::query();

        if ($search) {
            $query->where('CODI_PROG', 'like', "%{$search}%")
                  ->orWhere('CODI_ECON', 'like', "%{$search}%")
                  ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$search}%");
        }

        $resultados = $query->paginate(20);

        return view('index', compact('resultados', 'search'));
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $sort = $request->input('sort', 'id');
        $dir = $request->input('dir', 'asc');

        $query = GastoPresupuesto::query();

        if ($q) {
            $query->where('CODI_PROG', 'like', "%{$q}%")
                ->orWhere('CODI_ECON', 'like', "%{$q}%")
                ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
        }

        // Solo permitir ordenar por ciertos campos
        $camposOrdenables = ['CODI_PROG', 'CODI_ECON', 'APLICACION_PRESUPUESTARIA', 'CR_INIC_2024', 'CR_INIC_2025', 'VARIACION'];

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
            'VARIACION'
        ]));

        return response()->json(['message' => 'Creado', 'id' => $nuevo->id]);
    }

    public function update(Request $request, $id)
    {
        $gasto = GastoPresupuesto::findOrFail($id);

        $gasto->CODI_PROG = $request->CODI_PROG;
        $gasto->CODI_ECON = $request->CODI_ECON;
        $gasto->APLICACION_PRESUPUESTARIA = $request->APLICACION_PRESUPUESTARIA;
        $gasto->CR_INIC_2024 = $request->CR_INIC_2024;
        $gasto->CR_INIC_2025 = $request->CR_INIC_2025;
        $gasto->VARIACION = $request->VARIACION;

        $gasto->save();

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
        // Obtener parámetros de búsqueda y orden, con valores por defecto
        $q = $request->input('q', '');
        $sort = $request->input('sort') ?: 'CODI_PROG';
        $dir  = $request->input('dir') ?: 'asc';

        // Validar que el campo de orden sea válido
        $camposOrdenables = ['CODI_PROG','CODI_ECON','APLICACION_PRESUPUESTARIA','CR_INIC_2024','CR_INIC_2025','VARIACION'];
        if (!in_array($sort, $camposOrdenables)) {
            $sort = 'CODI_PROG';
        }
        if (!in_array(strtolower($dir), ['asc','desc'])) {
            $dir = 'asc';
        }

        // Construir la consulta
        $query = GastoPresupuesto::query();
        if ($q) {
            $query->where(function($subquery) use ($q) {
                $subquery->where('CODI_PROG', 'like', "%{$q}%")
                        ->orWhere('CODI_ECON', 'like', "%{$q}%")
                        ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
            });
        }

        $gastos = $query->orderBy($sort, $dir)->get();

        // Recibir imágenes base64 de los gráficos (opcional)
        $graficoPrograma  = $request->input('grafico_programa', null);
        $graficoEconomico = $request->input('grafico_economico', null);

        // Cargar la vista PDF con los datos y gráficos
        $pdf = PDF::loadView('gastos.pdf', compact('gastos', 'graficoPrograma', 'graficoEconomico'));

        // Descargar el PDF
        return $pdf->download('gastos_presupuestarios.pdf');
    }





    public function resumenGlobal(Request $request)
    {
        $q = $request->query('q');

        $query = GastoPresupuesto::query();

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('CODI_PROG', 'like', "%{$q}%")
                    ->orWhere('CODI_ECON', 'like', "%{$q}%")
                    ->orWhere('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%");
            });
        }

        $porPrograma = (clone $query)
            ->select('CODI_PROG as codigo_programa', DB::raw('SUM(CR_INIC_2025) as total'))
            ->groupBy('CODI_PROG')
            ->orderBy('total', 'desc')
            ->get();

        $porEconomico = (clone $query)
            ->select('CODI_ECON as codigo_economico', DB::raw('SUM(CR_INIC_2025) as total'))
            ->groupBy('CODI_ECON')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json([
            'porPrograma' => $porPrograma,
            'porEconomico' => $porEconomico
        ]);
    }


    public function chartDataEconomico(Request $request)
    {
        $query = GastoPresupuesto::query();

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%")
                ->orWhere('CODI_ECON', 'like', "%{$q}%")
                ->orWhere('CODI_PROG', 'like', "%{$q}%");
        }

        $datos = $query
            ->select('CODI_ECON as codigo_economico', DB::raw('SUM(CR_INIC_2025) as total'))
            ->groupBy('CODI_ECON')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($datos);
    }


    public function chartDataPrograma(Request $request)
    {
        $query = GastoPresupuesto::query();

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where('APLICACION_PRESUPUESTARIA', 'like', "%{$q}%")
                ->orWhere('CODI_ECON', 'like', "%{$q}%")
                ->orWhere('CODI_PROG', 'like', "%{$q}%");
        }

        $datos = $query
            ->select('CODI_PROG as codigo_programa', DB::raw('SUM(CR_INIC_2025) as total'))
            ->groupBy('CODI_PROG')
            ->get();

        return response()->json($datos);
    }





}
