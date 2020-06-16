<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEtapas;
use App\Etapa;
use App\TipoProducto;
use App\UnidadMedida;
use App\Procesos;
use App\Planta;
use App\Colaborador;
use App\detalleAgrupadorColaborador;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ColaboradorController extends Controller {

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
     *     path="/GestionProyectos/public/index.php/asig_cola_aun_agru",
     *     tags={"Gestion Colaborador"},
     *     summary="obtiene datos del usuario a través del dni",
     *     @OA\Parameter(
     *         description="ingresamos el idAgrupador",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   @OA\Parameter(
     *         description="ingresamos el idColaborador",
     *         in="path",
     *         name="intIdColaborador",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="registrar la usuario que esta asignando un colaborador a un grupo",
     *         in="path",
     *         name="acti_usua",
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
     *                 example={"varNumeDni": "25214121"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registrado satisfactorio."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function asig_cola_aun_agru(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdAgru' => 'required|max:255',
            'intIdColaborador' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT

        $intIdAgru = ($request->input('intIdAgru'));
        $intIdColaborador = ($request->input('intIdColaborador'));
        $acti_usua = ($request->input('acti_usua'));

        for ($i = 0; $i < count($intIdColaborador); $i++) {
            $agre = detalleAgrupadorColaborador::create([
                        'intIdAgru' => $intIdAgru,
                        'intIdColaborador' => $intIdColaborador[$i],
                        'intIdEsta' => 3,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_cola_agru",
     *     tags={"Gestion Colaborador"},
     *     summary="cambio el estado",
     *     @OA\Parameter(
     *         description="Id Agrupador seleccionara",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *     @OA\Parameter(
     *         description="idColaborador que se usa para ingresar al sistema",
     *         in="path",
     *         name="intIdColaborador",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *   *     @OA\Parameter(
     *         description="codigo de usuario que se usa para ingresar al sistema",
     *         in="path",
     *         name="usua_modi",
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
     *                     property="intIdColaborador",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ),
     *                 example={"intIdAgru": "5", "intIdColaborador": "10","usua_modi":"usuarios"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="El usuario ha sido eliminado."
     *     )
     * )
     */
    public function elim_cola_agru(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdAgru' => 'required|max:255',
            'intIdColaborador' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $intIdAgru = ($request->input('intIdAgru'));
        $intIdColaborador = ($request->input('intIdColaborador'));
        $usua_modi = ($request->input('acti_usua'));

        for ($i = 0; $i < count($intIdColaborador); $i++) {

            $actu_cola = detalleAgrupadorColaborador::where('intIdAgru', '=', (int) $intIdAgru)
                    ->where('intIdColaborador', '=', (int) $intIdColaborador[$i])
                    ->update([
                'intIdEsta' => 14,
                'usua_modi' => $usua_modi,
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
        }

        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_cola_segun_id_agru",
     *     tags={"Gestion Colaborador"},
     *     summary="listar colaboradores segun el IdAgrupador",
     *     @OA\Parameter(
     *         description="Ingresamos el idAgrupador",
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
     *         description="Lista los Colaboradores para ese IdAgrupador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //LISTAR COLABORADOR SEGUN EL ID AGRUPADOR
    public function list_cola_segun_id_agru(Request $request) {
        $regla = [
            'intIdAgru' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdAgru = ($request->input('intIdAgru'));
        $validar = detalleAgrupadorColaborador::join('agrupador', 'agrupador.intIdAgru', '=', 'deta_agru_supe.intIdAgru')
                        ->join('estado', 'estado.intIdEsta', '=', 'deta_agru_supe.intIdEsta')
                        ->leftjoin('colaborador', 'colaborador.intIdColaborador', '=', 'deta_agru_supe.intIdColaborador')
                        ->where('deta_agru_supe.intIdAgru', '=', $intIdAgru)
                        ->select('deta_agru_supe.intIdAgru', 'agrupador.varDescAgru', 'deta_agru_supe.intIdColaborador', 'colaborador.varNumeIden', DB::raw("CONCAT(varNombColabo, ' ', varApelColabo) as varNombColabo"), 'deta_agru_supe.intIdEsta', 'estado.varDescEsta', 'deta_agru_supe.acti_usua', 'deta_agru_supe.acti_hora', 'deta_agru_supe.usua_modi', 'deta_agru_supe.hora_modi')->get();


        return $this->successResponse($validar);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_cola",
     *      tags={"Gestion Colaborador"},
     *     summary="lista los colaboradores",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista los colaboradores."
     *     )
     * )
     */
    public function list_cola() {

        $listcolaborador = Colaborador::select('intIdColaborador', DB::raw("CONCAT(varNombColabo, ' ', varApelColabo) as varNombColabo"))
                        ->orderBy('varNombColabo', 'ASC')->get();

        return $this->successResponse($listcolaborador);
    }

    public function asig_cola_aun_agru_jquery(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdColaborador' => 'required',
            'intIdAgru' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdColaborador = json_decode($request->input('intIdColaborador'));
        $intIdAgru = $request->input('intIdAgru');
        $acti_usua = $request->input('acti_usua');
        //dd(count($numbintIdCont));
        //dd(" 1: ".$numbintIdCont[0]." 2: ".($numbintIdCont[1])." 3: ".($numbintIdCont[2]));
        // $corr_invi = mb_strtolower($deta_invi[$i]->CORREO, 'UTF-8');
        $vali_id_agru = detalleAgrupadorColaborador::where('intIdAgru', '=', $intIdAgru)
                ->get();
        // ELIMINO TODO LOS QUE PERTENECEN AL ID AGRUPADOR         
        detalleAgrupadorColaborador::where('intIdAgru', '=', $intIdAgru)->delete();
        //AGREGAMOS 
        for ($i = 0; $i < count($intIdColaborador); $i++) {
            $id_cola = $intIdColaborador[$i]->Id;
            $id_grup = $intIdColaborador[$i]->IdAgrupador;
            $agre_id_agru = detalleAgrupadorColaborador::create([
                        'intIdColaborador' => $id_cola,
                        'intIdAgru' => $id_grup,
                        'intIdEsta' => 3,
                        'acti_usua' => $acti_usua,
                        'acti_hora' => $current_date = date('Y/m/d H:i:s')
            ]);
        }
        $validar["mensaje"] = "Registro Satisfactorio.";
        return $this->successResponse($validar);
    }

    public function listar_colaborador_tipo_etapa(Request $request) {
        $regla = [
            'intIdTipoEtap.required' => 'EL Campo Tipo Etapa es obligatorio'];
        $validator = Validator::make($request->all(), ['intIdTipoEtap' => 'required|max:255'], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $listar_trabajador = DB::select(" select c.intIdColaborador,c.varNumeIden,concat(c.varNombColabo,' ',c.varApelColabo) as Nombres from colaborador c");
            for ($i = 0; count($listar_trabajador) > $i; $i++) {
                $id_colaborador = $listar_trabajador[$i]->intIdColaborador;
                $listar_colaboradores = DB::select("select d.intIdColaborador,c.varNumeIden,concat(c.varNombColabo,' ',c.varApelColabo) as Nombres
                                                from deta_tipo_pers d inner join colaborador c on d.intIdColaborador=c.intIdColaborador 
                                                where d.intIdTipoEtap= $request->intIdTipoEtap and d.intIdColaborador=$id_colaborador order by c.varNombColabo desc");
                if (count($listar_colaboradores) > 0) {
                } else {
                    $personal[] = ['intIdColaborador' => $listar_trabajador[$i]->intIdColaborador, 'varNumeIden' => $listar_trabajador[$i]->varNumeIden, 'Nombres' => $listar_trabajador[$i]->Nombres];
                }
            }
            return $this->successResponse($personal);
        }
    }

}
