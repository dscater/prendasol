<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CambioMoneda extends Model
{
    protected $fillable = [
        'valor_sus', 'valor_bs'
    ];

    public static function ajustaDecimal($numero)
    {
        $numero = number_format($numero, 2, ".", "");
        $array_numero = explode(".", $numero);
        $array_decimales = str_split($array_numero[1]);
        $numero_ajustado = "";
        if ((float)$array_decimales[1] > 0) {
            $array_decimales[0] = (int)$array_decimales[0] + 1;
            $array_decimales[1] = 0;
            if ($array_decimales[0] >= 10) {
                $array_numero[0] = (int)$array_numero[0] + 1;
                $array_decimales[0] = 0;
                $array_decimales[1] = 0;
            }
        }
        $numero_ajustado = $array_numero[0] . "." . $array_decimales[0] . $array_decimales[1];
        return number_format($numero_ajustado, 2, ".", "") . "";
    }
}
