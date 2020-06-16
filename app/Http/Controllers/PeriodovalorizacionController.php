<?php

namespace App\Http\Controllers;

//use App\Usuario;
use App\TipoEstructurado;
use App\Periodovalorizacion;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PeriodovalorizacionController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_peri_valo",
     *     tags={"Gestion Valorización"},
     *     summary="listas los periodo valorizacion",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista todo los periodo valorizacion."
     *     )
     * )
     */
    public function list_peri_valo() {
        $list_peri_valo = Periodovalorizacion::join('estado', 'estado.intIdEsta', '=', 'peri_valo.intIdEsta')
                        ->select('peri_valo.intIdPeriValo', 'peri_valo.varCodiPeriValo', 'peri_valo.varDescPeriValo', 'peri_valo.dateFechaInic', 'peri_valo.dateFechaFina', 'peri_valo.intIdEsta', 'estado.varDescEsta', 'peri_valo.acti_usua', 'peri_valo.acti_hora', 'peri_valo.usua_modi', 'peri_valo.hora_modi')->get();
        return $this->successResponse($list_peri_valo);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_peri_valo_abie",
     *     tags={"Gestion Valorización"},
     *     summary="listas los periodo valorizacion solamente ESTADO ABIERTO",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista todo los periodo valorizacion solamente ESTADO ABIERTO."
     *     )
     * )
     */
    public function list_peri_valo_abie() {
        $list_peri_valo_abie = Periodovalorizacion::join('estado', 'estado.intIdEsta', '=', 'peri_valo.intIdEsta')
                        ->where('estado.intIdEsta', '=', 8)
                        ->select('peri_valo.intIdPeriValo', 'peri_valo.varCodiPeriValo'
                        )->get();
        return $this->successResponse($list_peri_valo_abie);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/list_peri_valo_cerr",
     *     tags={"Gestion Valorización"},
     *     summary="listas los periodo valorizacion solamente ESTADO CERRADO",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="Lista todo los periodo valorizacion solamente ESTADO CERRADO."
     *     )
     * )
     */
    public function list_peri_valo_cerr() {
        $list_peri_valo_cerr = Periodovalorizacion::join('estado', 'estado.intIdEsta', '=', 'peri_valo.intIdEsta')
                        ->where('estado.intIdEsta', '=', 12)
                        ->select('peri_valo.intIdPeriValo', 'peri_valo.varCodiPeriValo'
                        )->get();
        return $this->successResponse($list_peri_valo_cerr);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/regi_peri_valo",
     *     tags={"Gestion Valorización"},
     *     summary="permite registrar un nuevo periodo de valorizacion",
     *     @OA\Parameter(
     *         description="Ingresamos el nuevo codigo de periodo de valorizacion",
     *         in="path",
     *         name="varCodiPeriValo",
     *         required=true,
     *         example= "KIRLJSK",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="La descripcion del periodo de valorizacion que se va registrar",
     *         in="path",
     *         name="varDescPeriValo",
     *         required=true,
     *         example= "KIRLPRUEBA",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos la fecha de inicio",
     *         in="path",
     *         name="dateFechaInic",
     *         required=true,
     *         example= "2019-10-10",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingremos la fecha final",
     *         in="path",
     *         name="dateFechaFina",
     *         required=true,
     *         example= "2019-11-20",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *  @OA\Parameter(
     *         description="Ingresamos el usuario que ha registrado",
     *         in="path",
     *         name="acti_usua",
     *         required=true,
     *         example= "kunk_kuje",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *            @OA\RequestBody(
     *              @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="varCodiPeriValo",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varDescPeriValo",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="dateFechaInic",
     *                     type="date"
     *                 ) ,
     * 
     *                 @OA\Property(
     *                     property="dateFechaFina",
     *                     type="date"
     *                 ) ,
     *                  
     *                        
     *                  @OA\Property(
     *                     property="acti_usua",
     *                     type="string"
     *                 ) ,
     * 
     *                  
     *                 example={"varCodiPeriValo": "KIRLJSK", "varDescPeriValo": "KIRLPRUEBA", "dateFechaInic": "2019-10-10", "dateFechaFina": "2019-11-20", "intIdEsta": "13",  "acti_usua": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Codigo de periodo valorizacion ya se encuentra registrado."
     *     ),
     * )
     */
    public function regi_peri_valo(Request $request) {
        $validar = array('mensaje' => '');

        $regla = [
            //  'intIdPeriValo'=>'required|max:255',
            'varCodiPeriValo' => 'required|max:255',
            'varDescPeriValo' => 'required|max:255',
            'dateFechaInic' => 'required|max:255',
            'dateFechaFina' => 'required|max:255',
            'acti_usua' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT

        $varCodiPeriValo = $request->input('varCodiPeriValo');
        $varDescPeriValo = $request->input('varDescPeriValo');
        $dateFechaInic = $request->input('dateFechaInic');
        $dateFechaFina = $request->input('dateFechaFina');
        $acti_usua = $request->input('acti_usua');


        // dd(count($vali_fech));
        //VALIDACION SI EL CODIGO EXISTE 
        $vali_cod = Periodovalorizacion::where('varCodiPeriValo', $request->input('varCodiPeriValo'))
                ->first(['varCodiPeriValo']);

        if ($vali_cod['varCodiPeriValo'] != $varCodiPeriValo) {
            $vali_fech = DB::select("SELECT dateFechaFina FROM peri_valo where dateFechaFina>='$dateFechaInic'");


            if (count($vali_fech) == 0) {

                $regi_valo = Periodovalorizacion::create([
                            'varCodiPeriValo' => $varCodiPeriValo,
                            'varDescPeriValo' => $varDescPeriValo,
                            'dateFechaInic' => $dateFechaInic,
                            'dateFechaFina' => $dateFechaFina,
                            'intIdEsta' => 13, //registrado
                            'acti_usua' => $acti_usua,
                            'acti_hora' => $current_date = date('Y/m/d H:i:s')
                ]);
            } else {
                $validar['mensaje'] = "LA FECHA DE INICIO YA EXISTE EN OTRA VALORIZACION";
            }





            return $this->successResponse($validar);
        } else {
            $validar['mensaje'] = "El codigo ya existe.";



            return $this->successResponse($validar);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/vali_peri_valo",
     *     tags={"Gestion Valorización"},
     *     summary="Obtener los datos de idPeriodovalorizacion que se ha seleccionado",
     *     @OA\Parameter(
     *         description="para poder obtener la informacion necesitamos Id Periodo valorizacion",
     *         in="path",
     *         name="intIdPeriValo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *    
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdPeriValo",
     *                     type="number"
     *                 ),
     *                 
     *                 example={"intIdPeriValo": "120"}
     *             )
     *         )
     *     ),
     *       @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El idPeriodo valorizacion ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function vali_peri_valo(Request $request) {
        $regla = [
            'intIdPeriValo' => 'required|max:255'
        ];
        $vali_peri_valo = Periodovalorizacion::join('estado', 'estado.intIdEsta', '=', 'peri_valo.intIdEsta')
                ->where('intIdPeriValo', $request->input('intIdPeriValo'))
                ->first(['peri_valo.intIdPeriValo',
            'peri_valo.varCodiPeriValo',
            'peri_valo.varDescPeriValo',
            'peri_valo.dateFechaInic',
            'peri_valo.dateFechaFina',
            'peri_valo.intIdEsta',
            'estado.varDescEsta',
        ]);

        return $this->successResponse($vali_peri_valo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_peri_valo",
     *     tags={"Gestion Valorización"},
     *     summary="permite Actualizar un periodo de valorizacion",
     *  
     *  
     *  @OA\Parameter(
     *         description="Id de periodo de valorizacion que se va actualizar",
     *         in="path",
     *         name="intIdPeriValo",
     *         required=true,
     *         example= "27",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
     *     @OA\Parameter(
     *         description="Ingresamos del codigo periodo de valorizacion",
     *         in="path",
     *         name="varCodiPeriValo",
     *         required=true,
     *         example= "KIR_MODI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="La descripcion del periodo de valorizacion",
     *         in="path",
     *         name="varDescPeriValo",
     *         required=true,
     *         example= "DESC_MODI",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingresamos la fecha de inicio",
     *         in="path",
     *         name="dateFechaInic",
     *         required=true,
     *         example= "2019-10-20",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingremos la fecha final",
     *         in="path",
     *         name="dateFechaFina",
     *         required=true,
     *         example= "2019-12-20",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    
     *  @OA\Parameter(
     *         description="Ingresamos el usuario que ha registrado",
     *         in="path",
     *         name="usua_modi",
     *         required=true,
     *         example= "kunk_kuje",
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     
     *            @OA\RequestBody(
     *              @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                     property="intIdPeriValo",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varCodiPeriValo",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varDescPeriValo",
     *                     type="string"
     *                 ) ,
     *                     @OA\Property(
     *                     property="dateFechaInic",
     *                     type="date"
     *                 ) ,
     * 
     *                 @OA\Property(
     *                     property="dateFechaFina",
     *                     type="date"
     *                 ) ,
     *                  
     *                                
     *                  @OA\Property(
     *                     property="usua_modi",
     *                     type="string"
     *                 ) ,
     * 
     *                  
     *                 example={"intIdPeriValo": "27", "varCodiPeriValo": "KIR_MODI", "varDescPeriValo": "DESC_MODI", "dateFechaInic": "2019-10-20", "dateFechaFina": "2019-12-20", "intIdEsta": "",  "usua_modi": "usuario"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description= "Registro Satisfactorio"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     ),
     *     @OA\Response(
     *         response=408,
     *         description="Codigo de periodo valorizacion ya se encuentra registrado."
     *     ),
     * )
     */
    public function actu_peri_valo(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdPeriValo' => 'required|max:255',
            'varCodiPeriValo' => 'required|max:255',
            'varDescPeriValo' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'dateFechaInic' => 'required|max:255',
            'dateFechaFina' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT

        $intIdPeriValo = $request->input('intIdPeriValo');
        $varCodiPeriValo = $request->input('varCodiPeriValo');
        $varDescPeriValo = $request->input('varDescPeriValo');
        $intIdEsta = $request->input('intIdEsta');
        $dateFechaInic = $request->input('dateFechaInic');
        $dateFechaFina = $request->input('dateFechaFina');
        $usua_modi = $request->input('usua_modi');

        //validar si esta en la tabla proy_avance


        $vali_fech = DB::select("SELECT dateFechaFina FROM peri_valo where dateFechaFina>='$dateFechaInic' and intIdPeriValo != '$intIdPeriValo'");


        if (count($vali_fech) == 0) {

            $regi_valo = Periodovalorizacion::where('intIdPeriValo', '=', $intIdPeriValo)
                    ->update([
                'varCodiPeriValo' => $varCodiPeriValo,
                'varDescPeriValo' => $varDescPeriValo,
                'dateFechaInic' => $dateFechaInic,
                'dateFechaFina' => $dateFechaFina,
                'usua_modi' => $request->input('usua_modi'),
                'hora_modi' => $current_date = date('Y/m/d H:i:s')
            ]);
            $validar['mensaje'] = "";
        } else {
            $validar['mensaje'] = "LA FECHA DE INICIO YA EXISTE EN OTRA VALORIZACION";
        }

        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/elim_pedi_valor",
     *      tags={"Gestion Valorización"},
     *     summary="Eliminar el periodo valorizacion",
     *     @OA\Parameter(
     *         description="ingrese el id del periodo valorizacion",
     *         in="path",
     *         name="intIdPeriValo",
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
     *                     property="intIdPeriValo",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdPeriValo": "1000"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Se ha eliminado"
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
    public function elim_pedi_valor(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdPeriValo' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $peri_valo = $request->input('intIdPeriValo');
        $result = DB::select('select intIdProy from proy_avan where intIdPeriValo = ' . $peri_valo . ' limit 1');
        //die("ASd ".$result[0]->{'intIdProy'});
        if (count($result) > 0) {

            $validar['mensaje'] = "Error no se puede eliminar el periodo seleccionado.";
        } else {
            Periodovalorizacion::where('intIdPeriValo', $peri_valo)->delete();
        }






        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actu_pedi_valor_esta",
     *     tags={"Gestion Valorización"},
     *     summary="Obtener los datos de idPeriodovalorizacion que se ha seleccionado",
     *     @OA\Parameter(
     *         description="para poder obtener la informacion necesitamos Id Periodo valorizacion",
     *         in="path",
     *         name="intIdPeriValo",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      *     @OA\Parameter(
     *         description="usuario que ha a cambiar el estado de la valorizacion",
     *         in="path",
     *         name="usua_modi",
     *         example="asuario_autorizado",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *   *    
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdPeriValo",
     *                     type="number"
     *                 ),
     *                   @OA\Property(
     *                     property="asuario_autorizado",
     *                     type="number"
     *                 ),
     *                 
     *                 example={"intIdPeriValo": "120","usua_modi":"asuario_autorizado"}
     *             )
     *         )
     *     ),
     *       @OA\Response(
     *         response=200,
     *         description="Se ha Actualizado. "
     *     ),
     *     @OA\Response(
     *         response=407,
     *         description="El idPeriodo valorizacion ingresado no se encuentra registrado."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function actu_pedi_valor_esta(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdPeriValo' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdPeriValo = (int) $request->input('intIdPeriValo');
        $usua_modi = $request->input('usua_modi');

        /* $regi_valo = Periodovalorizacion::where('intIdPeriValo', $request->input('intIdPeriValo'))
          ->update([
          'intIdEsta' => 12,
          'usua_modi' => $request->input('usua_modi'),
          'hora_modi' => $current_date = date('Y/m/d H:i:s')
          ]);


          $validar['mensaje'] = "Se ha Actualizado."; */



        DB::select('CALL sp_valorizacion_P01(?,?,@v_mensaje,@cant)', array(
            $intIdPeriValo,
            $usua_modi
        ));


        $lsitar = DB::select("SELECT codigo, mensaje
                            FROM temp_mensajes
                            WHERE Usuario = '$usua_modi'");
        $count = count($lsitar);
        $results = DB::select('select @v_mensaje');
        $mensajes = [
            'mensajes'=>$results,
            'contador'=>$count
        ];
        return $this->successResponse($mensajes);
    }

}
