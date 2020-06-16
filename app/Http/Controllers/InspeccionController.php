<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InspeccionController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    public function listar_detalle_inspeccion(Request $request) {
        $regla = [
            'strDefecto.required' => 'EL Campo Defecto obligatorio'];
        $validator = Validator::make($request->all(), [
                    'strDefecto' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {

            $valores = trim($request->strDefecto, ',');
            $listar_defectos = DB::select("SELECT * FROM mimco.tab_defe where intIdDefe in($valores)");
            return $this->successResponse($listar_defectos);
        }
    }

    public function listar_detalle_causas(Request $request) {
        $regla = [
            'strCausa.required' => 'EL Campo Causa es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'strCausa' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {

            $valores = trim($request->strCausa, ',');
            $listar_causas = DB::select("SELECT * FROM mimco.tab_causa where intIdCausa in($valores)");
            return $this->successResponse($listar_causas);
        }
    }

}
