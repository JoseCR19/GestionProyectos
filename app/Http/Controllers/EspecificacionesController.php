<?php

namespace App\Http\Controllers;

use DB;
use Config;
use App\Especificacion;
use App\DetaGalva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EspecificacionesController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function crear_especificaion(Request $request) {

        $regla = [
            'intEspeci.required' => 'EL Campo Especificacion es obligatorio',
            'deciFactor.required' => 'EL Campo Factor es obligatorio',
            'varTipoMate.required' => 'EL Campo Tipo Material es obligatorio',
            'deciEspeciMax.required' => 'EL Campo Especificacion Maxima es obligatorio',
            'intIdEsta.required' => 'EL Campo Estado obligatorio',
            'acti_usua.required' => 'EL Campo Usuario obligatorio',];
        $validator = Validator::make($request->all(), [
                    'intEspeci' => 'required|max:255',
                    'deciFactor' => 'required|max:255',
                    'varTipoMate' => 'required|max:255',
                    'deciEspeciMax' => 'required|max:255',
                    'intIdEsta' => 'required|max:255',
                    'acti_usua' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $select_inspeccion = Especificacion::where('intEspeci', '=', $request->intEspeci)
                            ->select('intEspeci')->get();
            if (count($select_inspeccion) > 0) {
                $mensaje = "La especificación ingresada ya se encuentra registrado";
                return $this->successResponse($mensaje);
            } else {
                date_default_timezone_set('America/Lima'); // CDT
                $galvanizado = new Especificacion;
                $galvanizado->intEspeci = $request->intEspeci;
                $galvanizado->deciFactor = (float) $request->deciFactor;
                $galvanizado->varTipoMate = $request->varTipoMate;
                $galvanizado->deciEspeciMax = (float) $request->deciEspeciMax;
                $galvanizado->intIdEsta = 3;
                $galvanizado->acti_usua = $request->acti_usua;
                $galvanizado->acti_hora = $current_date = date('Y/m/d H:i:s');
                $galvanizado->save();
                $mensaje = "";
                return $this->successResponse($mensaje);
            }
        }
    }

    public function editar_especificacion(Request $request) {
        $regla = [
            'intIdEspeci.required' => 'EL Campo Especificacion es obligatorio',
            'deciFactor.required' => 'EL Campo Factor es obligatorio',
            'deciEspeciMax.required' => 'EL Campo Especificacion Maxima es obligatorio',
            'usua_modi.required' => 'EL Campo Usuario obligatorio',
            'intIdEsta.required' => 'EL Campo Usuario obligatorio',];
        $validator = Validator::make($request->all(), [
                    'intIdEspeci' => 'required|max:255',
                    'deciFactor' => 'required|max:255',
                    'deciEspeciMax' => 'required|max:255',
                    'usua_modi' => 'required|max:255',
                    'intIdEsta' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {

            $inspe_galvanizado = DB::select("SELECT intIdEspeci,estado.varDescEsta FROM mimco.insp_galv 
                                        INNER JOIN estado on insp_galv.intIdEsta=estado.intIdEsta
                                        WHERE insp_galv.intIdEspeci=$request->intIdEspeci");
            
            if (count($inspe_galvanizado) > 0) {
                $contador = 0;
                for ($i = 0; count($inspe_galvanizado) > $i; $i++) {
                    if ($inspe_galvanizado[$i]->{'varDescEsta'} === 'TERMINADO') {
                        $contador++;
                    }
                }
                if ($contador > 0) {
                    $respuesta = "La especificación que desea editar tiene inspecciones de galvanizo en estado TERMINADO";
                    return $this->successResponse($respuesta);
                } else {
                    $editar_galvanizado = $this->actualizar_especificacion($request);
                    return $this->successResponse($editar_galvanizado->original['data']);
                }
            } else {
                $editar_galvanizado = $this->actualizar_especificacion($request);
                return $this->successResponse($editar_galvanizado->original['data']);
            }
        }
    }

    public function listar_especificaciones() {
        $listar_especificaciones = DB::select("SELECT tab_espe.*,estado.varDescEsta FROM tab_espe inner join estado on tab_espe.intIdEsta=estado.intIdEsta");
        //dd($listar_especificaciones);
        return $this->successResponse($listar_especificaciones);
    }

    public function listar_especificaciones_lista() {
        $listar_especificaciones = DB::select("SELECT tab_espe.intIdEspeci,tab_espe.intEspeci,tab_espe.deciFactor,CONCAT(tab_espe.varTipoMate,'-',tab_espe.intEspeci) AS varTipoMate,tab_espe.deciEspeciMax,tab_espe.intIdEsta,estado.varDescEsta FROM tab_espe inner join estado on tab_espe.intIdEsta=estado.intIdEsta where tab_espe.intIdEsta=3 order by tab_espe.varTipoMate asc");
        return $this->successResponse($listar_especificaciones);
    }

    public function listar_especificaciones_id(Request $request) {
        $regla = [
            'intIdEspeci.required' => 'EL Campo Especificacion es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdEspeci' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $especificaciones = Especificacion::where('intIdEspeci', '=', $request->intIdEspeci)
                            ->select()->get();
            return $this->successResponse($especificaciones);
        }
    }

    public function actualizar_especificacion($request) {
        
        
        date_default_timezone_set('America/Lima'); // CDT
        Especificacion::where('intIdEspeci', '=', $request->intIdEspeci)
                ->update(['deciEspeciMax' =>(float) $request->deciEspeciMax,'deciFactor' =>(float) $request->deciFactor,
                    'intIdEsta'=>$request->intIdEsta,
                    'usua_modi' => $request->usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')]);

        $respuesta = "";
        return $this->successResponse($respuesta);
    }

    public function estado_especificacion(Request $request) {
        $regla = [
            'intIdEspeci.required' => 'EL Campo Id Especificacion es obligatorio',
            'usua_modi.required' => 'EL Campo Usuario es obligatorio',
            'intIdEsta.required' => 'EL Campo Estado es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdEspeci' => 'required|max:255',
                    'usua_modi' => 'required|max:255',
                    'intIdEsta' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            date_default_timezone_set('America/Lima'); // CDT

            Especificacion::where('intIdEspeci', '=', $request->intIdEspeci)
                    ->update(['intIdEsta' => $request->intIdEsta,
                        'usua_modi' => $request->usua_modi,
                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);

            $respuesta = "";
            return $this->successResponse($respuesta);
        }
    }

    public function get_especificacion(Request $request) {
        $regla = [
            'intIdEspeci.required' => 'EL Campo Id Especificacion es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdEspeci' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar = DB::select("SELECT * FROM mimco.tab_espe where intIdEspeci=$request->intIdEspeci");
            return $this->successResponse($listar);
        }
    }

}
