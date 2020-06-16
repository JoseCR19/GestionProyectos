<?php

namespace App\Http\Controllers;

use DB;
use App\Cabina;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CabinaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function listar_cabina() {
        $listar_cabina = Cabina::all();
        return $this->successResponse($listar_cabina);
    }

    public function crear_cabina(Request $request) {
        $regla = [
            'varCabina.required' => 'EL Campo Cabina es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'varCabina' => 'required|max:255',
                    'acti_usua' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            date_default_timezone_set('America/Lima'); // CDT
            $cabina = new Cabina;
            $cabina->varCabina = $request->varCabina;
            $cabina->acti_usua = $request->acti_usua;
            $cabina->intIdEsta = 0;
            $cabina->acti_hora = $current_date = date('Y/m/d H:i:s');
            $cabina->save();
            $mensaje = "";
            return $this->successResponse($mensaje);
        }
    }

    public function editar(Request $request) {
        $regla = [
            'varCabina.required' => 'EL Campo Cabina es obligatorio',
            'intIdCabina.required' => 'EL Campo Cabina es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'varCabina' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                    'intIdCabina' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $validar = DB::select("select varCabina from tab_cabina intIdCabina=$request->intIdCabina");
            if ($validar[0]->{varCabina} === $request->varCabina) {
                $mensaje = "No debe ser el mismo nombre de Cabina";
                return $this->successResponse($mensaje);
            } else {
                Cabina::where('intIdCabina', '=', $request->intIdCabina)
                        ->update(['varCabina' => $request->varCabina, 'acti_usua' => $request->acti_usua]);
                $mensaje = "";
                return $this->successResponse($mensaje);
            }
        }
    }

    public function actualizar(Request $request) {
        $regla = [
            'intIdCabina.required' => 'EL Campo Cabina es obligatorio',
            'intIdEsta.required' => 'EL Campo Cabina es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdCabina' => 'required|max:255',
                    'intIdEsta' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            Cabina::where('intIdCabina', '=', $request->intIdCabina)
                    ->update(['intIdEsta' => (int) $request->intIdEsta]);
            $mensaje = "";
            return $this->successResponse($mensaje);
        }
    }

}
