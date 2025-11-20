<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Muestra el listado de items para DataTables.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $items = Item::query();

            return DataTables::of($items)
                ->addColumn('action', function($item){
                    return '<a href="' . route('items.edit', $item->id) . '" class="text-green-600 hover:text-green-900">Editar</a>' .
                        '<form method="POST" action="' . route('items.destroy', $item->id) . '" class="inline-block ml-2">' .
                        csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm(\'¿Estás seguro?\')">Eliminar</button>' .
                        '</form>';
                })
                ->make(true);
        }

        return view('items.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    {
        Item::create($request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item): View
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $item->update($request->validated());

        return redirect()->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        // Aqui se podrian añadir comprobaciones, como no permitir borrar
        // un item si esta en una orden de trabajo abierta
        $item->delete(); // Esto ejecuta el Soft Delete

        return redirect()->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
