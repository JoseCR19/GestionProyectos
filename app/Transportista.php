<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Transportista extends Model {

    public $timestamps = false;
    protected $table = 'transportista';
    protected $primaryKey = 'intIdTrans';
    protected $fillable = [
        'varNumeIden',
        'varRazonSoci',
        'varDirec',
        'intIdEsta',
        'acti_usua',
        'acti_hora',
        'usua_modi',
        'hora_modi'
    ];

}
