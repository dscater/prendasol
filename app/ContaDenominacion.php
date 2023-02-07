<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContaDenominacion extends Model
{

	protected $table      = 'conta_denominacion';
    
    protected $fillable   = [
        'id',
        'cod_deno',
        'descripcion',
    	'nocuenta',
    	'numerocod',
    	'grupo',
    	'subgrupo',
    	'subgrupo2',
        'estado_id'
    ];
    //

    public static function fnTotalCuenta($fechaInicio,$fechaFinal,$cuenta){
        // $fechaI = $request['txtFechaInicio'];
        // $fechaF = $request['txtFechaFin'];
        $totalDato = DB::table('conta_diario')
            ->select(DB::raw('SUM(debe) as debe'),DB::raw('SUM(haber) as haber'))
            ->whereBetween('fecha_a',[$fechaInicio,$fechaFinal])
            ->where('cod_deno',$cuenta)
            ->where('estado_id',1)
            ->first();
            //->groupBy('cod_deno','cuenta')
            //->orderBy('id','ASC')->get();
        return $totalDato;
    }


    public static function fnTotalCuentaGeneral($fechaInicio,$fechaFinal,$cuenta){
        // $fechaI = $request['txtFechaInicio'];
        // $fechaF = $request['txtFechaFin'];
        $cuentaGrupo = ContaDenominacion::select('cod_deno')->where('grupo',$cuenta)->get();
        //dd($cuentaGrupo);
        $totalDato = DB::table('conta_diario')
            ->select(DB::raw('SUM(debe) as debe'),DB::raw('SUM(haber) as haber'))
            ->whereBetween('fecha_a',[$fechaInicio,$fechaFinal])
            //->whereBetween('cod_deno',['40000','49999'])
            ->whereIn('cod_deno',$cuentaGrupo)
            ->where('estado_id',1)
            ->first();
            //->groupBy('cod_deno','cuenta')
            //->orderBy('id','ASC')->get();
        return $totalDato;
    }
}
