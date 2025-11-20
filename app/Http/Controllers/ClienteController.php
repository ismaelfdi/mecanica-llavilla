<?php

namespace App\Http\Controllers;

// 1. ASEGÚRATE DE QUE ESTA LÍNEA ESTÁ IMPORTANDO TU FORM REQUEST
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{
    /**
     * Muestra una lista paginada de todos los clientes.
     */
    public function index(Request $request)
    {
        // Si la petición es de AJAX, devolvemos los datos para DataTables
        if ($request->ajax()) {
            $clientes = Cliente::select(['id', 'nombre', 'documento', 'email', 'telefono']);

            return DataTables::of($clientes)
                ->addColumn('action', function($cliente){
                    return '<a href="' . route('clientes.show', $cliente->id) . '" class="text-indigo-600 hover:text-indigo-900">Ver</a>' .
                        '<a href="' . route('clientes.edit', $cliente->id) . '" class="ml-4 text-green-600 hover:text-green-900">Editar</a>';
                })
                ->make(true);
        }

        // Si la petición no es de AJAX, devolvemos la vista
        return view('clientes.index');

        /*
        $query = Cliente::query();

        // Si hay un término de búsqueda, lo aplicamos con el scope
        if ($request->has('search')) {
            $query->search($request->input('search'));
        }

        $clientes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hacemos que la paginación recuerde el término de búsqueda
        $clientes->appends($request->only('search'));

        return view('clientes.index', [
            'clientes' => $clientes,
            'searchTerm' => $request->input('search', '') // Pasamos el término a la vista
        ]);
        */
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Show the form for creating o new resource
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request): RedirectResponse
    {
        // El request ya viene validado gracias a StoreClienteRequest
        $cliente = Cliente::create($request->validated());

        // CORRECCIÓN: Redirigimos a la ficha del cliente recién creado
        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente creado satisfactoriamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente): View
    {
        // Cargamos las relaciones para que esten disponibles en la vista
        // Esto es mas eficiente que llamarlas directamente en Blade
        $cliente->load('vehiculos', 'ordenesTrabajo.vehiculo');

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente): View
    {
        // Muestra el formulario para editar un cliente eistente
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        // La validacion se ejecuto automaticamente a traves de UpdateClienteRequest
        $cliente->update($request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente): RedirectResponse
    {
        // Antes de borrar, podriamos añadir validaciones (ej. no borrar si tiene ordenes abiertas)
        // Por ahora, lo montamos simple
        $cliente->delete(); // Esto ejecutara el Soft Delete gracias al trait en el modelo

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado satisfactoriamente');
    }
}
