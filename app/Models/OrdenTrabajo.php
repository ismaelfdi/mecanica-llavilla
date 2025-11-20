<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrdenTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ordenes_trabajo';

    // Estados
    public const ESTADO_ABIERTA     = 'abierta';
    public const ESTADO_EN_PROCESO  = 'en_proceso';
    public const ESTADO_COMPLETADA  = 'completada';
    public const ESTADO_CANCELADA   = 'cancelada';

    // Estados de pago
    public const PAGO_NO_PAGADA     = 'no_pagada';
    public const PAGO_PARCIAL       = 'parcial';
    public const PAGO_PAGADA        = 'pagada';

    protected $fillable = [
        'vehiculo_id',
        'estado',
        'estado_pago',
        'abierta_en',
        'cerrada_en',
        'total_mano_obra',
        'total_repuestos',
        'total_descuento',
        'total_impuestos',
        'total_general',
        'moneda',
        'tasa_impuesto_default',
        'factura_id',
        'queja_cliente',
        'diagnostico',
        'notas',
    ];

    protected $casts = [
        'abierta_en' => 'datetime',
        'cerrada_en' => 'datetime',
        'total_mano_obra' => 'decimal:2',
        'total_repuestos' => 'decimal:2',
        'total_descuento' => 'decimal:2',
        'total_impuestos' => 'decimal:2',
        'total_general' => 'decimal:2',
        'tasa_impuesto_default' => 'decimal:2',
    ];

    /**
     * Obtiene el vehículo al que pertenece esta orden de trabajo.
     *
     * Laravel por defecto busca una columna llamada 'vehiculo_id'
     * Nos aseguramos de que este explicitamente definida para evitar problemas
     */
    public function vehiculo(): BelongsTo
    {
        // La clave esta en el segundo argumento: 'vehiculo_id'
        // Este DEBE ser el nombre exactvo de la columna en tu tabla 'ordenes_trabajo'
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    /**
     * Obtiene los ítems asociados a esta orden de trabajo.
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'orden_trabajo_item', 'orden_trabajo_id', 'item_id')
            ->withPivot('id', 'cantidad', 'precio_aplicado', 'descuento', 'linea_total')
            ->withTimestamps()
            ->as('pivot'); // Usamos un alias para acceder a los datos de la tabla pivote mas facil
    }

    /**
     * Scope para la busqueda rapida de ordenes de trabajo
     */
    public function scopeSearch($query, $term)
    {
        $term = "%{$term}%";

        return $query->where('id', 'like', $term)
            ->orWhereHas('vehiculo', function ($q) use ($term) {
                $q->where('matricula', 'like', $term)
                    ->orWhereHas('cliente', function ($subQ) use ($term) {
                        $subQ->where('nombre', 'like', $term);
                    });
            });
    }




    /**
     * Recalcula los totales a partir de las líneas de la orden.
     */
    public function recalcularTotales(): void
    {
        // $labor = $this->items()->withPivot('linea_total')->wherePivot('tipo', 'labor')->sum('pivot.linea_total');
        // $parts = $this->items()->withPivot('linea_total')->wherePivot('tipo', 'part')->sum('pivot.linea_total');

        // CORRECCIÓN: Usamos where() para filtrar por la columna 'tipo' en la tabla 'items'
        $labor = $this->items()->where('tipo', 'labor')->sum('orden_trabajo_item.linea_total');
        $parts = $this->items()->where('tipo', 'part')->sum('orden_trabajo_item.linea_total');
        $this->total_mano_obra  = $labor;
        $this->total_repuestos  = $parts;

        // CORRECCIÓN: Usamos el nombre completo de la columna para el descuento
        $this->total_descuento  = $this->items()->sum('orden_trabajo_item.descuento');
        // $this->total_descuento  = $this->items()->withPivot('descuento')->sum('pivot.descuento');

        // Lógica de impuestos (se puede expandir en el futuro)
        $this->total_impuestos  = 0;

        // Cálculo final
        $this->total_general    = $this->total_mano_obra + $this->total_repuestos - $this->total_descuento + $this->total_impuestos;

        $this->save();
    }
}
