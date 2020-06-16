<?php

namespace App\Http\Controllers;

//use App\Usuario;

use App\detalleAgrupadorColaborador;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\PersonaTipoEtapa;
use Illuminate\Support\Facades\Validator;

class PersonaTipoEtapaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function listar_personas_por_tipo_etapa(Request $request) {
        $regla = [
            'intIdTipoEtap.required' => 'EL Campo Tipo Etapa es obligatorio'];
        $validator = Validator::make($request->all(), ['intIdTipoEtap' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_personas_por_tipo_etapa = DB::select("select colaborador.intIdColaborador, estado.varDescEsta,concat(colaborador.varNombColabo,' ',colaborador.varApelColabo) as Nombres,tipoetapa.intIdTipoEtap, colaborador.varNumeIden,tipoetapa.varDescTipoEtap ,deta_tipo_pers.acti_usua,deta_tipo_pers.acti_hora,deta_tipo_pers.usua_modi,deta_tipo_pers.hora_modi from deta_tipo_pers 
                                                inner join colaborador on deta_tipo_pers.intIdColaborador=colaborador.intIdColaborador 
                                                inner join tipoetapa on tipoetapa.intIdTipoEtap=deta_tipo_pers.intIdTipoEtap
                                                inner join estado on estado.intIdEsta=deta_tipo_pers.intIdEsta
                                                where deta_tipo_pers.intIdTipoEtap=$request->intIdTipoEtap order by  colaborador.varNombColabo asc");
            return $this->successResponse($listar_personas_por_tipo_etapa);
        }
    }

    public function crear_personas_por_tipo_etapa(Request $request) {

        $regla = [
            'informacion.required' => 'EL Campo Colaborador es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'informacion' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $informacion = json_decode($request->informacion);
            for ($i = 0; count($informacion) > $i; $i++) {
                date_default_timezone_set('America/Lima'); // CDT
                $personas_tipo_etapa = new PersonaTipoEtapa;
                $personas_tipo_etapa->intIdColaborador = $informacion[$i]->idcolaborador;
                $personas_tipo_etapa->intIdTipoEtap = $informacion[$i]->idetapa;
                $personas_tipo_etapa->acti_usua = $request->acti_usua;
                $personas_tipo_etapa->acti_hora = $current_date = date('Y/m/d H:i:s');
                $personas_tipo_etapa->intIdEsta = 3;
                $personas_tipo_etapa->save();
            }
            $mensaje = "";
            return $this->successResponse($mensaje);
        }
    }

    public function actualizar_personas_por_tipo_etapa(Request $request) {
        $regla = [
            'intIdTipoEtap.required' => 'EL Campo Colaborador es obligatorio',
            'intIdColaborador.required' => 'EL Campo Colaborador es obligatorio',
            'intIdEsta.required' => 'EL Campo Estado es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdTipoEtap' => 'required|max:255',
                    'intIdColaborador' => 'required|max:255',
                    'intIdEsta' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            date_default_timezone_set('America/Lima'); // CDT
            PersonaTipoEtapa::where('intIdTipoEtap', '=', $request->intIdTipoEtap)
                    ->where('intIdColaborador', '=', $request->intIdColaborador)
                    ->update(['usua_modi' => $request->acti_usua,
                        'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                        'intIdEsta' => $request->intIdEsta]);
            $mensaje = "";
            return $this->successResponse($mensaje);
        }
    }

}
