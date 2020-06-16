<?php

namespace App\Http\Controllers;

use DB;
use Config;
use App\Galvanizado;
use App\DetaGalva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GalvanizadoController extends Controller {

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

    public function crear_galvanizado(Request $request) {
        $regla = [
            'varTipoOrden.required' => 'EL Campo Tipo Orden es obligatorio',
            'varOrdenServi.required' => 'EL Campo Orden Servicio es obligatorio',
            'varRazoSoci.required' => 'EL Campo Razon Social es obligatorio',
            'varDescripcion.required' => 'EL Campo Descripción es obligatorio',
            'intCantTota.required' => 'EL Campo Cantidad Total es obligatorio',
            'deciPesoInge.required' => 'EL Campo Peso Ingenieria es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario es obligatorio',
            'deciPesoBruto.required' => 'EL Campo Peso Bruto es obligatorio',];
        $validator = Validator::make($request->all(), [
                    'varOrdenServi' => 'required|max:255',
                    'varTipoOrden' => 'required|max:255',
                    'varRazoSoci' => 'required|max:255',
                    'varDescripcion' => 'required|max:255',
                    'intCantTota' => 'required|max:255',
                    'deciPesoInge' => 'required|max:255',
                    'deciPesoBruto' => 'required|max:255',
                    'acti_usua' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $this->validate($request, $regla);
            date_default_timezone_set('America/Lima'); // CDT
            $galvanizado = new Galvanizado;
            $galvanizado->varTipoOrden = $request->varTipoOrden;
            $galvanizado->varRazoSoci = $request->varRazoSoci;
            $galvanizado->varOrdenServi = $request->varOrdenServi;
            $galvanizado->varDescripcion = $request->varDescripcion;
            $galvanizado->intCantTota = (int) $request->intCantTota;
            $galvanizado->deciPesoInge = (float) $request->deciPesoInge;
            $galvanizado->deciPesoBruto = (float) $request->deciPesoBruto;
            $galvanizado->intIdEsta = 32;
            $galvanizado->acti_usua = $request->acti_usua;
            $galvanizado->acti_hora = $current_date = date('Y/m/d H:i:s');
            $galvanizado->save();
            $mensaje = "";
            return $this->successResponse($mensaje);
        }
    }

    public function listar_galvanizado(Request $request) {
        $regla = [
            'varTipoOrden.required' => 'EL Campo Tipo Orden es obligatorio',];
        $validator = Validator::make($request->all(), [
                    'varTipoOrden' => 'required|max:255',
                        ], $regla);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $this->validate($request, $regla);
            $varTipoOrden = $request->varTipoOrden;
            $varOrdenServi = $request->varOrdenServi;
            $intIdTipoProducto = $request->intIdTipoProducto;
            $dateFechIngr = $request->dateFechIngr;
            $dateFechSali = $request->dateFechSali;
            $new_data = [];
            $peso_galvanizado = 0;
            $peso_negro = 0;
            if ($varTipoOrden === 'TERCERO') {
                $totl_zinc=0;
                if ($dateFechIngr != '' || $dateFechIngr != null) {

                    $results = DB::select("SELECT tab_galv.*,cast(tab_galv.varPorcZinc as decimal(18,3)) as porcentajezinc,estado.varDescEsta from tab_galv inner join estado on tab_galv.intIdEsta=estado.intIdEsta where varTipoOrden = '$varTipoOrden' and (varOrdenServi = '$varOrdenServi' or  '-1'='$varOrdenServi') and dateFechIngr between '$dateFechIngr' and '$dateFechSali' order by  intIdGalva desc");
                    if(count($results)>0){ 
                        for ($i = 0; count($results) > $i; $i++) {
                            $peso_galvanizado += $results[$i]->{'deciConsumoZinc'};
                            $peso_negro += $results[$i]->{'deciPesoNegro'};
                        }
                        //dd($peso_galvanizado,$peso_negro);
                        if($peso_galvanizado === 0.0 && $peso_negro===0.0){ 

                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }else{ 
                            $totl_zinc = ($peso_galvanizado / $peso_negro) * 100;
                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }
                        
                    }else{ 
                        return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                    }
                } else {
                    $results = DB::select("SELECT tab_galv.*,cast(tab_galv.varPorcZinc as decimal(18,3)) as porcentajezinc,estado.varDescEsta from tab_galv inner join estado on tab_galv.intIdEsta=estado.intIdEsta where varTipoOrden = '$varTipoOrden' and (varOrdenServi = '$varOrdenServi' or  '-1'='$varOrdenServi') order by  intIdGalva desc");
                    if(count($results)>0){ 
                        for ($i = 0; count($results) > $i; $i++) {
                            $peso_galvanizado += $results[$i]->{'deciConsumoZinc'};
                            $peso_negro += $results[$i]->{'deciPesoNegro'};
                        }
                        //dd($peso_galvanizado,$peso_negro);
                        if($peso_galvanizado === 0.0 && $peso_negro===0.0){ 

                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }else{ 
                            $totl_zinc = ($peso_galvanizado / $peso_negro) * 100;
                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }
                        
                    }else{ 
                        return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                    }
                    
                }
            } else {
                $totl_zinc=0;
                
                if ($dateFechIngr != '' || $dateFechIngr != null) {
                    
                    $results = DB::select("select tab_galv.*,cast(tab_galv.varPorcZinc as decimal(18,3)) as porcentajezinc,estado.varDescEsta,proyecto.varCodiProy,unidad_negocio.varDescripcion as unidad_negocio,tipo_producto.varDescTipoProd
                    from tab_galv 
                    inner join tipo_producto on tab_galv.intIdTipoProducto=tipo_producto.intIdTipoProducto
                    inner join unidad_negocio on unidad_negocio.intIdSql=tab_galv.intIdUniNego
                    inner join estado on tab_galv.intIdEsta=estado.intIdEsta  
                    inner join proyecto on tab_galv.intIdProy=proyecto.intIdProy  where tab_galv.varTipoOrden = '$varTipoOrden' and (tab_galv.intIdProy = $varOrdenServi or  '-1'='$varOrdenServi') and tab_galv.intIdTipoProducto = $intIdTipoProducto and tab_galv.dateFechIngr between '$dateFechIngr' and '$dateFechSali' order by  tab_galv.intIdGalva desc");
                    
                    if(count($results)>0){ 
                        for ($i = 0; count($results) > $i; $i++) {
                            $peso_galvanizado += $results[$i]->{'deciConsumoZinc'};
                            $peso_negro += $results[$i]->{'deciPesoNegro'};
                        }
                        //dd($peso_galvanizado,$peso_negro);
                        if($peso_galvanizado === 0.0 && $peso_negro===0.0){ 

                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }else{ 
                            $totl_zinc = ($peso_galvanizado / $peso_negro) * 100;
                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }
                        
                    }else{ 
                        return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                    }
                    
                    
                } else {
                    

                    $results = DB::select("select tab_galv.*,cast(tab_galv.varPorcZinc as decimal(18,3)) as porcentajezinc,estado.varDescEsta,proyecto.varCodiProy,unidad_negocio.varDescripcion as unidad_negocio,tipo_producto.varDescTipoProd
                    from tab_galv 
                    inner join tipo_producto on tab_galv.intIdTipoProducto=tipo_producto.intIdTipoProducto
                    inner join unidad_negocio on unidad_negocio.intIdSql=tab_galv.intIdUniNego
                    inner join estado on tab_galv.intIdEsta=estado.intIdEsta  
                    inner join proyecto on tab_galv.intIdProy=proyecto.intIdProy  where tab_galv.varTipoOrden = '$varTipoOrden' and (tab_galv.intIdProy = $varOrdenServi or  '-1'='$varOrdenServi') and tab_galv.intIdTipoProducto = $intIdTipoProducto order by  tab_galv.intIdGalva desc");
                    

                    if(count($results)>0){ 
                        for ($i = 0; count($results) > $i; $i++) {
                            $peso_galvanizado += $results[$i]->{'deciConsumoZinc'};
                            $peso_negro += $results[$i]->{'deciPesoNegro'};
                        }
                        //dd($peso_galvanizado,$peso_negro);
                        if($peso_galvanizado === 0.0 && $peso_negro===0.0){ 

                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }else{ 
                            $totl_zinc = ($peso_galvanizado / $peso_negro) * 100;
                            return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                        }
                        
                    }else{ 
                        return response()->json(['data' => $results, 'zinc' => $totl_zinc]);
                    }
                }
            }
        }
    }

    public function crear_detalle(Request $request) {
        $regla = [
            'intIdGalva.required' => 'EL Campo Id Galvanizado es obligatorio',
            'intIdPeriValo.required' => 'EL Campo Periodo de Valorizacion es obligatorio',
            'intGanchera.required' => 'EL Campo Cantidad de Ganchera es obligatorio',
            'varTurno.required' => 'EL Campo Turno es obligatorio',
            'varHoraEntr.required' => 'EL Campo Hora de Entrada es obligatorio',
            'intCantidad.required' => 'EL Campo Cantidad es obligatorio',
            'varTipoMate.required' => 'EL Campo Tipo Material Bruto es obligatorio',
            'dateFechInic.required' => 'EL Campo Fecha Ingreso es obligatorio',
            'varTipoGalv.required' => 'EL Campo Tipo Galvanizado es obligatorio',
            'acti_usua.required' => 'EL Campo Usuario obligatorio',];
        $validator = Validator::make($request->all(), [
                    'intIdGalva' => 'required|max:255',
                    'intIdPeriValo' => 'required|max:255',
                    'intGanchera' => 'required|max:255',
                    'varTurno' => 'required|max:255',
                    'varHoraEntr' => 'required|max:255',
                    'intCantidad' => 'required|max:255',
                    'varTipoMate' => 'required|max:255',
                    'dateFechInic' => 'required|max:255',
                    'varTipoGalv' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            if ($request->varTipoGalv === "GALVANIZADO") {
                $terminado = "";
                if ($request->varHoraSali !== "" && $request->deciPesoNegro !== "" && $request->deciPesoGalv !== "") {
                    
                }

                //dd((float) $request->deciConsumoZinc,$request->varPorcZinc);
                date_default_timezone_set('America/Lima'); // CDT
                $galvanizado = new DetaGalva;
                $galvanizado->intIdGalva = (int) $request->intIdGalva;
                $galvanizado->intIdPeriValo = (int) $request->intIdPeriValo;
                $galvanizado->intGanchera = (int) $request->intGanchera;
                $galvanizado->varTurno = $request->varTurno;
                $galvanizado->varHoraEntr = $request->varHoraEntr;
                $galvanizado->varHoraSali = $request->varHoraSali;
                $galvanizado->intCantidad = (int) $request->intCantidad;
                $galvanizado->varTipoMate = $request->varTipoMate;
                $galvanizado->deciPesoNegro = (float) $request->deciPesoNegro;
                $galvanizado->deciPesoGalv = (float) $request->deciPesoGalv;
                $galvanizado->deciConsumoZinc = $request->deciConsumoZinc;
                if ($request->varPorcZinc === 'NaN') {
                    $varporzinc = 0;
                    $galvanizado->varPorcZinc = 0;
                } else {
                    $varporzinc = $request->varPorcZinc;
                    $galvanizado->varPorcZinc = $request->varPorcZinc;
                }
                $galvanizado->dateFechInic = $request->dateFechInic;
                $galvanizado->varTipoGalv = $request->varTipoGalv;
                //dd($request->dateFechSali);
                if ($request->dateFechSali === "") {
                    
                } else {
                    $galvanizado->dateFechSali = $request->dateFechSali;
                }
                $galvanizado->acti_usua = $request->acti_usua;
                $galvanizado->acti_hora = $current_date = date('Y/m/d H:i:s');
                if ($request->varHoraSali !== "" && $request->deciPesoNegro !== "" && $request->deciPesoGalv !== "") {
                    $terminado = "si";
                    $galvanizado->intIdEsta = 36;
                } else {
                    $terminado = "no";
                    $galvanizado->intIdEsta = 35;
                }
                if ($terminado === "si") {
                    
                    /*procedemos a cambiar el estado del detalle galvanizado */
                    $galvanizado->intIdEstaInsp = 41;
                }else{
                    $galvanizado->intIdEstaInsp = 0;
                }
                $galvanizado->save();

                $total = DB::select("select sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end) as PesoNegro,sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoGalv else 0.0 end) as PesoGalvanizado , sum(deciConsumoZinc)as  deciConsumoZinc,  ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) as varPorcZinc from deta_galv where intIdGalva=$request->intIdGalva");
                $fechaini = DB::select("select dateFechInic from deta_galv 
                                    inner join (select min(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                $fechafin = DB::select("select dateFechSali from deta_galv 
                                    inner join (select max(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                $cantidad_total = DB::select("select intCantTota from tab_galv where intIdGalva=$request->intIdGalva");
                $cantidad_det = DB::select("select sum(intCantidad) as cantidad from deta_galv where intIdGalva=$request->intIdGalva");
                $esta_term = DB::select("select  intIdEsta as estado from deta_galv where intIdGalva=$request->intIdGalva and intIdEsta = 35");
                
                if ((int) $cantidad_total[0]->intCantTota === (int) $cantidad_det[0]->cantidad) {
                    $estado_cabecera = 38;
                    if (count($esta_term) > 0) {
                        Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                    'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                    'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                    'varPorcZinc' => $total[0]->varPorcZinc,
                                    'dateFechIngr' => $fechaini[0]->dateFechInic,
                                    'dateFechSali' => $fechafin[0]->dateFechSali,
                                    'intIdEsta' => $estado_cabecera,
                                    'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                    } else {

                        Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                    'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                    'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                    'varPorcZinc' => $total[0]->varPorcZinc,
                                    'dateFechIngr' => $fechaini[0]->dateFechInic,
                                    'dateFechSali' => $fechafin[0]->dateFechSali,
                                    'intIdEsta' => 34,
                                    'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                    }
                } else {
                    $estado_cabecera = 33;
                    Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                            ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                'varPorcZinc' => $total[0]->varPorcZinc,
                                'dateFechIngr' => $fechaini[0]->dateFechInic,
                                'dateFechSali' => $fechafin[0]->dateFechSali,
                                'intIdEsta' => $estado_cabecera,
                                'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                }
                $mensaje = "";
                return $this->successResponse($mensaje);
            } else {
                date_default_timezone_set('America/Lima'); // CDT
                $galvanizado = new DetaGalva;
                $galvanizado->intIdGalva = (int) $request->intIdGalva;
                $galvanizado->intIdPeriValo = (int) $request->intIdPeriValo;
                $galvanizado->intGanchera = (int) $request->intGanchera;
                $galvanizado->varTurno = $request->varTurno;
                $galvanizado->varHoraEntr = $request->varHoraEntr;
                $galvanizado->varHoraSali = $request->varHoraSali;
                $galvanizado->intCantidad = (int) $request->intCantidad;
                $galvanizado->varTipoMate = $request->varTipoMate;
                $galvanizado->deciPesoNegro = (float) $request->deciPesoNegro;
                $galvanizado->deciPesoGalv = (float) $request->deciPesoGalv;
                $galvanizado->deciConsumoZinc = $request->deciConsumoZinc;
                $galvanizado->varPorcZinc = $request->varPorcZinc;
                $galvanizado->dateFechInic = $request->dateFechInic;
                $galvanizado->varTipoGalv = $request->varTipoGalv;
                //dd($request->dateFechSali);
                if ($request->dateFechSali === "") {
                    
                } else {
                    $galvanizado->dateFechSali = $request->dateFechSali;
                }
                $galvanizado->acti_usua = $request->acti_usua;
                $galvanizado->acti_hora = $current_date = date('Y/m/d H:i:s');
                if ($request->varHoraSali !== "" && $request->deciPesoNegro !== "" && $request->deciPesoGalv !== "") {
                    $galvanizado->intIdEsta = 36;
                    $galvanizado->intIdEstaInsp = 41;
                } else {
                    $galvanizado->intIdEsta = 35;
                    $galvanizado->intIdEstaInsp = 41;
                }
                $galvanizado->save();

                $total = DB::select("select sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end) as PesoNegro,sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoGalv else 0.0 end) as PesoGalvanizado , sum(deciConsumoZinc)as  deciConsumoZinc,  ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) as varPorcZinc from deta_galv where intIdGalva=$request->intIdGalva");
                
                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                        ->update([
                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                            'varPorcZinc' => $total[0]->varPorcZinc]);
                $mensaje = "";
                return $this->successResponse($mensaje);
            }
        }
    }

    public function actualizar_detalle_galvanizado(Request $request) {
        $regla = [
            'intIdDetaGalv.required' => 'El Campo Id Galvanizado es obligatorio',
            'intIdGalva.required' => 'El Campo Id Galvanizado es obligatorio',
            'cantidad_total.required' => 'El Campo Cantidad Total es obligatorio',
            'intGanchera.required' => 'El Campo Cantidad de Ganchera es obligatorio',
            'varTurno.required' => 'El Campo Turno es obligatorio',
            'varHoraEntr.required' => 'El Campo Hora de Entrada es obligatorio',
            'intCantidad.required' => 'El Campo Cantidad es obligatorio',
            'varTipoMate.required' => 'El Campo Tipo Material Bruto es obligatorio',
            'dateFechInic.required' => 'El Campo Fecha Ingreso es obligatorio',
            'usua_modi.required' => 'El Campo Usuario obligatorio',
            'varTipoGalv.required' => 'EL Campo Tipo Galvanizado es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdDetaGalv' => 'required|max:255',
                    'intIdGalva' => 'required|max:255',
                    'intGanchera' => 'required|max:255',
                    'varTurno' => 'required|max:255',
                    'varHoraEntr' => 'required|max:255',
                    'intCantidad' => 'required|max:255',
                    'varTipoMate' => 'required|max:255',
                    'dateFechInic' => 'required|max:255',
                    'cantidad_total' => 'required|max:255',
                    'usua_modi' => 'required|max:255',
                    'varTipoGalv' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            if ($request->varTipoGalv === "GALVANIZADO") {
                date_default_timezone_set('America/Lima'); // CDT
                if ($request->varHoraSali !== "" && $request->deciPesoNegro !== "" && $request->deciPesoGalv !== "") {
                    $id_estado = 36;
                } else {
                    $id_estado = 35;
                }
                $vali_cant = DB::select(" select (case when varTipoGalv='GALVANIZADO' THEN sum(intCantidad) END  )  cantidad from deta_galv   where intIdGalva=$request->intIdGalva and intIdDetaGalv != $request->intIdDetaGalv");
                /*realizamos una operaciòn matematica */
                if ($request->cantidad_total === $vali_cant[0]->cantidad) {
                    $suma_cant_vali = (int) $vali_cant[0]->cantidad;
                } else {
                    $suma_cant_vali = (int) $request->intCantidad + (int) $vali_cant[0]->cantidad;
                }
                /*validamos si es que la cantidad regisrtada registrada con la cantidad que tiene el detalle supera no se debe registrar*/
                if ($suma_cant_vali > (int) $request->cantidad_total) {


                    $respuesta = "LA SUMA DE LAS CANTIDAD INGRESADAS $suma_cant_vali EXCEDE A LA CANTIDAD PERMITIDA $request->cantidad_total";
                    return $this->successResponse($respuesta);
                } else {
                    $esta_term_elem = DB::select("select  intIdEsta as estado from deta_galv where intIdGalva=$request->intIdGalva and intIdDetaGalv =$request->intIdDetaGalv");
                    $estado_inspeccion = DB::select("select intIdEsta FROM mimco.insp_galv where intIdDetaGalv=$request->intIdDetaGalv");
                    /*si no se encuentra todavia en inspecciòn se podra editar*/
                    if (count($estado_inspeccion) === 0) {
                        if ($esta_term_elem[0]->{'estado'} === 36 || $esta_term_elem[0]->{'estado'} === 37) {
                            DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intGanchera' => (int) $request->intGanchera,
                                        'varTurno' => $request->varTurno,
                                        'varHoraEntr' => $request->varHoraEntr,
                                        'varHoraSali' => $request->varHoraSali,
                                        'intCantidad' => $request->intCantidad,
                                        'varTipoMate' => $request->varTipoMate,
                                        'deciPesoNegro' => (float) $request->deciPesoNegro,
                                        'deciPesoGalv' => (float) $request->deciPesoGalv,
                                        'deciConsumoZinc' => (float) $request->deciConsumoZinc,
                                        'varPorcZinc' => $request->varPorcZinc,
                                        'dateFechInic' => $request->dateFechInic,
                                        'dateFechSali' => $request->dateFechSali,
                                        'usua_modi' => $request->usua_modi,
                                        'intIdEsta' => 37,
                                        'intIdEstaInsp' => 41,
                                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                        } else {
                            if ($id_estado === 36) {
                                DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intIdEstaInsp' =>41]);
                            }
                            DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intGanchera' => (int) $request->intGanchera,
                                        'varTurno' => $request->varTurno,
                                        'varHoraEntr' => $request->varHoraEntr,
                                        'varHoraSali' => $request->varHoraSali,
                                        'intCantidad' => $request->intCantidad,
                                        'varTipoMate' => $request->varTipoMate,
                                        'deciPesoNegro' => (float) $request->deciPesoNegro,
                                        'deciPesoGalv' => (float) $request->deciPesoGalv,
                                        'deciConsumoZinc' => (float) $request->deciConsumoZinc,
                                        'varPorcZinc' => $request->varPorcZinc,
                                        'dateFechInic' => $request->dateFechInic,
                                        'dateFechSali' => $request->dateFechSali,
                                        'usua_modi' => $request->usua_modi,
                                        'intIdEsta' => $id_estado,
                                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                        }


                        $total = DB::select("select sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end) as PesoNegro,sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoGalv else 0.0 end) as PesoGalvanizado , sum(deciConsumoZinc)as  deciConsumoZinc,  ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) as varPorcZinc from deta_galv where intIdGalva=$request->intIdGalva");
                        $fechaini = DB::select("select dateFechInic from deta_galv 
                                    inner join (select min(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                        $fechafin = DB::select("select dateFechSali from deta_galv 
                                    inner join (select max(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                        $cantidad_total = DB::select("select intCantTota from tab_galv where intIdGalva=$request->intIdGalva");
                        $cantidad_det = DB::select("select sum(intCantidad) as cantidad from deta_galv where intIdGalva=$request->intIdGalva");
                        $esta_term = DB::select("select  intIdEsta as estado from deta_galv where intIdGalva=$request->intIdGalva and intIdEsta = 35");
                        /*si la cantidad registrada en el detalle es igual a al cantidad de la cabecera actualziar el estado de la cabecera*/
                        if ((int) $cantidad_total[0]->intCantTota === (int) $cantidad_det[0]->cantidad) {
                            $estado_cabecera = 38;
                            /*si el estado se encuentra registrado pasa a estado en proceso*/
                            if (count($esta_term) > 0) {
                                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                        ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                            'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                            'varPorcZinc' => $total[0]->varPorcZinc,
                                            'dateFechIngr' => $fechaini[0]->dateFechInic,
                                            'dateFechSali' => $fechafin[0]->dateFechSali,
                                            'intIdEsta' => $estado_cabecera,
                                            'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                            } else {
                                /*si el estado es diferente a registrado pasamos a estado terminado*/
                                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                        ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                            'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                            'varPorcZinc' => $total[0]->varPorcZinc,
                                            'dateFechIngr' => $fechaini[0]->dateFechInic,
                                            'dateFechSali' => $fechafin[0]->dateFechSali,
                                            'intIdEsta' => 34,
                                            'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                            }
                        } else {
                            /*caso contrario se pasa a estado parcial*/
                            $estado_cabecera = 33;
                            Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                    ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                        'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                        'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                        'varPorcZinc' => $total[0]->varPorcZinc,
                                        'dateFechIngr' => $fechaini[0]->dateFechInic,
                                        'dateFechSali' => $fechafin[0]->dateFechSali,
                                        'intIdEsta' => $estado_cabecera,
                                        'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                        }
                        $respuesta = "";
                        return $this->successResponse($respuesta);
                    } else 
                        /*si el estado del detalle de inspeccion esta ene stado 41 todavia se puede editar*/
                        if ($estado_inspeccion[0]->{'intIdEsta'} === 41) {
                        if ($esta_term_elem[0]->{'estado'} === 36 || $esta_term_elem[0]->{'estado'} === 37) {
                            DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intGanchera' => (int) $request->intGanchera,
                                        'varTurno' => $request->varTurno,
                                        'varHoraEntr' => $request->varHoraEntr,
                                        'varHoraSali' => $request->varHoraSali,
                                        'intCantidad' => $request->intCantidad,
                                        'varTipoMate' => $request->varTipoMate,
                                        'deciPesoNegro' => (float) $request->deciPesoNegro,
                                        'deciPesoGalv' => (float) $request->deciPesoGalv,
                                        'deciConsumoZinc' => (float) $request->deciConsumoZinc,
                                        'varPorcZinc' => $request->varPorcZinc,
                                        'dateFechInic' => $request->dateFechInic,
                                        'dateFechSali' => $request->dateFechSali,
                                        'usua_modi' => $request->usua_modi,
                                        'intIdEsta' => 37,
                                        'intIdEstaInsp' => 41,
                                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                        } else {
                            if ($id_estado === 36) {
                                DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intIdEstaInsp' =>41]);
                            }
                            DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                                    ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                                    ->update([
                                        'intGanchera' => (int) $request->intGanchera,
                                        'varTurno' => $request->varTurno,
                                        'varHoraEntr' => $request->varHoraEntr,
                                        'varHoraSali' => $request->varHoraSali,
                                        'intCantidad' => $request->intCantidad,
                                        'varTipoMate' => $request->varTipoMate,
                                        'deciPesoNegro' => (float) $request->deciPesoNegro,
                                        'deciPesoGalv' => (float) $request->deciPesoGalv,
                                        'deciConsumoZinc' => (float) $request->deciConsumoZinc,
                                        'varPorcZinc' => $request->varPorcZinc,
                                        'dateFechInic' => $request->dateFechInic,
                                        'dateFechSali' => $request->dateFechSali,
                                        'usua_modi' => $request->usua_modi,
                                        'intIdEsta' => $id_estado,
                                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                        }


                        $total = DB::select("select sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end) as PesoNegro,sum(deciPesoGalv) as PesoGalvanizado , sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciConsumoZinc else 0.0 end)as  deciConsumoZinc,  ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) as varPorcZinc from deta_galv where intIdGalva=$request->intIdGalva");
                        $fechaini = DB::select("select dateFechInic from deta_galv 
                                    inner join (select min(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                        $fechafin = DB::select("select dateFechSali from deta_galv 
                                    inner join (select max(intIdDetaGalv) as intIdDetaGalv, intIdGalva as intIdGalva  from deta_galv where intIdGalva=$request->intIdGalva group by intIdDetaGalv,intIdGalva ) b on deta_galv.intIdGalva=b.intIdGalva
                                    where deta_galv.intIdGalva=$request->intIdGalva");
                        $cantidad_total = DB::select("select intCantTota from tab_galv where intIdGalva=$request->intIdGalva");
                        $cantidad_det = DB::select("select sum(intCantidad) as cantidad from deta_galv where intIdGalva=$request->intIdGalva");
                        $esta_term = DB::select("select  intIdEsta as estado from deta_galv where intIdGalva=$request->intIdGalva and intIdEsta = 35");

                        if ((int) $cantidad_total[0]->intCantTota === (int) $cantidad_det[0]->cantidad) {
                            $estado_cabecera = 38;
                            if (count($esta_term) > 0) {
                                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                        ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                            'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                            'varPorcZinc' => $total[0]->varPorcZinc,
                                            'dateFechIngr' => $fechaini[0]->dateFechInic,
                                            'dateFechSali' => $fechafin[0]->dateFechSali,
                                            'intIdEsta' => $estado_cabecera,
                                            'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                            } else {

                                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                        ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                            'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                            'varPorcZinc' => $total[0]->varPorcZinc,
                                            'dateFechIngr' => $fechaini[0]->dateFechInic,
                                            'dateFechSali' => $fechafin[0]->dateFechSali,
                                            'intIdEsta' => 34,
                                            'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                            }
                        } else {
                            $estado_cabecera = 33;
                            Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                                    ->update(['deciPesoNegro' => $total[0]->PesoNegro,
                                        'deciPesoGalv' => $total[0]->PesoGalvanizado,
                                        'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                                        'varPorcZinc' => $total[0]->varPorcZinc,
                                        'dateFechIngr' => $fechaini[0]->dateFechInic,
                                        'dateFechSali' => $fechafin[0]->dateFechSali,
                                        'intIdEsta' => $estado_cabecera,
                                        'intCantRegi' => (int) $cantidad_det[0]->cantidad]);
                        }
                        $respuesta = "";
                        return $this->successResponse($respuesta);
                    } else {
                        /*no se puede editar cuando ya la inspecciòn se encuentra ene stado 42*/
                        $respuesta = "No se puede editar la ganchera ya se encuentra inspeccionada.";
                        return $this->successResponse($respuesta);
                    }
                }
            } else {
                $estado_inspeccion = DB::select("select intIdEsta FROM mimco.insp_galv where intIdDetaGalv=$request->intIdDetaGalv");
                if($estado_inspeccion[0]->{'intIdEsta'} === 41){
                    if ($request->varHoraSali !== "" && $request->deciPesoNegro !== "" && $request->deciPesoGalv !== "") {
                    $id_estado = 36;
                    $id_estado_insp=41;
                } else {
                    $id_estado = 35;
                   $id_estado_insp=02;
                }
                date_default_timezone_set('America/Lima'); // CDT
                DetaGalva::where('intIdGalva', '=', (int) $request->intIdGalva)
                        ->where('intIdDetaGalv', '=', (int) $request->intIdDetaGalv)
                        ->update([
                            'intGanchera' => (int) $request->intGanchera,
                            'varTurno' => $request->varTurno,
                            'varHoraEntr' => $request->varHoraEntr,
                            'varHoraSali' => $request->varHoraSali,
                            'intCantidad' => $request->intCantidad,
                            'varTipoMate' => $request->varTipoMate,
                            'deciPesoNegro' => (float) $request->deciPesoNegro,
                            'deciPesoGalv' => (float) $request->deciPesoGalv,
                            'deciConsumoZinc' => (float) $request->deciConsumoZinc,
                            'varPorcZinc' => $request->varPorcZinc,
                            'dateFechInic' => $request->dateFechInic,
                            'dateFechSali' => $request->dateFechSali,
                            'usua_modi' => $request->usua_modi,
                            'intIdEsta' => $id_estado,
                            'intIdEstaInsp' =>$id_estado_insp,
                            'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
                $total = DB::select("select sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end ) as PesoNegro,sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoGalv else 0.0 end) as PesoGalvanizado , sum(deciConsumoZinc)as  deciConsumoZinc,  ROUND(ifnull((sum(deta_galv.deciConsumoZinc) / sum(case when deta_galv.varTipoGalv='GALVANIZADO' then  deta_galv.deciPesoNegro else 0.0 end))*100,0),3) as varPorcZinc from deta_galv where intIdGalva=$request->intIdGalva");
                Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                        ->update([
                            'deciConsumoZinc' => $total[0]->deciConsumoZinc,
                            'varPorcZinc' => $total[0]->varPorcZinc]);
                $respuesta = "";
                return $this->successResponse($respuesta);
                }else{
                     $respuesta = "No se puede editar la ganchera ya se encuentra inspeccionada.";
                        return $this->successResponse($respuesta);
                }
                
            }
        }
    }

    public function listar_galvanizado_detalle(Request $request) {
        $regla = ['intIdGalva.required' => 'EL Campo Id Galvanizado es obligatorio'];
        $validator = Validator::make($request->all(), ['intIdGalva' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $Deta_galva = DB::select("select deta_galv.intIdGalva,deta_galv.intIdDetaGalv,deta_galv.intIdPeriValo,deta_galv.varTipoGalv,deta_galv.intGanchera,
deta_galv.varTurno,deta_galv.varHoraEntr,deta_galv.varHoraSali,deta_galv.intCantidad,deta_galv.varTipoMate,deta_galv.deciPesoNegro,
deta_galv.deciPesoGalv,deta_galv.deciConsumoZinc,(case when deta_galv.varPorcZinc='' then '0' else deta_galv.varPorcZinc end) as varPorcZinc,
deta_galv.dateFechInic,deta_galv.dateFechSali,deta_galv.acti_usua,deta_galv.acti_hora,deta_galv.hora_modi,deta_galv.usua_modi,deta_galv.intIdEsta,estado.varDescEsta,peri_valo.varDescPeriValo 
,(case when deta_galv.varTipoGalv='GALVANIZADO'  then deta_galv.deciPesoNegro else 0 end) as ConsumoPesoG
from deta_galv
                    inner join estado on deta_galv.intIdEsta=estado.intIdEsta 
                    inner join peri_valo on peri_valo.intIdPeriValo=deta_galv.intIdPeriValo where deta_galv.intIdGalva=$request->intIdGalva");
            $peso_galvanizado = 0;
            $peso_negro = 0;
            $total_zinc=0;
            if (count($Deta_galva) > 0) {
                for ($i = 0; count($Deta_galva) > $i; $i++) {
                    $peso_galvanizado += $Deta_galva[$i]->{'deciConsumoZinc'};
                    if ($Deta_galva[$i]->{'varTipoGalv'} === 'GALVANIZADO') {
                        $peso_negro += $Deta_galva[$i]->{'deciPesoNegro'};
                    }
                }
                $total_zinc = ($peso_galvanizado / $peso_negro) * 100;

                return response()->json(['data' => $Deta_galva, 'zinc' => $total_zinc]);
            } else {
                return response()->json(['data' => $Deta_galva, 'zinc' => $total_zinc]);
            }
        }
    }

    public function listar_guias_ot(Request $request) {
        $regla = [
            'intIdProy.required' => 'EL Campo Proyecto es obligatorio',
            'intIdTipoProducto.required' => 'EL Campo Tipo Elemento es obligatorio',
            'numDocTratSup.required' => 'EL Campo Documento de Transferencia es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdProy' => 'required|max:255',
                    'intIdTipoProducto' => 'required|max:255',
                    'numDocTratSup' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $lista_guia = DB::select("select varCodiElemento,count(varCodiElemento) as cantidad,(deciPesoNeto*count(varCodiElemento)) as PesoNeto,
                                    (deciPesoBruto *count(varCodiElemento)) as PesoBruto, (deciArea *count(varCodiElemento)) as AreaTotal
                                    from elemento 
                                    where intIdProy=$request->intIdProy and intIdTipoProducto=$request->intIdTipoProducto and numDocTratSup='$request->numDocTratSup'
                                    group by varCodiElemento,deciPesoNeto,deciPesoBruto,deciArea");
        }return $this->successResponse($lista_guia);
    }

    public function editar_cabecera(Request $request) {
        $regla = [
            'intIdGalva.required' => 'EL Campo Id Galvanizado es obligatorio',
            'varDescripcion.required' => 'EL Campo Descripcion es obligatorio',
            'usua_modi.required' => 'EL Campo Usuario obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdGalva' => 'required|max:255',
                    'varDescripcion' => 'required|max:255',
                    'usua_modi' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            date_default_timezone_set('America/Lima'); // CDT

            Galvanizado::where('intIdGalva', '=', $request->intIdGalva)
                    ->update(['varDescripcion' => $request->varDescripcion,
                        'usua_modi' => $request->usua_modi,
                        'hora_modi' => $current_date = date('Y/m/d H:i:s')]);

            $respuesta = "";
            return $this->successResponse($respuesta);
        }
    }

    public function reporte_galvanizado_cabecera_detalle(Request $request) {
        $regla = [
            'varTipoOrden.required' => 'EL Campo Id Tipo Orden es obligatorio',
            'fecha_inicio.required' => 'EL Campo Fecha Inicio es obligatorio',
            'fecha_fin.required' => 'EL Campo Fecha Fin obligatorio'];
        $validator = Validator::make($request->all(), [
                    'varTipoOrden' => 'required|max:255',
                    'fecha_inicio' => 'required|max:255',
                    'fecha_fin' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_cabecera = DB::select("select tab_galv.intIdGalva, (case when isnull(tab_galv.intIdProy) then tab_galv.varOrdenServi else proyecto.varCodiProy end ) as orden,tab_galv.varRazoSoci,
                                            tab_galv.varDescripcion,(case when isnull(tab_galv.varNumeGuia) then '' else tab_galv.varNumeGuia end ) as Guia,
                                            tab_galv.intCantTota,tab_galv.dateFechIngr,tab_galv.dateFechSali,tab_galv.dateFechIntern
                                            from tab_galv 
                                            left join proyecto on tab_galv.intIdProy=proyecto.intIdProy
                                            where (tab_galv.varTipoOrden='$request->varTipoOrden' or 'TODOS'='$request->varTipoOrden') and tab_galv.dateFechIngr between '$request->fecha_inicio' and '$request->fecha_fin'");
            //dd($listar_cabecera);
            $array_reporte = [];
            for ($i = 0; count($listar_cabecera) > $i; $i++) {
                $id_galva = $listar_cabecera[$i]->intIdGalva;
                $listar_detalle = DB::select("select *,peri_valo.varDescPeriValo,estado.varDescEsta from deta_galv inner join peri_valo on deta_galv.intIdPeriValo=peri_valo.intIdPeriValo inner join estado on deta_galv.intIdEsta=estado.intIdEsta where deta_galv.intIdGalva=$id_galva");
                for ($j = 0; count($listar_detalle) > $j; $j++) {
                    if ($listar_cabecera[$i]->intIdGalva === $listar_detalle[$j]->intIdGalva) {
                        $datos_componentes = [
                            'intIdGalva' => $listar_cabecera[$i]->intIdGalva,
                            'orden' => $listar_cabecera[$i]->orden,
                            'Cliente' => $listar_cabecera[$i]->varRazoSoci,
                            'varDescripcion' => $listar_cabecera[$i]->varDescripcion,
                            'Guia' => $listar_cabecera[$i]->Guia,
                            'intCantTota' => $listar_cabecera[$i]->intCantTota,
                            'dateFechIngrCabecera' => $listar_cabecera[$i]->dateFechIngr,
                            'dateFechSaliCabecera' => $listar_cabecera[$i]->dateFechSali,
                            'dateFechInternCabecera' => $listar_cabecera[$i]->dateFechIntern,
                            'intIdDetaGalv' => $listar_detalle[$j]->intIdDetaGalv,
                            'varDescPeriValo' => $listar_detalle[$j]->varDescPeriValo,
                            'varTipoGalv' => $listar_detalle[$j]->varTipoGalv,
                            'intGanchera' => $listar_detalle[$j]->intGanchera,
                            'varTurno' => $listar_detalle[$j]->varTurno,
                            'dateFechInic' => $listar_detalle[$j]->dateFechInic,
                            'dateFechSali' => $listar_detalle[$j]->dateFechSali,
                            'varHoraEntr' => $listar_detalle[$j]->varHoraEntr,
                            'varHoraSali' => $listar_detalle[$j]->varHoraSali,
                            'varTipoMate' => $listar_detalle[$j]->varTipoMate,
                            'deciPesoNegro' => $listar_detalle[$j]->deciPesoNegro,
                            'deciPesoGalv' => $listar_detalle[$j]->deciPesoGalv,
                            'deciConsumoZinc' => $listar_detalle[$j]->deciConsumoZinc,
                            'varDescEsta' => $listar_detalle[$j]->varDescEsta,
                            'varPorcZinc' => $listar_detalle[$j]->varPorcZinc . ' ' . '%',
                        ];
                        array_push($array_reporte, $datos_componentes);
                    }
                }
            }
            return $this->successResponse($array_reporte);
        }
    }

}
