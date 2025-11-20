<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'matricula',
        'marca',
        'modelo',
        'version',
        'numero_bastidor',
        'anio',
        'color',
        'kilometraje',
        'tipo_combustible',
        'transmision',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Obtiene el cliente al que pertenece este vehículo.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Obtiene las órdenes de trabajo asociadas a este vehículo.
     */
    public function ordenesTrabajo()
    {
        return $this->hasMany(OrdenTrabajo::class, 'vehiculo_id');
    }

    /**
     * Scope para la búsqueda rápida de vehículos.
     */
    public function scopeSearch($query, $term)
    {
        $term = "%{$term}%";
        return $query->where(function ($s) use ($term) {
            $s->where('matricula', 'like', $term)
                ->orWhere('marca', 'like', $term)
                ->orWhere('modelo', 'like', $term);
        });
    }
}
