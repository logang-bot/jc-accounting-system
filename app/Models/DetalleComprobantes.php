<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleComprobantes extends Model
{
    protected $table = 'detalle_comprobantes';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_comprobante',
        'id_cuenta',
        'debe',
        'haber',
        'glosa',
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobantes::class, 'comprobante_id', 'id_comprobante');
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentasContables::class, 'id_cuenta');
    }
}
