<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoEtapasController extends Controller {

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
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_tipo_etap",
     *     tags={"Gestion Tipo Etapa"},
     *     summary="permite registrar el tipo etapa",
     *     @OA\Parameter(
     *         description="Selecciona un IdAgrupador",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         example= "6",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),

     *     @OA\Parameter(
     *         description="Ingresamos el codigo del tipo etapa que se va registrar",
     *         in="path",
     *         name="varCodiTipoEtap",
     *         required=true,
     *         example= "EMPA_PRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Descripcion del nuevo tipo de etapa",
     *         in="path",
     *         name="varDescTipoEtap",
     *         required=true,
     *         example= "EMPAQUE_PRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="estado",
     *         in="path",
     *         name="varEstaTipoEtap",
     *         required=false,
     *         example= "ACT",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Usuario que va registrar el nuevo tipo etapa",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "montañez_andy",
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
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"intIdAgru": "6", "varCodiTipoEtap": "EMPA_PRUEBA", "varDescTipoEtap": "EMPAQUE_PRUEBA", "varEstaTipoEtap": "ACTIVO", "acti_usua": "montañez_andy"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="Código de Tipo Etapa Ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *    
     * )
     */
    //registrar el tipo de etapa  
    public function regi_tipo_etap(Request $request) {

        $regla = [
            'intIdAgru' => 'required|max:255',
            'varCodiTipoEtap' => 'required|max:255',
            'varDescTipoEtap' => 'required|max:255',
            // 'varEstaTipoEtap'=>'required|max:255',
            'acti_usua' => 'required|max:255',
                //'acti_hora'=>'required|max:255',
        ];
        $this->validate($request, $regla);

        $cond_etap = TipoEtapas::where('varCodiTipoEtap', $request->input('varCodiTipoEtap'))->first(['varCodiTipoEtap']);

        if ($cond_etap['varCodiTipoEtap'] == ($request->input('varCodiTipoEtap'))) {
            $mensaje = [
                'mensaje' => 'Código de Tipo Etapa Ya existe.'
            ];

            return $this->successResponse($mensaje);
        } else {
            date_default_timezone_set('America/Lima'); // CDT

            $ingr_etap = TipoEtapas::create([
                        'intIdAgru' => $request->input('intIdAgru'),
                        'varCodiTipoEtap' => $request->input('varCodiTipoEtap'),
                        'varDescTipoEtap' => $request->input('varDescTipoEtap'),
                        'varEstaTipoEtap' => 'ACT',
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
     *     path="/GestionProyectos/public/index.php/list_tipo_etap",
     *     tags={"Gestion Tipo Etapa"},
     *     summary="Listar todos las tipo etapas",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar Tipo Etapas."
     *     )
     * )
     */
    //listar el tipo de etapa
    public function list_tipo_etap() {


        $list_etap = TipoEtapas::join('agrupador', 'agrupador.intIdAgru', '=', 'tipoetapa.intIdAgru')
                        ->select('tipoetapa.intIdTipoEtap', 'agrupador.intIdAgru', 'agrupador.varCodiAgru', 'tipoetapa.varCodiTipoEtap',
                                        'tipoetapa.varDescTipoEtap', 'tipoetapa.varEstaTipoEtap','tipoetapa.acti_usua','tipoetapa.acti_hora','tipoetapa.usua_modi','tipoetapa.hora_modi')->get();

        return $this->successResponse($list_etap);
    }

   
     
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_tipo_etap",
     *     tags={"Gestion Tipo Etapa"},
     *     summary="obtiene datos del tipo etapa que se ha seleccionado",
     *     @OA\Parameter(
     *         description="El idTipoEtap que se va obtener su informacion",
     *         in="path",
     *         name="intIdTipoEtap",
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
     *                 example={"intIdTipoEtap": "5"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El idTipoEtapa no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_tipo_etap(Request $request) {
        $regla = [
            'intIdTipoEtap' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $vali_etap = TipoEtapas::where('intIdTipoEtap', $request->input('intIdTipoEtap'))->first(['intIdTipoEtap']);

        if ($vali_etap['intIdTipoEtap'] != ($request->input('intIdTipoEtap'))) {
            $mensaje = [
                'mensaje' => 'No se encuentra registrado el ID.'
            ];
            return $this->successResponse($mensaje);
        } else {


            $sele_tipo_etap = TipoEtapas::where('intIdTipoEtap', $request->input('intIdTipoEtap'))->first(['intIdTipoEtap']);

            $info_tipo_etap = TipoEtapas::where('tipoetapa.intIdTipoEtap', $sele_tipo_etap['intIdTipoEtap'])->join('agrupador', 'tipoetapa.intIdAgru', '=', 'agrupador.intIdAgru')
                    ->select('tipoetapa.intIdTipoEtap', 'agrupador.intIdAgru', 'agrupador.varCodiAgru', 'tipoetapa.varDescTipoEtap', 'tipoetapa.varEstaTipoEtap')
                    ->first();


            return $this->successResponse($info_tipo_etap);
        }
    }

    
    
    
    
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_tipo_etap",
     *     tags={"Gestion Tipo Etapa"},
     *     summary="Permite actualizar el tipo etapa.",
     *     @OA\Parameter(
     *         description="El idTipoEtap que se modificara",
     *         in="path",
     *         name="intIdTipoEtap",
     *         required=true,
     *         example= "120",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),
     * 
     *      @OA\Parameter(
     *         description="Selecciona un IdAgrupador",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         example= "120",
     *         @OA\Schema(
     *           type="string" 
     *         )
    *     ),

     *     @OA\Parameter(
     *         description="Ingresamos el codigo del tipo etapa",
     *         in="path",
     *         name="varCodiTipoEtap",
     *         required=true,
     *         example= "EMPA_PRUEBA_MODI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Descripcion del nuevo tipo de etapa",
     *         in="path",
     *         name="varDescTipoEtap",
     *         required=true,
     *         example= "EMPAQUE_PRUEBA_MODI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Estado de tipo etapa",
     *         in="path",
     *         name="varEstaTipoEtap",
     *         required=true,
     *         example= "ACT",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Usuario que va modificar el tipo etapa",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         example= "montañez_andy",
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
     *                     property="intIdTipoEtap",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"intIdTipoEtap":"120","intIdAgru": "120", "varCodiTipoEtap": "EMPA_PRUEBA_MODI", "varDescTipoEtap": "EMPAQUE_PRUEBA_MODI", "varEstaTipoEtap": "ACT", "usua_modi": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Actualizacion Satisfactorio"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="Código de Tipo Etapa Ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *    
     * )
     */
    public function actu_tipo_etap(Request $request) {

        $regla = [
            'intIdTipoEtap' => 'required|max:255',
            'varCodiTipoEtap' => 'required|max:255',
            'intIdAgru' => 'required|max:255',
            'varDescTipoEtap' => 'required|max:255',
            'varEstaTipoEtap' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $condi_tipo_etap = TipoEtapas::where('varCodiTipoEtap', $request->input('varCodiTipoEtap'))->first(['intIdTipoEtap']);

        if (($condi_tipo_etap['intIdTipoEtap']) == ($request->input('intIdTipoEtap'))) {
            date_default_timezone_set('America/Lima'); // CDT 

            $upta_tipo_etap = TipoEtapas::where('intIdTipoEtap', ($request->input('intIdTipoEtap')))->update([
                'varCodiTipoEtap' => $request->input('varCodiTipoEtap'),
                'intIdAgru' => $request->input('intIdAgru'),
                'varDescTipoEtap' => $request->input('varDescTipoEtap'),
                'varEstaTipoEtap' => $request->input('varEstaTipoEtap'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);

            $mensaje = [
                'mensaje' => 'Actualizacion satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else {

            if (!isset($condi_tipo_etap['intIdTipoEtap'])) {


                $upta_tipo_etap = TipoEtapas::where('intIdTipoEtap', ($request->input('intIdTipoEtap')))->update([
                    'varCodiTipoEtap' => $request->input('varCodiTipoEtap'),
                    'intIdAgru' => $request->input('intIdAgru'),
                    'varDescTipoEtap' => $request->input('varDescTipoEtap'),
                    'varEstaTipoEtap' => $request->input('varEstaTipoEtap'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s'),
                ]);

                $mensaje = [
                    'mensaje' => 'Actualizacion satisfactoria.'
                ];
                return $this->successResponse($mensaje);
            } else {

                if ($condi_tipo_etap['intIdAgru'] != ($request->input('intIdAgru'))) {

                    $mensaje = [
                        'mensaje' => 'El codigo Tipo Estapa ya esta usado.'
                    ];

                    return $this->successResponse($mensaje);
                }
            }
        }
    }

    
    
    
    
    /**
     * @OA\Post(
     *    path="/GestionProyectos/public/index.php/list_tipo_etapa_segun_agru",
     *   tags={"Gestion Tipo Etapa"},
     *     summary="obtener el agrupador mediante el id de tipo etapa",
     *     @OA\Parameter(
     *         description="Ingrese el id Tipo etapa",
     *         in="path",
     *         name="intIdTipoEtap",
        *      example="1",
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
     *                 example={"intIdTipoEtap": "1"}
     *             )
     *         )
     *     ),
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Muestra el Agrupador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_tipo_etapa_segun_agru(Request $request){
        $regla=[
          'intIdTipoEtap'=>'required|max:255'  
        ];
        $this->validate($request, $regla);
        $intIdTipoEtap=$request->input('intIdTipoEtap');
        $obten_agrupador=TipoEtapas::rightJoin('agrupador','tipoetapa.intIdAgru','=','agrupador.intIdAgru')
                                ->where('tipoetapa.intIdTipoEtap','=',$intIdTipoEtap)
                                  ->select('agrupador.intIdAgru','agrupador.varCodiAgru')->get();
        
        return $this->successResponse($obten_agrupador);
        
    }
    
    
}
