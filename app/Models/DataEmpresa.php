<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoEmpresa extends Model
{
    use HasFactory;

    protected $table = 'datosempresas';

    protected $fillable = [
        'empresa_id', 'nit', 'direccion', 'ciudad', 'provincia',
        'telefono', 'celular', 'correo_electronico', 'periodo', 'gestion'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
