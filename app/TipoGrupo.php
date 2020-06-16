<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoGrupo extends model {

    public $timestamps = false;
    protected $table = 'tipo_grupo';
    protected $primaryKey = 'intIdTipoGrupo';
    protected $fillable = ['varCodiTipoGrupo', 'varDescTipoGrupo', 'intIdEsta', 'acti_usua', 'acti_hora'];

}
