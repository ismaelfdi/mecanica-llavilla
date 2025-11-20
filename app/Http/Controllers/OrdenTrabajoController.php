<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemToOrdenRequest;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;
use App\Models\Item;
use App\Models\OrdenTrabajo;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si la peticion es de AJAX, devolvemos los datos para DataTables
        if ($request->ajax()) {
            // Eager load de las relaciones anidadas
            $ordenes = OrdenTrabajo::with(['vehiculo.cliente']);

            return DataTables::of($ordenes)
                ->addColumn('vehiculo', function(OrdenTrabajo $orden) {
                    return $orden->vehiculo->marca . ' ' . $orden->vehiculo->modelo . ' (' . Str::upper($orden->vehiculo->matricula) . ')';
                })
                ->addColumn('cliente', function(OrdenTrabajo $orden) {
                    return $orden->vehiculo->cliente->nombre ?? 'Sin cliente';
                })
                ->addColumn('estado_html', function(OrdenTrabajo $orden) {
                    $colorClass = match($orden->estado) {
                        'abierta' => 'bg-blue-200 text-blue-800',
                        'en_proceso' => 'bg-yellow-200 text-yellow-800',
                        'completada' => 'bg-green-200 text-green-800',
                        'cancelada' => 'bg-red-200 text-red-800',
                        default => 'bg-gray-200 text-gray-800',
                    };
                    return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $colorClass . '">' . ucfirst($orden->estado) . '</span>';
                })
                ->addColumn('action', function(OrdenTrabajo $orden){
                    return '<a href="' . route('ordenes-trabajo.show', $orden->id) . '" class="text-indigo-600 hover:text-indigo-900">Ver</a>';
                })
                // Filtro para buscar en la relación del vehículo
                ->filterColumn('vehiculo', function($query, $keyword) {
                    $query->whereHas('vehiculo', function($q) use ($keyword) {
                        $q->where('matricula', 'like', "%{$keyword}%")
                            ->orWhere('marca', 'like', "%{$keyword}%")
                            ->orWhere('modelo', 'like', "%{$keyword}%");
                    });
                })
                // Filtro para buscar en la relación del cliente
                ->filterColumn('cliente', function($query, $keyword) {
                    $query->whereHas('vehiculo.cliente', function($q) use ($keyword) {
                        $q->where('nombre', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['estado_html', 'action']) // Permite HTML en estas columnas
                ->make(true);
        }

        return view('ordenes-trabajo.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Obtenemos los vehiculos con sus clientes para el selector
        $vehiculos = Vehiculo::with('cliente')->get();
        return view('ordenes-trabajo.create', compact('vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdenTrabajoRequest $request): RedirectResponse
    {
        // Preparamos los datos que no vienen del formulario
        $datosAdicionales = [
            'estado' => OrdenTrabajo::ESTADO_ABIERTA, // Estado inicial
            'abierta_en' => Carbon::now(), // Fecha de apertura de la orden
        ];

        // Unimos los datos validados con los adicionales y creamos la orden
        OrdenTrabajo::create(array_merge($request->validated(), $datosAdicionales));

        return redirect()->route('ordenes-trabajo.index')
            ->with('success', 'Orden de trabajo creado satisfactoriamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenTrabajo $ordenTrabajo): View
    {

        // --- LÍNEA DE DEPURACIÓN AVANZADA ---
        // 1. Intentamos buscar el vehículo manualmente usando el ID guardado en la orden.
        $vehiculoEncontradoManualmente = Vehiculo::find($ordenTrabajo->vehiculo_id);

        // 2. Mostramos la orden de trabajo original Y el resultado de nuestra búsqueda manual.
        /*
        dd(
            $ordenTrabajo,
            $vehiculoEncontradoManualmente
        );
        */
        // --- LÍNEA DE DEPURACIÓN ---
        // Esto detendrá el código aquí y nos mostrará el contenido de la orden.
        // dd($ordenTrabajo);

        // Cargamos todas las relaciones necesarias para la vista
        // El código de abajo no se ejecutará mientras dd() esté activo.
        $ordenTrabajo->load('vehiculo.cliente', 'items');

        // Pasamos también la lista de ítems del catálogo para el formulario
        $items_catalogo = Item::orderBy('descripcion')->get();

        return view('ordenes-trabajo.show', compact('ordenTrabajo', 'items_catalogo'));
    }

    /**
     * Añade un nuevo ítem a una orden de trabajo existente.
     */
    public function addItem(AddItemToOrdenRequest $request, OrdenTrabajo $ordenTrabajo): RedirectResponse
    {
        $validated = $request->validated();

        // Calculamos el total de la línea
        $linea_total = $validated['cantidad'] * $validated['precio_aplicado'];

        // Usamos attach() para añadir el ítem a la tabla pivote
        $ordenTrabajo->items()->attach($validated['item_id'], [
            'cantidad' => $validated['cantidad'],
            'precio_aplicado' => $validated['precio_aplicado'],
            'linea_total' => $linea_total,
        ]);

        // Recalculamos los totales generales de la orden
        $ordenTrabajo->recalcularTotales();

        return redirect()->route('ordenes-trabajo.show', $ordenTrabajo)
            ->with('success', 'Ítem añadido correctamente.');
    }

    /**
     * Elimina un ítem de una orden de trabajo.
     */
    // CORRECCIÓN: Recibimos el $pivotId en lugar del objeto Item
    public function removeItem(OrdenTrabajo $ordenTrabajo, int $pivotId): RedirectResponse
    {
        // Usamos wherePivot para buscar por el ID de la tabla intermedia y detach solo ese registro
        $ordenTrabajo->items()->wherePivot('id', $pivotId)->detach();

        // Recalculamos los totales
        $ordenTrabajo->recalcularTotales();

        return redirect()->route('ordenes-trabajo.show', $ordenTrabajo)
            ->with('success', 'Ítem eliminado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdenTrabajo $ordenTrabajo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdenTrabajoRequest $request, OrdenTrabajo $ordenTrabajo): RedirectResponse
    {
        $validatedData = $request->validated();

        // Lógica para actualizar la fecha de cierre
        if (in_array($validatedData['estado'], [OrdenTrabajo::ESTADO_COMPLETADA, OrdenTrabajo::ESTADO_CANCELADA])) {
            // Si la orden ya no estaba cerrada, establecemos la fecha actual
            if (is_null($ordenTrabajo->cerrada_en)) {
                $validatedData['cerrada_en'] = Carbon::now();
            }
        } else {
            // Si se cambia a un estado abierto, eliminamos la fecha de cierre
            $validatedData['cerrada_en'] = null;
        }

        // Actualizamos la orden con los datos validados y la fecha de cierre
        $ordenTrabajo->update($validatedData);

        return redirect()->route('ordenes-trabajo.show', $ordenTrabajo)
            ->with('success', 'Orden de trabajo actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenTrabajo $ordenTrabajo)
    {
        //
    }


    public function generateInvoice(OrdenTrabajo $ordenTrabajo)
    {
        // Cargamos las relaciones necesarias para la factura
        $ordenTrabajo->load('vehiculo.cliente', 'items');

        // Cargamos la vista de Blade que diseñaste para la factura
        $pdf = Pdf::loadView('ordenes-trabajo.invoice', compact('ordenTrabajo'));

        // Descargamos el PDF con un nombre de archivo
        return $pdf->download('factura-orden-' . $ordenTrabajo->id . '.pdf');
    }
}
