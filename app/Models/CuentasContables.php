<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentasContables extends Model
{
    use HasFactory;

    protected $table = 'cuentas';
    protected $primaryKey = 'id_cuenta';
    public $timestamps = true;

    protected $fillable = [
        'codigo_cuenta',
        'nombre_cuenta',
        'tipo_cuenta',
        'nivel',
        'parent_id',
        'es_movimiento',
    ];

    protected $attributes = [
        'nivel' => 1,
    ];

    protected $casts = [
        'nivel' => 'integer',
        'es_movimiento' => 'boolean',
    ];

    // Relación jerárquica
    public function children()
    {
        return $this->hasMany(CuentasContables::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(CuentasContables::class, 'parent_id');
    }

    // Boot para generación automática
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cuenta) {
            if (!$cuenta->codigo_cuenta) {
                $cuenta->codigo_cuenta = self::generarCodigoCuenta($cuenta);
            }

            $cuenta->nivel = $cuenta->nivel ?? self::calcularNivel($cuenta->codigo_cuenta) ?? 1;

            // Respetar el valor enviado en el formulario
            $cuenta->es_movimiento = filter_var($cuenta->es_movimiento, FILTER_VALIDATE_BOOLEAN);
        });
    }

    // Generar código jerárquico
    private static function generarCodigoCuenta($cuenta)
    {
        $prefijos = [
            'Activo' => '1',
            'Pasivo' => '2',
            'Patrimonio' => '3',
            'Ingresos' => '4',
            'Egresos' => '5',
        ];

        if (!$cuenta->parent_id) {
            return str_pad($prefijos[$cuenta->tipo_cuenta], 10, '0', STR_PAD_RIGHT);
        }

        $ultimaCuenta = self::where('parent_id', $cuenta->parent_id)
            ->orderBy('codigo_cuenta', 'desc')
            ->first();

        if ($ultimaCuenta) {
            return (string)((int)$ultimaCuenta->codigo_cuenta + 1);
        }

        return $cuenta->parent->codigo_cuenta . '01';
    }

    // Calcular nivel a partir del código
    public static function calcularNivel($codigo)
    {
        if (preg_match('/^[1-5]000000000$/', $codigo)) {
            return 1;
        } elseif (preg_match('/^[1-5][1-9]00000000$/', $codigo)) {
            return 2;
        } elseif (preg_match('/^[1-5][1-9][0-9]{2}000000$/', $codigo)) {
            return 3;
        } elseif (preg_match('/^[1-5][1-9][0-9]{4}0000$/', $codigo)) {
            return 4;
        } elseif (preg_match('/^[1-5][1-9][0-9]{6}$/', $codigo)) {
            return 5;
        }
        return 1;
    }
}
