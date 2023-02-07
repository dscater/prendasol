<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrecioOro extends Model
{
    protected $table      = 'precio_oro';
    
    protected $fillable   = [
        'dies',
        'catorce',
    	'diesiocho',
    	'veinticuatro',
    	'fecha'
    ];

    public $timestamps = false;
}
