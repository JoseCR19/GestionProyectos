<?php

namespace App\Http\Controllers;

use App\Proyectos;
use App\Cliente;
use App\Contratista;
use Illuminate\Http\Request;
use App\Empresa;
use App\Agrupador;
use App\TipoEtapas;
use App\detalleAgrupadorContratado;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Response;

class ContratistaController extends Controller {

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
     * @OA\Post(
     *     path="/GestionUsuarios/public/index.php/asig_cont_aun_agru",
     *     tags={"Gestion Contratista"},
     *     summary="Asignar un contratista aun agrupador",
     *     @OA\Parameter(
     *         description="Ingresamos el idContratista",
     *         in="path",
     *         name="intIdCont",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingresamos el idAgrupador",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ), 
     *       @OA\Parameter(
     *         description="Se registra el usuario que va realizar el asignado",
     *         in="path",
     *         name="acti_usua",
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
     *                     property="intIdCont",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdCont": "2","intIdAgru":"2","acti_usua":"usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro Satisfactorio."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //asignar un contratista aun agrupador
    public function asig_cont_aun_agru(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdCont' => 'required',
            'intIdAgru' => 'required|max:255',
            'acti_usua' => 'required|max:255',
        ];
        $this->validate($request, $regla);


        $intIdCont = json_decode($request->input('intIdCont'));
        $intIdAgru = $request->input('intIdAgru');
        $acti_usua = $request->input('acti_usua');

        //dd(count($numbintIdCont));
        //dd(" 1: ".$numbintIdCont[0]." 2: ".($numbintIdCont[1])." 3: ".($numbintIdCont[2]));
        // $corr_invi = mb_strtolower($deta_invi[$i]->CORREO, 'UTF-8');


        $vali_id_agru = detalleAgrupadorContratado::where('intIdAgru', '=', $intIdAgru)
                ->get();

        if (count($vali_id_agru) > 0) {
            // ELIMINO TODO LOS QUE PERTENECEN AL ID AGRUPADOR         
            detalleAgrupadorContratado::where('intIdAgru', '=', $intIdAgru)->delete();

            //AGREGAMOS 
            for ($i = 0; $i < count($intIdCont); $i++) {
                $id_cont = $intIdCont[$i]->Id;
                $id_grup = $intIdCont[$i]->IdAgrupador;
                $agre_id_agru = detalleAgrupadorContratado::create([
                            'intIdCont' => $id_cont,
                            'intIdAgru' => $id_grup,
                            'acti_usua' => $acti_usua,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            }



            $validar["mensaje"] = "Registro Satisfactorio.";
            return $this->successResponse($validar);
        } else {
            //  die("asd".$intIdCont[0]['Id']);
            //AGREGAMOS 


            for ($i = 0; $i < count($intIdCont); $i++) {
                $id_cont = $intIdCont[$i]->Id;
                $id_grup = $intIdCont[$i]->IdAgrupador;
                $agre_id_agru = detalleAgrupadorContratado::create([
                            'intIdCont' => $id_cont,
                            'intIdAgru' => $id_grup,
                            'acti_usua' => $acti_usua,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            }

            $validar["mensaje"] = "Registro Satisfactorio.";
            return $this->successResponse($validar);
        }



        //  
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_cont_agru",
     *     tags={"Gestion Contratista"},
     *     summary="Eliminar a un contratista en un Agrupador",
     *     @OA\Parameter(
     *         description="Ingresamos el IdAgrupador",
     *         in="path",
     *         name="intIdAgru",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingresamos el idContratista",
     *         in="path",
     *         name="intIdCont",
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
     *                  @OA\Property(
     *                     property="intIdCont",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdCont": "2","intIdAgru":"2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha Eliminado."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    //PARA ELIMINAR CONTRATISTAS EN UN AGRUPADOR
    public function elim_cont_agru(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdAgru' => 'required|max:255',
            'intIdCont' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdAgru = ($request->input('intIdAgru'));
        $intIdCont = ($request->input('intIdCont'));

        // dd((int)$intIdCont[1]);
        date_default_timezone_set('America/Lima'); // CDT

        for ($i = 0; $i < count($intIdCont); $i++) {
            $eliminar = detalleAgrupadorContratado::where('intIdAgru', '=', $intIdAgru)
                    ->where('intIdCont', '=', (int) $intIdCont[$i])
                    ->delete();
        }


        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_cont_segun_agru",
     *     tags={"Gestion Contratista"},
     *     summary="Listar los contratistas segun Idagrupador",
     *     @OA\Parameter(
     *         description="Ingresamos el IdAgrupador",
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
     *                  
     *                 example={"intIdAgru":"2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha Eliminado."
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    // //LISTAR CONTRATISTA SEGUN EL ID AGRUPADOR
    public function list_cont_segun_agru(Request $request) {
        $regla = [
            'intIdAgru' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdAgru = ($request->input('intIdAgru'));

        $validar = detalleAgrupadorContratado::join('agrupador', 'agrupador.intIdAgru', '=', 'deta_agru_cont.intIdAgru')
                        ->leftjoin('contratista', 'contratista.intIdCont', '=', 'deta_agru_cont.intIdCont')
                        ->where('deta_agru_cont.intIdAgru', '=', $intIdAgru)
                        ->select('deta_agru_cont.intIdAgru', 'agrupador.varDescAgru', 'contratista.varRucCont', 'contratista.intIdCont', 'contratista.varRazCont', 'deta_agru_cont.acti_usua', 'deta_agru_cont.acti_hora')->get();
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_cont_segun_tipo_etapa",
     *     tags={"Gestion Contratista"},
     *     summary="Listar los contratistas segun del contratrista",
     *     @OA\Parameter(
     *         description="Ingresamos el id del tipo etapa",
     *         in="path",
     *         name="intIdTipoEtap",
     *       example="7",
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
     *                 example={"intIdTipoEtap":"7"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Muestra al contratista segundo la etapa o si coloca -1 envia todo los contratista"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_cont_segun_tipo_etapa(Request $request) {
        $regla = [
            'intIdTipoEtap' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTipoEtap = (int) ($request->input('intIdTipoEtap'));

        if ($intIdTipoEtap == -1) {
            $list_todo_contratistra = Contratista::select('intIdCont', 'varRazCont')
                    ->orderBy('varRazCont', 'DESC')
                    ->get();
            return $this->successResponse($list_todo_contratistra);
        } else {
            $validar_con = TipoEtapas::join('agrupador', 'tipoetapa.intIdAgru', '=', 'agrupador.intIdAgru')
                    ->join('deta_agru_cont', 'agrupador.intIdAgru', '=', 'deta_agru_cont.intIdAgru')
                    ->rightJoin('contratista', 'deta_agru_cont.intIdCont', '=', 'contratista.intIdCont')
                    ->where('tipoetapa.intIdTipoEtap', '=', (int) $intIdTipoEtap)
                    ->select('tipoetapa.intIdTipoEtap', 'contratista.intIdCont', 'contratista.varRazCont')
                    ->orderBy('contratista.varRazCont', 'DESC')
                    ->get();
            return $this->successResponse($validar_con);
        }


        //   dd($validar_con);
        /*   $validar_con = DB::select("SELECT tipoetapa.intIdTipoEtap,tipoetapa.intIdAgru,agrupador.varDescAgru, deta_agru_cont.intIdCont,contratista.varRazCont
          FROM tipoetapa
          inner join agrupador on tipoetapa.intIdAgru=agrupador.intIdAgru
          inner join deta_agru_cont on agrupador.intIdAgru=deta_agru_cont.intIdAgru
          right join contratista on  deta_agru_cont.intIdCont = contratista.intIdCont where tipoetapa.intIdTipoEtap= '$intIdTipoEtap'");
         */
    }

}
