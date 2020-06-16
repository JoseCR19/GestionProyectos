<?php

namespace App\Http\Controllers;

use App\Defecto;
use DB;
use Illuminate\Http\Request;

class ClienteController extends Controller {

    use \App\Traits\ApiResponser;

    public function listar_cliente(Request $request) {
        $regla = [
            'varRucClie' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $varRucClie = $request->input('varRucClie');
        $list_cliente = DB::select("SELECT * FROM cliente where varRucClie='$varRucClie'");
        return $this->successResponse($list_cliente);
    }

    public function list_departamento() {
        $list_departamento = DB::select("SELECT * FROM tab_depa order By varIdSqlDepa asc");
        return $this->successResponse($list_departamento);
    }
    public function list_provincia(Request $request){
        $regla = [
            'varIdSqlDepa' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $varIdSqlDepa = $request->input('varIdSqlDepa');
        $list_departamento = DB::select("SELECT * FROM tab_prov where varIdSqlDepa=$varIdSqlDepa order By varIdSqlProv asc");
        return $this->successResponse($list_departamento);
    }
     public function list_distrito(Request $request){
        $regla = [
            'varIdSqlProv' => 'required|max:255',
            'varIdSqlDepa' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $varIdSqlProv = $request->input('varIdSqlProv');
        $varIdSqlDepa = $request->input('varIdSqlDepa');
        $list_departamento = DB::select("SELECT * FROM tab_dist where varIdSqlDepa=$varIdSqlDepa and varIdSqlProv=$varIdSqlProv order By varIdSqlDist asc");
        return $this->successResponse($list_departamento);
    }

}
