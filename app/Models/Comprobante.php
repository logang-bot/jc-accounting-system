<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comprobante extends Model
{
    protected $fillable = ['numero', 'fecha', 'tipo', 'descripcion', 'destinatario',
    'lugar', 'total', 'tasa_cambio', 'user_id', 'empresa_id',];

    public function detalles()
    {
        return $this->hasMany(ComprobanteDetalles::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public static function booted()
    {
        static::creating(function ($comprobante) {
            $comprobante->numero = $comprobante->numero ?? static::generateNumero();
        });
    }

    public static function generateNumero()
    {
        $empresaId = session('empresa_id');
        $year = now()->format('Y');

        do {
            $lastId = DB::table('comprobantes')
                ->where('empresa_id', $empresaId)
                ->whereYear('fecha', $year)
                ->max('id') ?? 0;

            $numero = sprintf("COMP-%s-%04d", $year, $lastId + 1);
        } while (DB::table('comprobantes')->where('empresa_id', $empresaId)->where('numero', $numero)->exists());

        return $numero;
    }
}
