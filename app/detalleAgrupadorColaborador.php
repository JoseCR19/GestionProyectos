<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class detalleAgrupadorColaborador extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'deta_agru_supe';
  // protected $primaryKey = 'intIdTipoProducto';
   protected $fillable = [
      'intIdAgru',
      'intIdColaborador',
       'intIdEsta',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi'
    
   
  ];
}
