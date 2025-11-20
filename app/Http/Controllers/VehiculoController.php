<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\Cliente;
use App\Models\Vehiculo;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreVehiculoRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si la peticion es de AJAX, devolvemos los datos para DataTables
        if ($request->ajax()) {
            $vehiculos = Vehiculo::with('cliente'); // Eager load de la relacion

            return DataTables::of($vehiculos)
                ->addColumn('cliente', function(Vehiculo $vehiculo) {
                    return $vehiculo->cliente->nombre ?? 'Sin cliente';
                })
                ->addColumn('action', function($vehiculo){
                    return '<a href="' . route('vehiculos.show', $vehiculo->id) . '" class="text-indigo-600 hover:text-indigo-900">Ver</a>' .
                           '<a href="' . route('vehiculos.edit', $vehiculo->id) . '" class="ml-4 text-green-600 hover:text-green-900">Editar</a>';
                })
                // ¡AÑADE ESTE FILTRO!
                ->filterColumn('cliente', function($query, $keyword) {
                    $query->whereHas('cliente', function($q) use ($keyword) {
                        $q->where('nombre', 'like', "%{$keyword}%");
                    });
                })
                ->make(true);
        }

        // Si la peticion no es de AJAX, devolvemos la vista
        return view('vehiculos.index');

        /*
        $searchTerm = $request->input('search', '');

        // Empezamos la consulta cargando la relación con el cliente
        $query = Vehiculo::with('cliente');

        // Si hay un término de búsqueda, aplicamos el scope
        if ($searchTerm) {
            $query->search($searchTerm);
        }

        $vehiculos = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hacemos que la paginación recuerde el término de búsqueda
        $vehiculos->appends(['search' => $searchTerm]);

        return view('vehiculos.index', compact('vehiculos', 'searchTerm'));
        */
    }


    /**
     * Muestra el formulario para crear un nuevo vehículo.
     * Puede recibir un 'cliente_id' para preseleccionar al propietario.
     */
    public function create(Request $request): View
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $selectedClienteId = $request->query('cliente_id'); // Obtiene el ID del cliente de la URL

        return view('vehiculos.create', compact('clientes', 'selectedClienteId'));
    }

    /**
     * Guarda un nuevo vehiculo en la base de datos
     */
    public function store(StoreVehiculoRequest $request): RedirectResponse
    {
        $vehiculo = Vehiculo::create($request->validated());

        // CORRECCIÓN: Redirigimos a la ficha del cliente del nuevo vehículo
        return redirect()->route('clientes.show', $vehiculo->cliente_id)
            ->with('success', 'Vehículo creado satisfactoriamente.');
    }

    /**
     * Muestra la ficha detallada de un vehiculo especifico
     */
    public function show(Vehiculo $vehiculo): View
    {
        // Cargamos las relaciones para tener la informacion del propietario y su historial
        $vehiculo->load('cliente', 'ordenesTrabajo');

        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Muestra el formulario para editar un vehiculo existente
     */
    public function edit(Vehiculo $vehiculo): View
    {
        // Pasamos el vehiculo a editar y la lista de clientes para el selector
        $clientes = Cliente::orderBy('nombre')->get();
        return view('vehiculos.edit', compact('vehiculo', 'clientes'));
    }

    /**
     * Actualiza un vehiculo existente en la base de datos
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo): RedirectResponse
    {
        // La validacion se ejecuta automaticamente
        $vehiculo->update($request->validated());

        return redirect()->route('vehiculos.show', $vehiculo)
            ->with('success', 'Vehiculo actualizado correctamente.');
    }

    /**
     * Elimina un vehiculo de la base de datos (borrado logico)
     */
    public function destroy(Vehiculo $vehiculo): RedirectResponse
    {
        // Aqui se podrian añadir comprobaciones, como no permitir borrar
        // un vehiculo si tiene una orden de trabajo actualmente abierta
        $vehiculo->delete(); // Esto ejecuta el Soft Delete

        return redirect()->route('vehiculos.index')
            ->with('success', 'Vehiculo eliminado correctamente.');
    }
}
