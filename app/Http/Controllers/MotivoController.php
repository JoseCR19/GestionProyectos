<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\TipoProducto;
use App\Motivo;
use App\TipoMotivo;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MotivoController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_moti",
     *     tags={"Gestion Motivo"},
     *     summary="Listar motivo",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar motivo"
     *     )
     * )
     */
    public function list_moti() {
        $list_mot = Motivo::join('tipo_motivo', 'tipo_motivo.intIdTipoMoti', '=', 'motivo.intIdTipoMoti')
                        ->join('estado', 'estado.intIdEsta', '=', 'motivo.intIdEsta')
                        ->select('motivo.intIdMoti', 'motivo.intIdTipoMoti', 'tipo_motivo.varDescripcion as DescpTipomotivo', 'motivo.varDescripcion', 'motivo.intIdEsta', 'estado.varDescEsta', 'motivo.acti_usua', 'motivo.acti_hora', 'motivo.usua_modi', 'motivo.hora_modi')->get();

        return $this->successResponse($list_mot);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_moti",
     *     tags={"Gestion Motivo"},
     *     summary="Registras nuevos  motivos",
     *     @OA\Parameter(
     *         description="Ingrese el tipo de motivo",
     *         in="path",
     *         name="intIdTipoMoti",
     *         example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese la descripcion del motivo",
     *         in="path",
     *         name="varDescripcion",
     *          example="RETRASO",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el usuario que va registrar un nuevo motivo",
     *         in="path",
     *         name="acti_usua",
     *         example="andy_ancajima",
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
     *                     property="intIdTipoMoti",
     *                     type="string"
     *                 ) ,
     *          @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *          @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *       @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTipoMoti": "2","varDescripcion":"RETRASO","intIdEsta":"3","acti_usua":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="La descripcion ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_moti(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdTipoMoti' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTipoMoti = $request->input('intIdTipoMoti');
        $intIdEsta = $request->input('intIdEsta');
        $varDescripcion = $request->input('varDescripcion');
        $acti_usua = $request->input('acti_usua');
        date_default_timezone_set('America/Lima'); // CDT


        $vali_descri = Motivo::where('varDescripcion', '=', $varDescripcion)
                ->first(['varDescripcion']);


        if ($varDescripcion == $vali_descri['varDescripcion']) {
            $validar['mensaje'] = "La descripcion ya existe.";
        } else {

            $regis_motivo = Motivo::create([
                        'intIdTipoMoti' => $intIdTipoMoti,
                        'varDescripcion' => $varDescripcion,
                        'intIdEsta' => $intIdEsta,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "Registro Satisfactorio.";
        }
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_moti",
     *     tags={"Gestion Motivo"},
     *     summary="Actualizar un motivo",
     *    @OA\Parameter(
     *         description="Ingrese el id de motivo que se va actualizar",
     *         in="path",
     *         name="intIdMoti",
     *         example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      
     *     @OA\Parameter(
     *         description="Ingrese el id del tipo motivo",
     *         in="path",
     *         name="intIdTipoMoti",
     *         example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      
     *       @OA\Parameter(
     *         description="Ingrese La descripcion del motivo",
     *         in="path",
     *         name="varDescripcion",
     *         example="PRUEBA_FALTA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *        @OA\Parameter(
     *         description="Ingrese el id estado",
     *         in="path",
     *         name="intIdEsta",
     *          example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *           @OA\Parameter(
     *         description="Ingrese el nombre del usuario quien va modificar un motivo",
     *         in="path",
     *         name="usua_modi",
     *          example="jose_castillo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdTipoMoti",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) , 
     *               @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     * 
     * 
     *                 example={"intIdMoti":"2","intIdTipoMoti": "2","varDescripcion":"PRUEBA_FALTA","intIdEsta":"3","usua_modi":"jose_castillo"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactoria"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="La Descripcion ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_moti(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdMoti' => 'required|max:255',
            'intIdTipoMoti' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdMoti = $request->input('intIdMoti');
        $intIdTipoMoti = $request->input('intIdTipoMoti');
        $intIdEsta = $request->input('intIdEsta');
        $varDescripcion = $request->input('varDescripcion');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT


        $vali_descri = Motivo::where('varDescripcion', '=', $varDescripcion)
                ->first(['intIdMoti', 'varDescripcion']);

        if (($vali_descri['varDescripcion'] == $varDescripcion) && ($vali_descri['intIdMoti'] == $intIdMoti)) {

            $update_moti = Motivo::where('intIdMoti', '=', $intIdMoti)
                    ->update([
                'intIdTipoMoti' => $intIdTipoMoti,
                'varDescripcion' => $varDescripcion,
                'intIdEsta' => $intIdEsta,
                'usua_modi' => $usua_modi,
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "Actualizacion Satisfactoria";
        } else {

            if (isset($vali_descri)) {
                $validar['mensaje'] = "La Descripcion ya existe.";
            } else {
                $update_moti = Motivo::where('intIdMoti', '=', $intIdMoti)
                        ->update([
                    'intIdTipoMoti' => $intIdTipoMoti,
                    'varDescripcion' => $varDescripcion,
                    'intIdEsta' => $intIdEsta,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);
                $validar['mensaje'] = "Actualizacion Satisfactoria";
            }
        }


        return $this->successResponse($validar);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_tipo_moti_act",
     *     tags={"Gestion Motivo"},
     *     summary="Listar Tipo motivos Activos",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar Tipo motivos Activos"
     *     )
     * )
     */
    public function list_tipo_moti_act() {
        $list_tipo_moti = TipoMotivo::where('intIdEsta', '=', 3)
                        ->select('intIdTipoMoti', 'varDescripcion')->get();
        return $this->successResponse($list_tipo_moti);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_mot",
     *     tags={"Gestion Motivo"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="ingresar el id del motivo",
     *         in="path",
     *         name="intIdMoti",
     *        example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="ingresar el usuario que ha modificado un motivo",
     *         in="path",
     *         name="usua_modi",
     *        example="omar_chacon",
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
     *                     property="intIdMoti",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdMoti": "2","usua_modi":"omar_chacon"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha eliminado."
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function elim_mot(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdMoti' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdMoti = $request->input('intIdMoti');
        $usua_modi = $request->input('usua_modi');
        $elim_moti = Motivo::where('intIdMoti', '=', $intIdMoti)
                ->update([
            'intIdEsta' => 14,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
        $validar['mensaje'] = "Se ha eliminado.";
        return $this->successResponse($validar);
    }

    public function list_motivo_id(Request $request) {
        $regla = [
            'intIdTipoMoti' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTipoMoti = $request->input('intIdTipoMoti');
        $motivo = DB::select("SELECT m.intIdMoti,m.varDescripcion FROM mimco.tipo_motivo tm 
                            left join mimco.motivo m on tm.intIdTipoMoti=m.intIdTipoMoti where tm.intIdTipoMoti=$intIdTipoMoti");
        return $this->successResponse($motivo);
    }

}
