<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\TipoProducto;
use App\Chofer;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChoferController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_chof",
     *     tags={"Gestion Chofer"},
     *     summary="Listar Chofer",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar Chofer"
     *     )
     * )
     */
    public function list_chof() {
        $list_chofe = Chofer::join('estado', 'chofer.intIdEsta', '=', 'estado.intIdEsta')
                        ->join('transportista', 'transportista.intIdTrans', '=', 'chofer.intIdTrans')
                        ->select('chofer.intIdChofer','transportista.intIdTrans','transportista.varRazonSoci', 'chofer.varNumeIden', 'chofer.varNombChofer', 'chofer.varNumeLicen', 'chofer.intIdEsta', 'estado.varDescEsta', 'chofer.acti_usua', 'chofer.acti_hora', 'chofer.usua_modi', 'chofer.hora_modi')->get();
        return $this->successResponse($list_chofe);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_chof",
     *     tags={"Gestion Chofer"},
     *     summary="Se registra a un chofer",
     *     @OA\Parameter(
     *         description="Ingrese el numero de identidad",
     *         in="path",
     *         name="varNumeIden",
     *       example="73458127",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Nombres completos del chofer",
     *         in="path",
     *         name="varNombChofer",
     *        example="Enrique Lozano",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el numero licencia del conductor",
     *         in="path",
     *         name="varNumeLicen",
     *       example="X125412471",
     *         required=true,
     *        example=123,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *     @OA\Parameter(
     *         description="Ingrese el Estado de chofer",
     *         in="path",
     *         name="intIdEsta",
     *        example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresar el usuario que ha registrado al chofer",
     *         in="path",
     *         name="acti_usua",
     *        example="jose_castillo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *        
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="varNombChofer",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="varNumeLicen",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="intIdEsta",
     *                     type="integer"
     *                 ) ,
     *        @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varNumeIden": "73458127","varNombChofer":"Enrique Lozano","varNumeLicen":"X125412471",
     *                        "intIdEsta":"3","acti_usua":"jose_castillo"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El numero de licencia ya existe."
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="El numero de DNI ya se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_chof(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varNumeIden' => 'required|max:255',
            'varNombChofer' => 'required|max:255',
            'varNumeLicen' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255',
            'intIdTrans'=>'required|max:255'
        ];
        $this->validate($request, $regla);

        $varNumeIden = $request->input('varNumeIden');
        $varNombChofer = $request->input('varNombChofer');
        $varNumeLicen = $request->input('varNumeLicen');
        $intIdEsta = $request->input('intIdEsta');
        $acti_usua = $request->input('acti_usua');
         $intIdTrans = $request->input('intIdTrans');
        date_default_timezone_set('America/Lima'); // CDT

        $vali_nume_dni = Chofer::where('varNumeIden', '=', $varNumeIden)
                ->first(['intIdChofer']);
        // dd($vali_nume_dni['intIdChofer']=="");
        if ($vali_nume_dni['intIdChofer'] == "") {

            $vali_nume_lice = Chofer::where('varNumeLicen', '=', $varNumeLicen)->first(['intIdChofer']);
            if ($vali_nume_lice['intIdChofer'] == "") {
                $regi_chofer = Chofer::create([
                             'intIdTrans'=>$intIdTrans,
                            'varNumeIden' => $varNumeIden,
                            'varNombChofer' => $varNombChofer,
                            'varNumeLicen' => $varNumeLicen,
                            'intIdEsta' => $intIdEsta,
                            'acti_usua' => $acti_usua,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
                $validar['mensaje'] = "Registro Satisfactorio.";
            } else {
                $validar['mensaje'] = "El numero de licencia ya existe";
            }
        } else {
            $validar['mensaje'] = "El numero de DNI ya se encuentra registrado";
        }




        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_chof",
     *     tags={"Gestion Chofer"},
     *     summary="buscamos la informacion del chofer mediante el ID",
     *     @OA\Parameter(
     *         description="Ingresar el id del chofer",
     *         in="path",
     *         name="intIdChofer",
     *             example="6",
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
     *                     property="intIdChofer",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdChofer": "6"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El id del chofer  ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_chof(Request $request) {

        $regla = [
            'intIdChofer' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdChofer = $request->input('intIdChofer');
        //  dd($intIdChofer);
        $vali_chofer = Chofer::where('intIdChofer', '=', $intIdChofer)
                ->first(['intIdChofer', 'varNumeIden', 'varNombChofer', 'varNumeLicen', 'intIdEsta']);
        return $this->successResponse($vali_chofer);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_chofe",
     *     tags={"Gestion Chofer"},
     *     summary="Permite Actualizar los datos del chofer",
     *     @OA\Parameter(
     *         description="Ingrese el id del chofer",
     *         in="path",
     *         name="intIdChofer",
     *         example="6",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *    @OA\Parameter(
     *         description="Ingresar el nombre completo del chofer",
     *         in="path",
     *         name="varNombChofer",
     *        example="Juan Vargas Mendoza",
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
     *        example="andy_ancajima",
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
     *                     property="intIdChofer",
     *                     type="integer"
     *                 ) ,
     *             @OA\Property(
     *                     property="varNombChofer",
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
     *                 example={"intIdChofer": "6","varNombChofer":"Juan Vargas Mendoza","intIdEsta":"14","usua_modi":"andy_ancajima"}
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
    public function actu_chof(Request $request) {
        $valida = array('mensaje' => '');
        $regla = [
            'intIdChofer' => 'required|max:255',
            'varNombChofer' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'usua_modi' => 'required|max:255',
            'intIdTrans'=>'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdChofer = $request->input('intIdChofer');
        $varNombChofer = $request->input('varNombChofer');
        $intIdEsta = $request->input('intIdEsta');
        $usua_modi = $request->input('usua_modi');
        $intIdTrans=$request->input('intIdTrans');
        date_default_timezone_set('America/Lima'); // CDT
        
        $update_chofer = Chofer::where('intIdChofer', '=', $intIdChofer)
                ->update([
                    'intIdTrans'=>$intIdTrans,
            'varNombChofer' => $varNombChofer,
            'intIdEsta' => $intIdEsta,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);

        $validar['mensaje'] = "Actualizacion Satisfactoria.";
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_chof",
     *     tags={"Gestion Chofer"},
     *     summary="Cambiar el estado de un chofer",
     *     @OA\Parameter(
     *         description="Ingresar el id del chofer",
     *         in="path",
     *         name="intIdChofer",
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
     *                     property="intIdChofer",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdChofer": "2","usua_modi": "andy_ancajima"}
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
    public function elim_chof(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdChofer' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdChofer = $request->input('intIdChofer');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT
        //  dd($intIdChofer);
        $vali_transport = Chofer::where('intIdChofer', '=', $intIdChofer)
                ->update([
            'intIdEsta' => 14,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);

        $validar['mensaje'] = "Se ha eliminado.";

        return $this->successResponse($validar);
    }

}
