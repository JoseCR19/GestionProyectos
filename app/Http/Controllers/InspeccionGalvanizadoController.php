<?php

namespace App\Http\Controllers;

use DB;
use App\InspecGalvanizado;
use App\Observacion;
use Config;
use App\Especificacion;
use App\DetaGalva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InspeccionGalvanizadoController extends Controller {

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

    public function lista_insp_galvanizado(Request $request) {
        $regla = [
            'varTurno.required' => 'EL Campo Turno es obligatorio',
            'intIdEsta.required' => 'EL Campo Estado es obligatorio',
            'fechaInicio.required' => 'EL Campo Fecha Fin es obligatorio',
            'fechaFin.required' => 'EL Campo Fecha Inicio es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'varTurno' => 'required|max:255',
                    'intIdEsta' => 'required|max:255',
                    'fechaInicio' => 'required|max:255',
                    'fechaFin' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            /*estado pendiente = 41*/
            if ($request->intIdEsta !== '41') {
                /*tipo de inspeccion galvanizado*/
                if ($request->intTipo === "1") {
                    $listar_insp = DB::select("select insp_galv.TieneObs,tab_galv.varTipoOrden,tab_galv.varOrdenServi,CASE WHEN ISNULL(tab_galv.intIdProy) THEN tab_galv.varOrdenServi ELSE  proyecto.varCodiProy END varCodiProy,
                        tipo_producto.varDescTipoProd,tab_galv.varRazoSoci,deta_galv.varTipoGalv,tab_galv.varNumeGuia,deta_galv.varTurno, deta_galv.varHoraEntr,deta_galv.varHoraSali,insp_galv.*,
                        concat(colaborador.varNombColabo, ' ',colaborador.varApelColabo ) as Nombre,tab_espe.varTipoMate as Especi,
                        (case when deta_galv.varTipoGalv='GALVANIZADO' then insp_galv.deciPesoNegro else 0 end) as ConsumoPesoG,
                        (case when deta_galv.varTipoGalv='GALVANIZADO' then insp_galv.deciPesoGalv else 0 end) as ConsumoPesoN,
                        deta_galv.intGanchera,deta_galv.varTipoMate
                        from tab_galv 
                        left join deta_galv on tab_galv.intIdGalva=deta_galv.intIdGalva
                        left join insp_galv on deta_galv.intIdDetaGalv=insp_galv.intIdDetaGalv
                        left join proyecto on tab_galv.intIdProy=proyecto.intIdProy
                        left join colaborador on colaborador.intIdColaborador=insp_galv.intIdSuper
                        left join tipo_producto on tab_galv.intIdTipoProducto=tipo_producto.intIdTipoProducto
                        left join tab_espe on tab_espe.intIdEspeci=insp_galv.intIdEspeci
                        where (deta_galv.varTurno='$request->varTurno' or 'TODOS'='$request->varTurno') and deta_galv.dateFechInic between '$request->fechaInicio' and '$request->fechaFin'
                        and insp_galv.intIdEsta=$request->intIdEsta and (insp_galv.intIdSuper=$request->intIdEspeci or '-1'=$request->intIdEspeci) order by insp_galv.acti_hora desc");
                    return $this->successResponse($listar_insp);
                } else {
                    $listar_insp = DB::select("select tab_causa.varDescCausa,tab_defe.varDescDefe ,deta_galv.*,tab_galv.*,
                        CASE WHEN ISNULL(tab_galv.intIdProy) THEN tab_galv.varOrdenServi ELSE  proyecto.varCodiProy END varCodiProy,
                        tipo_producto.varDescTipoProd,obse_galv.* 
                        ,concat(colaborador.varNombColabo, ' ',colaborador.varApelColabo ) as Nombre
                        from tab_galv
                        left join deta_galv on tab_galv.intIdGalva=deta_galv.intIdGalva
                        left join obse_galv on deta_galv.intIdDetaGalv=obse_galv.intIdDetaGalv
                        left join proyecto on tab_galv.intIdProy=proyecto.intIdProy
                        left join tipo_producto on tab_galv.intIdTipoProducto=tipo_producto.intIdTipoProducto
                        left join tab_defe on tab_defe.intIdDefe=obse_galv.intIdDefe
                        left join tab_causa on tab_causa.intIdCausa=obse_galv.intIdCausa
                        left join colaborador on  colaborador.intIdColaborador=obse_galv.intIdSuper
                        where  (deta_galv.varTurno='$request->varTurno' or 'TODOS'='$request->varTurno') and deta_galv.dateFechInic between '$request->fechaInicio' and '$request->fechaFin'
                        and obse_galv.intIdEsta=$request->intIdEsta and (obse_galv.intIdSuper=$request->intIdEspeci or '-1'=$request->intIdEspeci) order by obse_galv.acti_hora desc");
                    return $this->successResponse($listar_insp);
                }
                /**/
            } else {
                /* estdo pendiente */
                /*$listar_insp = DB::select("select deta_galv.*,tab_galv.*,CASE WHEN ISNULL(tab_galv.intIdProy) THEN tab_galv.varOrdenServi ELSE  proyecto.varCodiProy END varCodiProy,tipo_producto.varDescTipoProd,insp_galv.* from tab_galv 
                                LEFT join deta_galv on tab_galv.intIdGalva=deta_galv.intIdGalva
                                LEFT join proyecto on tab_galv.intIdProy=proyecto.intIdProy
                                LEFT join tipo_producto on tab_galv.intIdTipoProducto=tipo_producto.intIdTipoProducto
                                LEFT join insp_galv on insp_galv.intIdDetaGalv=deta_galv.intIdDetaGalv
                                where deta_galv.varTipoGalv='GALVANIZADO' and insp_galv.intIdEsta=$request->intIdEsta and (deta_galv.varTurno='$request->varTurno' or 'TODOS'='$request->varTurno') and deta_galv.dateFechInic between '$request->fechaInicio' and '$request->fechaFin'");*/
                $listar_insp = DB::select("select tg.varTipoOrden,tg.varOrdenServi,CASE WHEN ISNULL(tg.intIdProy) THEN tg.varOrdenServi ELSE  p.varCodiProy END varCodiProy , tp.varDescTipoProd, 
                                tg.varRazoSoci,tg.varNumeGuia,tg.intCantTota,tg.intCantRegi,d.* ,
                                (select (case when isnull(sum(intCantidad)) then 0 else sum(intCantidad) end) from insp_galv where insp_galv.intIdDetaGalv=d.intIdDetaGalv ) as intCantReg,
                                (case when d.varTipoGalv='GALVANIZADO' then d.deciPesoNegro else 0 end) as ConsumoPesoG,
                                (case when d.varTipoGalv='GALVANIZADO' then d.deciPesoGalv else 0 end) as ConsumoPesoN,
                                (case when d.varTipoGalv='GALVANIZADO' then tg.intCantTota else 0 end) as intCantTota_1,
                                (case when d.varTipoGalv='GALVANIZADO' then tg.intCantRegi else 0 end) as intCantRegi_1,
                                (case when d.varTipoGalv='GALVANIZADO' then tg.deciPesoBruto else 0 end) as deciPesoBruto_1,
                                (case when d.varTipoGalv='GALVANIZADO' then tg.deciPesoInge else 0 end) as deciPesoInge_1,
                                tg.dateFechIngr,tg.dateFechIntern,tg.deciPesoInge,tg.deciPesoBruto
                                from deta_galv d
                                left join tab_galv tg on d.intIdGalva=tg.intIdGalva
                                left join proyecto p on tg.intIdProy=p.intIdProy
                                left join tipo_producto tp on tg.intIdTipoProducto=tp.intIdTipoProducto
                                where d.intIdEstaInsp=41 and d.intIdEsta=36 
                                and (d.varTurno='$request->varTurno' or 'TODOS'='$request->varTurno') and d.dateFechInic between '$request->fechaInicio' and '$request->fechaFin' order by d.acti_hora desc");
                return $this->successResponse($listar_insp);
            }
        }
    }

    public function crear_inspeccion_galvanizado(Request $request) {
        $regla = [
            'intIdDetaGalv.required' => 'EL Campo Turno es obligatorio',
            'intIdEspeci.required' => 'EL Campo Estado es obligatorio',
            'deciMuesA1_1.required' => 'EL Campo Fecha Fin es obligatorio',
            'deciMuesA1_2.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA2_1.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA2_2.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA3_1.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA3_2.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA4_1.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA4_2.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA5_1.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMuesA5_2.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciPromedio.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciMaxiTota.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varTipoMaterial.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varMaterial.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdSuper.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciTolerancia.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciPesoExceso.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intCantidad.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciPesoNegro.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciPesoGalv.required' => 'EL Campo Fecha Inicio es obligatorio',
            'deciConsumoZinc.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varPorcZinc.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intEsHijo.required' => 'EL Campo Fecha Inicio es obligatorio',
            'acti_usua.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varMaterial.required' => 'EL Campo Fecha Inicio es obligatorio',
            'TieneObs.required'=>'El campo tiene observacion es obligatorio'];
        $validator = Validator::make($request->all(), [
                    'intIdDetaGalv' => 'required|max:255',
                    'intIdEspeci' => 'required|max:255',
                    'deciMuesA1_1' => 'required|max:255',
                    'deciMuesA1_2' => 'required|max:255',
                    'deciMuesA2_1' => 'required|max:255',
                    'deciMuesA2_2' => 'required|max:255',
                    'deciMuesA3_1' => 'required|max:255',
                    'deciMuesA3_2' => 'required|max:255',
                    'deciMuesA4_1' => 'required|max:255',
                    'deciMuesA4_2' => 'required|max:255',
                    'deciMuesA5_1' => 'required|max:255',
                    'deciMuesA5_2' => 'required|max:255',
                    'deciPromedio' => 'required|max:255',
                    'deciMaxiTota' => 'required|max:255',
                    'varTipoMaterial' => 'required|max:255',
                    'intIdSuper' => 'required|max:255',
                    'deciTolerancia' => 'required|max:255',
                    'deciPesoExceso' => 'required|max:255',
                    'intCantidad' => 'required|max:255',
                    'deciPesoNegro' => 'required|max:255',
                    'deciPesoGalv' => 'required|max:255',
                    'deciConsumoZinc' => 'required|max:255',
                    'varPorcZinc' => 'required|max:255',
                    'intEsHijo' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                    'varMaterial' => 'required|max:255',
                    'TieneObs' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $cantidad = DetaGalva::where('intIdDetaGalv','=',$request->intIdDetaGalv)->select('intCantidad')->get();
            $cantidad_real = $cantidad[0]->{'intCantidad'};
            
            date_default_timezone_set('America/Lima'); // CD
            $inspeccion = new InspecGalvanizado;
            $inspeccion->intIdEspeci=$request->intIdEspeci;
            $inspeccion->intIdDetaGalv=$request->intIdDetaGalv;
            $inspeccion->deciMuesA1_1=$request->deciMuesA1_1;
            $inspeccion->deciMuesA1_2=$request->deciMuesA1_2;
            $inspeccion->deciMuesA2_1=$request->deciMuesA2_1;
            $inspeccion->deciMuesA2_2=$request->deciMuesA2_2;
            $inspeccion->deciMuesA3_1=$request->deciMuesA3_1;
            $inspeccion->deciMuesA3_2=$request->deciMuesA3_2;
            $inspeccion->deciMuesA4_1=$request->deciMuesA4_1;
            $inspeccion->deciMuesA4_2=$request->deciMuesA4_2;
            $inspeccion->deciMuesA5_1=$request->deciMuesA5_1;
            $inspeccion->deciMuesA5_2=$request->deciMuesA5_2;
            $inspeccion->deciPromedio=$request->deciPromedio;
            $inspeccion->deciMaxiTota=$request->deciMaxiTota;
            $inspeccion->varTipoMaterial=$request->varTipoMaterial;
            $inspeccion->varMaterial=$request->varMaterial;
            $inspeccion->intIdSuper=$request->intIdSuper;
            $inspeccion->deciPesoExceso=$request->deciPesoExceso;
            $inspeccion->deciTolerancia=$request->deciTolerancia;
            $inspeccion->intCantidad=$request->intCantidad;
            $inspeccion->deciPesoNegro=$request->deciPesoNegro;
            $inspeccion->deciPesoGalv=$request->deciPesoGalv;
            $inspeccion->deciConsumoZinc=$request->deciConsumoZinc;
            $inspeccion->varPorcZinc=$request->varPorcZinc;
            $inspeccion->intEsHijo=$request->intEsHijo;
            $inspeccion->acti_usua=$request->acti_usua;
            $inspeccion->intIdEsta=42;
            $inspeccion->acti_hora=date('Y/m/d H:i:s');
            $inspeccion->TieneObs=$request->TieneObs;
            $inspeccion->save();
            $sumatoria = DB::select("SELECT sum(intCantidad) as cantidad FROM mimco.insp_galv where intIdDetaGalv= $request->intIdDetaGalv");
            $cantidad_inspeccion = $sumatoria[0]->{'cantidad'};
            //dd($cantidad_real,$cantidad_inspeccion);
            if($cantidad_real=== (int)$cantidad_inspeccion){
                DetaGalva::where('intIdDetaGalv','=',$request->intIdDetaGalv)->update(['intIdEstaInsp' =>42]);
            }
            $respuesta = "";
            return $this->successResponse($respuesta);
        }
    }
    public function cantidad_inspeccion(Request $request){
        $regla = [
            'intIdDetaGalv' => 'EL Campo Turno es obligatorio'
        ];
        $validator = Validator::make($request->all(), [
                    'intIdDetaGalv' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $cantidad_total=DetaGalva::where('intIdDetaGalv','=',$request->intIdDetaGalv)->select('intCantidad')->get();
            $cantidad_inspeccion = DB::select("SELECT (case when isnull( sum(intCantidad)) then 0 else sum(intCantidad) end ) as cantidad FROM mimco.insp_galv where intIdDetaGalv= $request->intIdDetaGalv");
            $resultado_cantidad = $cantidad_total[0]->{'intCantidad'} - (int) $cantidad_inspeccion[0]->{'cantidad'};
            return $this->successResponse($resultado_cantidad);
        }
    }
    public function detalle_inspeccion(Request $request){
        $regla = [
            'intIdDetaGalv' => 'EL Campo Turno es obligatorio'
        ];
        $validator = Validator::make($request->all(), [
                    'intIdDetaGalv' => 'required|max:255'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            //$detalle_inspeccion=InspecGalvanizado::where('intIdDetaGalv','=',$request->intIdDetaGalv)->select()->get();
            $detalle_inspeccion=DB::select("select i.*,concat(e.varTipoMate,'-',e.intEspeci) as especificacion,e.*  from insp_galv i inner join tab_espe e on i.intIdEspeci=e.intIdEspeci where i.intIdDetaGalv=$request->intIdDetaGalv");
            return $this->successResponse($detalle_inspeccion);
        } 
    }
    public function crear_observacion_galvanizado(Request $request) {
        $regla = [
            'intIdDetaGalv' => 'EL Campo Turno es obligatorio',
            'varTipo.required' => 'EL Campo Turno es obligatorio',
            'intCantidad.required' => 'EL Campo Estado es obligatorio',
            'deciPesoObse.required' => 'EL Campo Fecha Fin es obligatorio',
            'intIdDefe.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdCausa.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varAccion.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varOrigFalla.required' => 'EL Campo Fecha Inicio es obligatorio',
            'acti_usua.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varTipoMate.required' => 'EL Campo Fecha Inicio es obligatorio',
            'varMaterial.required' => 'EL Campo Fecha Inicio es obligatorio',
            'intIdSuper.required' => 'EL Campo Fecha Inicio es obligatorio',
            'fechaInsp' => 'EL Campo Fecha Inicio es obligatorio'
        ];
        $validator = Validator::make($request->all(), [
                    'intIdDetaGalv' => 'required|max:255',
                    'varTipo' => 'required|max:255',
                    'intCantidad' => 'required|max:255',
                    'deciPesoObse' => 'required|max:255',
                    'intIdDefe' => 'required|max:255',
                    'intIdCausa' => 'required|max:255',
                    'varAccion' => 'required|max:255',
                    'varOrigFalla' => 'required|max:255',
                    'acti_usua' => 'required|max:255',
                    'varTipoMate' => 'required|max:255',
                    'varMaterial' => 'required|max:255',
                    'intIdSuper' => 'required|max:255',
                    'fechaInsp' => 'required|max:255',
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            date_default_timezone_set('America/Lima'); // CD
            
            $observacion = new Observacion;
            $observacion->intIdDetaGalv = (int) $request->intIdDetaGalv;
            $observacion->varTipo = $request->varTipo;
            $observacion->intCantidad = $request->intCantidad;
            $observacion->deciPesoObse = $request->deciPesoObse;
            $observacion->intIdDefe = $request->intIdDefe;
            $observacion->intIdCausa = $request->intIdCausa;
            $observacion->varAccion = $request->varAccion;
            $observacion->varOrigFalla = $request->varOrigFalla;
            $observacion->varObse = $request->varObse;
            $observacion->acti_usua = $request->acti_usua;
            $observacion->varTipoMate = $request->varTipoMate;
            $observacion->varMaterial = $request->varMaterial;
            $observacion->fechaInsp = $request->fechaInsp;
            $observacion->intIdSuper = $request->intIdSuper;
            $observacion->IntCantNoConf = $request->IntCantNoConf;
            $observacion->intIdEsta = 42; //modifico andy
            $observacion->acti_hora = $current_date = date('Y/m/d H:i:s');
            $observacion->save(); //modifico andy
            InspecGalvanizado::where('intIdDetaGalv', '=', $request->intIdDetaGalv)
                    ->update([
                        'usua_modi' => $request->acti_usua,
                        'intIdEsta' => 42,
                        'intIdObse' =>$observacion->intIdObse,
                        'TieneObs' =>0,
                        'hora_modi' => date('Y/m/d H:i:s')]);
            $mensaje = ""; 
            return $this->successResponse($mensaje);
        }
    }
    

}
