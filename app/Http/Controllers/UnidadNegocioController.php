<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\TipoProducto;
use App\UnidadNegocio;
use App\Proyectos;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnidadNegocioController extends Controller {

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
    public function list_unid_nego_acti() {
        
        $list_unidad_negocio = DB::select("select  intIdSql as intIdUniNego,varDescripcion from unidad_negocio where  intIdEsta=3");          

        return $this->successResponse($list_unidad_negocio);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_unid_nego_todo",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Listar todo la unidad de negocio",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todo la unidad de negocio"
     *     )
     * )
     */
    public function list_unid_nego_todo() {
        $list_unidad_negocio = UnidadNegocio::select('intIdSql as intIdUniNego', 'varDescripcion')->get();
        
        return $this->successResponse($list_unidad_negocio);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_unid_nego",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Registrar un nuevo unidad de negocio",
     *     @OA\Parameter(
     *         description="Ingrese la descripcion",
     *         in="path",
     *         name="varDescripcion",
     *          example="HAKUNA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *          example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el usuario que ha realizado el registro",
     *         in="path",
     *         name="acti_usua",
     *          example="acti_usua",
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
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescripcion": "HAKUNA","intIdEsta":"3","acti_usua":"edimir_timana"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *   *     @OA\Response(
     *         response=201,
     *         description="La descripcion ya existe."
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_unid_nego(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $varDescripcion = $request->input('varDescripcion');
        $intIdEsta = $request->input('intIdEsta');
        $acti_usua = $request->input('acti_usua');


        $vali_descrip = UnidadNegocio::where('varDescripcion', '=', $varDescripcion)->first(['varDescripcion']);

        if ($varDescripcion == $vali_descrip['varDescripcion']) {
            $validar['mensaje'] = "La descripcion ya existe.";
        } else {
            $list_unidad_negocio = UnidadNegocio::create([
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
     *     path="/GestionProyectos/public/index.php/actu_unid_nego",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Actualiza una unidad de negocio",
     *     @OA\Parameter(
     *         description="Ingrese el id de la unidad de negocio",
     *         in="path",
     *         name="intIdUniNego",
     *          example="2",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese la descripcion de la unidad de negocio",
     *         in="path",
     *         name="varDescripcion",
     *         example="PRUEBA_NEGOCIO",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingrese el id estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="14",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        *     @OA\Parameter(
     *         description="se va ingresar el usuario quien va realizado la modificacion",
     *         in="path",
     *         name="usua_modi",
     *         example="edimir_timana",
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
     *                     property="intIdUniNego",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varDescripcion",
     *                     type="string"
     *                 ) ,
     *   *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdUniNego": "2","varDescripcion":"PRUEBA_NEGOCIO","intIdEsta":"14","usua_modi":"edimir_timana"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La descripcion ya exite."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_unid_nego(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdUniNego' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $intIdUniNego = $request->input('intIdUniNego');
        $varDescripcion = $request->input('varDescripcion');
        $intIdEsta = $request->input('intIdEsta');
        $usua_modi = $request->input('usua_modi');


        $vali_descrip = UnidadNegocio::where('varDescripcion', '=', $varDescripcion)
                ->first(['intIdUniNego', 'varDescripcion']);

        if (($varDescripcion == $vali_descrip['varDescripcion']) && ($intIdUniNego == $vali_descrip['intIdUniNego'])) {
            $update_negocio = UnidadNegocio::where('intIdUniNego', '=', $intIdUniNego)
                    ->update([
                'varDescripcion' => $varDescripcion,
                'intIdEsta' => $intIdEsta,
                'usua_modi' => $usua_modi,
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "Actualizacion Satisfactorio.";
        } else {
            if (isset($vali_descrip)) {
                $validar['mensaje'] = "La descripcion ya exite.";
            } else {

                $update_negocio = UnidadNegocio::where('intIdUniNego', '=', $intIdUniNego)
                        ->update([
                    'varDescripcion' => $varDescripcion,
                    'intIdEsta' => $intIdEsta,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);
                $validar['mensaje'] = "Actualizacion Satisfactorio.";
            }
        }

        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_unid_nego",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Para eliminar o cambiar el estado de la unidad de negocio de ACT a INACT",
     *     @OA\Parameter(
     *         description="Ingresar el id unidad de negocio",
     *         in="path",
     *         name="intIdUniNego",
     *         example="3",
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
     *                     property="intIdUniNego",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdUniNego": "2"}
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
    public function elim_unid_nego(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdUniNego' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $intIdUniNego = $request->input('intIdUniNego');
        $intIdEsta = $request->input('intIdEsta');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT


        $list_unidad_negocio = UnidadNegocio::where('intIdUniNego', '=', $intIdUniNego)
                ->update([
            'intIdEsta' => 14,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
        $validar['mensaje'] = "Se ha eliminado.";


        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/combox_list_unid_nego_proy",
     *     tags={"Gestion Unidad Negocio"},
     *     summary="Listar las OT dependiendo a id de la unidad de negocio",
     *     @OA\Parameter(
     *         description="Ingresar el id unidad de negocio",
     *         in="path",
     *         name="intIdUniNego",
     *         example="1",
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
     *                     property="intIdUniNego",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdUniNego": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra las OT dependiendo a la unidad de negocio"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function combox_list_unid_nego_proy(Request $request) {
        $regla = [
            'intIdUniNego' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdUniNego = (int) $request->input('intIdUniNego');


        
        
        date_default_timezone_set('America/Lima');
            $fechaactual = "";
            $fecha_ante_6_meses = "";
            $fechaactual = date("Y-m-d");

        if ($intIdUniNego === -1) {

           /* $fecha_ante_1_meses = date("Y-m-d", strtotime($fechaactual . "- 1 month"));
           
            $reservations = Proyectos::where('dateFechFina','>',$fecha_ante_1_meses)
                           ->select('proyecto.intIdProy as intIdproy', DB::raw('CONCAT(varCodiProy,"/",varAlias) as varCodiProy'))->get();
                            */
           
            $fecha_ante_6_meses = date("Y-m-d", strtotime($fechaactual . "- 1 month"));
           //dd($fecha_ante_6_meses);
            $reservations = Proyectos::where('dateFechFina','>',$fecha_ante_6_meses)
                                ->where('proyecto.status','=',1)
                           ->select('proyecto.intIdProy as intIdproy', DB::raw('CONCAT(varCodiProy,"/",varAlias) as varCodiProy'))->get();

            return $this->successResponse($reservations);
        } else {
            /* $fecha_ante_1_meses = date("Y-m-d", strtotime($fechaactual . "- 1 month"));
             
            $list_ot_por_unid_nego = UnidadNegocio::join('proyecto', 'unidad_negocio.intIdUniNego', '=', 'proyecto.intIdUniNego')
                            ->where('proyecto.intIdUniNego', '=', $intIdUniNego)
                            ->where('dateFechFina','>',$fecha_ante_1_meses)
                           ->select('proyecto.intIdProy as intIdproy', DB::raw('CONCAT(varCodiProy,"/",varAlias) as varCodiProy'))->get();
            return $this->successResponse($list_ot_por_unid_nego);*/
            $fecha_ante_6_meses = date("Y-m-d", strtotime($fechaactual . "- 1 month"));
             //dd($fecha_ante_6_meses);
            $list_ot_por_unid_nego = UnidadNegocio::join('proyecto', 'unidad_negocio.intIdSql', '=', 'proyecto.intIdUniNego')
                            ->where('proyecto.intIdUniNego', '=', $intIdUniNego)
                            ->where('dateFechFina','>',$fecha_ante_6_meses)
                              ->where('proyecto.status','=',1)
                           ->select('proyecto.intIdProy as intIdproy', DB::raw('CONCAT(varCodiProy,"/",varAlias) as varCodiProy'))->get();
            return $this->successResponse($list_ot_por_unid_nego);
            
            
        }
    }

}
