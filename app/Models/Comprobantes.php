<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobantes extends Model
{
    protected $table = 'comprobantes';
    protected $primaryKey = 'id_comprobante';

    protected $fillable = [
        'numero_comprobante',
        'fecha',
        'tipo_comprobante',
        'glosa_general',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleComprobantes::class, 'comprobante_id', 'id_comprobante');
    }
}
