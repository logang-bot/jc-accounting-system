<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalles extends Model
{
    protected $table = 'comprobante_detalles';
    protected $fillable = [
        'comprobante_id',
        'cuenta_contable_id',
        'debe_bs',
        'haber_bs',
        'debe_usd',
        'haber_usd',
        'descripcion',
    ];

    protected $casts = [
        'debe_bs' => 'decimal:2',
        'haber_bs' => 'decimal:2',
        'debe_usd' => 'decimal:2',
        'haber_usd' => 'decimal:2',
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
        return $query->whereHas('comprobante', fn($q) => $q->where('empresa_id', $empresaId));
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
            $query->whereHas('comprobante', fn($q) => $q->whereDate('fecha', '>=', $desde));
        }
        if ($hasta) {
            $query->whereHas('comprobante', fn($q) => $q->whereDate('fecha', '<=', $hasta));
        }
        return $query;
    }

    /**
     * Filter detalles for one or more cuentas.
     * Accepts single id or array of ids.
     */
    public function scopeForCuentas($query, $cuentas)
    {
        return is_array($cuentas)
            ? $query->whereIn('cuenta_contable_id', $cuentas)
            : $query->where('cuenta_contable_id', $cuentas);
    }
}
