<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'documento',
        'telefono',
        'email',
        'direccion',
        'ciudad',
        'provincia',
        'pais',
        'codigo_postal',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Obtiene los vehículos que pertenecen a este cliente.
     */
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'cliente_id');
    }

    /**
     * Obtiene las órdenes de trabajo del cliente a través de sus vehículos.
     */
    public function ordenesTrabajo()
    {
        return $this->hasManyThrough(OrdenTrabajo::class, Vehiculo::class);
    }

    /**
     * Scope para la búsqueda rápida.
     */
    public function scopeSearch($query, $term)
    {
        $term = "%{$term}%";
        return $query->where(function ($s) use ($term) {
            $s->where('nombre', 'like', $term)
                ->orWhere('documento', 'like', $term)
                ->orWhere('telefono', 'like', $term)
                ->orWhere('email', 'like', $term);
        });
    }
}
