<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'ciudad',
        'telefono',
        'casa_matriz',
        'periodo',
        'fecha_inicio',
        'fecha_fin',
        'activa',
    ];

    public function cuentasContables()
    {
        return $this->hasMany(CuentasContables::class);
    }

    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class);
    }
}
