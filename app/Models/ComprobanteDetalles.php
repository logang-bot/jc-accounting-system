<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalles extends Model
{
    protected $table = 'comprobante_detalles';
    protected $fillable = ['comprobante_id', 'cuenta_contable_id', 'debe', 'haber', 'descripcion', 'iva'];

    protected $casts = [
        'debe' => 'float',
        'haber' => 'float',
        'iva' => 'float',
    ];

    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'comprobante_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(CuentasContables::class, 'cuenta_contable_id', 'id_cuenta');
    }

    public function scopeForEmpresa($query, $empresaId)
    {
        return $query->whereHas('comprobante', function ($q) use ($empresaId) {
            $q->where('empresa_id', $empresaId);
        });
    }

    /**
     * Filter by date range on the comprobante.fecha field.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $desde  Y-m-d
     * @param string|null $hasta  Y-m-d
     */
    public function scopeBetweenFechas($query, $desde = null, $hasta = null)
    {
        if ($desde) {
            $query->whereHas('comprobante', function ($q) use ($desde) {
                $q->whereDate('fecha', '>=', $desde);
            });
        }
        if ($hasta) {
            $query->whereHas('comprobante', function ($q) use ($hasta) {
                $q->whereDate('fecha', '<=', $hasta);
            });
        }
        return $query;
    }

    /**
     * Filter detalles for one or more cuentas.
     * Accepts single id or array of ids.
     */
    public function scopeForCuentas($query, $cuentas)
    {
        if (is_array($cuentas)) {
            return $query->whereIn('cuenta_contable_id', $cuentas);
        }
        return $query->where('cuenta_contable_id', $cuentas);
    }
}
