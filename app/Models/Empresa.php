<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Campos asignables masivamente
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'ciudad',
        'telefono',
        'casa_matriz',
        'sucursal',         
        'tipo_empresa',     
        'fecha_inicio',
        'fecha_fin',
        'activa',
    ];

    // Relación con cuentas contables
    public function cuentasContables()
    {
        return $this->hasMany(CuentasContables::class, 'empresa_id', 'id');
    }

    // Relación con comprobantes
    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class, 'empresa_id', 'id');
    }
}
