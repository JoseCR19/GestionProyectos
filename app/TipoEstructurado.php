<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class TipoEstructurado extends Model {

    public $timestamps = false;
    protected $table = 'tipo_estructurado';
    protected $primaryKey = 'intIdTipoEstructurado';
    protected $fillable = [
        'varDescTipoEstru',
        'varEstaTipoEstru',
        'acti_usua',
        'acti_hora',
    ];

}
