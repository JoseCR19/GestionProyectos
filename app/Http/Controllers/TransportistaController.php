<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\TipoProducto;
use App\Chofer;
use App\Transportista;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransportistaController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_tran",
     *     tags={"Gestion Transportista"},
     *     summary="Listar Transportistas",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar Transportistas."
     *     )
     * )
     */
    public function list_tran() {
        $listar_transportista = Transportista::join('estado', 'transportista.intIdEsta', '=', 'estado.intIdEsta')
                        ->select('transportista.intIdTrans', 'transportista.varNumeIden', 'transportista.varRazonSoci', 'transportista.varDirec', 'transportista.intIdEsta', 'estado.varDescEsta', 'transportista.acti_usua', 'transportista.acti_hora', 'transportista.usua_modi', 'transportista.hora_modi')->get();

        return $this->successResponse($listar_transportista);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_tran",
     *     tags={"Gestion Transportista"},
     *     summary="Registrar un nuevo transportista",
     *     @OA\Parameter(
     *         description="Ingrese el numero de identidad",
     *         in="path",
     *         name="varNumeIden",
     *          example="2015487212",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el nombre de la empresa",
     *         in="path",
     *         name="varRazonSoci",
     *          example="COLORADOR S.A.C",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *        *     @OA\Parameter(
     *         description="Ingrese la direccion de la empresa que se ha registrar",
     *         in="path",
     *         name="varDirec",
     *          example="Av. Las Flores Mz D LT 75",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="El estado del transportista",
     *         in="path",
     *         name="intIdEsta",
     *          example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *      *     @OA\Parameter(
     *         description="Ingresar el usuario quien va registrar al transportista",
     *         in="path",
     *         name="acti_usua",
     *          example="jose_castillo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeIden",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="varRazonSoci",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varDirec",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varNumeIden": "2015487212","varRazonSoci":"COLORADOR S.A.C","varDirec":"Av. Las Flores Mz D LT 75",
     *                         "intIdEsta":"3","acti_usua":"jose_castillo"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El numero de Razon Social ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_tran(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varNumeIden' => 'required|max:255',
            'varRazonSoci' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $varNumeIden = $request->input('varNumeIden');
        $varRazonSoci = $request->input('varRazonSoci');
        $varDirec = $request->input('varDirec');
        $intIdEsta = $request->input('intIdEsta');
        $acti_usua = $request->input('acti_usua');
        date_default_timezone_set('America/Lima'); // CDT


        $vali_iden = Transportista::where('varNumeIden', '=', $varNumeIden)
                ->first(['intIdTrans']);
        // dd($vali_iden['intIdTrans']);
        if ($vali_iden['intIdTrans'] == "") {
            // die("entre");
            $regis_transpor = Transportista::create([
                        'varNumeIden' => $varNumeIden,
                        'varRazonSoci' => $varRazonSoci,
                        'varDirec' => $varDirec,
                        'intIdEsta' => $intIdEsta,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "Registro Satisfactorio.";
        } else {
            $validar['mensaje'] = "El numero de Razon Social ya existe.";
        }

        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_tran",
     *     tags={"Gestion Transportista"},
     *     summary="buscamos la informacion del transportista mediante el ID",
     *     @OA\Parameter(
     *         description="Ingresar el id del transportista",
     *         in="path",
     *         name="intIdTrans",
     *             example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdTrans",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTrans": "2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El id del transportista ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_tran(Request $request) {

        $regla = [
            'intIdTrans' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTrans = $request->input('intIdTrans');
        //  dd($intIdChofer);
        $vali_transport = Transportista::where('intIdTrans', '=', $intIdTrans)
                ->first(['intIdTrans', 'varNumeIden', 'varRazonSoci', 'varDirec', 'intIdEsta']);
        return $this->successResponse($vali_transport);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_tran",
     *     tags={"Gestion Transportista"},
     *     summary="Permite Actualizar los datos de un transportista",
     *     @OA\Parameter(
     *         description="Ingrese el id del transportista",
     *         in="path",
     *         name="intIdTrans",
     *         example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *    @OA\Parameter(
     *         description="Ingresar el nombre completo del transportista",
     *         in="path",
     *         name="varRazonSoci",
     *        example="EVENT_SCOLE",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        @OA\Parameter(
     *         description="Ingrese la direccion del transportista",
     *         in="path",
     *         name="varDirec",
     *        example="Av. Los Olivos Mz Z Lt 32 Palmeras",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el estado de chofer(3='ACTIVO' o 14='INACTIVO')",
     *         in="path",
     *         name="intIdEsta",
     *        example="14",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="EL nombre del usuario que ha realizado la modificacion del chofer",
     *         in="path",
     *         name="usua_modi",
     *        example="jose_castillo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdTrans",
     *                     type="integer"
     *                 ) ,
     *             @OA\Property(
     *                     property="varRazonSoci",
     *                     type="string"
     *                 ) ,
     *    @OA\Property(
     *                    property="varDirec",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                    property="intIdEsta",
     *                     type="integer"
     *                 ) ,
     *                  @OA\Property(
     *                    property="usua_modi",
     *                     type="integer"
     *                 ) ,
     *                 example={"intIdTrans": "2","varRazonSoci":"EVENT_SCOLE","varDirec":"Av. Los Olivos Mz Z Lt 32 Palmeras","intIdEsta":"14","usua_modi":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactoria."
     *     ),
     *  
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_tran(Request $request) {
        $valida = array('mensaje' => '');
        $regla = [
            'intIdTrans' => 'required|max:255',
            'varRazonSoci' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTrans = $request->input('intIdTrans');
        $varRazonSoci = $request->input('varRazonSoci');
        $varDirec = $request->input('varDirec');
        $intIdEsta = $request->input('intIdEsta');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT
        $update_chofer = Transportista::where('intIdTrans', '=', $intIdTrans)
                ->update([
            'varRazonSoci' => $varRazonSoci,
            'varDirec' => $varDirec,
            'intIdEsta' => $intIdEsta,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);

        $validar['mensaje'] = "Actualizacion Satisfactoria.";
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_tran",
     *     tags={"Gestion Transportista"},
     *     summary="Cambiar el estado de un transportista",
     *     @OA\Parameter(
     *         description="Ingresar el id del transportista",
     *         in="path",
     *         name="intIdTrans",
     *             example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="Ingresa el usuario que ha modificado a un transportista",
     *         in="path",
     *         name="usua_modi",
     *             example="andy_ancajima",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdTrans",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTrans": "2","usua_modi": "andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha eliminado."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function elim_tran(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdTrans' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTrans = $request->input('intIdTrans');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT
        //  dd($intIdChofer);
        $vali_transport = Transportista::where('intIdTrans', '=', $intIdTrans)
                ->update([
            'intIdEsta' => 14,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
       

        $validar['mensaje'] = "Se ha eliminado.";

        return $this->successResponse($validar);
    }
    public function list_tran_activo() {
        $listar_transportista = Transportista::join('estado', 'transportista.intIdEsta', '=', 'estado.intIdEsta')
                                    ->where('transportista.intIdEsta','!=',14)
                        ->select('transportista.intIdTrans',
                                'transportista.varNumeIden', 
                                'transportista.varRazonSoci',
                                'transportista.varDirec',
                                'transportista.intIdEsta',
                                'estado.varDescEsta',
                                'transportista.acti_usua',
                                'transportista.acti_hora',
                                'transportista.usua_modi',
                                'transportista.hora_modi')->get();

        return $this->successResponse($listar_transportista);
    }
    public function list_chofer_trans(Request $request){
        $regla = [
            'intIdTrans' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTrans = $request->input('intIdTrans');
        $listar_choferes = DB::select("select * from chofer where intIdTrans=$intIdTrans and intIdEsta=3");
        return $this->successResponse($listar_choferes);
    }
    
    
}
