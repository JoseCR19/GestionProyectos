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
use App\contrato_tarifa;
use DateTime;
use Illuminate\Http\Response;

class Contrato_tarifaControllres extends Controller {

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

    public function listar_tarifa(Request $request) {
        $regla = [
            'intIdContrato' => 'required'
        ];
        $this->validate($request, $regla);
        $id = $id_contrato = (int) $request->input('intIdContrato');
        $listar = DB::select("SELECT ct.*,e.varDescEtap FROM mimco.contrato_tarifa ct inner join etapa e on ct.idetapa=e.intIdEtapa where idcontrato=$id");
        return $this->successResponse($listar);
    }

    public function crear_tarifa(Request $request) {
        $regla = [
            'idcontrato' => 'required',
            'idetapa' => 'required',
            'varCodVal' => 'required',
            'deciTarifa' => 'required',
            'acti_usua' => 'required',
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima');
        $tarifa = new contrato_tarifa;
        $tarifa->idcontrato = $request->input('idcontrato');
        $tarifa->idetapa = $request->input('idetapa');
        $tarifa->vardescripcion = $request->input('vardescripcion');
        $tarifa->varCodVal = $request->input('varCodVal');
        $tarifa->deciTarifa = $request->input('deciTarifa');
        $tarifa->acti_usua = $request->input('acti_usua');
        $tarifa->acti_hora = $current_date = date('Y/m/d H:i:s');
        $tarifa->save();
        $mensaje = [
            'mensaje' => 'Guardado con exito.'
        ];
        return $this->successResponse($mensaje);
    }

    public function editar_tarifa(Request $request) {
        $regla = [
            'idetapa' => 'required',
            'varCodVal' => 'required',
            'deciTarifa' => 'required',
            'usua_modi' => 'required',
            'idcontrato_tarifa' => 'required',
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima');
        contrato_tarifa::where('idcontrato_tarifa', '=', $request->idcontrato_tarifa)
                ->update(['idetapa' => $request->idetapa,
                    'varCodVal' => $request->varCodVal,
                    'usua_modi' => $request->usua_modi,
                    'vardescripcion' => $request->vardescripcion,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')]);
        $mensaje = [
            'mensaje' => 'Actualizado  con exito.'
        ];
        return $this->successResponse($mensaje);
    }
    public function contrato_tarifa(Request $request){
         $regla = [
            'intIdContrato' => 'required'
        ];
        $this->validate($request, $regla);
        $id_contrato=(int) $request->input('intIdContrato');
        $listar= DB::select("select e.varDescEtap,ct.vardescripcion,ct.deciTarifa from contrato_tarifa ct 
                            inner join etapa e on ct.idetapa=e.intIdEtapa
                            where ct.idcontrato=$id_contrato");
        return $this->successResponse($listar);
    }

}


