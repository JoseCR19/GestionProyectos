<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\TipoProducto;
use App\UnidadMedida;
use App\Procesos;
use App\Planta;
use App\Etapa;
use App\AsignarEtapaProyecto;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EtapaController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_tipo_prod",
     *     tags={"Gestion etapa"},
     *     summary="Listar todos los tipo productos",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todos los tipo productos"
     *     )
     * )
     */
//  lista de tipo producto
    public function list_tipo_prod() {
        $list_tipo_prod = TipoProducto::where('varEstaTipoProd', '=', 'ACT')->get(['intIdTipoProducto', 'varDescTipoProd']);
        //  dd($list_tipo_prod);
        return $this->successResponse($list_tipo_prod);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_unid_medi",
     *     tags={"Gestion etapa"},
     *     summary="Listar todos las unidad de medida",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todos las unidad de medida"
     *     )
     * )
     */
    // lista unidad de medida 
    public function list_unid_medi() {

        $list_unidadmedida = UnidadMedida::join('estado', 'estado.intIdEsta', '=', 'unidad_medida.intEstaMedi')
                        ->select('unidad_medida.intIdUniMedi', 'unidad_medida.varDescMedi', 'estado.varDescEsta')
                        ->where('estado.varDescEsta', '=', 'ACTIVO')->get();
        return $this->successResponse($list_unidadmedida);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_proc",
     *     tags={"Gestion etapa"},
     *     summary="Listar todos los procesos",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todos los procesos"
     *     )
     * )
     */
    //lista de procesos
    public function list_proc() {
        $list_proc = Procesos::where('varEstaProc', '=', '3')->get([
            'intIdProc', 'varDescProc'
        ]);
        return $this->successResponse($list_proc);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_plan",
     *     tags={"Gestion etapa"},
     *     summary="Listar planta",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar  planta"
     *     )
     * )
     */
    // lista de planta 
    public function list_plan() {
        $list_plan = Planta::where('varEstaPlanta', '=', 'ACT')->get([
            'intIdPlanta', 'varDescPlanta','dire_plant','varIdSqlDepa','varIdSqlProv','varIdSqlDist'
        ]);
        return $this->successResponse($list_plan);
    }

    /** @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_t_etap",
     *     tags={"Gestion etapa"},
     *     summary="lista el tipo de etapa que su estado sea 'ACT'",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="lista el tipo de etapa que su estado sea 'ACT'"
     *     )
     * )
     */
    // lista de tipo de etap
    public function list_t_etap() {

        $lista_tipo_etapa = TipoEtapas::where('varEstaTipoEtap', '=', 'ACT')
                ->get(['intIdTipoEtap', 'varDescTipoEtap']);





        return $this->successResponse($lista_tipo_etapa);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_etap",
     *     tags={"Gestion etapa"},
     *     summary="permite registro de la etapa",
     *     @OA\Parameter(
     *         description="Ingresamos la descripcion para la etapa que se va registrar",
     *         in="path",
     *         name="varDescEtap",
     *         required=true,
     *         example= "PRUEBA_DESPACHO",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idtipoetapa",
     *         in="path",
     *         name="intIdTipoEtap",
     *         required=true,
     *         example= "9",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Asignamos si el valor es si o no",
     *         in="path",
     *         name="varValoEtapa",
     *         required=true,
     *         example= "SI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el idProcesos para este nueva etapa",
     *         in="path",
     *         name="intIdProc",
     *         required=true,
     *         example= "7",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el idTipoproducto para la etapa.",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         example= "1",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el Id de la unidad de media",
     *         in="path",
     *         name="intIdUniMedi",
     *         required=true,
     *         example= "15",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idPlanta",
     *         in="path",
     *         name="intIdPlan",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el  boolDesp",
     *         in="path",
     *         name="boolDesp",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostMaqu",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostSupe",
     *         required=true,
     *         example= "1",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostCont",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * * *     @OA\Parameter(
     *         description="se registrar el usuario que ha creado la estapa",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "montañez",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varDescEtap",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varValoEtapa",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdProc",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                  
     *                 @OA\Property(
     *                     property="intIdUniMedi",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdPlan",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolDesp",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolMostMaqu",
     *                     type="string"
     *                 ) ,
     *             @OA\Property(
     *                     property="boolMostSupe",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolMostCont",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescEtap": "PRUEBA_DESPACHO", "intIdTipoEtap": "9", "varValoEtapa": "SI","intIdProc":"7",
     *                         "intIdTipoProducto":"1","intIdUniMedi":"15","intIdPlan":"2","boolDesp":"0","boolMostMaqu":"0",
     *                           "boolMostSupe":"1","boolMostCont":"0","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Guardado con exito."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Código de Etapa Ya existe."
     *     ),
     * )
     */
    public function regi_etap(Request $request) {
        $regla = [
            'boolDesp' => 'required|max:255',
            'varDescEtap' => 'required|max:255',
            'intIdTipoEtap' => 'required|max:255',
            'varValoEtapa' => 'required|max:255',
            'intIdProc' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdUniMedi' => 'required|max:255',
            'intIdPlan' => 'required|max:255',
            'boolMostMaqu' => 'required|max:255',
            'boolMostSupe' => 'required|max:255',
            'boolMostCont' => 'required|max:255',
            //'varEstaEtap'=>'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT





        $varDescEtap = trim($request->input('varDescEtap'));
        //  dd($varDescEtap);
        $cond_etap = Etapa::where('varDescEtap', '=', $varDescEtap)->first(['varDescEtap']);
        //dd(trim($cond_etap['varDescEtap']));



        if ($cond_etap['varDescEtap'] == $varDescEtap) {
            $mensaje = [
                'mensaje' => 'Código de Etapa Ya existe.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $BoolTipoValorizacion = "";
            if ($request->input('BoolTipoValorizacion') == "no") {
                $BoolTipoValorizacion = null;
            } else {
                $BoolTipoValorizacion = $request->input('BoolTipoValorizacion');
            }



            $crear_etap = Etapa::create([
                        'intIdTipoEtap' => $request->input('intIdTipoEtap'),
                        'varDescEtap' => $request->input('varDescEtap'),
                        'varValoEtapa' => $request->input('varValoEtapa'),
                        'intIdProc' => $request->input('intIdProc'),
                        'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                        'intIdUniMedi' => $request->input('intIdUniMedi'),
                        'intIdPlan' => $request->input('intIdPlan'),
                        'boolDesp' => $request->input('boolDesp'),
                        'varEstaEtap' => 'ACT',
                        'boolMostMaqu' => $request->input('boolMostMaqu'),
                        'boolMostSupe' => $request->input('boolMostSupe'),
                        'boolMostCont' => $request->input('boolMostCont'),
                        'boolTipoValorizacion' => $BoolTipoValorizacion,
                        'acti_usua' => $request->input('acti_usua'),
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Guardado con exito.'
            ];

            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_etap",
     *     tags={"Gestion etapa"},
     *     summary="obtiene datos estapa por el idetapa",
     *     @OA\Parameter(
     *         description="Seleccionamos el idetapa que vamos obtener la informacion",
     *         in="path",
     *         name="intIdEtapa",
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
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdEtapa": "5"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_etap(Request $request) {
        $regla = [
            'intIdEtapa' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $cond_id_etap = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->first(['intIdEtapa', 'varValoEtapa']);
        //  $prueba=$condi_etap['varValoEtapa'];
        if ($cond_id_etap['intIdEtapa'] != ($request->input('intIdEtapa'))) {
            $mensaje = [
                'mensaje' => 'No se encuentra registrado'
            ];
            return $this->successResponse($mensaje);
        } else {

            $validacion_etapa = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->first([
                'intIdEtapa', 'varDescEtap', 'boolDesp', 'intIdTipoEtap', 'varValoEtapa', 'intIdProc', 'intIdTipoProducto',
                'intIdUniMedi', 'intIdPlan', 'varEstaEtap', 'boolMostMaqu', 'boolMostSupe', 'boolMostCont'
            ]);


            return $this->successResponse($validacion_etapa);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_etap",
     *     tags={"Gestion etapa"},
     *     summary="permite actualizar una etapa seleccionada",
     * @OA\Parameter(
     *         description="Ingresar el intIdEtapa",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         example= "1000",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos la descripcion para la etapa que se va registrar",
     *         in="path",
     *         name="varDescEtap",
     *         required=true,
     *         example= "PRUEBA_DESPACHO_prueba",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idtipoetapa",
     *         in="path",
     *         name="intIdTipoEtap",
     *         required=true,
     *         example= "9",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Asignamos si el valor es si o no",
     *         in="path",
     *         name="varValoEtapa",
     *         required=true,
     *         example= "SI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el idProcesos para este nueva etapa",
     *         in="path",
     *         name="intIdProc",
     *         required=true,
     *         example= "7",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el idTipoproducto para la etapa.",
     *         in="path",
     *         name="intIdTipoProducto",
     *         required=true,
     *         example= "1",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresamos el Id de la unidad de media",
     *         in="path",
     *         name="intIdUniMedi",
     *         required=true,
     *         example= "15",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idPlanta",
     *         in="path",
     *         name="intIdPlan",
     *         required=true,
     *         example= "2",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el  boolDesp",
     *         in="path",
     *         name="boolDesp",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostMaqu",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostSupe",
     *         required=true,
     *         example= "1",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * *     @OA\Parameter(
     *         description="se colocara 1 o 0 dependiendo a la epata que se registrar",
     *         in="path",
     *         name="boolMostCont",
     *         required=true,
     *         example= "0",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     * * *     @OA\Parameter(
     *         description="se registrar el usuario que ha creado la estapa",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "montañez",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varDescEtap",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varValoEtapa",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdProc",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                  
     *                 @OA\Property(
     *                     property="intIdUniMedi",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdPlan",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolDesp",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolMostMaqu",
     *                     type="string"
     *                 ) ,
     *             @OA\Property(
     *                     property="boolMostSupe",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="boolMostCont",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescEtap": "PRUEBA_DESPACHO", "intIdTipoEtap": "9", "varValoEtapa": "SI","intIdProc":"7",
     *                         "intIdTipoProducto":"1","intIdUniMedi":"15","intIdPlan":"2","boolDesp":"0","boolMostMaqu":"0",
     *                           "boolMostSupe":"1","boolMostCont":"0","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Actualizacion exitosa."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Código de Etapa Ya existe."
     *     ),
     * )
     */
    public function actu_etap(Request $request) {

        $regla = [
            'intIdEtapa' => 'required|max:255',
            'boolDesp' => 'required|max:255',
            'varDescEtap' => 'required|max:255',
            'intIdTipoEtap' => 'required|max:255',
            'varValoEtapa' => 'required|max:255',
            'intIdProc' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdUniMedi' => 'required|max:255',
            'intIdPlan' => 'required|max:255',
            'varEstaEtap' => 'required|max:255',
            'boolMostMaqu' => 'required|max:255',
            'boolMostSupe' => 'required|max:255',
            'boolMostCont' => 'required|max:255',
            // 'boolTipoValorizacion'=> 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT

        $BoolTipoValorizacion = "";

        $condicion = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->first(['intIdEtapa']);
        if ($condicion['intIdEtapa'] == ($request->input('intIdEtapa'))) {


            if ($request->input('BoolTipoValorizacion') == "no") {
                $BoolTipoValorizacion = null;
            } else {
                $BoolTipoValorizacion = $request->input('BoolTipoValorizacion');
            }




            $crear_etap = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->update([
                'varDescEtap' => $request->input('varDescEtap'),
                'intIdTipoEtap' => $request->input('intIdTipoEtap'),
                'varValoEtapa' => $request->input('varValoEtapa'),
                'intIdProc' => $request->input('intIdProc'),
                'intIdTipoProducto' => $request->input('intIdTipoProducto'),
                'intIdUniMedi' => $request->input('intIdUniMedi'),
                'intIdPlan' => $request->input('intIdPlan'),
                'boolDesp' => $request->input('boolDesp'),
                'varEstaEtap' => $request->input('varEstaEtap'),
                'boolMostMaqu' => $request->input('boolMostMaqu'),
                'boolMostSupe' => $request->input('boolMostSupe'),
                'boolMostCont' => $request->input('boolMostCont'),
                'boolTipoValorizacion' => $BoolTipoValorizacion,
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);


            $mensaje = [
                'mensaje' => 'Actualizacion exitosa.'
            ];

            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => 'Actualizacion Incorrecta.'
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/lis_etap",
     *     tags={"Gestion etapa"},
     *     summary="Listar etapa",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar etapa"
     *     )
     * )
     */
    public function lis_etap() {



        $sele_etap = DB::table('etapa')
                        ->join('tipoetapa', 'tipoetapa.intIdTipoEtap', '=', 'etapa.intIdTipoEtap')
                        ->join('procesos', 'procesos.intIdProc', '=', 'etapa.intIdProc')
                        ->join('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'etapa.intIdTipoProducto')
                        ->join('unidad_medida', 'unidad_medida.intIdUniMedi', '=', 'etapa.intIdUniMedi')
                        ->join('planta', 'planta.intIdPlanta', '=', 'etapa.intIdPlan')
                        ->select('etapa.intIdEtapa', 'etapa.varDescEtap', 'tipoetapa.intIdTipoEtap', 'tipoetapa.varDescTipoEtap', 'etapa.varValoEtapa', 'procesos.intIdProc', 'procesos.varDescProc', 'tipo_producto.intIdTipoProducto', 'tipo_producto.varDescTipoProd', 'unidad_medida.intIdUniMedi', 'unidad_medida.varDescMedi', 'planta.intIdPlanta', 'planta.varDescPlanta', 'etapa.varEstaEtap', 'etapa.boolDesp', 'etapa.boolMostMaqu', 'etapa.boolMostSupe', 'etapa.boolMostCont', 'etapa.acti_usua', 'etapa.acti_hora', 'etapa.usua_modi', 'etapa.hora_modi', 'etapa.boolTipoValorizacion as BoolTipoValorizacion'
                        )->get();


        return $this->successResponse($sele_etap);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_etap",
     *     tags={"Gestion etapa"},
     *     summary="Permite eleminar una etapa",
     *     @OA\Parameter(
     *         description="Selecciona el idEtapa que se eliminara",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Registrar el usuario quien lo va eliminar",
     *         in="path",
     *         name="usua_modi",
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
     *                     property="varNumeDni",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdEtapa": "1000","usua_modi":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El Documento de identidad ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function elim_etap(Request $request) {
        $regla = [
            'intIdEtapa' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $buscar_id_etap = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->first(['intIdEtapa']);

        if ($buscar_id_etap['intIdEtapa'] == ($request->input('intIdEtapa'))) {

            date_default_timezone_set('America/Lima'); // CDT
            $cambi_esta_etap = Etapa::where('intIdEtapa', $request->input('intIdEtapa'))->update([
                'varEstaEtap' => 'INA',
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Se ha eliminado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => 'No se ha podido eliminar.'
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/obte_desc_tipo_etapa_acue_etap",
     *     tags={"Gestion etapa"},
     *     summary="Obtener el id y la descripcion del tipoetapa deacuerdo al idEtapa",
     *     @OA\Parameter(
     *         description="Selecciona el idEtapa",
     *         in="path",
     *         name="intIdEtapa",
     *        example="1",
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
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdEtapa": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestra la informacion tanto el id y la descripcion de tipo etapa"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function obte_desc_tipo_etapa_acue_etap(Request $request) {
        $regla = [
            'intIdEtapa' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdEtapa = $request->input('intIdEtapa');
        $obt_descrip = Etapa::join('tipoetapa', 'etapa.intIdTipoEtap', '=', 'tipoetapa.intIdTipoEtap')
                        ->where('etapa.intIdEtapa', '=', $intIdEtapa)
                        ->select('tipoetapa.intIdTipoEtap', 'tipoetapa.varDescTipoEtap')->get();
        return $this->successResponse($obt_descrip);
    }

    /**
     * @OA\Post(
     *     path="/GestionPartList/public/index.php/list_tipo_etap_proy_tipopro",
     *    tags={"Gestion etapa"},
     *     summary="lista el tipo de etapa por el idproyecto + idtipoproductos",
     *     @OA\Parameter(
     *         description="ingrese el id proyecto",
     *         in="path",
     *         name="intIdProy",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   @OA\Parameter(
     *         description="ingrese el id tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
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
     *                     property="intIdProy",
     *                     type="integer"
     *                 ) ,
     *                     @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="integer"
     *                 ) ,
     *                 example={"intIdProy": "126","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="lista el tipo etapa deacuerdo al ot+ tipoproductos"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_tipo_etap_proy_tipopro(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');

        $lista_tipo_etapa = Etapa::join('asig_etap_proy', 'asig_etap_proy.intIdEtapa', '=', 'etapa.intIdEtapa')
                        ->join('tipoetapa', 'etapa.intIdTipoEtap', '=', 'tipoetapa.intIdTipoEtap')
                        ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                        ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                        ->where('asig_etap_proy.intOrden', '!=', '')
                        ->select(DB::raw('DISTINCT tipoetapa.intIdTipoEtap'), 'tipoetapa.varDescTipoEtap')
                        ->orderBy('tipoetapa.varDescTipoEtap', 'DESC')->get();

        if (count($lista_tipo_etapa) > 0) {
            $dato_todo = ['intIdTipoEtap' => -1, 'varDescTipoEtap' => 'TODOS'];
            $lista_tipo_etapa->push($dato_todo);
            return $this->successResponse($lista_tipo_etapa);
        } else {
            return $this->successResponse($lista_tipo_etapa);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/etapa_asig_al_proy",
     *       tags={"Gestion etapa"},
     *     summary="Etapa asignada al proyecto",
     *     @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *     example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="ingrese el id del tipo producto",
     *         in="path",
     *         name="intIdProy",
     *    example="193",
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
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *            @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTipoProducto": "1","intIdProy":"193"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar las etapa"
     *     ),
     *  
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function etapa_asig_al_proy(Request $request) {
        $regla = [
            'intIdTipoProducto' => 'required|max:255',
            'intIdProy' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $intIdProy = $request->input('intIdProy');
        $visual_las_asig_etapa_proy = AsignarEtapaProyecto::join('etapa', 'etapa.intIdEtapa', '=', 'asig_etap_proy.intIdEtapa')
                        ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                        ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                        ->where('etapa.varValoEtapa', '=', 'SI')
                        ->where('etapa.varEstaEtap', '=', 'ACT')
                        ->select('etapa.intIdEtapa', 'etapa.varDescEtap')->get();
        return $this->successResponse($visual_las_asig_etapa_proy);


        /* SELECT e.intIdEtapa,e.varDescEtap FROM mimco.asig_etap_proy  a
          inner join etapa e on a.intIdEtapa=e.intIdEtapa
          where a.intIdProy=176 and a.intIdTipoProducto=1 and e.varValoEtapa='SI' and varEstaEtap='ACT';

         */
    }

    //**********************REPORTE DE VALORIZACION ***************************//

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_t_etap_repo_valo",
     *      tags={"Gestion etapa"},
     *     summary="Obtener los tipo etapa que estan asignados a la etapas del proyectos",
     *     @OA\Parameter(
     *         description="Ingresar la OT",
     *         in="path",
     *         name="intIdProy",
     *        example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="ingresar el tipo producto",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "193","intIdTipoProducto":"1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista los tipo estapa que estan asignados al proyectos"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_t_etap_repo_valo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');

        if ($intIdProy === -1) {
            //DB::raw('DISTINCT tipoetapa.intIdTipoEtap')
            $lista_tipo_etapa = TipoEtapas::join('etapa', 'tipoetapa.intIdTipoEtap', '=', 'etapa.intIdTipoEtap')
                            ->where('etapa.intIdTipoProducto', '=', $intIdTipoProducto)
                            ->where('tipoetapa.varEstaTipoEtap', '=', 'ACT')
                            ->select(DB::raw('DISTINCT tipoetapa.intIdTipoEtap'), 'tipoetapa.varDescTipoEtap')
                            ->orderBy('tipoetapa.varDescTipoEtap', 'DESC')->get();



            return $this->successResponse($lista_tipo_etapa);
        } else {
            $lista_tipo_etapa = Etapa::join('asig_etap_proy', 'asig_etap_proy.intIdEtapa', '=', 'etapa.intIdEtapa')
                            ->join('tipoetapa', 'etapa.intIdTipoEtap', '=', 'tipoetapa.intIdTipoEtap')
                            ->where('asig_etap_proy.intIdProy', '=', $intIdProy)
                            ->where('asig_etap_proy.intIdTipoProducto', '=', $intIdTipoProducto)
                            ->where('asig_etap_proy.intOrden', '!=', '')
                            ->select(DB::raw('DISTINCT tipoetapa.intIdTipoEtap'), 'tipoetapa.varDescTipoEtap')
                            ->orderBy('tipoetapa.varDescTipoEtap', 'DESC')->get();

            return $this->successResponse($lista_tipo_etapa);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/etap_actu_repo_valo",
     *      tags={"Gestion etapa"},
     *     summary="Obtemos las etapas actuales dependiendo al tipo etapa",
     *     
      @OA\Parameter(
     *         description="ingresar el tipo etapa",
     *         in="path",
     *         name="intIdTipoEtap",
     *        example="3",
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
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                
     *                 example={"intIdTipoEtap": "3"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista los tipo estapa que estan asignados al proyectos"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function etap_actu_repo_valo(Request $request) {
        $regla = [
            'intIdTipoEtap' => 'required|max:255'
        ];
        $this->validate($request, $regla);


        $intIdTipoEtap = (int) $request->input('intIdTipoEtap');

        if ($intIdTipoEtap === -1) {
            $obt_descrip = Etapa::select('intIdEtapa', 'varDescEtap')
                            ->orderBy('varDescEtap', 'DESC')->get();

            return $this->successResponse($obt_descrip);
        } else {
            $obt_descrip = Etapa::leftJoin('tipoetapa', 'etapa.intIdTipoEtap', '=', 'tipoetapa.intIdTipoEtap')
                            ->where('tipoetapa.intIdTipoEtap', '=', $intIdTipoEtap)
                            ->select('etapa.intIdEtapa', 'etapa.varDescEtap')
                            ->orderBy('etapa.varDescEtap', 'DESC')->get();
            return $this->successResponse($obt_descrip);
        }
    }



}
