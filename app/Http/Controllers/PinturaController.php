<?php

namespace App\Http\Controllers;

use DB;
use App\Pintura;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PinturaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function list_metrado_pintura(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'varValo1' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdProyTarea = (int) $request->input('intIdProyTarea');
        $varValo1 = $request->input('varValo1');
        //dd($intIdProy,$intIdTipoProducto,$intIdProyZona,$intIdProyTarea,$varModelo,$varBulto,$varCodiElemento);
        $listar_bulto = "";
        //dd($intIdProyZona,$intIdProyTarea,$varCodiElemento,$varBulto);
        $listar_bulto = DB::select("SELECT E.varCodiElemento, 
		E.varDescripcion as nombre, 
                COUNT(E.intIdEleme) AS Canti, 
		E.intRevision, 
                E.intCantRepro, 
                E.deciAreaPintura,
                PZ.varDescrip as Zona, 
                PT.varDescripTarea AS Programa,
		PG.varCodigoPaquete as Grupo,
		(CASE WHEN E.intIdEsta <> 25 THEN emp.varRazCont ELSE emp1.varRazCont END)  as Contratista, 
                E.deciPrec,
		E.deciPesoNeto, E.deciPesoBruto, E.deciArea, E.deciLong, E.deciAncho, 
		ETA.varDescEtap as etapa_anterior, ETS.varDescEtap as etapa_siguiente,
		E.varPerfil, E.varModelo, E.varCodVal,
		E.intIdProyZona, E.intIdProyPaquete,E.intIdEtapaAnte,E.intIdEtapaSiguiente,
                (CASE WHEN E.intIdEsta <> 25 THEN AV.intIdContr ELSE E.IdContraAnt END) AS intIdContr,
                E.intIdProyPaquete, E.intidetapa, E.intIdProyTarea, E.intIdRuta,
		emp1.varRazCont  as ContratistaAnt,
		E.FechaUltimAvan as FechaAvanAnt,
		 E.numDocTratSup as DocEnvioTS , -- Doc_Ant,
		-- E.varDocAnt as Doc_Ant,
		E.varValo1 as Pintura,
                E.varBulto AS bulto,
                E.varValo2 AS Obs1,
		E.varValo3 AS obs2,
		E.varValo4 AS obs3,
                E.varValo5 AS obs4,
		EST.varDescEsta AS estado,
		E.IdContraAnt,
                E.intIdEsta,
                (E.deciPesoNeto * COUNT(E.intIdEleme)) as TotalPesoNeto, 
                (E.deciPesoBruto * COUNT(E.intIdEleme)) as TotalPesoBruto,
                (E.deciArea * COUNT(E.intIdEleme)) as TotalArea
		FROM elemento as E left join proy_avan as AV on 
		E.intIdEleme = AV.intIdEleme and E.intIdEtapa = AV.intIdEtapa and E.intCantRepro = AV.intNuConta 
                left join  contratista as emp on AV.intIdContr = emp.intIdCont  
                left join etapa as ETAA on ETAA.intIdEtapa=E.intIdEtapa
                left join etapa as ETA on E.intIdEtapaAnte = ETA.intIdEtapa 
                left join etapa as ETS on E.intIdEtapaSiguiente = ETS.intIdEtapa 
                left join proyecto_zona as PZ on E.intIdProyZona = PZ.intIdProyZona 
                left join proyecto_paquetes as PG on E.intIdProyPaquete = PG.intIdProyPaquete 
                left join proyecto_tarea as PT on E.intIdProyTarea = PT.intIdProyTarea 
                left join contratista as emp1 on E.IdContraAnt = emp1.intIdCont 
                left join estado as EST ON E.intIdEsta = EST.intIdEsta
		WHERE E.intIdProy = $intIdProy AND E.intIdTipoProducto = $intIdTipoProducto
		AND (-1 = $intIdProyZona OR  E.intIdProyZona = $intIdProyZona)
		AND (-1 = $intIdProyTarea OR E.intIdProyTarea = $intIdProyTarea)
		AND ETAA.intIdTipoEtap =26
		AND (E.intIdEsta <> 2 AND E.intIdEsta <> 6) AND
                ('TODOS'= '$varValo1' or E.varValo1='$varValo1')
		GROUP BY 
		E.varCodiElemento, E.varDescripcion,  
		E.intRevision, E.intCantRepro, PZ.varDescrip,PT.varDescripTarea,PG.varCodigoPaquete,
		emp.varRazCont, E.deciPrec, ETAA.intIdTipoEtap,
		E.deciPesoNeto, E.deciPesoBruto, E.deciArea, E.deciLong, E.deciAncho, 
		ETA.varDescEtap, ETS.varDescEtap,
		E.varPerfil, E.varModelo, E.varCodVal,E.intIdProyZona, E.intIdProyPaquete,
		E.intIdEtapaAnte,E.intIdEtapaSiguiente,AV.intIdContr,E.intIdProyPaquete, E.intidetapa,
		E.intIdProyTarea,E.intIdRuta,emp1.varRazCont,E.FechaUltimAvan,
		-- E.varDocAnt,
		E.numDocTratSup,
                E.varValo1 ,
                E.varBulto,
                E.varValo2 ,
		E.varValo3 ,
		E.varValo4 ,
                E.varValo5 ,
		EST.varDescEsta ,
		E.IdContraAnt,
                E.deciAreaPintura,
                E.intIdEsta ORDER BY E.varCodiElemento desc");

        return $this->successResponse($listar_bulto);
    }

    /* JS ROTULO */

    public function listar_sistema_pintura(Request $request) {
        $regla = [
            'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Tipo Producto es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_sistema_pintura = DB::select("select distinct elemento.varValo1
                                                from elemento 
                                                inner join etapa on elemento.intIdEtapa=etapa.intIdEtapa
                                                where (elemento.intIdProy=$request->intIdProy or -1=$request->intIdProy) and elemento.intIdTipoProducto=$request->intIdTipoProducto and  
                                                length(elemento.varValo1)>0 and elemento.varValo1<>'null' and etapa.intIdTipoEtap=26
                                                and elemento.intIdEsta <> 2 AND elemento.intIdEsta <> 6");
            return $this->successResponse($listar_sistema_pintura);
        }
    }

    public function crear_pintura(Request $request) {
        DB::beginTransaction();
        try {
            $validar = array('mensaje' => array(), 'mensaje_alternativo' => '','dta' => '');
            $regla = [
                'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
                'intIdTipoProducto.required' => 'EL Campo Tipo Producto es obligatorio',
                'varLotePintura.required' => 'EL Campo lote de Pintura es obligatorio',
                'intIdCabina.required' => 'EL Campo Cabina es obligatorio',
                'dateFechInic.required' => 'EL Campo Fecha Inicio es obligatorio',
                'dateFechFin.required' => 'EL Campo Fecha Inicio es obligatorio',
                'intCantidad.required' => 'EL Campo Cantidad es obligatorio',
                'deciPesoNeto.required' => 'EL Campo Peso Neto es obligatorio',
                'deciAreaPintura.required' => 'EL Campo Area Pintura es obligatorio',
                'deciAreaTotal.required' => 'EL Campo Area Total es obligatorio',
                'acti_usua.required' => 'EL Campo Usuario es obligatorio'];
            $validator = Validator::make($request->all(), [
                        'intIdProy' => 'required|max:255',
                        'intIdTipoProducto' => 'required|max:255',
                        'varLotePintura' => 'required|max:255',
                        'intIdCabina' => 'required|max:255',
                        'dateFechInic' => 'required|max:255',
                        'intCantidad' => 'required|max:255',
                        'deciPesoNeto' => 'required|max:255',
                        'deciAreaPintura' => 'required|max:255',
                        'deciAreaTotal' => 'required|max:255',
                        'acti_usua' => 'required|max:255',
                        'dateFechFin' => 'required|max:255'], $regla);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->successResponse($errors);
            } else {
                date_default_timezone_set('America/Lima'); // CDT
                $lote_pintura = New Pintura;
                $lote_pintura->intIdProy = (int) $request->intIdProy;
                $lote_pintura->intIdCont = (int) $request->intIdCont;
                $lote_pintura->intIdTipoProducto = (int) $request->intIdTipoProducto;
                $lote_pintura->varLotePintura = $request->varLotePintura;
                $lote_pintura->intIdCabina = $request->intIdCabina;
                $lote_pintura->varPintor = trim($request->input('varPintor'), ',');
                $lote_pintura->dateFechInic = $request->dateFechInic;
                $lote_pintura->intCantidad = $request->intCantidad;
                $lote_pintura->deciPesoNeto = (float) $request->deciPesoNeto;
                $lote_pintura->deciAreaPintura = (float) $request->deciAreaPintura;
                $lote_pintura->deciAreaTotal = (float) $request->deciAreaTotal;
                $lote_pintura->intIdEsta = 39;
                $lote_pintura->dateFechFin = $request->dateFechFin;
                $lote_pintura->acti_usua = $request->acti_usua;
                $lote_pintura->varObservacion = $request->varObservacion;
                $lote_pintura->acti_hora = $current_date = date('Y/m/d H:i:s');
                $lote_pintura->save();
                $id_lote = $lote_pintura->intIdLotePintura;
                //dd($id_lote);

                $v_usuario = $request->input('v_usuario');
                $v_intIdproy = (int) $request->input('v_intIdproy'); //
                $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto'); //
                $v_strDeObser = ""; //
                $v_intIdMaqui = ""; //
                $v_strBulto = ""; //
                $v_intIdPeriValo = (int) $request->input('v_intIdPeriValo'); //
                $v_intIdInspe = (int) $request->input('v_intIdInspe'); //
                $v_varValoEtapa = $request->input('v_varValoEtapa'); //
                $v_varCodiTipoEtap = $request->input('v_varCodiTipoEtap');
                $v_boolDesp = (int) $request->input('v_boolDesp');
                $v_tinFlgConforForzosa = (int) $request->input('v_tinFlgConforForzosa');
                $v_strDefecto = trim($request->input('v_strDefecto'), ',');
                $v_strCausa = trim($request->input('v_strCausa'), ',');
                $strEstadoInspe = $request->input('strEstadoInspe');

                $v_intIdDespa = (int) $request->input('v_intIdDespa');
                $deciTotaPesoNeto = (float) $request->input('deciTotaPesoNeto');
                $deciTotaPesoBruto = (float) $request->input('deciTotaPesoBruto');
                $deciTotaArea = (float) $request->input('deciTotaArea');
                $cantidadtotal = (int) $request->input('cantidadtotal');
                $v_intIdAsigEtapProy = (int) $request->input('v_intIdAsigEtapProy');
                $v_informacion = json_decode($request->input('v_informacion'));
                //dd($v_informacion);
                $v_varNumeroGuia = "";
                $v_fech_avan = $current_date = date('Y-m-d H:i:s');
                $v_intIdContr = (int) $request->input('v_intIdContr');
                if ($request->input('v_intIdSuper') == "NaN") {
                    $v_intIdSuper = null;
                } else {
                    $v_intIdSuper = (int) $request->input('v_intIdSuper');
                }
                if ($request->input('v_strDeObser') == '' || $request->input('v_strDeObser') == null) {
                    $v_strDeObser = '';
                } else {
                    $v_strDeObser = $request->input('v_strDeObser');
                }
                if ($request->input('v_intIdMaqui') == "NaN") {
                    $v_intIdMaqui = null;
                } else {
                    $v_intIdMaqui = (int) $request->input('v_intIdMaqui');
                }
                if ($request->input('v_strBulto') == "") {
                    $v_strBulto = '';
                } else {
                    $v_strBulto = $request->input('v_strBulto');
                }
                if ($request->input('v_varNumeroGuia') == "") {
                    $v_varNumeroGuia = '';
                } else {
                    $v_varNumeroGuia = $request->input('v_varNumeroGuia');
                }
                foreach ($v_informacion as $index) {
                    $Pintura = $index->{"Pintura"};
                    $DocEnvioTS = $index->{"DocEnvioTS"};
                    $v_intIdEtapaActual = (int) $index->{"intidetapa"};
                    $v_varCodiElemento = $index->{"varCodiElemento"};
                    $v_nCanti = (int) $index->{'Canti'};
                    $v_intNuConta = (int) $index->{"intCantRepro"}; // incantrepro
                    $tipo_reporte = (int) $index->{"tipo_reporte"};
                    $v_intNuRevis = (int) $index->{"intRevision"};
                    $v_intIdProyZona = (int) $index->{"intIdProyZona"};
                    $v_intIdProyTarea = (int) $index->{"intIdProyTarea"};
                    $v_intIdProyPaquete = (int) $index->{"intIdProyPaquete"};
                    $v_intIdEtapaAnt = (int) $index->{"intIdEtapaAnte"};
                    $v_intIdEtapaSig = (int) $index->{"intIdEtapaSiguiente"};
                    $v_intIdRuta = (int) $index->{"intIdRuta"};
                    $intIdEsta = (int) $index->{"intIdEsta"};
                    if ($index->{"deciPrec"} == 0) {
                        $v_deciPrec = 0;
                    } else {
                        $v_deciPrec = $index->{"deciPrec"};
                    }
                    if (!isset($index->{"varcodelement"})) {

                        $v_Series = '';
                    } else {

                        $v_Series = trim($index->{"varcodelement"}, ',');
                    }
                    $Obs1 = $index->{'Obs1'};
                    $obs2 = $index->{'obs2'};
                    $obs3 = $index->{'obs3'};
                    $obs4 = $index->{'obs4'};
                    /* dd($v_intIdproy, //
                      $v_intIdTipoProducto, //
                      $v_intIdEtapaActual, //
                      $v_varCodiElemento, //
                      $v_nCanti, //
                      $v_fech_avan, //
                      $v_intIdContr, //
                      $v_intNuConta,
                      $v_intNuRevis, //
                      $v_intIdSuper,
                      $v_intIdProyZona, //
                      $v_intIdProyTarea, //
                      $v_intIdProyPaquete, //
                      $v_deciPrec, //
                      $v_intIdEtapaAnt, //
                      $v_intIdEtapaSig, //
                      $v_strDeObser, //
                      $v_intIdMaqui, //
                      $v_Series, //
                      $v_strBulto, //
                      $v_intIdPeriValo, //
                      $v_usuario, //
                      $v_intIdInspe, //
                      $v_intIdRuta, //
                      $v_varValoEtapa, //
                      $v_varCodiTipoEtap, //
                      $v_boolDesp, //
                      $v_intIdAsigEtapProy, //
                      $v_varNumeroGuia, //
                      $intIdEsta,
                      $v_tinFlgConforForzosa,
                      $v_strDefecto,
                      $v_strCausa,
                      $strEstadoInspe,
                      $v_intIdDespa,
                      $DocEnvioTS,
                      $Pintura,
                      $Obs1,
                      $obs2,
                      $obs3,
                      $obs4,
                      $id_lote); */
                    DB::select('CALL sp_avance_P02(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@mensaje1)', array(
                        $v_intIdproy, //
                        $v_intIdTipoProducto, //
                        $v_intIdEtapaActual, //
                        $v_varCodiElemento, //
                        $v_nCanti, //
                        $v_fech_avan, //
                        $v_intIdContr, //
                        $v_intNuConta,
                        $v_intNuRevis, //
                        $v_intIdSuper,
                        $v_intIdProyZona, //
                        $v_intIdProyTarea, //
                        $v_intIdProyPaquete, //
                        $v_deciPrec, //
                        $v_intIdEtapaAnt, //
                        $v_intIdEtapaSig, //
                        $v_strDeObser, //
                        $v_intIdMaqui, //
                        $v_Series, //
                        $v_strBulto, //
                        $v_intIdPeriValo, //
                        $v_usuario, //
                        $v_intIdInspe, //
                        $v_intIdRuta, //
                        $v_varValoEtapa, //
                        $v_varCodiTipoEtap, //
                        $v_boolDesp, //
                        $v_intIdAsigEtapProy, //
                        $v_varNumeroGuia, //
                        $intIdEsta,
                        $v_tinFlgConforForzosa,
                        $v_strDefecto,
                        $v_strCausa,
                        $strEstadoInspe,
                        $v_intIdDespa,
                        $DocEnvioTS,
                        $Pintura,
                        $Obs1,
                        $obs2,
                        $obs3,
                        $obs4,
                        $id_lote
                    ));

                    $results = DB::select('select @mensaje1');

                    if (count($results) == 0) {

                        $results = "";
                    } else {
                        $results = $results[0]->{"@mensaje1"};
                    }
                    //dd($results);
                    $validar['mensaje'][] .= $results;
                }
                //dd(count($validar));
                if (count($validar) > 0) {
                    //dd($validar['mensaje'][0]);
                    if ($validar['mensaje'][0] === "" || $validar['mensaje'][0] === null) {

                        $validar['mensaje_alternativo'] = 'sin error';
                        DB::commit();
                        /*dd($v_intIdproy,
                            $v_intIdTipoProducto,
                            $id_lote,
                            7,
                            $v_usuario,
                            $v_fech_avan);*/
                        /*DB::select('CALL sp_LotePintura_V01 (?,?,?,?,?,?)', array(
                            $v_intIdproy,
                            $v_intIdTipoProducto,
                            $id_lote,
                            7,
                            $v_usuario,
                            $v_fech_avan//$v_fecha,
                        ));*/
                        
                        //dd($validar);
                        $listar_pintura = DB::select("select concat('LP',LPAD(tab_pint.intIdLotePintura,6,'0')) as Codigo
                                            from tab_pint 
                                            where tab_pint.intIdLotePintura=$id_lote");
                        
                        $validar['data'] =$listar_pintura[0]->{'Codigo'};
                        return $this->successResponse($validar);
                    } else {

                        throw new \Exception('error forzoso');
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $validar['mensaje_alternativo'] = 'error';
            $validar['mensaje'][] .= 'No se registro el Avance';
            return $this->successResponse($validar);
        }
    }
    public function lote_pintura_serie(){
        $id_guia = DB::select("select concat('LP',LPAD(ifnull(max(tab_pint.intIdLotePintura),0)+1,6,'0'))   as Codigo from tab_pint");
        return $this->successResponse($id_guia[0]->{'Codigo'});
        
    }

    public function listar_pintura(Request $request) {
        $regla = [
            'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Tipo Producto es obligatorio',];
        $validator = Validator::make($request->all(), [
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            if ($request->fecha_inicio !== null || $request->fecha_fin !== null) {
                $listar_pintura = DB::select("select concat('LP',LPAD(tab_pint.intIdLotePintura,6,'0')) as Codigo,tab_pint.*,proyecto.varCodiProy,tipo_producto.varDescTipoProd,estado.varDescEsta,tab_cabina.varCabina
                                            from tab_pint 
                                            inner join proyecto on tab_pint.intIdProy=proyecto.intIdProy
                                            inner join tipo_producto on tab_pint.intIdTipoProducto=tipo_producto.intIdTipoProducto
                                            inner join estado on tab_pint.intIdEsta=estado.intIdEsta
                                            inner join tab_cabina on tab_pint.intIdCabina=tab_cabina.intIdCabina
                                            where tab_pint.intIdProy=$request->intIdProy and tab_pint.intIdTipoProducto=$request->intIdTipoProducto and tab_pint.dateFechInic between '$request->fecha_inicio' and  '$request->fecha_fin'");
            } else {
                $listar_pintura = DB::select("select concat('LP',LPAD(tab_pint.intIdLotePintura,6,'0')) as Codigo,tab_pint.*,proyecto.varCodiProy,tipo_producto.varDescTipoProd,estado.varDescEsta,tab_cabina.varCabina
                                            from tab_pint 
                                            inner join proyecto on tab_pint.intIdProy=proyecto.intIdProy
                                            inner join tipo_producto on tab_pint.intIdTipoProducto=tipo_producto.intIdTipoProducto
                                            inner join estado on tab_pint.intIdEsta=estado.intIdEsta
                                            inner join tab_cabina on tab_pint.intIdCabina=tab_cabina.intIdCabina
                                            where tab_pint.intIdProy=$request->intIdProy and tab_pint.intIdTipoProducto=$request->intIdTipoProducto");
            }
            return $this->successResponse($listar_pintura);
        }
    }

    public function editar_pintura(Request $request) {

        DB::beginTransaction();
        try {
            $validar = array('mensaje' => array(), 'mensaje_alternativo' => '');
            $regla = [
                'intIdLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio',
                'deciPesoNeto.required' => 'EL Campo Peso Neto es obligatorio',
                'deciAreaPintura.required' => 'EL Campo Area Pintura es obligatorio',
                'deciAreaTotal.required' => 'EL Campo Area Total es obligatorio',
                'intCantidad.required' => 'EL Campo Area Total es obligatorio',
                'usua_modi.required' => 'EL Campo Usuario es obligatorio'];
            $validator = Validator::make($request->all(), [
                        'intIdLotePintura' => 'required|max:255',
                        'deciPesoNeto' => 'required|max:255',
                        'deciAreaPintura' => 'required|max:255',
                        'deciAreaTotal' => 'required|max:255',
                        'intCantidad' => 'required|max:255',
                        'usua_modi' => 'required|max:255'], $regla);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return $this->successResponse($errors);
            } else {
                date_default_timezone_set('America/Lima'); // CDT

                Pintura::where('intIdLotePintura', '=', $request->intIdLotePintura)
                        ->update(['deciPesoNeto' => $request->deciPesoNeto, 'deciAreaPintura' => $request->deciAreaPintura, 'deciAreaTotal' => $request->deciAreaTotal, 'varObservacion' => $request->varObservacion, 'intCantidad' => $request->intCantidad, 'usua_modi' => $request->usua_modi, 'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                $v_usuario = $request->input('v_usuario');
                $v_intIdproy = (int) $request->input('v_intIdproy'); //
                $v_intIdTipoProducto = (int) $request->input('v_intIdTipoProducto'); //
                $v_strDeObser = ""; //
                $v_intIdMaqui = ""; //
                $v_strBulto = ""; //
                $v_intIdPeriValo = (int) $request->input('v_intIdPeriValo'); //
                $v_intIdInspe = (int) $request->input('v_intIdInspe'); //
                $v_varValoEtapa = $request->input('v_varValoEtapa'); //
                $v_varCodiTipoEtap = $request->input('v_varCodiTipoEtap');
                $v_boolDesp = (int) $request->input('v_boolDesp');
                $v_tinFlgConforForzosa = (int) $request->input('v_tinFlgConforForzosa');
                $v_strDefecto = trim($request->input('v_strDefecto'), ',');
                $v_strCausa = trim($request->input('v_strCausa'), ',');
                $strEstadoInspe = $request->input('strEstadoInspe');

                $v_intIdDespa = (int) $request->input('v_intIdDespa');
                $deciTotaPesoNeto = (float) $request->input('deciTotaPesoNeto');
                $deciTotaPesoBruto = (float) $request->input('deciTotaPesoBruto');
                $deciTotaArea = (float) $request->input('deciTotaArea');
                $cantidadtotal = (int) $request->input('cantidadtotal');
                $v_intIdAsigEtapProy = (int) $request->input('v_intIdAsigEtapProy');
                $v_informacion = json_decode($request->input('v_informacion'));
                //dd($v_informacion);
                $v_varNumeroGuia = "";
                $v_fech_avan = $current_date = date('Y-m-d H:i:s');
                $v_intIdContr = (int) $request->input('v_intIdContr');
                if ($request->input('v_intIdSuper') == "NaN") {
                    $v_intIdSuper = null;
                } else {
                    $v_intIdSuper = (int) $request->input('v_intIdSuper');
                }
                if ($request->input('v_strDeObser') == '' || $request->input('v_strDeObser') == null) {
                    $v_strDeObser = '';
                } else {
                    $v_strDeObser = $request->input('v_strDeObser');
                }
                if ($request->input('v_intIdMaqui') == "NaN") {
                    $v_intIdMaqui = null;
                } else {
                    $v_intIdMaqui = (int) $request->input('v_intIdMaqui');
                }
                if ($request->input('v_strBulto') == "") {
                    $v_strBulto = '';
                } else {
                    $v_strBulto = $request->input('v_strBulto');
                }
                if ($request->input('v_varNumeroGuia') == "") {
                    $v_varNumeroGuia = '';
                } else {
                    $v_varNumeroGuia = $request->input('v_varNumeroGuia');
                }
                foreach ($v_informacion as $index) {
                    $Pintura = $index->{"Pintura"};
                    $DocEnvioTS = $index->{"DocEnvioTS"};
                    $v_intIdEtapaActual = (int) $index->{"intidetapa"};
                    $v_varCodiElemento = $index->{"varCodiElemento"};
                    $v_nCanti = (int) $index->{'Canti'};
                    $v_intNuConta = (int) $index->{"intCantRepro"}; // incantrepro
                    $tipo_reporte = (int) $index->{"tipo_reporte"};
                    $v_intNuRevis = (int) $index->{"intRevision"};
                    $v_intIdProyZona = (int) $index->{"intIdProyZona"};
                    $v_intIdProyTarea = (int) $index->{"intIdProyTarea"};
                    $v_intIdProyPaquete = (int) $index->{"intIdProyPaquete"};
                    $v_intIdEtapaAnt = (int) $index->{"intIdEtapaAnte"};
                    $v_intIdEtapaSig = (int) $index->{"intIdEtapaSiguiente"};
                    $v_intIdRuta = (int) $index->{"intIdRuta"};
                    $intIdEsta = (int) $index->{"intIdEsta"};
                    if ($index->{"deciPrec"} == 0) {
                        $v_deciPrec = 0;
                    } else {
                        $v_deciPrec = $index->{"deciPrec"};
                    }
                    if (!isset($index->{"varcodelement"})) {

                        $v_Series = '';
                    } else {

                        $v_Series = trim($index->{"varcodelement"}, ',');
                    }
                    $Obs1 = $index->{'Obs1'};
                    $obs2 = $index->{'obs2'};
                    $obs3 = $index->{'obs3'};
                    $obs4 = $index->{'obs4'};
                    DB::select('CALL sp_avance_P02(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@mensaje1)', array(
                        $v_intIdproy, //
                        $v_intIdTipoProducto, //
                        $v_intIdEtapaActual, //
                        $v_varCodiElemento, //
                        $v_nCanti, //
                        $v_fech_avan, //
                        $v_intIdContr, //
                        $v_intNuConta,
                        $v_intNuRevis, //
                        $v_intIdSuper,
                        $v_intIdProyZona, //
                        $v_intIdProyTarea, //
                        $v_intIdProyPaquete, //
                        $v_deciPrec, //
                        $v_intIdEtapaAnt, //
                        $v_intIdEtapaSig, //
                        $v_strDeObser, //
                        $v_intIdMaqui, //
                        $v_Series, //
                        $v_strBulto, //
                        $v_intIdPeriValo, //
                        $v_usuario, //
                        $v_intIdInspe, //
                        $v_intIdRuta, //
                        $v_varValoEtapa, //
                        $v_varCodiTipoEtap, //
                        $v_boolDesp, //
                        $v_intIdAsigEtapProy, //
                        $v_varNumeroGuia, //
                        $intIdEsta,
                        $v_tinFlgConforForzosa,
                        $v_strDefecto,
                        $v_strCausa,
                        $strEstadoInspe,
                        $v_intIdDespa,
                        $DocEnvioTS,
                        $Pintura,
                        $Obs1,
                        $obs2,
                        $obs3,
                        $obs4,
                        $request->intIdLotePintura
                    ));

                    $results = DB::select('select @mensaje1');

                    if (count($results) == 0) {

                        $results = "";
                    } else {
                        $results = $results[0]->{"@mensaje1"};
                    }

                    $validar['mensaje'][] .= $results;
                    //dd($validar);
                }
                //dd(count($validar));
                if (count($validar) > 0) {
                    //dd($validar['mensaje'][0]);
                    if ($validar['mensaje'][0] === "" || $validar['mensaje'][0] === null) {
                        $validar['mensaje_alternativo'] = 'sin error';
                        DB::select('CALL sp_LotePintura_V01 (?,?,?,?,?,?)', array(
                            $v_intIdproy,
                            $v_intIdTipoProducto,
                            $request->intIdLotePintura,
                            7,
                            $v_usuario,
                            $v_fech_avan//$v_fecha,
                        ));
                        DB::commit();
                        //dd($validar);
                        return $this->successResponse($validar);
                    } else {
                        throw new \Exception('error forzoso');
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $validar['mensaje_alternativo'] = 'error';
            $validar['mensaje'][] .= 'No se registro el Avance';
            return $this->successResponse($validar);
        }
    }

    public function list_detalle_pintura(Request $request) {
        $regla = [
            'intIdLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdLotePintura' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_detalle = DB::select("select count(e.intIdLotePintura) as cant,e.varCodiElemento,e.varDescripcion,pt.varDescripTarea,pz.varDescrip,e.deciPesoNeto,e.deciPesoBruto,e.deciArea
                                from elemento e 
                                inner join proyecto_zona pz on e.intIdProyZona=pz.intIdProyZona
                                inner join tab_pint tp on tp.intIdLotePintura=e.intIdLotePintura
                                inner join proyecto_tarea pt on pt.intIdProyTarea=e.intIdProyTarea
                                where tp.intIdLotePintura=$request->intIdLotePintura
                                group by e.varCodiElemento,e.varCodiElemento,e.varDescripcion,pt.varDescripTarea,pz.varDescrip,e.deciPesoNeto,e.deciPesoBruto,e.deciArea");
            return $this->successResponse($listar_detalle);
        }
    }

    public function list_tab_pintura_id(Request $request) {
        $regla = [
            'intIdLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdLotePintura' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar = DB::select("SELECT tab_pint.*,proyecto.varCodiProy,tipo_producto.varDescTipoProd FROM tab_pint 
                                inner join proyecto on tab_pint.intIdProy=proyecto.intIdProy
                                inner join tipo_producto on tab_pint.intIdTipoProducto=tipo_producto.intIdTipoProducto
                                where tab_pint.intIdLotePintura=$request->intIdLotePintura");
            return $this->successResponse($listar);
        }
    }

    public function combo_pintura(Request $request) {
        $regla = [
            'varLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'varLotePintura' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_pintura = DB::select("SELECT concat('LP',LPAD(intIdLotePintura,6,'0')) as Codigo,intIdLotePintura   FROM mimco.tab_pint where intIdEsta<>40  and varLotePintura='$request->varLotePintura'");
            return $this->successResponse($listar_pintura);
        }
    }

    public function listar_pintores(Request $request) {
        $regla = [
            'colaboradores.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'colaboradores' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_colaboradores = DB::select("select * from colaborador where intIdColaborador in ($request->colaboradores) ");
            return $this->successResponse($listar_colaboradores);
        }
    }

    public function combo_cabina(Request $request) {
        $regla = [
            'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Proucto es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $list_cabinas = DB::select("select distinct tab_cabina.intIdCabina,tab_cabina.varCabina 
                                        from tab_pint inner join tab_cabina on tab_pint.intIdCabina=tab_cabina.intIdCabina 
                                        where (tab_pint.intIdProy=$request->intIdProy -1=$request->intIdProy) and tab_pint.intIdTipoProducto=$request->intIdTipoProducto and 
                                        tab_cabina.intIdEsta<>14 and tab_pint.intIdEsta<>40");
            return $this->successResponse($list_cabinas);
        }
    }

    public function combo_contratista() {
        $listar_contratistas = DB::select("select distinct contratista.intIdCont,contratista.varRazCont from tab_pint inner join contratista on tab_pint.intIdCont=contratista.intIdCont");
        return $this->successResponse($listar_contratistas);
    }

    public function cabecera_seguimiento(Request $request) {
        $regla = [
            'intIdLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdLotePintura' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_cabecera = DB::select("select concat('LP',LPAD(tab_pint.intIdLotePintura,6,'0')) as Codigo,tab_pint.*,proyecto.varCodiProy,contratista.varRazCont,tab_cabina.varCabina,tipo_producto.varDescTipoProd
                                            from tab_pint 
                                            inner join proyecto on tab_pint.intIdProy=proyecto.intIdProy
                                            inner join contratista on tab_pint.intIdCont=contratista.intIdCont
                                            inner join tab_cabina on tab_pint.intIdCabina=tab_cabina.intIdCabina
                                            inner join tipo_producto on tab_pint.intIdTipoProducto=tipo_producto.intIdTipoProducto
                                            where tab_pint.intIdLotePintura=$request->intIdLotePintura");
            return $this->successResponse($listar_cabecera);
        }
    }

    public function detalle_pintura(Request $request) {
        $regla = [
            'intIdLotePintura.required' => 'EL Campo Lote de Pintura es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdLotePintura' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_detalle = DB::select("select elemento.varCodiElemento as Codigo,elemento.varDescripcion,count(elemento.varCodiElemento) as Cantidad,
                                        (count(elemento.varCodiElemento)*elemento.deciPesoNeto) as TotalPesoNeto,(count(elemento.varCodiElemento)*elemento.deciPesoBruto) as TotalPesoBruto,
                                        eac.varDescEtap as EtapaActual,esg.varDescEtap as EtapaSiguiente
                                        from elemento 
                                        inner join etapa as eac on elemento.intIdEtapa=eac.intIdEtapa
                                        inner join etapa as esg on elemento.intIdEtapaSiguiente=esg.intIdEtapa
                                        where elemento.intIdLotePintura=$request->intIdLotePintura
                                        group by elemento.varCodiElemento,elemento.varDescripcion,elemento.deciPesoNeto,elemento.deciPesoBruto,eac.varDescEtap,esg.varDescEtap");
            return $this->successResponse($listar_detalle);
        }
    }

}
