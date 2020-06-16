<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class CostoContratista extends Model 
{
    

    public $timestamps = false;

   protected $table = 'costo_contratista';
   protected $primaryKey = 'idcostocontratista';
   protected $fillable = [
       'intIdAsigEtapProy',
       'intIdContr',
       'decPrecio',
       'acti_usua',
       'acti_hora',
       'usua_modi',
       'hora_modi'
   
  ];
}
