<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CambioMoneda extends Model
{
    protected $fillable = [
        'valor_sus','valor_bs'
    ];
}
