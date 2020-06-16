<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class TipoMotivo extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'tipo_motivo';
   protected $primaryKey = 'intIdMoti';
   protected $fillable = [
       'varDescripcion',
      'intIdEsta',
      'intIdEsta',
      'acti_usua',
      'acti_hora',
      
    
     
    
   
  ];
}
