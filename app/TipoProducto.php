<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class TipoProducto extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'tipo_producto';
   protected $primaryKey = 'intIdTipoProducto';
   protected $fillable = [
      'varDescTipoProd',
      'varEstaTipoProd',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi'
    
   
  ];
}
