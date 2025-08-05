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
        'estado',
        'moneda_principal',
        'empresa_id'
    ];

    protected $attributes = [
        'nivel' => 1,
    ];

    protected $casts = [
        'nivel' => 'integer',
        'es_movimiento' => 'boolean',
        'estado' => 'boolean',
        'moneda_principal' => 'string'
    ];

    
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Relación jerárquica
    public function children()
    {
        return $this->hasMany(CuentasContables::class, 'parent_id')
            ->where('estado', true)
            ->orderBy('codigo_cuenta', 'asc')
            ->with('children');
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

            $cuenta->nivel = self::calcularNivel($cuenta->codigo_cuenta);

            // Respetar el valor enviado en el formulario
            $cuenta->es_movimiento = match (true) {
                $cuenta->nivel === 5 => true,
                $cuenta->nivel === 4 => filter_var($cuenta->es_movimiento, FILTER_VALIDATE_BOOLEAN),
                default => false,
            };

            $cuenta->estado = $cuenta->estado ?? true;
            if (!in_array($cuenta->nivel, [4, 5])) {
                $cuenta->moneda_principal = null;
            }
        });

        static::updating(function ($cuenta) {
            if (!in_array($cuenta->nivel, [4, 5])) {
                $cuenta->moneda_principal = null;
            }
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
            // Nivel 1: código base como "1000000000", "2000000000", etc.
            return str_pad($prefijos[$cuenta->tipo_cuenta], 10, '0', STR_PAD_RIGHT);
        }

        $parent = $cuenta->parent;
        $codigoPadre = $parent->codigo_cuenta;
        $nivelPadre = self::calcularNivel($codigoPadre);
        $nivelHijo = $nivelPadre + 1;

        // Definimos los rangos de dígitos por nivel
        $rangos = [
            1 => [0, 1],     // 1er dígito
            2 => [1, 1],     // 2do dígito
            3 => [2, 2],     // dígitos 3-4
            4 => [4, 2],     // dígitos 5-6
            5 => [6, 4],     // dígitos 7-10
        ];

        if (!isset($rangos[$nivelPadre]) || !isset($rangos[$nivelHijo])) {
            throw new \Exception("Nivel no válido para generación de código.");
        }

        // Prefijo hasta el final del nivel padre
        $inicioPrefijo = 0;
        $longitudPrefijo = $rangos[$nivelPadre][0] + $rangos[$nivelPadre][1];
        $prefijo = substr($codigoPadre, $inicioPrefijo, $longitudPrefijo);

        // Buscar el último hijo
        $hijoMasAlto = self::where('parent_id', $cuenta->parent_id)
            ->where('codigo_cuenta', 'like', $prefijo . '%')
            ->orderBy('codigo_cuenta', 'desc')
            ->first();

        $nuevoSegmento = $nivelHijo === 5 ? '0001' : '01';

        if ($hijoMasAlto) {
            $inicioSegmento = $rangos[$nivelHijo][0];
            $longitudSegmento = $rangos[$nivelHijo][1];
            $segmento = substr($hijoMasAlto->codigo_cuenta, $inicioSegmento, $longitudSegmento);
            $nuevoNumero = str_pad((int)$segmento + 1, $longitudSegmento, '0', STR_PAD_LEFT);
            $nuevoSegmento = $nuevoNumero;
        }

        // Armar el nuevo código
        $inicio = $rangos[$nivelHijo][0];

        $codigoBase = substr($codigoPadre, 0, $inicio) . $nuevoSegmento;
        $codigoFinal = str_pad($codigoBase, 10, '0', STR_PAD_RIGHT);

        return substr($codigoFinal, 0, 10);

        // Asegurar que tenga 10 dígitos rellenando con ceros
        return str_pad($nuevoCodigo, 10, '0', STR_PAD_RIGHT);
    }

    // Calcular nivel a partir del código
    public static function calcularNivel($codigo)
    {
        $codigo = str_pad($codigo, 10, '0', STR_PAD_RIGHT);
    
        // Verificar desde el nivel más profundo al más superficial
        if (substr($codigo, 7, 3) !== '000') return 5;
        if (substr($codigo, 5, 2) !== '00') return 4;
        if (substr($codigo, 3, 2) !== '00') return 3;
        if (substr($codigo, 1, 2) !== '00') return 2;
        
        return 1; // Nivel raíz
    }

}
