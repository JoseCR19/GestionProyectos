<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Maquina extends Model 
{
    

    public $timestamps = false;
   
   protected $table = 'maquinas';
   protected $primaryKey = 'intIdMaquinas';
   protected $fillable = [
       'varDescripcion',
       'intIdAgru',
      'intIdEsta',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi',
    
     
    
   
  ];
}
