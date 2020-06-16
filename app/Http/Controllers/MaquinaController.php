<?php

namespace App\Http\Controllers;

use App\Maquina;
use App\Agrupador;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaquinaController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/list_agru_acti",
     *     tags={"Gestion Maquina"},
     *     summary="lista a los agrupadores solo ACTIVOS",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="ista a los agrupadores solo ACTIVOS"
     *     )
     * )
     */
       public function list_agru_acti() {
        $list_agru = Agrupador::where('varEstaAgru','=','ACT')
                            ->select('intIdAgru', 'varCodiAgru', 'varDescAgru', 'varEstaAgru')->get();

        return $this->successResponse($list_agru);
    }
    
    
    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_maqu_acti",
     *     tags={"Gestion Maquina"},
     *     summary="Listar las maquinas ,en  estado ACTIVA",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar las maquinas ,en  estado ACTIVA"
     *     )
     * )
     */
    public function list_maqu_acti() {
        $list_maquina = Maquina::where('intIdEsta', '=', 3)
                        ->select('intIdMaquinas', 'varDescripcion')->get();

        return $this->successResponse($list_maquina);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_maqui_todo",
     *     tags={"Gestion Maquina"},
     *     summary="Listar todas las maquinas",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Listar todas las maquinas"
     *     )
     * )
     */
    public function list_maqui_todo() {
        $list_maquina = Maquina::join('agrupador','agrupador.intIdAgru','=','maquinas.intIdAgru')
                                ->join('estado','estado.intIdEsta','=','maquinas.intIdEsta')
                        ->select('maquinas.intIdMaquinas',
                                'maquinas.varDescripcion',
                                'maquinas.intIdAgru',
                                'agrupador.varDescAgru',
                                'maquinas.intIdEsta',
                                'estado.varDescEsta',
                                'maquinas.acti_usua',
                                'maquinas.acti_hora',
                                'maquinas.usua_modi',
                                'maquinas.hora_modi')->get();

        return $this->successResponse($list_maquina);
    }

    
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_maqui",
     *     tags={"Gestion Maquina"},
     *     summary="Permite registrar una nueva maquina",
     *     @OA\Parameter(
     *         description="Ingrese la descripcion de la maquina",
     *         in="path",
     *         name="varDescripcion",
     *         example="CORTADORA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el id del Agrupador",
     *         in="path",
     *         name="intIdAgru",
     *         example="5",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * *      @OA\Parameter(
     *         description="Ingrese el id del estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="3",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el codigo del usuario quien lo ha registrado",
     *                   
     * 
     *          in="path",
     *         name="acti_usua",
     *         example="edimir_timana",
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
     *         @OA\Property(
     *                     property="intIdAgru",
     *                     type="string"
     *                 ) ,
     *         @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *      @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"varDescripcion": "CORTADORA","intIdAgru":"5","intIdEsta":"3","acti_usua":"edimir_timana","intIdEsta":"a"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La descripcion ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function regi_maqui(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdAgru' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdAgru = $request->input('intIdAgru');
        $varDescripcion = $request->input('varDescripcion');
        $intIdEsta = $request->input('intIdEsta');
        $acti_usua = $request->input('acti_usua');
        date_default_timezone_set('America/Lima'); // CDT


        $valid_nombre = Maquina::where('varDescripcion', '=', $varDescripcion)
                ->first(['varDescripcion']);

        if ($valid_nombre['varDescripcion'] == $varDescripcion) {

            $validar['mensaje'] = "La descripcion ya existe.";
        } else {
            $regi_maquina = Maquina::create([
                        'intIdAgru' => $intIdAgru,
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
     *     path="/GestionProyectos/public/index.php/actu_maqui",
     *     tags={"Gestion Maquina"},
     *     summary="Permite Actualizar los datos de una maquina",
         @OA\Parameter(
     *         description="Ingrese el id de la maquina",
     *         in="path",
     *         name="intIdMaquinas",
     *         example="7",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese la descripcion de la maquina",
     *         in="path",
     *         name="varDescripcion",
     *         example="PUENTE_PRUEBA",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el id del Agrupador",
     *         in="path",
     *         name="intIdAgru",
     *         example="6",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * *      @OA\Parameter(
     *         description="Ingrese el id del estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="14",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingresar el codigo del usuario quien va actualizar",
     * 
     *          in="path",
     *         name="usua_modi",
     *         example="edimir_timana",
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
     *         @OA\Property(
     *                     property="intIdAgru",
     *                     type="string"
     *                 ) ,
     *         @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *      @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdMaquinas"="7", "varDescripcion": "PUENTE_PRUEBA","intIdAgru":"6","intIdEsta":"14","usua_modi":"edimir_timana"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actualizacion Satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="La descripcion ya existe."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_maqui(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdMaquinas' => 'required|max:255',
            'intIdAgru' => 'required|max:255',
            'varDescripcion' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdMaquinas = $request->input('intIdMaquinas');
        $intIdAgru = $request->input('intIdAgru');

        $varDescripcion = $request->input('varDescripcion');
        $intIdEsta = $request->input('intIdEsta');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT


        $valid_nombre = Maquina::where('varDescripcion', '=', $varDescripcion)
                ->first(['varDescripcion', 'intIdMaquinas']);
       // dd($valid_nombre['intIdMaquinas']);

        if (($valid_nombre['varDescripcion'] == $varDescripcion) && ($valid_nombre['intIdMaquinas'] == $intIdMaquinas)) {
            
            $regi_maquina = Maquina::where('intIdMaquinas', '=', $intIdMaquinas)
                    ->update([
                'intIdAgru' => $intIdAgru,
                'varDescripcion' => $varDescripcion,
                'intIdEsta' => $intIdEsta,
                'usua_modi' => $usua_modi,
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "Actualizacion Satisfactoria";
        } else {
            if (isset($valid_nombre)) {
                
                $validar['mensaje'] = "La descripcion ya existe";
            } else {
             
                $regi_maquina = Maquina::where('intIdMaquinas','=', $intIdMaquinas)
                        ->update([
                    'intIdAgru' => $intIdAgru,
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
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_maqui",
     *     tags={"Gestion Maquina"},
     *     summary="Eliminar maquina",
     *     @OA\Parameter(
     *         description="Ingresar id de la maquina",
     *         in="path",
     *         name="intIdMaquinas",
     *         example="8",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  *     @OA\Parameter(
     *         description="Ingrese el usuario que ha realizado la eliminacion de la maquina",
     *         in="path",
     *         name="usua_modi",
     *          example="jeferson_rodriguez",
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
     *                     property="intIdMaquinas",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdMaquinas": "8","usua_modi":"jeferson_rodriguez"}
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
    public function elim_maqui(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdMaquinas' => 'required|max:255',
            'usua_modi' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdMaquinas=$request->input('intIdMaquinas');
        $usua_modi = $request->input('usua_modi');
        date_default_timezone_set('America/Lima'); // CDT
        $elim_maqui = Maquina::where('intIdMaquinas', '=', $intIdMaquinas)
                ->update([
            'intIdEsta' => 14,
            'usua_modi' => $usua_modi,
            'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
        $validar['mensaje'] = "Se ha eliminado.";

        return $this->successResponse($validar);
    }

}
