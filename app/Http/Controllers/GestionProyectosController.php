<?php

namespace App\Http\Controllers;

use App\Proyectos;
use App\Cliente;
use App\Contratista;
use Illuminate\Http\Request;
use App\Empresa;
use App\Estado;
use App\detalleProyecto;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;

class GestionProyectosController extends Controller {

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

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_proy",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="realiza un listado de todos los proyectos",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="realiza un listado de todos los proyectos"
     *     )
     * )
     */
    // realiza un listado de todos los proyectos
    public function list_proy() {
        date_default_timezone_set('America/Lima'); // CDT
        $readproy = Proyectos::join('cliente', 'cliente.varRucClie', '=', 'proyecto.varRucClie')
                        ->join('estado', 'estado.intIdEsta', '=', 'proyecto.intIdEsta')
                        ->select(
                                'proyecto.intIdProy', 'proyecto.IntAnioProy', 'proyecto.varCodiOt', 'proyecto.varCodiProy', 'proyecto.varDescProy', 'proyecto.varAlias', 'cliente.varRazClie', 'proyecto.dateFechInic', 'proyecto.dateFechFina', 'estado.varEsta', 'proyecto.acti_usua', 'proyecto.acti_hora', 'proyecto.usua_modi', 'proyecto.hora_modi'
                        )->orderBy('proyecto.varCodiOt', 'DESC')->get();


        return $this->successResponse($readproy);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_proy",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="obtiene datos del proyecto",
     *     @OA\Parameter(
     *         description="idProyecto que vamos ha seleccionar",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "5"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El IdProyecto ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // valida al proyecto si se encuentra en registrado .. en caso que si se encuentre manda un mensaje al usuario que el codigo no existe
    public function vali_proy(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $codi_proy = Proyectos::where('intIdProy', $request->input('intIdProy'))->first(['intIdProy']);

        if (($codi_proy['intIdProy']) != ($request->input('intIdProy'))) {

            $mensaje = [
                'mensaje' => 'El Codigo del Proyecto no se encuentra Registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {

            $nomb_cliente = Proyectos::select('intIdProy', 'varCodiOt', 'varDescProy', 'varUbicacionProy', 'varRucClie', 'dateFechInic', 'dateFechFina', 'acti_usua', 'acti_hora', 'usua_modi', 'hora_modi')->get();

            return $this->successResponse($nomb_cliente);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_proy",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="Actualizar los datos del proyecto,solamente el atributo Alias",
     *     @OA\Parameter(
     *         description="idProyecto que vamos ha seleccionar",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el Alias del proyecto",
     *         in="path",
     *         name="varAlias",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="El usuario que va realizar el actualizar tipo estructura",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * 
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                    @OA\Property(
     *                     property="varAlias",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "1000","varAlias":"FERRETEROS","usua_modi":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion satisfactoria"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El IdProyecto ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // Permite actualizar el proyecto solamente el atributo Alias    
    public function actu_proy(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            //'varAlias' => 'required|max:255',
            'usua_modi' => 'required|max:255',
                //  'boolCambioFecha'=>'required|max:255',
                // 'FechaCambTerm'=>'required|max:255',
                // 'varObservacion'=>'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $varAlias = strtoupper($request->input('varAlias'));
        $usua_modi = $request->input('usua_modi');
        //$boolCambioFecha=$request->input('boolCambioFecha');
        $FechaCambTerm = $request->input('FechaCambTerm');
        $varObservacion = $request->input('varObservacion');
        $varNombArch = $request->input('varNombArch');
        // dd($intIdProy,$varAlias,$usua_modi,$FechaCambTerm,$varObservacion);


        $validar = Proyectos::where('varAlias', '=', $varAlias)
                ->first(['varAlias']);

        date_default_timezone_set('America/Lima'); // CDT


        $buscar_fecha_final = Proyectos::where('intIdProy', '=', $intIdProy)
                ->first(['dateFechFina', 'usua_modi', 'hora_modi']);

        //dd($FechaCambTerm,$buscar_fecha_final['dateFechFina'],$FechaCambTerm,);

        if ($varObservacion == "" && $FechaCambTerm === $buscar_fecha_final['dateFechFina']) {

            if ($validar['varAlias'] == $varAlias) {
                $mensaje = [
                    'mensaje' => 'El Alias ya existe'
                ];
            } else {

                Proyectos::where('intIdProy', '=', $intIdProy)->update([
                    'varAlias' => $varAlias,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);

                $mensaje = [
                    'mensaje' => 'Se ha actualizado correctamente.'
                ];
            }
        } else {

            // dd($buscar_fecha_final['dateFechFina'],$buscar_fecha_final['usua_modi'],$buscar_fecha_final['hora_modi']);
            if ($varAlias === "") {
                detalleProyecto::create([
                    'intIdProy' => $intIdProy,
                    'FechaCambTerm' => $buscar_fecha_final['dateFechFina'],
                    'dateFechaNuev' => $FechaCambTerm,
                    'varObservacion' => $varObservacion,
                    'varNombArch' => $varNombArch,
                    'acti_usua' => $usua_modi,
                    'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                ]);

                Proyectos::where('intIdProy', '=', $intIdProy)->update([
                    'dateFechFina' => $FechaCambTerm,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);

                $mensaje = [
                    'mensaje' => 'Se ha actualizado correctamente.'
                ];
            } else {

                detalleProyecto::create([
                    'intIdProy' => $intIdProy,
                    'FechaCambTerm' => $buscar_fecha_final['dateFechFina'],
                    'dateFechaNuev' => $FechaCambTerm,
                    'varObservacion' => $varObservacion,
                    'varNombArch' => $varNombArch,
                    'acti_usua' => $usua_modi,
                    'acti_hora' => $current_date = date('Y/m/d H:i:s'),
                ]);

                Proyectos::where('intIdProy', '=', $intIdProy)->update([
                    'varAlias' => $varAlias,
                    'dateFechFina' => $FechaCambTerm,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);

                $mensaje = [
                    'mensaje' => 'Se ha actualizado correctamente.'
                ];
            }
        }




        return $this->successResponse($mensaje);
    }

    public function detalle_proyecto(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $obte_deta_proy = detalleProyecto::where('intIdProy', '=', $intIdProy)
                        ->select('FechaCambTerm','dateFechaNuev', 'varObservacion','varNombArch','acti_usua', 'acti_hora')->get();

        return $this->successResponse($obte_deta_proy);
    }

    
    
    public function obte_maxi_archi_id(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $explote = "";
        $valor = "";
        $intIdProy = $request->input('intIdProy');
        
        
        $info_deta_proy = detalleProyecto::where('intIdProy', '=', $intIdProy)
                ->select('intIdProy', 'FechaCambTerm', 'dateFechaNuev', 'varObservacion', 'varNombArch', 'acti_usua', 'acti_hora')
                ->max('varNombArch');
       // dd($info_deta_proy);
        if ($info_deta_proy == null || $info_deta_proy == "") {
            $valor = 1;
        } else {
            $explote = explode(".", $info_deta_proy);
            //dd($explote);
            //separar el  nombre de archivo 
            $explote_nombre =  explode("-", $explote[0]);
           
            $valor = $explote_nombre[1];
            
            //aumentamos 1 
            $valor = (int) $valor + 1;
        }
     
       

        return $this->successResponse($valor);
    }

    // listado de cliente  cliente

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_clie",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="Listar todo los clientes",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todo los clientes"
     *     )
     * )
     */
    public function list_clie() {

        $listClie = Cliente::all();

        return $this->successResponse($listClie);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_cont",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="Listar todo los contratista",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todo los contratista"
     *     )
     * )
     */
    public function list_cont() {

        $listcont = Contratista::select('intIdCont', 'varIdContsql', 'varRazCont', 'varRucCont', 'varEstaCont', 'acti_usua', 'acti_hora', 'usua_modi', 'hora_modi')->orderBy('varRazCont', 'ASC')->get();

        return $this->successResponse($listcont);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_empr",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="Listar todo las Empresas",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todo las Empresa"
     *     )
     * )
     */
    public function list_empr() {
        $listEmpr = Empresa::all();

        return $this->successResponse($listEmpr);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/eleg_proc_estado",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="obtenemos una lista de estado mediante el idProcEst",
     *     @OA\Parameter(
     *         description="ingresamos el idProcEstado",
     *         in="path",
     *         name="intIdProcEsta",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProcEsta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProcEsta": "4"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //intIdProcEsta
    public function eleg_proc_estado(Request $request) {
        $regla = [
            'intIdProcEsta' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        $list_esta_acti = Estado::where('intIdProcEsta', $request->input('intIdProcEsta'))
                        ->where('varEsta', '=', 'ACT')
                        ->select('intIdEsta', 'varDescEsta')->orderBy('varDescEsta', 'DESC')->get();


        return $this->successResponse($list_esta_acti);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/gsp_proy_nom",
     *     tags={"Mantenimiento de Proyecto"},
     *     summary="Obtener el nombre del programa + la zona",
     *     @OA\Parameter(
     *         description="ingresamos id del proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="126",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        @OA\Parameter(
     *         description="ingresamos el proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdProyZona":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Obtener los datos mediante los parametros"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function gsp_proy_nom(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdProyZona' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        $list_proyecto = proyectos::join('proyecto_zona', 'proyecto.intIdProy', '=', 'proyecto_zona.intIdProy')
                ->where('proyecto.intIdProy', $request->input('intIdProy'))
                ->where('proyecto_zona.intIdProyZona', $request->input('intIdProyZona'))
                ->select('proyecto.intIdProy', 'proyecto_zona.intIdProyZona', 'proyecto_zona.varDescrip', 'proyecto.varCodiOt', 'proyecto.varCodiProy', 'proyecto.varAlias')
                ->get();



        return $this->successResponse($list_proyecto);
    }

}
