<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tipo',
        'codigo',
        'descripcion',
        'precio_unitario',
        'tasa_impuesto',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'tasa_impuesto' => 'decimal:2',
    ];

    /**
     * Obtiene las órdenes de trabajo que utilizan este ítem.
     */
    public function ordenesTrabajo(): BelongsToMany
    {
        return $this->belongsToMany(OrdenTrabajo::class, 'orden_trabajo_item', 'item_id', 'orden_trabajo_id')
            ->withPivot('cantidad', 'precio_aplicado', 'descuento', 'linea_total')
            ->withTimestamps();
    }
}
