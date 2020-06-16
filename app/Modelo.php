<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Modelo extends Model {

    public $timestamps = false;
    protected $table = 'elemento';
    protected $primaryKey = 'intIdEleme';
    protected $fillable = [
        'intIdProy',
        'intIdTipoProducto',
        'varModelo',
        'intIdEsta'
    ];
}
