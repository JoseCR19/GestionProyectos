<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class TipoEstructura extends Model 
{
    
   public $timestamps = false;
   
   protected $table = 'tipo_estructura';
   protected $primaryKey = 'intIdTipoEstru';
   protected $fillable = [
      'varCodiEstru',
      'varDescrip',
      'varEstaEstru',
      'acti_usua',
      'acti_hora',
      'usua_modi',
      'hora_modi' 
   
  ];
}
