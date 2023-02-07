<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InicioFinCaja extends Model
{
    protected $table      = 'inicio_fin_caja';
    
    protected $fillable   = [
        'id',
	    'sucursal_id',	    
	    'caja',
	    'fecha',
	    'fecha_hora',
	    'fecha_cierre',
	    'inicio_caja_bs',
	    'fin_caja_bs',
	    'inicio_caja_s',
	    'fin_caja_s',	    
	    'ingreso_bs',
	    'ingreso_s',
	    'egreso_bs',
	    'egreso_s',
	    'tipo_de_movimiento',
	    'usuarioIniciado',
	    'usuario_id',
		'estado_id',
		'moneda_id'
    ];

    public function sucural1(){
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class,'usuario_id');
    }

    public static function detalleIniciFinCaja($id){
        $datosDetalle = InicioFinCajaDetalle::where('inicio_fin_caja_id',$id)
            ->where('estado_id',1)
            ->orderBy('id','ASC')->get();
        return $datosDetalle;
	}
	
	public function moneda()
	{
		return $this->belongsTo(Moneda::class,'moneda_id');
	}
}
