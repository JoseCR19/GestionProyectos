<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEstructurado;
use App\TipoEstructura;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoEstructuradoController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/regis_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="permite registro tipo de estructurado",
     *     @OA\Parameter(
     *         description="Descripcion de tipo de estructurado",
     *         in="path",
     *         name="varDescTipoEstru",
     *         required=true,
     *         example= "OBRA_PRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="El usuario quien esta registrando el nuevo tipo estructurado",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "mendoza_luis",
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
     *                  
     *                 example={"varDescTipoEstru": "OBRA_PRUEBA","varEstaTipoEstru":"ACT" ,"acti_usua": "mendoza_luis"}
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
     *         description="La Descripcion ya existe."
     *     ),
     * )
     */
    public function regis_tipo_estru(Request $request) {
        $regla = [
            'varDescTipoEstru' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $cond_tipo_estru = TipoEstructurado::where('varDescTipoEstru', $request->input('varDescTipoEstru'))
                ->first(['intIdTipoEstructurado', 'varDescTipoEstru', 'varEstaTipoEstru']);
        if (($cond_tipo_estru['varDescTipoEstru']) == ($request->input('varDescTipoEstru'))) {

            $mensaje = [
                'mensaje' => 'La Descripcion ya existe.'
            ];

            return $this->successResponse($mensaje);
        } else {
            date_default_timezone_set('America/Lima'); // CDT
            $crear_tipo_estru = TipoEstructurado::create([
                        'varDescTipoEstru' => $request->input('varDescTipoEstru'),
                        'varEstaTipoEstru' => 'ACT',
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
     *     path="/GestionProyectos/public/index.php/vali_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="obtiene el tipo estructurado que se ha seleccionado",
     *     @OA\Parameter(
     *         description="Obtener la descripcion",
     *         in="path",
     *         name="varDescTipoEstru",
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
     *                     property="varDescTipoEstru",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescTipoEstru": "OBRA_PRUEBA"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="La descripcion del tipo estructurado  no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_tipo_estru(Request $request) {
        $regla = [
            'varDescTipoEstru' => 'required|max:255'
        ];

        $this->validate($request, $regla);

        $busc_tipo_estru = TipoEstructurado::where('varDescTipoEstru', $request->input('varDescTipoEstru'))
                ->first(['intIdTipoEstructurado', 'varDescTipoEstru', 'varEstaTipoEstru']);

        if ($busc_tipo_estru['varDescTipoEstru'] != ($request->input('varDescTipoEstru'))) {
            $mensaje = [
                'mensaje' => 'No se encuentra registrado.'
            ];
            return $this->successResponse($mensaje);
        } else {

            return $this->successResponse($busc_tipo_estru);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="Permite Actualizacion tipo de estructurado",
     *     @OA\Parameter(
     *         description="selecciona el idTipoEstructura que se va actualizar",
     *         in="path",
     *         name="intIdTipoEstructurado",
     *         required=true,
     *         example= "9",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresa la descripcion del tipo estructurado",
     *         in="path",
     *         name="varDescTipoEstru",
     *         required=true,
     *         example= "OBRA_PRUEBA_PRO",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   @OA\Parameter(
     *         description="Cambia el estado activo o inactivo",
     *         in="path",
     *         name="varEstaTipoEstru",
     *         required=true,
     *         example= "INA",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *    @OA\Parameter(
     *         description="Cambia el estado activo o inactivo",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         example= "montañez",
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
     *                     property="intIdTipoEstructurado",
     *                     type="string"
     *                 ) ,
     *                  
     *                 example={"intIdTipoEstructurado":"9" ,"varDescTipoEstru": "OBRA_PRUEBA_PRO","varEstaTipoEstru":"INA" ,"usua_modi": "montañez"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Actualizacion Satisfactoria"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="La Descripcion ya existe."
     *     ),
     * )
     */
    public function actu_tipo_estru(Request $request) {
        $regla = [
            'varDescTipoEstru' => 'required|max:255',
            'varEstaTipoEstru' => 'required|max:255',
            'usua_modi' => 'required|max:255',
        ];

        $this->validate($request, $regla);
        $enco_tipo_estru = TipoEstructurado::where('varDescTipoEstru', $request->input('varDescTipoEstru'))
                ->first(['intIdTipoEstructurado']);
        // dd("entre");    
        ;

        if ($enco_tipo_estru['intIdTipoEstructurado'] == $request->input('intIdTipoEstructurado')) {
            date_default_timezone_set('America/Lima'); // CDT
            $actu_esta = TipoEstructurado::where('intIdTipoEstructurado', $request->input('intIdTipoEstructurado'))
                    ->update([
                'varEstaTipoEstru' => $request->input('varEstaTipoEstru'),
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $mensaje = [
                'mensaje' => 'Actualizacion Satisfactoria.'
            ];
            return $this->successResponse($mensaje);
        } else {

            if (!isset($enco_tipo_estru['intIdTipoEstructurado'])) {

                date_default_timezone_set('America/Lima'); // CDT

                $actu_tipo_ = TipoEstructurado::where('intIdTipoEstructurado', $request->input('intIdTipoEstructurado'))
                        ->update([
                    'varDescTipoEstru' => $request->input('varDescTipoEstru'),
                    'varEstaTipoEstru' => $request->input('varEstaTipoEstru'),
                    'usua_modi' => $request->input('usua_modi'),
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
                ]);
                $mensaje = [
                    'mensaje' => 'Actualizacion Satisfactoria.'
                ];
                return $this->successResponse($mensaje);
            } else {
                //echo 'hola';
                if ($enco_tipo_estru['intIdTipoEstructurado'] != ($request->input('intIdTipoEstructurado'))) {

                    $mensaje = [
                        'mensaje' => 'La Descripción Tipo Estructurado ya esta en uso.'
                    ];
                    return $this->successResponse($mensaje);
                }
            }
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="inactiva un tipo estructurado que se ha seleccionado",
     *     @OA\Parameter(
     *         description="El idTipoEstructurado que se desea inactivar",
     *         in="path",
     *         name="intIdTipoEstructurado",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="codigo de usuario que se usa para ingresar al sistema",
     *         in="path",
     *         name="usua_modi",
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
     *                     property="intIdTipoEstructurado",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ),
     *                 example={"intIdTipoEstructurado": "1000", "usua_modi": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El tipo estructurado ha sido eliminado."
     *     )
     * )
     */
    public function elim_tipo_estru(Request $request) {
        $regla = [
            'intIdTipoEstructurado' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $enco_tipo_estru = TipoEstructurado::where('intIdTipoEstructurado', $request->input('intIdTipoEstructurado'))->first(['intIdTipoEstructurado']);
        date_default_timezone_set('America/Lima'); // CDT
        if ($enco_tipo_estru['intIdTipoEstructurado'] == ($request->input('intIdTipoEstructurado'))) {
            $desh_tipo_estru = TipoEstructurado::where('intIdTipoEstructurado', $request->input('intIdTipoEstructurado'))->update([
                'varEstaTipoEstru' => 'INA',
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
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="lista todos los tipo esctructurado",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista todos los tipo esctructurado."
     *     )
     * )
     */
    public function list_tipo_estru() {
        $list_tipo_estru = TipoEstructurado::select('intIdTipoEstructurado', 'varDescTipoEstru', 'varEstaTipoEstru', 'acti_usua', 'acti_hora', 'usua_modi', 'hora_modi', DB::raw('(case when varEstaTipoEstru = "ACT" then "ACTIVO" else "INACTIVO" end) as desEstaTipoEstru'))->get();
        return $this->successResponse($list_tipo_estru);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/busc_boton_tipo_estru",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="buscar el tipo Estructurado para obtener la zona  idZona",
     *     @OA\Parameter(
     *         description="Obtener la descripcion",
     *         in="path",
     *         name="varDescTipoEstru",
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
     *                     property="varDescTipoEstru",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescTipoEstru": "CANAL"}
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
    //buscar el tipo Estructurado para obtener la zona  idZona
    public function busc_boton_tipo_estru(Request $request) {
        $regla = [
            'varDescTipoEstru' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $buscar_tipo_estru = TipoEstructurado::where('varDescTipoEstru', $request->input('varDescTipoEstru'))
                ->first(['intIdTipoEstructurado']);

        return $this->successResponse($buscar_tipo_estru);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/buscar_tipo_Estructura",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="buscar el tipo Estructura , el usuario ingresa el codigo de tipo estructura se le mandara el idTipoEstructurado",
     *     @OA\Parameter(
     *         description="Obtener la descripcion",
     *         in="path",
     *         name="varCodiEstru",
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
     *                     property="varCodiEstru",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiEstru": "ANTENA"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo.Error"
     *     )
     * )
     */
    //buscar el tipo Estructura , el usuario ingresa el codigo de tipo estructura
    // se le mandara el idTipoEstructurado
    public function buscar_tipo_Estructura(Request $request) {


        $regla = [
            'varCodiEstru' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $buscar_tipoEstructura = TipoEstructura::where('varCodiEstru', $request->input('varCodiEstru'))->first(['intIdTipoEstru', 'varCodiEstru']);

        // dd($buscar_tipoEstructura['intIdTipoEstru']);

        if ($buscar_tipoEstructura['varCodiEstru'] == ($request->input('varCodiEstru'))) {

            $mensaje = [
                'mensaje' => "Exitoso",
                'id' => $buscar_tipoEstructura['intIdTipoEstru']
            ];


            return $this->successResponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => 'Error.'
            ];
            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/validar_TipoEstructurado",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="function que retorna intIdTipoEstructurado  si existe .En caso contrario devulve un mensaje de error (Para el partlist)",
     *     @OA\Parameter(
     *         description="Obtener la descripcion",
     *         in="path",
     *         name="varDescTipoEstru",
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
     *                     property="varDescTipoEstru",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescTipoEstru": "CANAL"}
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
// function que retorna intIdTipoEstructurado  si existe .En caso contrario devulve un mensaje de error (Para el partlist)
    public function validar_TipoEstructurado(Request $request) {
        $regla = [
            'varDescTipoEstru' => 'required|max:255',
        ];


        $this->validate($request, $regla);

        $cond_tipo_estru = TipoEstructurado::where('varDescTipoEstru', $request->input('varDescTipoEstru'))
                ->first(['intIdTipoEstructurado', 'varDescTipoEstru', 'varEstaTipoEstru']);

        if ($cond_tipo_estru['varDescTipoEstru'] == ($request->input('varDescTipoEstru'))) {

            $mensaje = [
                'mensaje' => "Exitoso",
                'id' => $cond_tipo_estru['intIdTipoEstructurado']
            ];


            return $this->successResponse($mensaje);
        } else {

            $mensaje = [
                'mensaje' => 'Error.'
            ];

            return $this->successResponse($mensaje);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/validar_TipoEstructura",
     *     tags={"Gestion Tipo Estructurado"},
     *     summary="valida si el tipo estructura",
     *     @OA\Parameter(
     *         description="ingresar el codigo del Tipo Estructura ",
     *         in="path",
     *         name="varCodiEstru",
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
     *                     property="varCodiEstru",
     *                     type="string"
     *                 ) ,
     *                 example={"varCodiEstru": "MONOPOLO"}
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
    //valida si el TIPO DE ESTRUCTURA EXISTE, 
    //EN CASO QUE NO EXISTA MANDA UN "ERROR,POR FAVOR TIENE QUE REGISTRARLO."
    public function validar_TipoEstructura(Request $request) {


        $regla = [
            'varCodiEstru' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $buscar_tipoEstructura = TipoEstructura::where('varCodiEstru', $request->input('varCodiEstru'))->first(['intIdTipoEstru', 'varCodiEstru']);

        // dd($buscar_tipoEstructura['intIdTipoEstru']);

        if ($buscar_tipoEstructura['varCodiEstru'] == ($request->input('varCodiEstru'))) {
            $mensaje = [
                'mensaje' => "Exitoso",
                'id' => $buscar_tipoEstructura['intIdTipoEstru']
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
