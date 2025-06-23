<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nit',
        'direccion',
        'ciudad',
        'provincia',
        'telefono',
        'celular',
        'correo_electronico',
        'periodo',
        'gestion'
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