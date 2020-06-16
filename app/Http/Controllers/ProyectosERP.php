<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
class ProyectosERP extends Controller {

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

    public function obtener_os_erp() {
       
        $datos_array = DB::connection('mysql2')->select("SELECT 
                            CAB.NUMERO as intIdProy,
                            CAB.NUMERO as varCodiProy,
                            DET.CANTIDAD, 
                            DET.ESTRUCTURA_PESADA, 
                            DET.ESTRUCTURA_MEDIANA,
                            DET.ESTRUCTURA_LIVIANA,
                            DET.PESO_ESTRUCTURA, 
                            P.RAZON_SOCIAL,
                            CAB.FECHAREG, 
                            CAB.FECHA_INICIO, 
                            CAB.FECHA_FIN, 
                            CAB.ELIMINADO 
                            FROM 
                            ERP.TBL_ORDEN_DET AS DET 
                            LEFT JOIN ERP.TBL_ORDEN_CAB AS CAB ON CAB.ID = DET.ID_ORDEN 
                            LEFT JOIN ERP.TBL_PERSONA AS P ON CAB.ID_PERSONA=P.ID
                            WHERE 
                            CAB.ID_TIPO_ORDEN = 2945 AND 
                            CAB.ID_UNIDAD_NEGOCIO = 4 AND 
                            CAB.ELIMINADO = 'N' AND 
                            DET.ELIMINADO = 'N';");
        //dd($datos_array);
        $listar_galvanizado = DB::select("SELECT varOrdenServi FROM tab_galv where varOrdenServi<>''");
        //dd($datos_array);

        if ($listar_galvanizado) {
            
            $array_ultimo = array_diff_key($datos_array, $listar_galvanizado);

            return $this->successResponse($array_ultimo);
        } else {
            
            return $this->successResponse($datos_array);
           
        }
        
    }

    public function obtener_ot_erp() {
        $lista_ot = DB::select("select distinct p.intIdProy,p.varCodiProy from proyecto p inner join tab_galv t on p.intIdProy=t.intIdProy");
        return $this->successResponse($lista_ot);
    }

    public function obtener_ot_os_g() {
        $lista_os = DB::select("SELECT varOrdenServi as intIdProy,varOrdenServi as varCodiProy  FROM tab_galv where varOrdenServi<>''");
        return $this->successResponse($lista_os);
    }

    public function obtener_os_id(Request $request) {
        $regla = [
            'NUMERO.required' => 'EL Campo OS es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'NUMERO' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $os = $request->NUMERO;
            //dd($os);
            $personal_sql = DB::connection('mysql2')->select("SELECT 
                            CAB.NUMERO,
                            DET.CANTIDAD, 
                            DET.ESTRUCTURA_PESADA, 
                            DET.ESTRUCTURA_MEDIANA,
                            DET.ESTRUCTURA_LIVIANA,
                            DET.PESO_ESTRUCTURA, 
                            P.RAZON_SOCIAL,
                            CAB.FECHAREG, 
                            CAB.FECHA_INICIO, 
                            CAB.FECHA_FIN, 
                            CAB.ELIMINADO 
                            FROM 
                            ERP.TBL_ORDEN_DET AS DET 
                            LEFT JOIN ERP.TBL_ORDEN_CAB AS CAB ON CAB.ID = DET.ID_ORDEN 
                            LEFT JOIN ERP.TBL_PERSONA AS P ON CAB.ID_PERSONA=P.ID
                            WHERE 
                            CAB.ID_TIPO_ORDEN = 2945 AND 
                            CAB.ID_UNIDAD_NEGOCIO = 4 AND 
                            CAB.ELIMINADO = 'N' AND 
                            DET.ELIMINADO = 'N' AND
                            CAB.NUMERO='$os'");
            return $this->successResponse($personal_sql);
        }
    }
   

}
