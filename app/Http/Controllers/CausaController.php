<?php

namespace App\Http\Controllers;

use App\TipoGrupo;
use DB;
use Illuminate\Http\Request;

class CausaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function list_causa() {
        $list_causa = DB::select('SELECT * FROM mimco.tab_causa WHERE intIdEsta=3');
        return $this->successResponse($list_causa);
    }

    public function list_caus() {
        $list_causa = DB::select('SELECT t.intIdCausa,t.varCodiCausa,t.varCodiCausa,t.varDescCausa,t.intIdEsta,t.acti_usua,t.acti_hora,t.usua_modi,t.hora_modi,e.varDescEsta FROM tab_causa t inner join estado e on t.intIdEsta=e.intIdEsta');
        
        return $this->successResponse($list_causa);
    }

    public function regi_caus(Request $request) {
        $regla = [
            'varCodiCausa' => 'required|max:255',
            'varDescCausa' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $this->validate($request, $regla);
        $varCodiCausa = $request->input('varCodiCausa');
        $varDescCausa = $request->input('varDescCausa');
        $acti_usua = $request->input('acti_usua');
        date_default_timezone_set('America/Lima'); // CDT
        $v_fecha_time = $current_date = date('Y-m-d H:i:s');
        $mensaje = "";
        $vali_codi = DB::select("select * from tab_causa where varCodiCausa='$varCodiCausa'");
        if (count($vali_codi) > 0) {
            $mensaje = "EXISTE CODIGO";
            return $this->successResponse($mensaje);
        } else {
            $insert_causa = DB::insert('insert into tab_causa(varCodiCausa,varDescCausa,acti_usua,acti_hora,intIdEsta) values(?,?,?,?,?)', [$varCodiCausa, $varDescCausa, $acti_usua, $v_fecha_time, 3]);
            if ($insert_causa === true) {
                return $this->successResponse($mensaje);
            } else {
                $mensaje = "ERROR AL REGISTRAR";
                return $this->successResponse($mensaje);
            }
        }
    }

    public function edit_caus(Request $request) {
        $regla = [
            'varCodiCausa' => 'required|max:255',
            'varDescCausa' => 'required|max:255',
            'usua_modi' => 'required|max:255',
            'intIdCausa' => 'required|max:255',
            'intIdEsta' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $this->validate($request, $regla);
        $varCodiCausa = $request->input('varCodiCausa');
        $varDescCausa = $request->input('varDescCausa');
        $usua_modi = $request->input('usua_modi');
        $intIdCausa = (int) $request->input('intIdCausa');
        $intIdEsta = (int) $request->input('intIdEsta');
        date_default_timezone_set('America/Lima'); // CDT
        $hora_modi = $current_date = date('Y-m-d H:i:s');
        $mensaje = "";
        //dd($varCodiCausa,$varDescCausa,$usua_modi,$intIdCausa,$intIdEsta);
        $vali_codi = DB::select("select intIdCausa from tab_causa where varCodiCausa='$varCodiCausa'");
        //dd($vali_codi);
        if (count($vali_codi) > 0) {
            if ($vali_codi[0]->intIdCausa === $intIdCausa) {
                $affected = DB::update("update tab_causa set varCodiCausa ='$varCodiCausa' ,varDescCausa='$varDescCausa',usua_modi='$usua_modi',hora_modi='$hora_modi',intIdEsta=$intIdEsta where intIdCausa = ?", [$intIdCausa]);
                //dd($affected);
                return $this->successResponse($mensaje);
            } else {
                $mensaje = "EXISTE CODIGO";
                return $this->successResponse($mensaje);
            }
        } else {
            //dd($varCodiCausa,$varDescCausa,$usua_modi,$hora_modi,$intIdEsta,$intIdCausa);
                $affected = DB::update("update tab_causa set varCodiCausa ='$varCodiCausa' ,varDescCausa='$varDescCausa',usua_modi='$usua_modi',hora_modi='$hora_modi' where intIdCausa = ?", [$intIdCausa]);
            return $this->successResponse($mensaje);
        }
    }

    public function dele_caus(Request $request) {
        $regla = [
            'intIdCausa' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $this->validate($request, $regla);
        $usua_modi = $request->input('usua_modi');
        $intIdCausa = (int) $request->input('intIdCausa');
        date_default_timezone_set('America/Lima'); // CDT
        $hora_modi = $current_date = date('Y-m-d H:i:s');
        $mensaje = "";
        $affected = DB::update("update tab_causa set intIdEsta =14 ,usua_modi='$usua_modi',hora_modi='$hora_modi' where intIdCausa = ?", [$intIdCausa]);
        return $this->successResponse($mensaje);
    }

}
