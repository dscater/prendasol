<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InteresAdministrable extends Model
{
    protected $fillable = [
        "monto1",
        "monto2",
        "porcentaje",
    ];
}
