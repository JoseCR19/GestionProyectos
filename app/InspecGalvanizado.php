<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class InspecGalvanizado extends Model {

    public $timestamps = false;
    protected $table = 'insp_galv';
    protected $primaryKey = 'intIdInspGalv';
    protected $fillable = [
        'intIdDetaGalv',
        'intIdEspeci',
        'deciMuesA1_1',
        'deciMuesA1_2',
        'deciMuesA2_1',
        'deciMuesA2_2',
        'deciMuesA3_1',
        'deciMuesA3_2',
        'deciMuesA4_1',
        'deciMuesA4_2',
        'deciMuesA5_1',
        'deciMuesA5_2',
        'deciPromedio',
        'deciMaxiTota',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
        'intIdEsta',
        'varTipoMaterial',
        'varMaterial',
        'intIdSuper',
        'deciTolerancia',
        'deciPesoExceso',
        'intCantidad',
        'deciPesoNegro',
        'deciPesoGalv',
        'deciConsumoZinc',
        'varPorcZinc',
        'intEsHijo',
        'intIdObse',
        'TieneObs'
    ];

}