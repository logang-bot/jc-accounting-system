<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    protected $fillable = ['numero', 'fecha', 'tipo', 'descripcion', 'total', 'tasa_cambio', 'user_id'];

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
        $year = now()->format('Y');
        $last = static::whereYear('fecha', $year)->max('id') ?? 0;
        return sprintf("COMP-%s-%04d", $year, $last + 1);
    }
}
