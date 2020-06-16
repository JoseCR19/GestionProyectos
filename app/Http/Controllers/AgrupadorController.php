<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\Agrupador;
use App\TipoEtapas;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AgrupadorController extends Controller {

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
     * @OA\Info(title="Gestion Proyectos", version="1",
     * @OA\Contact(
     *     email="andy.ancajima@mimco.com.pe"
     *   )
     * )
     */
    
    
     /**
     * @OA\Post(
     *     path="/GestionProgramas/public/index.php/regi_usua",
     *     tags={"Gestion Agrupador"},
     *     summary="permite el registro de un agrupador",
     *     @OA\Parameter(
     *         description="Codigo para el agrupador",
     *         in="path",
     *         name="varCodiAgru",
     *         required=true,
        *         example= "DESPPRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     *    
     *     @OA\Parameter(
     *         description="Descripcion de agrupador",
     *         in="path",
     *         name="varDescAgru",
     *         required=true,
     *         example= "GALVANIZADO EPOXICO",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      *    @OA\Parameter(
     *         description="usuario quien registra",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "usuario",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiAgru",
     *                     type="string"
     *                 ) ,
      *                    @OA\Property(
     *                     property="varDescAgru",
     *                     type="string"
     *                 ) ,
      *                      @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"varCodiAgru": "KIRLM", "varEstaUsua": "ACT", "codi_usua": "usuario"}
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
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Codigo de agrupador ya se encuentra registrado."
     *     ),
     * )
     */
    public function regi_agru(Request $request) {

        $regla = [
            'varCodiAgru' => 'required|max:255',
            'varDescAgru' => 'required|max:255',
            //'varEstaAgru'=>'required|max:255',
            'acti_usua' => 'required|max:255',
                //'acti_hora'=>'required|max:255',
        ];
        $this->validate($request, $regla);

        $cond_etap = Agrupador::where('varCodiAgru', $request->input('varCodiAgru'))->first(['varCodiAgru']);

        if ($cond_etap['varCodiAgru'] == ($request->input('varCodiAgru'))) {
            $mensaje = [
                'mensaje' => 'El Codigo ya existe'
            ];
            return $this->successResponse($mensaje);
        } else {
            date_default_timezone_set('America/Lima'); // CDT

            $crea_agru = Agrupador::create([
                        'varCodiAgru' => $request->input('varCodiAgru'),
                        'varDescAgru' => $request->input('varDescAgru'),
                        'varEstaAgru' => 'ACT',
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
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_agru",
     *     tags={"Gestion Agrupador"},
     *     summary="lista todos los agrupadores",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los agrupadores."
     *     )
     * )
     */
    public function list_agru() {
        $list_agru = Agrupador::get(['intIdAgru', 'varCodiAgru', 'varDescAgru', 'varEstaAgru']);

        return $this->successResponse($list_agru);
    }
    
      
    
    
   
    
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_agru",
     *     tags={"Gestion Agrupador"},
     *     summary="obtiene datos del agrupador con su numero de id Agrupado",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdAgru",
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
     *                     property="intIdAgru",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdAgru": "5"}
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
    public function vali_agru(Request $request) {
        $regla = [
            'intIdAgru' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $vali_cond = Agrupador::where('intIdAgru', $request->input('intIdAgru'))->first(['intIdAgru']);

        if (($vali_cond['intIdAgru']) != ($request->input('intIdAgru'))) {
            $mensaje = [
                'mensaje' => 'No se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {
            $sele_agru = Agrupador::where('intIdAgru', $request->input('intIdAgru'))->first([
                'intIdAgru', 'varCodiAgru', 'varDescAgru', 'varEstaAgru'
            ]);

            return $this->successResponse($sele_agru);
        }
    }
    
    
    
    
    
    
    
     /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_agru",
     *     tags={"Gestion Agrupador"},
     *     summary="Permite actualizar el agrupador",
     *     @OA\Parameter(
     *         description="Codigo para el agrupador",
     *         in="path",
     *         name="varCodiAgru",
     *         required=true,
        *         example= "KIRLMPRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     *    
     *     @OA\Parameter(
     *         description="Descripcion de agrupador",
     *         in="path",
     *         name="varDescAgru",
     *         required=true,
     *         example= "Habilitado",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
            @OA\Parameter(
     *         description="usuario quien registra",
     *         in="path",
     *         name="codi_usua",
     *         required=true,
     *         example= "usuario",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiAgru",
     *                     type="string"
     *                 ) ,
      *                    @OA\Property(
     *                     property="varDescAgru",
     *                     type="string"
     *                 ) ,
      *                      @OA\Property(
     *                     property="codi_usua",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"varCodiAgru": "KIRLMPRUEBA", "varDescAgru": "Habilitado", "codi_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Actualizacion Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Codigo de agrupador ya se encuentra registrado."
     *     ),
     * )
     */
    public function actu_agru(Request $request) {

        $regla = [
            'intIdAgru' => 'required|max:255',
            'varCodiAgru' => 'required|max:255',
            'varDescAgru' => 'required|max:255',
            'varEstaAgru' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $condi_agru = Agrupador::where('varCodiAgru', $request->input('varCodiAgru'))
                ->first(['intIdAgru']);

        if ($condi_agru['intIdAgru'] == ($request->input('intIdAgru'))) {
            date_default_timezone_set('America/Lima'); // CDT
            $upda_agru = Agrupador::where('intIdAgru', $request->input('intIdAgru'))->update([
                'varCodiAgru' => $request->input('varCodiAgru'),
                'varDescAgru' => $request->input('varDescAgru'),
                'varEstaAgru' => $request->input('varEstaAgru'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);


            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];

            return $this->successResponse($mensaje);
        } else {

            if (!isset($condi_agru['intIdAgru'])) {
                date_default_timezone_set('America/Lima'); // CDT
                $upda_agru = Agrupador::where('intIdAgru', $request->input('intIdAgru'))->update([
                    'varCodiAgru' => $request->input('varCodiAgru'),
                    'varDescAgru' => $request->input('varDescAgru'),
                    'varEstaAgru' => $request->input('varEstaAgru'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);

                $mensaje = [
                    'mensaje' => 'Actualizacion Satisfactoria.'
                ];

                return $this->successResponse($mensaje);
            } else {
                if ($condi_agru['intIdAgru'] != ($request->input('intIdAgru'))) {

                    $mensaje = [
                        'mensaje' => 'El codigo ya esta usado.'
                    ];

                    return $this->successResponse($mensaje);
                }
            }
        }
    }

     
    
    
    
    
     /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_etap_acue_agru",
     *     tags={"Gestion Agrupador"},
     *     summary="Listar Etapa de acuerdo al agrupador",
     *     @OA\Parameter(
     *         description="documento de identidad",
     *         in="path",
     *         name="intIdAgru",
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
     *                     property="intIdAgru",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdAgru": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="muestra una lista deacuerdo a la etapa"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_etap_acue_agru(Request $request){
         $regla = [
            'intIdAgru' => 'required|max:255',
        ];
         
        $this->validate($request, $regla);
        $intIdAgru = $request->input('intIdAgru');
        $lista_etap= TipoEtapas::join('agrupador','tipoetapa.intIdAgru','=','agrupador.intIdAgru')
                            ->rightJoin('etapa','etapa.intIdTipoEtap','=','tipoetapa.intIdTipoEtap')
                             ->where('agrupador.intIdAgru','=',$intIdAgru)
                             ->select('etapa.intIdEtapa','etapa.varDescEtap')->get();
        
          return $this->successResponse($lista_etap);
    }
    
    
    
}
