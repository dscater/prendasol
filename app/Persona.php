<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table      = 'persona';
    
    protected $fillable   = [
        'tipodocumento_genericoid',
        'nrodocumento',
        'nombres',
        'primerapellido',
        'segundoapellido',
        'sexo_genericoid',
        'fechanacimiento',
        'muninacimiento_id',
        'telefonodomicilio',
        'correoelectronico',
        'estadocivil_genericoid',
        'nacionalidad_genericoid',
        'direcciontrabajo',
        'telefonotrabajo',
        'nivelestudio_genericoid',
        'idiomamaterno_genericoid',
        'idioma_genericoid',
        'autopertenencia_genericoid',
		'estado_id',
        'fotografia',
        'expedido_id',
        'complemento',
        'celular',
        'domicilio',
        'zona'

    ];
    
    //public $timestamps    = false;    


    public function nombreCompleto(){
        return $this->nombres.' '.$this->primerapellido.' '.$this->segundoapellido;
    }

    public function paciente(){
        return $this->hasOne(Paciente::class);
    }

    public function cliente(){
        return $this->hasOne(Cliente::class);
    }


    public function personaNoValidada(){
        return $this->hasOne(PersonaNoValidada::class);
    }
}
