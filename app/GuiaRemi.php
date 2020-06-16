<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class GuiaRemi extends Model {

    public $timestamps = false;
    protected $table = 'guia_remi';
    protected $primaryKey = 'intIdGuia';
    protected $fillable = [
        'intIdDesp',
        'varContaDocu',
        'intIdEsta',
        'intIdCliente',
        'intIdDistrito',
        'intIdProvincia',
        'intIdDepa',
        'intIdDistritoSali',
        'intIdProvinciaSali',
        'intIdDepaSali',
        'varNombChof',
        'varNumeChof',
        'varNumeLicen',
        'intIdTrans',
        'intIdProy',
        'intIdTipoProducto',
        'dateFechEmis',
        'dateFechTras',
        'varTipoGuia',
        'intIdMoti',
        'varPuntSali',
        'varPuntLleg',
        'varPlaca',
        'varRefe',
        'acti_usua',
        'acti_hora',
        'varArchEmit',
        'usua_emit',
        'hora_emit',
        'varArchRecep',
        'usua_recep',
        'hora_recep',
        'usua_modi',
        'hora_modi',
        'usua_impr',
        'hora_impr'
    ];

}
