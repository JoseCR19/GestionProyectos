<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\Armadores;
use App\Contratista;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArmadoresController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_arma",
     *     tags={"Gestion Armadores"},
     *      
     *     summary="lista todo los armadores",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los armadores."
     *     )
     * )
     */
    public function list_arma() {


        $list_arma = Armadores::join('contratista', 'contratista.intIdCont', '=', 'armadores.intIdCont')
                        ->join('etapa', 'etapa.intIdEtapa', '=', 'armadores.intIdEtapa')
                        ->select('armadores.intIdArmadores', 'armadores.varNombArma', 'armadores.varApelArma', 'etapa.intIdEtapa', 'etapa.varDescEtap', 'contratista.intIdCont', 'contratista.varRazCont', 
                                        'armadores.varEstaArma','armadores.acti_usua','armadores.acti_hora','armadores.usua_modi','armadores.hora_modi')->get();

        return $this->successResponse($list_arma);
    }

     /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_arma",
     *     tags={"Gestion Armadores"},
     *     summary="permite registrar un nuevo armador",
     *     @OA\Parameter(
     *         description="Nombre del nuevo armador",
     *         in="path",
     *         name="varNombArma",
     *         required=true,
     *         example= "CONFIGURATION",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     *     @OA\Parameter(
     *         description="Apellido del nuevo armador",
     *         in="path",
     *         name="varApelArma",
     *         required=true,
     *         example= "CONFIGU_R",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idEtapa",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         example= "91",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idContratista",
     *         in="path",
     *         name="intIdCont",
     *         required=true,
     *         example= "200",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el estado del armador",
     *         in="path",
     *         name="varEstaArma",
     *         required=true,
     *         example= "ACTIVO",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="El usuario que va registrar el nuevo armador",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "asuario",
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
     *                     property="varNombArma",
     *                     type="string"
     *                 ) ,
      *               @OA\Property(
     *                     property="varApelArma",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="intIdCont",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="varEstaArma",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"varNombArma": "CONFIGURATION", "varApelArma": "CONFIGU_R", "intIdEtapa": "91", "intIdCont": "200", "varEstaArma": "ACTIVO", "acti_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     *     
     * )
     */
    public function regi_arma(Request $request) {

        $regla = [
            'varNombArma' => 'required|max:255',
            'varApelArma' => 'required|max:255',
            'intIdEtapa' => 'required|max:255',
            'intIdCont' => 'required|max:255',
            'varEstaArma' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT
        $cond_arma = Armadores::where('intIdArmadores', $request->input('intIdArmadores'))->create([
            'varNombArma' => $request->input('varNombArma'),
            'varApelArma' => $request->input('varApelArma'),
            'intIdEtapa' => $request->input('intIdEtapa'),
            'intIdCont' => $request->input('intIdCont'),
            'varEstaArma' => $request->input('varEstaArma'),
            'acti_usua' => $request->input('acti_usua'),
            'acti_hora' => $current_date = date('Y/m/d H:i:s')
        ]);
        $mensaje = [
            'mensaje' => 'Guardado con exito.'
        ];

        return $this->successResponse($mensaje);
    }

    
    
    
     /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_arma",
     *     tags={"Gestion Armadores"},
     *     summary="obtiene datos del armador que se desea seleccionar",
     *     @OA\Parameter(
     *         description="Obtenemos informacion del armador que se ha elegido.",
     *         in="path",
     *         name="intIdArmadores",
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
     *                     property="intIdArmadores",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdArmadores": "263"}
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
    public function vali_arma(Request $request) {

        $regla = [
            'intIdArmadores' => 'required|max:255'
        ];
        $this->validate($request, $regla);



        $cond_id_etap = Armadores::where('intIdArmadores', $request->input('intIdArmadores'))
                ->first(['intIdArmadores', 'varNombArma', 'varApelArma', 'intIdEtapa', 'intIdCont', 'varEstaArma']);

        return $this->successResponse($cond_id_etap);
    }

    
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_arma",
     *     tags={"Gestion Armadores"},
     *     summary="permite actualizar un armador",
     * 
     * @OA\Parameter(
     *         description="Ingrese el idArmadores que se va actualizar",
     *         in="path",
     *         name="intIdArmadores",
     *         required=true,
     *         example= "CONFIGURATION",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     *     @OA\Parameter(
     *         description="Nombre del  armador",
     *         in="path",
     *         name="varNombArma",
     *         required=true,
     *         example= "CONFIGURATION",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     *     @OA\Parameter(
     *         description="Apellido del  armador",
     *         in="path",
     *         name="varApelArma",
     *         required=true,
     *         example= "CONFIGU_R",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idEtapa",
     *         in="path",
     *         name="intIdEtapa",
     *         required=true,
     *         example= "91",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el idContratista",
     *         in="path",
     *         name="intIdCont",
     *         required=true,
     *         example= "200",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos el estado del armador",
     *         in="path",
     *         name="varEstaArma",
     *         required=true,
     *         example= "ACTIVO",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="El usuario que va registrar el nuevo armador",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         example= "usua_modi",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     * 
     *              @OA\Property(
     *                     property="intIdArmadores",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varNombArma",
     *                     type="string"
     *                 ) ,
      *               @OA\Property(
     *                     property="varApelArma",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="intIdEtapa",
     *                     type="string"
     *                 ) ,
      *              @OA\Property(
     *                     property="intIdCont",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="varEstaArma",
     *                     type="string"
     *                 ) ,
      *                  @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"intIdArmadores":"48" ,"varNombArma": "CONFIGUR_ACTU", "varApelArma": "CONFIGU_ACTU", "intIdEtapa": "91", "intIdCont": "150", "varEstaArma": "INACTIVO", "usua_modi": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     *     
     * )
     */
    public function actu_arma(Request $request) {

        $regla = [
            'intIdArmadores'=>'required|max:255',
           
            'varNombArma' => 'required|max:255',
            'varApelArma' => 'required|max:255',
            'intIdEtapa' => 'required|max:255',
            'intIdCont' => 'required|max:255',
            'varEstaArma' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT
        $cond_arma = Armadores::where('intIdArmadores', $request->input('intIdArmadores'))->update([
            'varNombArma'=>$request->input('varNombArma'),
            'varApelArma'=>$request->input('varApelArma'),
            'intIdEtapa'=>$request->input('intIdEtapa'),
            'intIdCont'=>$request->input('intIdCont'),
            'varEstaArma' => $request->input('varEstaArma'),
            'usua_modi' => $request->input('usua_modi'),
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
        $mensaje = [
            'mensaje' => 'Actualizacion exitosa.'
        ];

        return $this->successResponse($mensaje);
    }

    
    
      /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_arma",
     *     tags={"Gestion Armadores"},
     *     summary="inactiva un armador",
     *     @OA\Parameter(
     *         description="Numero de dni del usuario",
     *         in="path",
     *         name="intIdArmadores",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="codigo de usuario que se usa para ingresar al sistema",
     *         in="path",
     *         name="codi_usua",
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
     *                     property="intIdArmadores",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ),
     *                 example={"intIdArmadores": "91", "codi_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El usuario ha sido eliminado."
     *     )
     * )
     */
    public function elim_arma(Request $request) {

        $regla = ['intIdArmadores' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $bus_armadores = Armadores::where('intIdArmadores', $request->input('intIdArmadores'))
                ->first(['intIdArmadores']);

        date_default_timezone_set('America/Lima'); // CDT
        if ($bus_armadores['intIdArmadores'] == ($request->input('intIdArmadores'))) {
            $elim_armador = Armadores::where('intIdArmadores', $request->input('intIdArmadores'))
                    ->update([
                'varEstaArma' => 'INA',
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $mensaje = [
                'mensaje' => 'se ha eliminado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $mensaje = [
                'mensaje' => 'Error.'
            ];
            return $this->successResponse($mensaje);
        }
    }

}
