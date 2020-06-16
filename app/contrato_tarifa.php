<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class contrato_tarifa extends Model {

    public $timestamps = false;
    protected $table = 'contrato_tarifa';
    protected $primaryKey = 'idcontrato_tarifa';
    protected $fillable = [
        'idcontrato',
        'idetapa',
        'vardescripcion',
        'varCodVal',
        'deciTarifa',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi',
    ];

}
