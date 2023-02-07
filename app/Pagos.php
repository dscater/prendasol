<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table      = 'pagos';

    protected $fillable   = [
        'contrato_id',
        'sucursal_id',
        'fecha_pago',
        'fecha_inio',
        'fecha_fin',
        'caja',
        'dias_atraso',
        'dias_atraso_total',
        'cuota_mora',
        'capital',
        'interes',
        'comision',
        'total_ai',
        'total_capital',
        'estado',
        'usuario_id',
        'estado_id',
        'moneda_id'
    ];

    public function sucural()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class, 'moneda_id');
    }

    public function inicio_fin_caja_detalle()
    {
        return $this->hasMany(InicioFinCajaDetalle::class, 'pago_id');
    }
}
