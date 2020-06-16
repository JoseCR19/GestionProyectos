<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Pintura extends Model {

    public $timestamps = false;
    protected $table = 'tab_pint';
    protected $primaryKey = 'intIdLotePintura';
    protected $fillable = [
        'intIdProy',
        'intIdTipoProducto',
        'varLotePintura',
        'intIdCabina',
        'varPintor',
        'dateFechInic',
        'dateFechFin',
        'dateFechFinReal',
        'varObservacion',
        'intCantidad',
        'deciPesoNeto',
        'deciAreaPintura',
        'deciAreaTotal',
        'intIdEsta',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
        'intIdCont'
    ];

}
