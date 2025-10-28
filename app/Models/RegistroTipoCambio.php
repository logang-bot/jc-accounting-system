<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroTipoCambio extends Model
{
    use HasFactory;

    protected $table = 'registro_tipo_cambios';

    protected $fillable = [
        'fecha',
        'valor_ufv',
        'valor_sus',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor_ufv' => 'decimal:2',
        'valor_sus' => 'decimal:2',
    ];
}
