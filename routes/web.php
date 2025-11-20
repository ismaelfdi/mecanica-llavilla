<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; // ¡Añadimos el nuevo controlador!
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\OrdenTrabajoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de bienvenida pública
Route::get('/', function () {
    // Si no esta autenticado lo redirigimos a la vista de login/register.
    if (! auth()->check()) {
        return redirect()->route('login');
    }
    // Si estás autenticado, te llevamos al dashboard.
    // Si ya está logueado, lo llevamos a su dashboard
    return redirect()->route('dashboard');
});


// Grupo de rutas que requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // La ruta principal de nuestra aplicación
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para el perfil de usuario (de Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Aquí irán las rutas de nuestra aplicación del taller
    Route::resource('clientes', ClienteController::class);
    Route::resource('vehiculos', VehiculoController::class);


    // CORRECCIÓN: Le decimos a Laravel cómo nombrar el parámetro
    Route::resource('ordenes-trabajo', OrdenTrabajoController::class)
        ->parameters(['ordenes-trabajo' => 'ordenTrabajo']);

    Route::resource('items', ItemController::class);

    // ¡NUEVA RUTA PARA AÑADIR ÍTEMS A UNA ORDEN!
    Route::post('ordenes-trabajo/{ordenTrabajo}/items', [OrdenTrabajoController::class, 'addItem'])
        ->name('ordenes-trabajo.items.store');

    // ¡NUEVA RUTA PARA ELIMINAR ÍTEMS DE UNA ORDEN!
    Route::delete('ordenes-trabajo/{ordenTrabajo}/items/{pivotId}', [OrdenTrabajoController::class, 'removeItem'])
        ->name('ordenes-trabajo.items.destroy');

    // Ruta para generar una factura en PDF
    Route::get('ordenes-trabajo/{ordenTrabajo}/invoice', [OrdenTrabajoController::class, 'generateInvoice'])
        ->name('ordenes-trabajo.invoice');


});


require __DIR__.'/auth.php';
