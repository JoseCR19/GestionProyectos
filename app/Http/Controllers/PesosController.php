<?php

namespace App\Http\Controllers;

use App\Programas;
use App\Software;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DateTime;
use Illuminate\Support\Facades\Validator;

class PesosController extends Controller {

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

    public function llistar_pesos_ot(Request $request) {
        $regla = ['v_varintIdProy.required' => 'EL Campo Fecha Inicio es obligatorio',
            'v_tipo.required' => 'EL Campo Fecha Fin es obligatorio',
            'v_usuario.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['v_varintIdProy' => 'required|max:255',
                    'v_tipo' => 'required|max:255', 'v_usuario' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /* dd($request->input('v_varintIdProy'),
              (int) $request->v_tipo,
              $request->v_usuario); */
            $v_strIdPaquete = trim($request->input('v_varintIdProy'), ',');
            $v_strusuario = $request->input('v_usuario');
            $tipo = (int) $request->v_tipo;

            DB::select('CALL sp_ReportePesos(?,?,?)', array(
                $v_strIdPaquete,
                $tipo,
                $v_strusuario
            ));

            $select_ot = DB::select("select varCodiProy,intIdProy from proyecto where intIdProy in ($v_strIdPaquete)");

            for ($i = 0; count($select_ot) > $i; $i++) {

                $pesos_erp_nuevo = DB::connection('mysql2')->select("CALL USP_ORDEN_CONSULTAR_POR_OT_TIPO(?,?,?)", array(1, $select_ot[$i]->{'varCodiProy'}, $tipo));

                $ot = [
                    'varCodiProy' => $select_ot[$i]->{'varCodiProy'},
                    'varTipo' => $tipo
                ];
                //dd($ot);
                $ch = curl_init('https://mimcoapps.mimco.com.pe/Sincronizacion/public/index.php/obtener_pesos_erp');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $ot);
                $data = curl_exec($ch);
                //dd($data);
                curl_close($ch);
                $datos_array = json_decode($data, true);

                if (count($datos_array['data']) > 0) {
                    $data_prcesada = $datos_array['data'][0];
                    $oc = $data_prcesada['O/C'];
                    $recepcionados = $data_prcesada['RECEPCIONADO'];
                    $salidas = $data_prcesada['SALIDAS'];
                } else {
                    $oc = 0.0;
                    $recepcionados = 0.0;
                    $salidas = 0.0;
                }

                //dd($pesos_erp_nuevo,$data_prcesada);

                if (count($pesos_erp_nuevo) > 0) {
                    $feecha_termino = $pesos_erp_nuevo[0]->{'FECHA_FIN'};
                    $peso_estimado = $pesos_erp_nuevo[0]->{'PESO ESTIMADO'};
                    $total_cantidad = $pesos_erp_nuevo[0]->{'TOTAL CANTIDAD'};
                } else {
                    $feecha_termino = '';
                    $peso_estimado = 0.0;
                    $total_cantidad = 0;
                }
                $id_ot = $select_ot[$i]->{'intIdProy'};

                //dd($feecha_termino, $id_ot, $v_strusuario);
                DB::select("SET SQL_SAFE_UPDATES = 0");
                DB::update("update tab_pesos set dateFecTermino='$feecha_termino' , deciPesoCompraSol=$oc, deciPesoCompraRec=$recepcionados,deciPesoCompraDes=$salidas,deciPesoComer=$peso_estimado,intCanti=$total_cantidad where intIdProy=$id_ot and varUsuario='$v_strusuario'");
                DB::select("SET SQL_SAFE_UPDATES = 1");
            }

            $listar_pesos_ot = DB::select("select *,tab_pesos.intIdProy as id_reporta from tab_pesos where intIdProy in($v_strIdPaquete) and varUsuario='$v_strusuario'");

            return $this->successResponse($listar_pesos_ot);
        }
    }

    public function listqar_pesos_sub_ot(Request $request) {

        $regla = ['v_intIdProy.required' => 'El campo id proyecto',
            'v_tipo.required' => 'EL Campo tipo.',
            'v_usuario.required' => 'EL Campo usuario.'];
        $validator = Validator::make($request->all(), ['v_intIdProy' => 'required|max:255',
                    'v_tipo' => 'required|max:255', 'v_usuario' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $v_strIdPaquete = (int) $request->input('v_intIdProy');
            $v_strusuario = $request->input('v_usuario');
            $tipo = (int) $request->v_tipo;
            //dd($v_strIdPaquete,$v_strusuario,$tipo);
            DB::select('CALL sp_ReportePesos_SubOt(?,?,?)', array(
                $v_strIdPaquete,
                $tipo,
                $v_strusuario
            ));
            $select_ot = DB::select("select varCodiProy,intIdProy from proyecto where intIdProy in ($v_strIdPaquete)");
            //dd($select_ot);
            $listar_pesos_ot = DB::select("select varCodigoSubOt from tab_pesos_zonas where intIdProy in($v_strIdPaquete) and varUsuario='$v_strusuario'");
            for ($i = 0; count($listar_pesos_ot) > $i; $i++) {
                $subot = $listar_pesos_ot[$i]->{'varCodigoSubOt'};
                //dd(2,$select_ot[$i]->{'varCodiProy'},$tipo);
                $pesos_erp_nuevo = DB::connection('mysql2')->select("CALL USP_ORDEN_CONSULTAR_POR_OT_TIPO(?,?,?)", array(2, $listar_pesos_ot[$i]->{'varCodigoSubOt'}, $tipo));

//dd($pesos_erp_nuevo);
                $ot = [
                    'varCodiProy' => $listar_pesos_ot[$i]->{'varCodigoSubOt'},
                    'varTipo' => $tipo
                ];

                $ch = curl_init('https://mimcoapps.mimco.com.pe/Sincronizacion/public/index.php/obtener_pesos_subot_erp');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $ot);
                $data = curl_exec($ch);

                curl_close($ch);
                $datos_array = json_decode($data, true);

                //dd($datos_array);
                if (count($datos_array['data']) > 0) {
                    $data_prcesada = $datos_array['data'][0];
                    $oc = $data_prcesada['O/C'];
                    $recepcionados = $data_prcesada['RECEPCIONADO'];
                    $salidas = $data_prcesada['SALIDAS'];
                } else {
                    $oc = 0.0;
                    $recepcionados = 0.0;
                    $salidas = 0.0;
                }

                if (count($pesos_erp_nuevo) > 0) {
                    $feecha_termino = $pesos_erp_nuevo[0]->{'FECHA_FIN'};
                    $peso_estimado = $pesos_erp_nuevo[0]->{'TOTAL PESO'};
                    $total_cantidad = $pesos_erp_nuevo[0]->{'TOTAL CANTIDAD'};
                } else {
                    $feecha_termino = '';
                    $peso_estimado = 0.0;
                    $total_cantidad = 0.0;
                }
                //dd($total_cantidad);
                $id_ot = $select_ot[0]->{'intIdProy'};
                DB::select("SET SQL_SAFE_UPDATES = 0");
                DB::update("update tab_pesos_zonas set dateFecTermino='$feecha_termino' , deciPesoCompraSol=$oc, deciPesoCompraRec=$recepcionados,deciPesoCompraDes=$salidas,deciPesoComer=$peso_estimado,intCanti=$total_cantidad where intIdProy=$id_ot and varUsuario='$v_strusuario' and varCodigoSubOt='$subot'");
                DB::select("SET SQL_SAFE_UPDATES = 1");
            }
            $listar_pesos_sub_ot = DB::select("select * from tab_pesos_zonas where intIdProy in($v_strIdPaquete) and varUsuario='$v_strusuario'");
            return $this->successResponse($listar_pesos_sub_ot);
        }
    }

    public function listar_ot_pesos(Request $request) {
        $regla = ['intIdUniNego.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdEsta.required' => 'EL Campo Fecha Fin es obligatorio'];
        $validator = Validator::make($request->all(), ['intIdUniNego' => 'required|max:255',
                    'intIdEsta' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $unidadNegocio = trim($request->input('intIdUniNego'), ',');
            if ($request->intIdEsta === '1') {
                $listar_ot = DB::select("SELECT intIdproy, 
        intAnioProy,
        varIdTipOT ,
        varcodiOt,
        concat(varCodiProy,' / ',varAlias) AS varCodiProy,
        
        varDescProy,
        varUbicacionProy,
        varAlias,
        varRucClie,
		dateFechInic,
        dateFechFina,
        (CASE WHEN intIdEsta = '3'THEN 'ACTIVO' ELSE 'INACTIVO' END) AS intIdEsta,
        acti_usua,
        acti_hora,
        usua_modi,
        hora_modi 
		FROM proyecto
		WHERE now() <= adddate(dateFechFina,INTERVAL 1 DAY) and intIdUniNego in($unidadNegocio)
        and status = 1 order by dateFechFina asc");
            } else {
                $listar_ot = DB::select("SELECT intIdproy, 
        intAnioProy,
        varIdTipOT ,
        varcodiOt,
        concat(varCodiProy,' / ',varAlias) AS varCodiProy,
        
        varDescProy,
        varUbicacionProy,
        varAlias,
        varRucClie,
		dateFechInic,
        dateFechFina,
        (CASE WHEN intIdEsta = '3'THEN 'ACTIVO' ELSE 'INACTIVO' END) AS intIdEsta,
        acti_usua,
        acti_hora,
        usua_modi,
        hora_modi 
		FROM proyecto
		WHERE now() >= adddate(dateFechFina,INTERVAL 1 DAY) and intIdUniNego in($unidadNegocio)
        and status = 1 order by dateFechFina asc");
            }

            return $this->successResponse($listar_ot);
        }
    }

}
