<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalles extends Model
{
    protected $table = 'comprobante_detalles';
    protected $fillable = ['comprobante_id', 'cuenta_contable_id', 'debe', 'haber', 'descripcion', 'iva'];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentasContables::class, 'cuenta_contable_id', 'id_cuenta');
    }
}
