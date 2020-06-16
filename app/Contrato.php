<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Contrato extends Model {

    public $timestamps = false;

    protected $table = 'contrato';
    protected $primaryKey = 'idcontrato';
    protected $fillable = [
        'intIdProy',
        'intIdTipoProducto',
        'intIdContr',
        'varNuContrato',
        'varDescripcion',
        'fech_Ini',
        'fech_Fin',
        'varNuOS',
        'deciImpTotal',
        'deciImpValor',
        'deciImpSaldo',
        'intTipoUnidad',
        'deciPesoTotal',
        'deciPesoSaldo',
        'deciAreaTotal',
        'deciAreaSaldo',
        'fech_IniVal',
        'fech_UltValor',
        'varObservacion',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
        'contratocol',
        'intIdEsta',
    ];

}
