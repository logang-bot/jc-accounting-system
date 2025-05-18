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
        'estado'
    ];

    protected $attributes = [
        'nivel' => 1,
    ];

    protected $casts = [
        'nivel' => 'integer',
        'es_movimiento' => 'boolean',
        'estado' => 'boolean'
    ];

    
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

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
            $cuenta->es_movimiento = match (true) {
                $cuenta->nivel === 5 => true,
                $cuenta->nivel === 4 => filter_var($cuenta->es_movimiento, FILTER_VALIDATE_BOOLEAN),
                default => false,
            };

            $cuenta->estado = $cuenta->estado ?? true;
        });
    }

    // Generar código jerárquico
    public static function generarCodigoCuenta($cuenta)
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

        // Obtener el código del padre
        $parent = $cuenta->parent;
        $parentCodigo = $parent->codigo_cuenta;

        // Calcular el nivel actual a partir del padre
        $nivelPadre = self::calcularNivel($parentCodigo);

        // Determinar cuántos dígitos activos tiene el código del padre
        $longitudActivo = 1 + ($nivelPadre - 1) * 2;

        // Obtener el prefijo del padre (por ejemplo '1101')
        $prefijo = substr($parentCodigo, 0, $longitudActivo);

        // Buscar el último hijo con ese prefijo
        $hijos = self::where('parent_id', $cuenta->parent_id)
            ->where('codigo_cuenta', 'like', $prefijo . '%')
            ->orderBy('codigo_cuenta', 'desc')
            ->first();

        $nuevoSegmento = '01';

        if ($hijos) {
            $codigoUltimo = substr($hijos->codigo_cuenta, $longitudActivo, 2);
            $nuevoSegmento = str_pad(((int)$codigoUltimo + 1), 2, '0', STR_PAD_LEFT);
        }

        $nuevoPrefijo = $prefijo . $nuevoSegmento;

        // Rellenar con ceros hasta completar 10 caracteres
        return str_pad($nuevoPrefijo, 10, '0', STR_PAD_RIGHT);
    }

    // Calcular nivel a partir del código
    public static function calcularNivel($codigo)
    {
        // Cuenta cuántos grupos de 2 dígitos no-cero hay después del primer dígito
        $chunks = str_split(substr($codigo, 1), 2);

        $nivel = 1;
        foreach ($chunks as $chunk) {
            if ((int)$chunk > 0) {
                $nivel++;
            } else {
                break;
            }
        }

        return $nivel;
    }

}
