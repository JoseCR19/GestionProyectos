<?php

namespace App\Http\Controllers;
use App\TipoEstructura;
use Illuminate\Http\Request;

class TipoEstructuraController extends Controller {

    use \App\Traits\ApiResponser;

//LISTAR
    public function list_tipo_estructura() {

        //$list_tipo_estructura = TipoEstructura::select('intIdTipoEstru', 'varCodiEstru', 'varDescrip', DB::raw('(case when varEstaEstru='ACT' then 'ACTIVO" else 'varEstaEstru' end'))->get();
        //time_format(hora_modi,'%H:%i:%s %p') 'hora_modi'
        $list_tipo_estructura = TipoEstructura::selectRaw("intIdTipoEstru, varCodiEstru, varDescrip,(case when varEstaEstru='ACT' then 'ACTIVO' else 'INACTIVO' end) as 'varEstaEstru',acti_usua, usua_modi,hora_modi,varEstaEstru as 'varEstado',acti_hora")->get();
        //date_format(acti_hora,'%d/%m/%Y')
        return $this->successResponse($list_tipo_estructura);
    }

//NUEVO REGISTRO - VALIDA SI EXISTE VARCODIESTRU
    public function regis_tipo_estructura(Request $request) {
        $regla = [
            'varCodiEstru' => 'required|max:255',
            'varDescrip' => 'required|max:255',
            'varEstaEstru' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);
//'intIdTipoEstructurado',
        $cond_tipo_estructura = TipoEstructura::where('varCodiEstru', $request->input('varCodiEstru'))
                ->first([ 'varCodiEstru', 'varDescrip', 'varEstaEstru', 'acti_usua']);

        if (($cond_tipo_estructura['varCodiEstru']) == ($request->input('varCodiEstru'))) {

            $mensaje = [
                'mensaje' => 'El Codigo ya Existe. !!'
            ];

            return $this->successResponse($mensaje);
        } else {
            date_default_timezone_set('America/Lima');
            $crear_tipo_estructura = TipoEstructura::create([
                        'varCodiEstru' => $request->input('varCodiEstru'),
                        'varDescrip' => $request->input('varDescrip'),
                        'varEstaEstru' => 'ACT',
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
            $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];

            return $this->successResponse($mensaje);
        }
    }
    
    
    public function actu_tipo_estructura(Request $request) {
        $regla = [
            'intIdTipoEstru' => 'required|max:255',
            'varCodiEstru' => 'required|max:255',
            'varDescrip' => 'required|max:255',
            'varEstaEstru' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $condi_agru = TipoEstructura::where('varCodiEstru', $request->input('varCodiEstru'))
                ->first(['intIdTipoEstru']);

        if ($condi_agru['intIdTipoEstru'] == ($request->input('intIdTipoEstru'))) {
            date_default_timezone_set('America/Lima'); // CDT
            $upda_agru = TipoEstructura::where('intIdTipoEstru', $request->input('intIdTipoEstru'))->update([
                'varCodiEstru' => $request->input('varCodiEstru'),
                'varDescrip' => $request->input('varDescrip'),
                'varEstaEstru' => $request->input('varEstaEstru'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];

            return $this->successResponse($mensaje);
        } else {

            if (!isset($condi_agru['intIdTipoEstru'])) {
                date_default_timezone_set('America/Lima'); // CDT
                $upda_agru = TipoEstructura::where('intIdTipoEstru', $request->input('intIdTipoEstru'))->update([
                    'varCodiEstru' => $request->input('varCodiEstru'),
                    'varDescrip' => $request->input('varDescrip'),
                    'varEstaEstru' => $request->input('varEstaEstru'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);

                $mensaje = [
                    'mensaje' => 'Actualizacion Satisfactoria.'
                ];

                return $this->successResponse($mensaje);
            } else {
                if ($condi_agru['intIdTipoEstru'] != ($request->input('intIdTipoEstru'))) {

                    $mensaje = [
                        'mensaje' => 'El Codigo ya Existe. !!'
                    ];

                    return $this->successResponse($mensaje);
                }
            }
        }
    }
    
    

}
