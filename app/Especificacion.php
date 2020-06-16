<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Especificacion extends Model {

    //public $timestamps = false;
    public $timestamps = false;
    protected $table = 'tab_espe';
    protected $primaryKey = 'intIdEspeci';
    protected $fillable = [
        'intEspeci',
        'deciFactor',
        'varTipoMate',
        'deciEspeciMax',
        'intIdEsta',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
    ];

}
