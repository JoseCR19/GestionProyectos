<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class detalleProyecto extends Model {

    public $timestamps = false;
    protected $table = 'deta_proyecto';
    // protected $primaryKey = 'intIdTipoProducto';
    protected $fillable = [
        'intIdProy',
        'FechaCambTerm',
        'varObservacion',
        'acti_usua',
        'acti_hora'
    ];

}
