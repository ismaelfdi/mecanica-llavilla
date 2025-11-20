<?php

namespace App\Http\Controllers;

use App\Models\OrdenTrabajo;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal con las estadísticas clave.
     */
    public function index(): View
    {
        // Resumen de órdenes de trabajo
        $ordenesAbiertas = OrdenTrabajo::where('estado', OrdenTrabajo::ESTADO_ABIERTA)->count();
        $ordenesEnProceso = OrdenTrabajo::where('estado', OrdenTrabajo::ESTADO_EN_PROCESO)->count();
        $ordenesCompletadas = OrdenTrabajo::where('estado', OrdenTrabajo::ESTADO_COMPLETADA)->count();

        // Ingresos del mes actual
        $ingresosMes = OrdenTrabajo::where('estado_pago', OrdenTrabajo::PAGO_PAGADA)
            ->whereBetween('cerrada_en', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('total_general');

        // Órdenes recientes (con relaciones)
        $ordenesRecientes = OrdenTrabajo::with(['vehiculo.cliente'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Desglose de ingresos entre mano de obra y repuestos
        // La solucion es usar DB::table() en lugar de modelo Eloquent
        $ingresosPorTipo = DB::table('ordenes_trabajo')
            ->select(
                DB::raw('COALESCE(SUM(total_mano_obra), 0) as mano_obra'),
                DB::raw('COALESCE(SUM(total_repuestos), 0) as repuestos')
            )
            ->where('estado_pago', OrdenTrabajo::PAGO_PAGADA)
            ->first();
        /*
        $ingresosPorTipo = OrdenTrabajo::select(
            DB::raw('COALESCE(SUM(total_mano_obra), 0) as mano_obra'),
            DB::raw('COALESCE(SUM(total_repuestos), 0) as repuestos')
        )
            ->where('estado_pago', OrdenTrabajo::PAGO_PAGADA)
            ->first();
        */

        // dd($ingresosPorTipo); // ¡Añade esta línea para ver los datos!

        return view('dashboard', compact(
            'ordenesAbiertas',
            'ordenesEnProceso',
            'ordenesCompletadas',
            'ingresosMes',
            'ordenesRecientes',
            'ingresosPorTipo'
        ));
    }
}
