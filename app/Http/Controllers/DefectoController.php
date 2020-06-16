<?php

namespace App\Http\Controllers;

use App\Defecto;
use DB;
use Illuminate\Http\Request;

class DefectoController extends Controller {

    use \App\Traits\ApiResponser;

    public function lista_defecto() {
        $list_tipfalla = DB::select('select d.intIdDefe,d.intIdAgru,a.varDescAgru,d.varCodiDefe,d.varDescDefe,d.acti_usua,d.acti_hora,d.usua_modi,d.hora_modi,e.varDescEsta,d.intIdEsta from tab_defe d inner join agrupador a on d.intIdAgru=a.intIdAgru inner join estado e on d.intIdEsta=e.intIdEsta where e.intIdProcEsta=5');
        return $this->successResponse($list_tipfalla);
    }

    public function registrar_defecto(Request $request) {
        $regla = [
            'intIdAgru' => 'required|max:255',
            'varCodiDefe' => 'required|max:255',
            'varDescDefe' => 'required|max:255',
            'acti_usua' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdAgru = $request->input('intIdAgru');
        $varCodiDefe = $request->input('varCodiDefe');
        $cond_defecto = DB::select('select d.intIdDefe,d.intIdAgru,a.varDescAgru,d.varCodiDefe,d.varDescDefe,d.acti_usua,d.acti_hora,d.usua_modi,d.hora_modi
                                    from tab_defe d inner join agrupador a on d.intIdAgru=a.intIdAgru 
                                    where d.intIdAgru="' . $intIdAgru . '" and d.varCodiDefe="' . $varCodiDefe . '" and d.intIdEsta=3');
        //dd(count($cond_defecto));
        if (count($cond_defecto) > 0) {
            if (($cond_defecto[0]->varCodiDefe) == $varCodiDefe) {
                $mensaje = [
                    'mensaje' => 'El Codigo ya Existe. !!'
                ];
                return $this->successResponse($mensaje);
            } else {
                date_default_timezone_set('America/Lima');
                $crear_defecto = Defecto::create([
                            'intIdAgru' => $request->input('intIdAgru'),
                            'varCodiDefe' => $request->input('varCodiDefe'),
                            'varDescDefe' => $request->input('varDescDefe'),
                            'intIdEsta' => 3,
                            'acti_usua' => $request->input('acti_usua'),
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
                $mensaje = [
                    'mensaje' => 'Guardado con exito.'
                ];
                return $this->successResponse($mensaje);
            }
        } else {
            date_default_timezone_set('America/Lima');
            $crear_defecto = Defecto::create([
                        'intIdAgru' => $request->input('intIdAgru'),
                        'varCodiDefe' => $request->input('varCodiDefe'),
                        'varDescDefe' => $request->input('varDescDefe'),
                        'intIdEsta' => 3,
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
            $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];
            return $this->successResponse($mensaje);
        }
    }

    public function actualizar_defecto(Request $request) {
        $regla = [
            'intIdDefe' => 'required|max:255',
            'intIdAgru' => 'required|max:255',
            'varCodiDefe' => 'required|max:255',
            'varDescDefe' => 'required|max:255',
            'usua_modi' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $enco_defecto = Defecto::where('intIdAgru', $request->input('intIdAgru'))
                ->where('varCodiDefe', $request->input('varCodiDefe'))
                ->first(['intIdDefe']);
        if ($enco_defecto['intIdDefe'] == $request->input('intIdDefe')) {
            date_default_timezone_set('America/Lima');
            $actu_defecto = Defecto::where('intIdDefe', $request->input('intIdDefe'))
                    ->update([
                'intIdAgru' => $request->input('intIdAgru'),
                'varCodiDefe' => $request->input('varCodiDefe'),
                'varDescDefe' => $request->input('varDescDefe'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                'intIdEsta' => $request->input('intIdEsta')
            ]);
            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else {

            if (!isset($enco_defecto['intIdDefe'])) {
                date_default_timezone_set('America/Lima');
                $actu_defecto = Defecto::where('intIdDefe', $request->input('intIdDefe'))
                        ->update([
                    'intIdAgru' => $request->input('intIdAgru'),
                    'varCodiDefe' => $request->input('varCodiDefe'),
                    'varDescDefe' => $request->input('varDescDefe'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                    'intIdEsta' => $request->input('intIdEsta')
                ]);
                $mensaje = [
                    'mensaje' => 'Actualizacion Satisfactoria.'
                ];
                return $this->successResponse($mensaje);
            } else {
                if ($enco_defecto['intIdDefe'] != ($request->input('intIdDefe'))) {
                    $mensaje = [
                        'mensaje' => 'el codigo ya esta en uso.'
                    ];
                    return $this->successResponse($mensaje);
                }
            }
        }
    }

    public function lista_defecto_etapa(Request $request) {
        $regla = [
            'intIdEtapa' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdEtapa = $request->input('intIdEtapa');
        $list_defecto = DB::select("select td.varCodiDefe as intIdDefe,td.varDescDefe from etapa e 
                                    inner join tipoetapa t on e.intIdTipoEtap=t.intIdTipoEtap 
                                    inner join agrupador a on t.intIdAgru=a.intIdAgru
                                    inner join tab_defe td on t.intIdAgru=td.intIdAgru 
                                    where e.intIdEtapa=$intIdEtapa and td.intIdEsta=3");
        return $this->successResponse($list_defecto);
    }

}
