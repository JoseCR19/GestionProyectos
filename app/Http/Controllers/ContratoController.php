<?php

namespace App\Http\Controllers;

use App\Proyectos;
use App\Cliente;
use App\Contratista;
use Illuminate\Http\Request;
use App\Contrato;
use DB;
use App\Agrupador;
use App\TipoEtapas;
use App\detalleAgrupadorContratado;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;

class ContratoController extends Controller {

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

    public function listar_contrato(Request $request){
        $regla = [
            'intIdProy' => 'required'
        ];
        $this->validate($request, $regla);
        $ot =(int) $request->input('intIdProy');
        $listar_contratos = DB::select("select co.varRazCont,concat(p.varCodiProy,' / ',p.varAlias) as varCodiProy ,tp.varDescTipoProd,c.*,e.varDescEsta,
                                        (case c.intTipoUnidad when 1 then 'PESO' when 2 then 'AREA' when 3 then 'AMBOS' end) as tipo_unidad
                                        from contrato c 
                                        inner join proyecto p on c.intIdProy=p.intIdProy 
                                        inner join tipo_producto tp on c.intIdTipoProducto=tp.intIdTipoProducto
                                        inner join contratista co on c.intIdContr=co.intIdCont
                                        inner join estado e on c.intIdEsta=e.intIdEsta
                                        where (c.intIdProy=$ot or -1=$ot)");
        return $this->successResponse($listar_contratos);
    }
    public function listar_contratista(){
        $contratista = DB::select('SELECT c.intIdCont,c.varRazCont FROM contratista c WHERE NOT EXISTS (SELECT NULL FROM contrato co WHERE co.intIdContr = c.intIdCont)');
        return $this->successResponse($contratista);
    }
    public function listar_contratistas_editar(){
        $contratista = DB::select("SELECT  intIdCont,varRazCont FROM mimco.contratista");
        return $this->successResponse($contratista);
    }
    public function crear_contrato(Request $request){
        $regla = [
            'intIdProy' => 'required',
            'intIdTipoProducto' => 'required',
            'intIdContr' => 'required',
            'varNuContrato' => 'required',
            'fech_Ini' => 'required',
            'fech_Fin' => 'required',
            'varNuOS' => 'required',
            'deciImpTotal' => 'required',
            'deciImpValor' => 'required',
            'deciImpSaldo' => 'required',
            'intTipoUnidad' => 'required',
            'deciPesoTotal' => 'required',
            'deciPesoSaldo' => 'required',
            'deciAreaTotal' => 'required',
            'deciAreaSaldo' => 'required',
            'acti_usua' => 'required',
            
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima');
        
        $contrato =new Contrato;
        $contrato->intIdProy= $request->input('intIdProy');
        $contrato->intIdTipoProducto= $request->input('intIdTipoProducto');
        $contrato->intIdContr= $request->input('intIdContr');
        $contrato->varNuContrato= $request->input('varNuContrato');
        $contrato->varDescripcion= $request->input('varDescripcion');
        $contrato->varObservacion= $request->input('varObservacion');
        $contrato->fech_Ini= $request->input('fech_Ini');
        $contrato->fech_Fin= $request->input('fech_Fin');
        $contrato->varNuOS= $request->input('varNuOS');
        $contrato->deciImpTotal= (float) $request->input('deciImpTotal');
        $contrato->deciImpValor= (float) $request->input('deciImpValor');
        $contrato->deciImpSaldo= (float) $request->input('deciImpSaldo');
        $contrato->intTipoUnidad= (int) $request->input('intTipoUnidad');
        $contrato->deciPesoTotal= (float) $request->input('deciPesoTotal');
        $contrato->deciPesoSaldo= (float) $request->input('deciPesoSaldo');
        $contrato->deciAreaTotal= (float) $request->input('deciAreaTotal');
        $contrato->deciAreaSaldo= (float) $request->input('deciAreaSaldo');
        $contrato->fech_IniVal=  $request->input('fech_IniVal');
        $contrato->fech_UltValor=  $request->input('fech_UltValor');
        $contrato->acti_usua=  $request->input('acti_usua');
        $contrato->contratocol=  $request->input('contratocol');
        $contrato->acti_hora=   $current_date = date('Y/m/d H:i:s');
        $contrato->intIdEsta=  44;
        $contrato->save();
        $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];
        return $this->successResponse($mensaje);
        
    }
    public function contrato_valorizado(Request $request){
        $regla = [
            'intIdContrato' => 'required'
        ];
        $this->validate($request, $regla);
        $id_contrato=(int) $request->input('intIdContrato');
        $listar_valorizaciones= DB::select("select vc.*,pv.varCodiPeriValo,e.varDescEsta,vc.acti_hora as f_acti_hora  from valorizacion_cab vc  
                                            inner join peri_valo pv on vc.intIdPeriValo=pv.intIdPeriValo 
                                            left join estado e on vc.intIdEsta=e.intIdEsta where vc.intIdContrato=$id_contrato");
        return $this->successResponse($listar_valorizaciones);
    }
    public function editar_contrato(Request $request){
         $regla = [
            'intIdProy' => 'required',
            'intIdTipoProducto' => 'required',
            'intIdContr' => 'required',
            'varNuContrato' => 'required',
            'fech_Ini' => 'required',
            'fech_Fin' => 'required',
            'varNuOS' => 'required',
            'deciImpTotal' => 'required',
            'deciImpValor' => 'required',
            'deciImpSaldo' => 'required',
            'intTipoUnidad' => 'required',
            'deciPesoTotal' => 'required',
            'deciPesoSaldo' => 'required',
            'deciAreaTotal' => 'required',
            'deciAreaSaldo' => 'required',
            'usua_modi' => 'required',
            'intIdContr'=> 'required'
            
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima');
        Contrato::where('idcontrato', '=', $request->idcontrato)
                            ->update(['intIdProy' => $request->intIdProy,
                                'intIdTipoProducto' => $request->intIdTipoProducto,
                                'intIdContr' => $request->intIdContr,
                                'varDescripcion'=> $request->varDescripcion,
                                'varNuContrato' => $request->varNuContrato,
                                'varObservacion'=> $request->varObservacion,
                                'fech_Ini' => $request->fech_Ini,
                                'fech_Fin' => $request->fech_Fin,
                                'varNuOS' => $request->varNuOS,
                                'deciImpTotal' => $request->deciImpTotal,
                                'deciImpValor' => $request->deciImpValor,
                                'deciImpSaldo' => $request->deciImpSaldo,
                                'intTipoUnidad' => $request->intTipoUnidad,
                                'deciPesoTotal' => $request->deciPesoTotal,
                                'deciPesoSaldo' => $request->deciPesoSaldo,
                                'deciAreaTotal' => $request->deciAreaTotal,
                                'deciAreaSaldo' => $request->deciAreaSaldo,
                                'usua_modi' => $request->usua_modi,
                                'hora_modi' =>$current_date = date('Y/m/d H:i:s')]);
        $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];
        return $this->successResponse($mensaje);
    }

    public function contrato_id(Request $request){
        $regla = [
            'intIdContrato' => 'required'
        ];
        $this->validate($request, $regla);
        $id_contrato=(int) $request->input('intIdContrato');
        $listar = DB::select("SELECT p.varCodiProy,tp.varDescTipoProd,co.varRazCont,c.varNuContrato,c.deciImpTotal,c.deciImpValor,c.deciImpSaldo,
                            c.fech_Ini,c.fech_Fin,(case c.intTipoUnidad when 1 then 'PESO' when 2 then 'AREA' when 3 then 'AMBOS' end) as tipo_unidad,
                            c.deciPesoTotal,c.deciPesoSaldo,c.deciAreaTotal,c.deciAreaSaldo
                            FROM mimco.contrato c 
                            inner join proyecto p on c.intIdProy=p.intIdProy 
                            inner join tipo_producto tp on c.intIdTipoProducto=tp.intIdTipoProducto
                            inner join contratista co on c.intIdContr=co.intIdCont
                            where c.idcontrato=$id_contrato");
        return $this->successResponse($listar);
    }
    public function valorizacion_contrato(Request $request){
        $regla = [
            'intIdContrato' => 'required'
        ];
        $this->validate($request, $regla);
        $id_contrato=(int) $request->input('intIdContrato');
        $listar = DB::select("select v.intNumValor,p.varCodiPeriValo,v.deciImpTotal, v.deciUnidadTotal,v.varNumFactura,v.acti_hora from valorizacion_cab v 
                    inner join peri_valo p on v.intIdPeriValo=p.intIdPeriValo
                    where v.intIdContrato=$id_contrato");
        return $this->successResponse($listar);
    }
}

