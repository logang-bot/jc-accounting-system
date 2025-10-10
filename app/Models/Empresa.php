<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nit_ci',
        'casa_matriz',
        'fecha_inicio',
        'fecha_fin',
        'periodo',
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
