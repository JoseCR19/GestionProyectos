<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Proyectos extends Model {

    public $timestamps = false;
    protected $table = 'proyecto';
    protected $primaryKey = 'intIdProy';
    protected $fillable = [
        'IntAnioProy',
        'intIdUniNego',
        'varCodiOt',
        'varCodiProy',
        'varAlias',
        'varRucClie',
        'dateFechInic',
        'dateFechFina',
        'intIdEsta',
        'varRutaProg',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
    ];

}
