<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class proyecto_avance extends Model {

    public $timestamps = false;
    protected $table = 'proy_avan';
    protected $primaryKey = 'intIdProyAvan';
    protected $fillable = [
        'intIdProy',
        'intIdTipoProducto',
        'varBulto'
    ];

}
