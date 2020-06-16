<?php

namespace App\Http\Controllers;

//use App\Usuario;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnidadMedidaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_unid_nego_acti",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Listar la unidad de negocio que solo sea ACTIVO",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar la unidad de negocio que solo sea ACTIVO"
     *     )
     * )
     */
    public function list_unid_medida() {
        $list_undiad_medida = DB::select("SELECT intIdUniMedi,varDescMedi,varAbrevMedi FROM mimco.unidad_medida where intEstaMedi=3");
        return $this->successResponse($list_undiad_medida);
    }

    

}
