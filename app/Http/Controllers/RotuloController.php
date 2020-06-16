<?php

namespace App\Http\Controllers;

use DB;
use App\GuiaRemi;
use Illuminate\Http\Request;

class RotuloController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */

    /**
     * @OA\Post(
     *    path="/GestionProyectos/public/index.php/list_bulto",
     *     
     *     tags={"Gestion Rotulo"},
     *     summary="Listar Bulto",
     *     @OA\Parameter(
     *         description="Ingrese el codigo del proyecto",
     *         in="path",
     *         name="intIdProy",
     *        example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el codigo OT",
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
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy":188,"intIdTipoProducto": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar guia"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    /* LISTA BULTO TODOS MENOS LOS QUE ESTAN VACIOS */
    public function list_bulto(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $lista_bulto = DB::select("select distinct elemento.varBulto
                                    from proy_avan 
                                    LEFT JOIN elemento on proy_avan.intIdEleme=elemento.intIdEleme
                                    where proy_avan.intIdProy=$intIdProy and 
                                    proy_avan.varBulto<>'' and 
                                    proy_avan.varBulto<>'A GRANEL' and 
                                    proy_avan.varBulto<>'GRANEL' and
                                    proy_avan.intIdTipoProducto=$intIdTipoProducto and 
                                    elemento.intIdEsta<>6 order by  elemento.varBulto desc");
        return $this->successResponse($lista_bulto);
    }

    /**
     * @OA\Post(
     *    path="/GestionProyectos/public/index.php/list_bulto_2",
     *     
     *     tags={"Gestion Rotulo"},
     *     summary="Listar Bulto 2",
     *     @OA\Parameter(
     *         description="Ingrese el codigo del proyecto",
     *         in="path",
     *         name="intIdProy",
     *        example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el codigo OT",
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
     *                 @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy":188,"intIdTipoProducto": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listar guia"
     *     ),

     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_bulto_2(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $lista_bulto = DB::select("select distinct elemento.varBulto,elemento.intIdEsta 
                                    from elemento 
                                    left join etapa on etapa.intIdEtapa=elemento.intIdEtapa
                                    where elemento.intIdProy=$intIdProy and 
                                    elemento.intIdTipoProducto=$intIdTipoProducto and 
                                    etapa.intIdTipoEtap=29 and
                                    elemento.intIdEsta<>2 and elemento.intIdEsta<>6 order by  elemento.varBulto desc");
        return $this->successResponse($lista_bulto);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_codigo",
     *       tags={"Gestion Rotulo"},
     *     summary="Listar los codigos elementos",
     *     @OA\Parameter(
     *         description="ingrese el codigo de proyecto de identidad",
     *         in="path",
     *         name="intIdProy",
     *         example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el codigo del tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="ingrese el Bulto",
     *         in="path",
     *         name="varBulto",
     *         example="'BULTO 5 - TAT 42MTS TIPO B'",
     *         
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *        
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","varBulto":"'BULTO 5 - TAT 42MTS TIPO B'"}
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
    public function list_codigo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varBulto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $varBulto = trim($request->input('varBulto'), ',');
        if ($varBulto === "'TODOS'") {
            $var_bulto_1 = $varBulto;
            $var_bulto_2 = $varBulto;
        } else {
            $var_bulto_1 = "'no'";
            $var_bulto_2 = $varBulto;
        }
        $codigo = DB::select("select distinct elemento.varCodiElemento,elemento.varBulto
                                from elemento 
                                 left join etapa on etapa.intIdEtapa=elemento.intIdEtapa
                                where 
                                elemento.intIdProy=$intIdProy and 
                                elemento.intIdTipoProducto=$intIdTipoProducto and 
                                 etapa.intIdTipoEtap=29 and
                                elemento.intIdEsta<>2 and elemento.intIdEsta<>6 and 
                                ($var_bulto_1='TODOS' or elemento.varBulto in ($var_bulto_2))
                                order by  elemento.varCodiElemento asc");
        return $this->successResponse($codigo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_modelo",
     *       tags={"Gestion Rotulo"},
     *     summary="Listar los modelo",
     *     @OA\Parameter(
     *         description="ingrese el codigo de proyecto de identidad",
     *         in="path",
     *         name="intIdProy",
     *         example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el codigo del tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
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
     *         description="listar modelo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    /* JS ROTULO */

    public function list_modelo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $lista_modelo = DB::select("select distinct elemento.varModelo,elemento.intIdEsta 
                                    from proy_avan 
                                    LEFT JOIN elemento on proy_avan.intIdEleme=elemento.intIdEleme
                                    where proy_avan.intIdProy=$intIdProy and 
                                    proy_avan.intIdTipoProducto=$intIdTipoProducto and 
                                    proy_avan.varBulto<>'' and 
                                    proy_avan.varBulto<>'A GRANEL' and 
                                    proy_avan.varBulto<>'GRANEL' and
                                    elemento.intIdEsta<>6 order by elemento.varModelo asc");

        return $this->successResponse($lista_modelo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_modelo_2",
     *       tags={"Gestion Rotulo"},
     *     summary="Listar los modelo2",
     *     @OA\Parameter(
     *         description="ingrese el codigo de proyecto de identidad",
     *         in="path",
     *         name="intIdProy",
     *         example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="ingrese el codigo del tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),

     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
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
     *         description="listar modelo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_modelo_2(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        date_default_timezone_set('America/Lima'); // CDT
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $lista_modelo = DB::select("select distinct elemento.varModelo,elemento.intIdEsta 
                                    from elemento 
                                    left join etapa on etapa.intIdEtapa=elemento.intIdEtapa
                                    where elemento.intIdProy=$intIdProy and 
                                    elemento.intIdTipoProducto=$intIdTipoProducto and 
                                    etapa.intIdTipoEtap=29 and
                                    elemento.intIdEsta<>2 and elemento.intIdEsta<>6 order by elemento.varModelo asc");
        return $this->successResponse($lista_modelo);
    }

    /* js ROTULO */

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_bulto_total",
     *      tags={"Gestion Rotulo"},
     *     summary="Listar bulto total",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *          example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el codigo del proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *          example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo del elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el codigo del Bulto",
     *         in="path",
     *         name="varBulto",
     *         example="9",
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
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","intIdProyZona":"9","intIdProyTarea":"9","varCodiElemento":"78","varBulto":"-"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_bulto_total(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
            'varBulto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdProyTarea = (int) $request->input('intIdProyTarea');
        $varCodiElemento = trim($request->input('varCodiElemento'), ',');
        $varBulto = trim($request->input('varBulto'), ',');
        
        $var_bulto_1 = "";
        $var_bulto_2 = "";
        
        if ($varBulto === "'TODOS'") {
            $var_bulto_1 = $varBulto;
            $var_bulto_2 = $varBulto;
        } else {
            $var_bulto_1 = "'no'";
            $var_bulto_2 = $varBulto;
        }
        if ($varCodiElemento === "'TODOS'") {
            $var_ce_1 = $varCodiElemento;
            $var_ce_2 = $varCodiElemento;
        } else {
            $var_ce_1 = "'no'";
            $var_ce_2 = $varCodiElemento;
        }
        $listar_bulto = "";
        
        $listar_bulto = DB::select("SELECT e.intIdProy ,concat(py.varCodiProy,' /',py.varAlias) as nomb_proyecto, e.intIdTipoProducto,
                                    tp.varDescTipoProd, e.intIdProyZona,z.varDescrip,e.intIdProyTarea,t.varDescripTarea,
                                    e.varBulto ,e.varModelo,sum(e.deciPesoNeto) as deciPesoNeto,sum(e.deciPesoBruto) 
                                    as deciPesoBruto,sum(e.deciArea) as deciArea,count(e.varBulto) as cantidad
                                    FROM mimco.proy_avan P 
                                    INNER join mimco.elemento e on P.intIdProy=e.intIdProy and 
                                    P.intIdTipoProducto=e.intIdTipoProducto and 
                                    P.intIdEleme=e.intIdEleme and P.intIdProyZona=e.intIdProyZona and
                                    P.intIdProyTarea=e.intIdProyTarea
                                    INNER join mimco.proyecto_zona z on P.intIdProyZona=z.intIdProyZona
                                    INNER join mimco.proyecto_tarea t on P.intIdProyTarea=t.intIdProyTarea
                                    INNER join mimco.proyecto py on py.intIdProy=e.intIdProy 
                                    INNER join mimco.tipo_producto tp on tp.intIdTipoProducto=e.intIdTipoProducto
                                    where P.intIdProy=:intIdProy and 
                                    P.intIdTipoProducto=:intIdTipoProducto and 
                                    ($intIdProyZona=-1 or P.intIdProyZona=$intIdProyZona) AND
                                    ($intIdProyTarea=-1 or P.intIdProyTarea=$intIdProyTarea) AND
                                    ($var_ce_1='TODOS' or e.varModelo in ($var_ce_2)) AND
                                    ($var_bulto_1='TODOS' or P.varBulto in ($var_bulto_2)) AND
                                    P.varBulto<>'' and 
                                    P.varBulto<>'A GRANEL' AND
                                    e.varBulto<>'' AND
                                    P.varBulto<>'GRANEL' and e.intIdEsta<>6
                                    group by e.intIdProy , e.intIdTipoProducto, e.intIdProyZona,e.intIdProyTarea,e.varBulto,e.varModelo order by e.varBulto asc", ['intIdProy' => $intIdProy, 'intIdTipoProducto' => $intIdTipoProducto]);
        return $this->successResponse($listar_bulto);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_bulto_total_despacho",
     *      tags={"Gestion Rotulo"},
     *     summary="listar el bulto total despacho",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo del proyecto zona",
     *         in="path",
     *         name="intIdProyZona",
     *          example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el codigo del proyecto tarea",
     *         in="path",
     *         name="intIdProyTarea",
     *          example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo del elemento",
     *         in="path",
     *         name="varCodiElemento",
     *         example="9",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el codigo del Bulto",
     *         in="path",
     *         name="varBulto",
     *         example="9",
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
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyTarea",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varCodiElemento",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","intIdProyZona":"9","intIdProyTarea":"9","varCodiElemento":"78","varBulto":"-"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_bulto_total_despacho(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'intIdProyTarea' => 'required|max:255',
            'varModelo' => 'required|max:255',
            'varBulto' => 'required|max:255',
            'varCodiElemento' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdProyTarea = (int) $request->input('intIdProyTarea');
        $varModelo = trim($request->input('varModelo'), ',');
        $varBulto = trim($request->input('varBulto'), ',');
        $varCodiElemento = trim($request->input('varCodiElemento'), ',');
        
        $var_bulto_1 = "";
        $var_bulto_2 = "";
        
        if ($varBulto === "'TODOS'") {
            $var_bulto_1 = $varBulto;
            $var_bulto_2 = $varBulto;
        } else {
            $var_bulto_1 = "'no'";
            $var_bulto_2 = $varBulto;
        }
        if ($varModelo === "'TODOS'") {
            $var_ce_1 = $varModelo;
            $var_ce_2 = $varModelo;
        } else {
            $var_ce_1 = "'no'";
            $var_ce_2 = $varModelo;
        }
        if ($varCodiElemento === "'TODOS'") {
            $var_codigo_1 = $varCodiElemento;
            $var_codgio_2 = $varCodiElemento;
        } else {
            $var_codigo_1 = "'no'";
            $var_codgio_2 = $varCodiElemento;
        }


        $listar_bulto = "";
        
        $listar_bulto = DB::select("SELECT E.varCodiElemento, 
		E.varDescripcion as nombre, 
                COUNT(E.intIdEleme) AS Canti, 
                COUNT(E.intIdEleme) AS CantiReal, 
		E.intRevision, 
                E.intCantRepro, 
                PZ.varDescrip as Zona, 
                PT.varDescripTarea AS Programa,
		PG.varCodigoPaquete as Grupo,
		(CASE WHEN E.intIdEsta <> 25 THEN emp.varRazCont ELSE emp1.varRazCont END)  as Contratista, 
                E.deciPrec,
		E.deciPesoNeto, E.deciPesoBruto, E.deciArea, E.deciLong, E.deciAncho, 
		ETA.varDescEtap as etapa_anterior, ETS.varDescEtap as etapa_siguiente,
		E.varPerfil, E.varModelo, E.varCodVal,
		E.intIdProyZona, E.intIdProyPaquete,E.intIdEtapaAnte,E.intIdEtapaSiguiente,
                (CASE WHEN E.intIdEsta <> 25 THEN AV.intIdContr ELSE E.IdContraAnt END) AS intIdContr,
                E.intIdProyPaquete, E.intidetapa, E.intIdProyTarea, E.intIdRuta,
		emp1.varRazCont  as ContratistaAnt,
		E.FechaUltimAvan as FechaAvanAnt,
		 E.numDocTratSup as DocEnvioTS , -- Doc_Ant,
		-- E.varDocAnt as Doc_Ant,
		E.varValo1 as Pintura,
                E.varBulto AS bulto,
                E.varValo2 AS Obs1,
		E.varValo3 AS obs2,
		E.varValo4 AS obs3,
                E.varValo5 AS obs4,
		EST.varDescEsta AS estado,
		E.IdContraAnt,
                E.intIdEsta,
                (E.deciPesoNeto * COUNT(E.intIdEleme)) as TotalPesoNeto, 
                (E.deciPesoBruto * COUNT(E.intIdEleme)) as TotalPesoBruto,
                (E.deciArea * COUNT(E.intIdEleme)) as TotalArea
		FROM elemento as E left join proy_avan as AV on 
		E.intIdEleme = AV.intIdEleme and E.intIdEtapa = AV.intIdEtapa and E.intCantRepro = AV.intNuConta 
                left join  contratista as emp on AV.intIdContr = emp.intIdCont  
                left join etapa as ETAA on ETAA.intIdEtapa=E.intIdEtapa
                left join etapa as ETA on E.intIdEtapaAnte = ETA.intIdEtapa 
                left join etapa as ETS on E.intIdEtapaSiguiente = ETS.intIdEtapa 
                left join proyecto_zona as PZ on E.intIdProyZona = PZ.intIdProyZona 
                left join proyecto_paquetes as PG on E.intIdProyPaquete = PG.intIdProyPaquete 
                left join proyecto_tarea as PT on E.intIdProyTarea = PT.intIdProyTarea 
                left join contratista as emp1 on E.IdContraAnt = emp1.intIdCont 
                left join estado as EST ON E.intIdEsta = EST.intIdEsta
		WHERE E.intIdProy = $intIdProy AND E.intIdTipoProducto = $intIdTipoProducto
		AND (-1 = $intIdProyZona OR  E.intIdProyZona = $intIdProyZona)
		AND (-1 = $intIdProyTarea OR E.intIdProyTarea = $intIdProyTarea)
		AND ETAA.intIdTipoEtap =  29
		AND (E.intIdEsta <> 2 AND E.intIdEsta <> 6) AND
                ('TODOS'= $var_ce_1 or E.varModelo in ($var_ce_2)) AND
                ('TODOS'=$var_bulto_1 or E.varBulto in ($var_bulto_2)) AND
                ('TODOS'=$var_codigo_1 or E.varCodiElemento in ($var_codgio_2)) 
		GROUP BY 
		E.varCodiElemento, E.varDescripcion,  
		E.intRevision, E.intCantRepro, PZ.varDescrip,PT.varDescripTarea,PG.varCodigoPaquete,
		emp.varRazCont, E.deciPrec, ETAA.intIdTipoEtap,
		E.deciPesoNeto, E.deciPesoBruto, E.deciArea, E.deciLong, E.deciAncho, 
		ETA.varDescEtap, ETS.varDescEtap,
		E.varPerfil, E.varModelo, E.varCodVal,E.intIdProyZona, E.intIdProyPaquete,
		E.intIdEtapaAnte,E.intIdEtapaSiguiente,AV.intIdContr,E.intIdProyPaquete, E.intidetapa,
		E.intIdProyTarea,E.intIdRuta,emp1.varRazCont,E.FechaUltimAvan,
		-- E.varDocAnt,
		E.numDocTratSup,
                E.varValo1 ,
                E.varBulto,
                E.varValo2 ,
		E.varValo3 ,
		E.varValo4 ,
                E.varValo5 ,
		EST.varDescEsta ,
		E.IdContraAnt,
                E.intIdEsta ORDER BY E.varCodiElemento desc");

        return $this->successResponse($listar_bulto);
    }

    /* JS ROTULO */

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/listar_bulto_detalle",
     *      tags={"Gestion Rotulo"},
     *     summary="listar el bulto total despacho detalle",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el bulto",
     *         in="path",
     *         name="varBulto",
     *         example="BLOQUE 2",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","varBulto":"BLOQUE 2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function listar_bulto_detalle(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varBulto' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $varBulto = $request->input('varBulto');
        
        $listar_bulto_detalle = "";
        $listar_bulto_detalle = DB::select("SELECT e.varCodiElemento,e.varDescripcion,e.intRevision,e.intCantRepro,e.intSerie,e.varModelo,e.varPerfil,
                                            pz.varDescrip as zona,pt.varDescripTarea,pp.varCodigoPaquete,e.varModelo,e.deciPesoNeto,e.deciPesoBruto,e.deciArea,e.varBulto,
                                            e.deciAlto,e.deciAncho,e.deciLong FROM elemento e 
                                            inner join proyecto_zona pz on e.intIdProyZona=pz.intIdProyZona
                                            inner join proyecto_tarea pt on e.intIdProyTarea=pt.intIdProyTarea
                                            inner join proyecto p on e.intIdProy=p.intIdProy
                                            inner join tipo_producto tp on e.intIdTipoProducto=tp.intIdTipoProducto
                                            inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                            where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and  e.varBulto='$varBulto' and
                                            e.varBulto<>'' and e.varBulto<>'A GRANEL' AND e.varBulto<>'GRANEL' and e.intIdEsta<>6   
                                            order by e.varCodiElemento asc");

        return $this->successResponse($listar_bulto_detalle);
    }

    /* JS ROTULO */

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/listar_por_codigo_bulto",
     *      tags={"Gestion Rotulo"},
     *     summary="listar codigo ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el bulto",
     *         in="path",
     *         name="varBulto",
     *         example="BLOQUE 2",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","varBulto":"BLOQUE 2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function listar_por_codigo_bulto(Request $request) {

        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varBulto' => 'required|max:255'
        ];

        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $varBulto = $request->input('varBulto');
        
        $listar_bulto_detalle = "";
        $listar_bulto_detalle = DB::select("SELECT e.varCodiElemento,e.varDescripcion,e.intRevision,e.intCantRepro,e.varPerfil,
                                    pp.varCodigoPaquete,sum(e.deciPesoNeto) as deciPesoNeto,sum(e.deciPesoBruto) as deciPesoBruto,
                                    sum(e.deciArea) as deciArea,e.varBulto,
                                    sum(e.deciAlto) as deciAlto, sum(e.deciAncho) as deciAncho,count(e.varCodiElemento) as cantidad,sum(e.deciLong) as deciLong
                                    from mimco.elemento e 
                                    inner join mimco.proyecto_zona z on e.intIdProyZona=z.intIdProyZona
                                    inner join mimco.proyecto_tarea t on e.intIdProyTarea=t.intIdProyTarea
                                    inner join mimco.proyecto py on py.intIdProy=e.intIdProy 
                                    inner join mimco.tipo_producto tp on tp.intIdTipoProducto=e.intIdTipoProducto
                                    inner join mimco.proyecto_paquetes pp on pp.intIdProyPaquete=e.intIdProyPaquete
                                    where e.intIdProy=:intIdProy and  
                                    e.intIdTipoProducto=:intIdTipoProducto and 
                                    e.varBulto='$varBulto' and
                                    e.varBulto<>'' and 
                                    e.varBulto<>'A GRANEL' AND
                                    e.varBulto<>'GRANEL' and e.intIdEsta<>6  
                                    group by e.varCodiElemento,
                                    e.varDescripcion,e.intRevision,e.intCantRepro,e.varPerfil,pp.varCodigoPaquete,e.deciPesoNeto,
                                    e.deciPesoBruto,e.deciArea,e.varBulto,e.deciLong,
                                    e.deciAlto,e.deciAncho order by e.varCodiElemento asc
                                    ", ['intIdProy' => $intIdProy, 'intIdTipoProducto' => $intIdTipoProducto]);
        return $this->successResponse($listar_bulto_detalle);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/lista_despacho",
     *      tags={"Gestion Rotulo"},
     *     summary="listar despacho ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
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
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": "193","intIdTipoProducto":"1","intIdEsta":"2"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function lista_despacho(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255'
        ];
        $ot = [];
        $contador = 0;
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $lista_despacho = DB::select("select td.intIdDesp,td.cantidadtotal, td.deciTotaPesoNeto,td.deciTotaPesoBruto,td.deciTotaArea,es.varDescEsta,td.acti_usua,td.acti_hora,
                                    td.varArchDesp,td.arch_desp_usua,td.arch_desp_hora,td.varArchRece,td.arch_rece_usua,td.arch_rece_hora,td.intIdProy,p.varCodiProy,p.varRucClie
                                    from tab_desp td 
                                    inner join proyecto p on td.intIdProy=p.intIdProy
                                    inner join elemento e on td.intIdDesp=e.intIdDespacho and td.intIdProy=e.intIdProy and td.intIdTipoProducto=e.intIdTipoProducto
                                    inner join estado es on td.intIdEsta=es.intIdEsta
                                    where (td.intIdProy=$intIdProy or -1=$intIdProy) and td.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta
                                    group by td.intIdDesp,td.cantidadtotal,td.intIdProy,p.varCodiProy, td.deciTotaPesoNeto,td.deciTotaPesoBruto,p.varRucClie,td.deciTotaArea,es.varDescEsta,td.acti_usua,td.acti_hora,td.varArchDesp,td.arch_desp_usua,td.arch_desp_hora,td.varArchRece,td.arch_rece_usua,td.arch_rece_hora
                                    order by td.intIdProy asc");


        for ($i = 0; count($lista_despacho) > $i; $i++) {
            $contador++;
            $id_des = $lista_despacho[$i]->intIdDesp;
            $id_ot = $lista_despacho[$i]->intIdProy;
            $guias_despachadas = 0;
            $num_guias = DB::select("select count(e.nume_guia) as guias from tab_desp tb 
                                    inner join elemento e on tb.intIdDesp=e.intIdDespacho and tb.intIdProy=e.intIdProy and tb.intIdTipoProducto=e.intIdTipoProducto
                                    where e.nume_guia<>'' and tb.intIdDesp=$id_des and tb.intIdProy=$id_ot and tb.intIdTipoProducto=$intIdTipoProducto ");
            if ($num_guias[0]->guias === 0) {
                $guias_despachadas = 0;
            } else {
                $guias_despachadas = $num_guias[0]->guias;
            }
            $guiar_final = $guias_despachadas . '/' . $lista_despacho[$i]->cantidadtotal;
            array_push($ot, ['count' => $contador, 'intIdDesp' => $lista_despacho[$i]->intIdDesp, 'guias_despachadas' => $guiar_final, 'deciTotaPesoNeto' => $lista_despacho[$i]->deciTotaPesoNeto, 'deciTotaPesoBruto' => $lista_despacho[$i]->deciTotaPesoBruto, 'deciTotaArea' => $lista_despacho[$i]->deciTotaArea, 'varDescEsta' => $lista_despacho[$i]->varDescEsta, 'acti_usua' => $lista_despacho[$i]->acti_usua, 'acti_hora' => $lista_despacho[$i]->acti_hora
                , 'varArchDesp' => $lista_despacho[$i]->varArchDesp, 'arch_desp_usua' => $lista_despacho[$i]->arch_desp_usua, 'arch_desp_hora' => $lista_despacho[$i]->arch_desp_hora, 'varArchRece' => $lista_despacho[$i]->varArchRece, 'arch_rece_usua' => $lista_despacho[$i]->arch_rece_usua, 'arch_rece_hora' => $lista_despacho[$i]->arch_rece_hora, 'intIdProy' => $lista_despacho[$i]->intIdProy, 'varCodiProy' => $lista_despacho[$i]->varCodiProy, 'varRucClie' => $lista_despacho[$i]->varRucClie]);
        }
        
        return $this->successResponse($ot);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_bulto_codigo",
     *      tags={"Gestion Rotulo"},
     *     summary="listar bulto codigo ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
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
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_bulto_codigo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdDesp' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdDesp = (int) $request->input('intIdDesp');
        $list_bulto = DB::select("select e.varBulto
                                from tab_desp td 
                                inner join elemento e on td.intIdDesp=e.intIdDespacho 
                                inner join estado es on td.intIdEsta=es.intIdEsta
                                where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia=''
                                group by e.varBulto
                                order by e.varBulto desc");
        return $this->successResponse($list_bulto);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_modelo_codigo",
     *      tags={"Gestion Rotulo"},
     *     summary="listar modelo codigo ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Id Tipo Grupo",
     *         in="path",
     *         name="intIdTipoGrupo",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoGrupo",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1,"intIdTipoGrupo":1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_modelo_codigo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdDesp' => 'required|max:255',
            'intIdTipoGrupo' => 'required|max:255'
        ];
        $this->validate($request, $regla);

        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdDesp = (int) $request->input('intIdDesp');
        $intIdTipoGrupo = (int) $request->input('intIdTipoGrupo');
        $list_modelo = DB::select("select  e.varModelo
                                                from tab_desp td 
                                                inner join elemento e on td.intIdDesp=e.intIdDespacho 
                                                inner join proyecto_paquetes pp on pp.intIdProyPaquete = e.intIdProyPaquete
                                                where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia=''
                                                and (pp.intIdTipoGrupo=$intIdTipoGrupo or -1=$intIdTipoGrupo)
                                                group by  e.varModelo
                                                order by  e.varModelo desc");
        return $this->successResponse($list_modelo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_tipo_grupo",
     *      tags={"Gestion Rotulo"},
     *     summary="listar tipo grupo ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_tipo_grupo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdDesp' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdDesp = (int) $request->input('intIdDesp');
        $list_t_gtupo = DB::select("select tg.varDescTipoGrupo,tg.intIdTipoGrupo
                                from tab_desp td 
                                inner join elemento e on td.intIdDesp=e.intIdDespacho 
                                inner join estado es on td.intIdEsta=es.intIdEsta
                                inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                inner join tipo_grupo tg on pp.intIdTipoGrupo=tg.intIdTipoGrupo
                                where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia='' 
                                group by tg.varDescTipoGrupo,tg.intIdTipoGrupo order by tg.varDescTipoGrupo asc");
        return $this->successResponse($list_t_gtupo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_tipo_zona",
     *      tags={"Gestion Rotulo"},
     *     summary="listar tipo zona ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_tipo_zona(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdDesp' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdDesp = (int) $request->input('intIdDesp');
        $list_zona = DB::select("select e.intIdProyZona,pz.varDescrip
                                    from tab_desp td 
                                    inner join elemento e on td.intIdDesp=e.intIdDespacho 
                                    inner join estado es on td.intIdEsta=es.intIdEsta
                                    inner join proyecto_zona pz on pz.intIdProyZona=e.intIdProyZona
                                    where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia=''
                                    group by e.intIdProyZona,pz.varDescrip order by pz.varDescrip desc");
        return $this->successResponse($list_zona);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_despc_codigo",
     *      tags={"Gestion Rotulo"},
     *     summary="listar despacho codigo ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Id Zona",
     *         in="path",
     *         name="intIdProyZona",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el bulto",
     *         in="path",
     *         name="varBulto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese tipo grupo",
     *         in="path",
     *         name="intIdTipoGrupo",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese modelo",
     *         in="path",
     *         name="varModelo",
     *         example="1",
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
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoGrupo",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="varModelo",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1,"intIdProyZona":1,"varBulto":"xxx","intIdTipoGrupo":1,"varModelo":"xxx"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_despc_codigo(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'varBulto' => 'required|max:255',
            'intIdDesp' => 'required|max:255',
            'intIdTipoGrupo' => 'required|max:255',
            'varModelo' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdTipoGrupo = (int) $request->input('intIdTipoGrupo');
        $intIdDesp = (int) $request->input('intIdDesp');
        $varBulto = $request->input('varBulto');
        $varModelo = $request->input('varModelo');
        $list_codigo = DB::select("select td.intIdDesp ,e.varCodiElemento,e.varDescripcion,count(e.varCodiElemento) as cantidad, td.deciTotaPesoNeto,
                                td.deciTotaPesoBruto,td.deciTotaArea,es.varDescEsta,td.acti_usua,td.acti_hora,e.varBulto,e.varModelo,e.varPerfil,
                                e.nume_guia,e.intIdProyTarea,pt.varDescripTarea,e.intIdProyZona,pz.varDescrip,tg.varDescTipoGrupo,tg.intIdTipoGrupo,(case when tg.varDescTipoGrupo='ESTRUCTURA' then 'UND' else 'KIT' end )   as Unidad_Medida
                                from tab_desp td 
                                inner join elemento e on td.intIdDesp=e.intIdDespacho and td.intIdProy=e.intIdProy and td.intIdTipoProducto=e.intIdTipoProducto
                                inner join estado es on td.intIdEsta=es.intIdEsta
                                inner join proyecto_tarea pt on pt.intIdProyTarea=e.intIdProyTarea
                                inner join proyecto_zona pz on pz.intIdProyZona=e.intIdProyZona
                                inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                inner join tipo_grupo tg on pp.intIdTipoGrupo=tg.intIdTipoGrupo
                                where td.intIdProy=$intIdProy and td.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia='' and (e.intIdProyZona=$intIdProyZona or -1=$intIdProyZona) and (e.varBulto='$varBulto' or 'TODOS'='$varBulto') and (tg.intIdTipoGrupo=$intIdTipoGrupo or -1=$intIdTipoGrupo) and (e.varModelo='$varModelo' or 'TODOS'='$varModelo')
                                group by td.intIdDesp ,e.varCodiElemento,e.varDescripcion, td.deciTotaPesoNeto,td.deciTotaPesoBruto,td.deciTotaArea,es.varDescEsta,td.acti_usua,td.acti_hora,
                                e.nume_guia,e.intIdProyTarea,e.varPerfil,e.varModelo,pt.varDescripTarea,e.intIdProyZona,pz.varDescrip,tg.varDescTipoGrupo,e.varBulto,tg.intIdTipoGrupo order by LENGTH(e.varCodiElemento),e.varCodiElemento asc");
        return $this->successResponse($list_codigo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_despc_serie",
     *      tags={"Gestion Rotulo"},
     *     summary="listar despacho serie ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de proyecto",
     *         in="path",
     *         name="intIdProy",
     *          example="193",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el codigo tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese el Id Despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Id Zona",
     *         in="path",
     *         name="intIdProyZona",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el bulto",
     *         in="path",
     *         name="varBulto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese tipo grupo",
     *         in="path",
     *         name="intIdTipoGrupo",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese modelo",
     *         in="path",
     *         name="varModelo",
     *         example="1",
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
     *                     property="intIdProy",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdEsta",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdDesp",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProyZona",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="varBulto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoGrupo",
     *                     type="number"
     *                 ) ,
     *              @OA\Property(
     *                     property="varModelo",
     *                     type="string"
     *                 ) ,
     *              
     *                 example={"intIdProy": 193,"intIdTipoProducto":1,"intIdEsta":2,"intIdDesp":1,"intIdProyZona":1,"varBulto":"xxx","intIdTipoGrupo":1,"varModelo":"xxx"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_despc_serie(Request $request) {
        $regla = [
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdEsta' => 'required|max:255',
            'intIdProyZona' => 'required|max:255',
            'varBulto' => 'required|max:255',
            'intIdDesp' => 'required|max:255',
            'intIdTipoGrupo' => 'required|max:255',
            'varModelo' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdEsta = (int) $request->input('intIdEsta');
        $intIdProyZona = (int) $request->input('intIdProyZona');
        $intIdTipoGrupo = (int) $request->input('intIdTipoGrupo');
        $intIdDesp = (int) $request->input('intIdDesp');
        $varBulto = $request->input('varBulto');
        $varModelo = $request->input('varModelo');
        $list_codigo = DB::select("select td.intIdDesp ,e.varCodiElemento,e.varDescripcion,e.intSerie, td.deciTotaPesoNeto,
                                td.deciTotaPesoBruto,td.deciTotaArea,es.varDescEsta,td.acti_usua,td.acti_hora,e.varBulto,e.varModelo,e.varPerfil,
                                e.nume_guia,e.intIdProyTarea,pt.varDescripTarea,e.intIdProyZona,pz.varDescrip,tg.varDescTipoGrupo,tg.intIdTipoGrupo,(case when tg.varDescTipoGrupo='ESTRUCTURA' then 'UND' else 'KIT' end )   as Unidad_Medida
                                from tab_desp td 
                                inner join elemento e on td.intIdDesp=e.intIdDespacho  and td.intIdProy=e.intIdProy and td.intIdTipoProducto=e.intIdTipoProducto
                                inner join estado es on td.intIdEsta=es.intIdEsta
                                inner join proyecto_tarea pt on pt.intIdProyTarea=e.intIdProyTarea
                                inner join proyecto_zona pz on pz.intIdProyZona=e.intIdProyZona
                                inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                inner join tipo_grupo tg on pp.intIdTipoGrupo=tg.intIdTipoGrupo
                                where e.intIdProy=$intIdProy and e.intIdTipoProducto=$intIdTipoProducto and td.intIdEsta=$intIdEsta and  td.intIdDesp=$intIdDesp and e.nume_guia='' and (e.intIdProyZona=$intIdProyZona or -1=$intIdProyZona) and (e.varBulto='$varBulto' or 'TODOS'='$varBulto') and (tg.intIdTipoGrupo=$intIdTipoGrupo or -1=$intIdTipoGrupo)and (e.varModelo='$varModelo' or 'TODOS'='$varModelo')");
        return $this->successResponse($list_codigo);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/listar_guia",
     *      tags={"Gestion Rotulo"},
     *     summary="Guia ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdGuia": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function listar_guia(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdGuia = (int) $request->input('intIdGuia');
        $list_guia = DB::select("SELECT g.dateFechEmis as Fecha_Emision, YEAR(g.dateFechEmis) as Anio_emision, MONTH (g.dateFechEmis) as Mes_emision, day(g.dateFechEmis) as Dia_emision,
                                g.dateFechTras as Fecha_Traslado, YEAR(g.dateFechTras) as Anio_Traslado, MONTH (g.dateFechTras) as Mes_Traslado, day(g.dateFechTras) as Dia_Traslado,
                                g.varContaDocu as Serie_Codigo,p.varAlias,
                                g.varPuntSali as Direccion_Salida,tdis.varNombDist as Distrito_Salida, tps.varNombProv as Provincia_Salida, tdes.varNombDepa as Departamento_Salida,
                                g.varPuntLleg as Direccion_Llegada,tdi.varNombDist as Distrito_Llegada,tp.varNombProv as Provincia_Llegada,tde.varNombDepa as Departamento_Llegada,
                                c.varRazClie as Razon_Social_Cliente,c.varRucClie as Ruc_Cliente,g.varNombChof as Nombre_Chofer,g.varNumeChof as Documento_Chofer,g.varNumeLicen as Licencia_Documento,
                                p.varAlias as Orden_Trabajo,g.varRefe as Referencia,tr.varRazonSoci as Razon_Social_Transportista,tr.varNumeIden as Documento_Transportista,m.intIdMoti as Motivo, g.varMotiCome,g.intIdGuia,p.varCodiProy,g.varTituGuia
                                FROM guia_remi g 
                                inner join cliente c on g.intIdCliente=c.intIdClie
                                inner join tab_depa tde on g.varIdDepa=tde.varIdSqlDepa
                                inner join tab_depa tdes on g.varIdDepaSali=tdes.varIdSqlDepa
                                inner join tab_prov tp on g.varIdProvincia=tp.varIdSqlProv and g.varIdDepa=tp.varIdSqlDepa
                                inner join tab_prov tps on g.varIdProvinciaSali=tps.varIdSqlProv and g.varIdDepaSali=tps.varIdSqlDepa
                                inner join tab_dist tdi on g.varIdDistrito=tdi.varIdSqlDist and tdi.varIdSqlDepa=g.varIdDepa and tdi.varIdSqlProv=g.varIdProvincia
                                inner join tab_dist tdis on g.varIdDistritoSali=tdis.varIdSqlDist and tdis.varIdSqlDepa=g.varIdDepaSali and tdis.varIdSqlProv=g.varIdProvinciaSali
                                inner join transportista tr on g.intIdTrans=tr.intIdTrans
                                inner join proyecto p on g.intIdProy=p.intIdProy
                                inner join motivo m on g.intIdMoti=m.intIdMoti
                                where g.intIdGuia=$intIdGuia");

        return $this->successResponse($list_guia);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/detalle_guia",
     *      tags={"Gestion Rotulo"},
     *     summary="Detalle guia ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdGuia": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function detalle_guia(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdGuia = (int) $request->input('intIdGuia');
        $list_detalle_guia = DB::select("select concat(e.varCodiElemento,'-',e.intSerie) as varCodiElemento, 1   as cantidad, 
                                        e.varDescripcion,e.varPerfil,e.deciLong,e.deciLong,e.nume_guia,e.varGuia,e.varBulto,e.varUnidMedi as  varValo5,tp.varDescTipoGrupo 
                                        from elemento e 
                                        inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                        inner join tipo_grupo tp on pp.intIdTipoGrupo=tp.intIdTipoGrupo
                                        where e.varGuia=$intIdGuia order by LENGTH(e.varCodiElemento),e.varCodiElemento asc");
        return $this->successResponse($list_detalle_guia);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/detalle_guia_cantidad",
     *      tags={"Gestion Rotulo"},
     *     summary="Detalle guia cantidad ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *              
     *                 example={"intIdGuia": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function detalle_guia_cantidad(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdGuia = $request->input('intIdGuia');
        $list_detalle_guia = DB::select("select   e.varCodiElemento,e.varDescripcion,e.varPerfil,e.deciLong, count(e.varBulto)  as cantidad ,
                                        e.nume_guia,e.varGuia,e.varBulto,e.varUnidMedi as  varValo5,tp.varDescTipoGrupo 
                                        from elemento e 
                                        inner join proyecto_paquetes pp on e.intIdProyPaquete=pp.intIdProyPaquete
                                        inner join tipo_grupo tp on pp.intIdTipoGrupo=tp.intIdTipoGrupo
                                        where e.varGuia=$intIdGuia
                                        group by varCodiElemento,varDescripcion,varPerfil,deciLong,varBulto,varUnidMedi,tp.varDescTipoGrupo,e.nume_guia order by LENGTH(e.varCodiElemento),e.varCodiElemento asc");
        return $this->successResponse($list_detalle_guia);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/guia_emitida",
     *      tags={"Gestion Rotulo"},
     *     summary="Detalle guia cantidad ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese archivo emitido",
     *         in="path",
     *         name="varArchEmit",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese usuario emitido",
     *         in="path",
     *         name="usua_emit",
     *          example="1",
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
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varArchEmit",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="usua_emit",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": 1,"varArchEmit":"archivo","usua_emit":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function guia_emitida(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdGuia' => 'required',
            'varArchEmit' => 'required',
            'usua_emit' => 'required',
                //'hora_emit'=>'required'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $varArchEmit = $request->input('varArchEmit');
        $intIdGuia = $request->input('intIdGuia');
        $usua_emit = $request->input('usua_emit');
        $hora_emit = $request->input('hora_emit');
        $guardar_archivo = GuiaRemi::where('intIdGuia', '=', $intIdGuia)
                ->update([
            'varArchEmit' => $varArchEmit,
            'usua_emit' => $usua_emit,
            'hora_emit' => $current_date = date('Y/m/d H:i:s')
        ]);
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/guia_emitida",
     *      tags={"Gestion Rotulo"},
     *     summary="Detalle guia cantidad ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese archivo recepcionado",
     *         in="path",
     *         name="varArchRecep",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese usuario recepcionado",
     *         in="path",
     *         name="usua_recep",
     *          example="1",
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
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *                 @OA\Property(
     *                     property="varArchRecep",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="usua_recep",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": 1,"varArchRecep":"archivo","usua_recep":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function guia_recibida(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdGuia' => 'required',
            'varArchRecep' => 'required',
            'usua_recep' => 'required',
                //'hora_recep'=>'required'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $varArchRecep = $request->input('varArchRecep');
        $intIdGuia = $request->input('intIdGuia');
        $usua_recep = $request->input('usua_recep');
        $hora_recep = $request->input('hora_recep');
        $guardar_archivo = GuiaRemi::where('intIdGuia', '=', $intIdGuia)
                ->update([
            'varArchRecep' => $varArchRecep,
            'usua_recep' => $usua_recep,
            'hora_recep' => $current_date = date('Y/m/d H:i:s')
        ]);
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/obtn_info_idGuia",
     *      tags={"Gestion Rotulo"},
     *     summary="Informacion de guia ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *                 example={"intIdGuia": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function obtn_info_idGuia(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255',
        ];
        $this->validate($request, $regla);
        $intIdGuia = (int) $request->input('intIdGuia');
        $obtn_dist_prov_depa = GuiaRemi::where('intIdGuia', '=', $intIdGuia)
                ->select('varIdDistrito', 'varIdProvincia', 'varIdDepa', 'varIdDistritoSali', 'varIdProvinciaSali', 'varIdDepaSali')
                ->first();
        $obtn_dist = DB::select("select varNombDist from tab_dist where varIdSqlDepa='" . $obtn_dist_prov_depa['varIdDepaSali'] . "' and "
                        . "varIdSqlProv='" . $obtn_dist_prov_depa['varIdProvinciaSali'] . "' and varIdSqlDist='" . $obtn_dist_prov_depa['varIdDistritoSali'] . "'");
        $obtn_prov = DB::select("select varNombProv from tab_prov where varIdSqlDepa='" . $obtn_dist_prov_depa['varIdDepaSali'] . "' and "
                        . "varIdSqlProv='" . $obtn_dist_prov_depa['varIdProvinciaSali'] . "'");
        $obtn_depa = DB::select("select varNombDepa from tab_depa where varIdSqlDepa='" . $obtn_dist_prov_depa['varIdDepaSali'] . "'");
        $resultado = array("varNombDist" => $obtn_dist[0]->varNombDist, "varNombProv" => $obtn_prov[0]->varNombProv, "varNombDepa" => $obtn_depa[0]->varNombDepa);
        $obtn_informacion = GuiaRemi::where('intIdGuia', '=', $intIdGuia)
                        ->join('motivo', 'guia_remi.intIdMoti', '=', 'motivo.intIdMoti')
                        ->join('proyecto', 'proyecto.intIdProy', '=', 'guia_remi.intIdProy')
                        ->join('tipo_producto', 'tipo_producto.intIdTipoProducto', '=', 'guia_remi.intIdTipoProducto')
                        ->join('cliente', 'cliente.intIdClie', '=', 'guia_remi.intIdCliente')
                        ->select('guia_remi.intIdGuia', 'guia_remi.varContaDocu', 'guia_remi.dateFechEmis', 'guia_remi.dateFechTras', DB::raw('(CASE WHEN guia_remi.varTipoGuia ="1" THEN "CANTIDAD" ELSE "SERIE" END) AS varTipoGuia'), 'guia_remi.intIdMoti', 'motivo.varDescripcion', 'varMotiCome', 'varRefe', 'intIdDesp', DB::raw('CONCAT(proyecto.varCodiProy,"/",proyecto.varAlias) AS Proyecto'), 'guia_remi.intIdTipoProducto', 'tipo_producto.varDescTipoProd', 'guia_remi.intIdCliente', 'cliente.varRucClie', 'cliente.varRazClie', 'varPuntSali', 'varPuntLleg', 'varIdDistrito', 'varIdProvincia', 'varIdDepa'
                                , 'varPlaca', 'varNombChof', 'varNumeChof', 'varNumeLicen', 'intIdTrans'
                        )->get()->toArray();
        $resultado2 = array_merge($resultado, $obtn_informacion[0]);
        return $this->successResponse($resultado2);
    }
    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/obtn_info_idGuia",
     *      tags={"Gestion Rotulo"},
     *     summary="Informacion de guia ",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de Guia",
     *         in="path",
     *         name="intIdGuia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese la fecha de traslado",
     *         in="path",
     *         name="dateFechTras",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Motivo",
     *         in="path",
     *         name="intIdMoti",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Distrito",
     *         in="path",
     *         name="varIdDistrito",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Ingrese el Provincia",
     *         in="path",
     *         name="varIdProvincia",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese el Departamento",
     *         in="path",
     *         name="varIdDepa",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *    @OA\Parameter(
     *         description="Ingrese Punto de llegada",
     *         in="path",
     *         name="varPuntLleg",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese Transportista",
     *         in="path",
     *         name="intIdTrans",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="number" 
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="Ingrese Nombre del chofer",
     *         in="path",
     *         name="varNombChof",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese Documento del chofer",
     *         in="path",
     *         name="varNumeChof",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese Licencia del chofer",
     *         in="path",
     *         name="varNumeLicen",
     *          example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese la Placa",
     *         in="path",
     *         name="varPlaca",
     *          example="1",
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
     *                     property="intIdGuia",
     *                     type="number"
     *                 ) ,
     *                  @OA\Property(
     *                     property="dateFechTras",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdMoti",
     *                     type="number"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varIdDistrito",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varIdProvincia",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varIdDepa",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varPuntLleg",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdTrans",
     *                     type="number"
     *                 ) ,
     *                  @OA\Property(
     *                     property="varNombChof",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sin Mensaje"
     *     ),
     *    
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    function editar_idguia(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdGuia' => 'required|max:255',
            'dateFechTras' => 'required|max:255',
            'intIdMoti' => 'required|max:255',
            'varIdDistrito' => 'required|max:255',
            'varIdProvincia' => 'required|max:255',
            'varIdDepa' => 'required|max:255',
            'varPuntLleg' => 'required|max:255',
            'intIdTrans' => 'required|max:255',
            'varNombChof' => 'required|max:255',
            'varNumeChof' => 'required|max:255',
            'varNumeLicen' => 'required|max:255',
            'varPlaca' => 'required|max:255',
            'usua_modi' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        date_default_timezone_set('America/Lima'); // CDT
        $intIdGuia = $request->input('intIdGuia');
        $dateFechTras = $request->input('dateFechTras');
        $intIdMoti = $request->input('intIdMoti');
        $varMotiCome = $request->input('varMotiCome');
        $varRefe = $request->input('varRefe');
        $varIdDistrito = $request->input('varIdDistrito');
        $varIdProvincia = $request->input('varIdProvincia');
        $varIdDepa = $request->input('varIdDepa');
        $varPuntLleg = $request->input('varPuntLleg');
        $intIdTrans = $request->input('intIdTrans');
        $varNombChof = $request->input('varNombChof');
        $varNumeChof = $request->input('varNumeChof');
        $varNumeLicen = $request->input('varNumeLicen');
        $varPlaca = $request->input('varPlaca');
        $usua_modi = $request->input('usua_modi');
        GuiaRemi::where('intIdGuia', '=', $intIdGuia)
                ->update([
                    'dateFechTras' => $dateFechTras,
                    'intIdMoti' => $intIdMoti,
                    'varMotiCome' => $varMotiCome,
                    'varRefe' => $varRefe,
                    'varIdDistrito' => $varIdDistrito,
                    'varIdProvincia' => $varIdProvincia,
                    'varIdDepa' => $varIdDepa,
                    'varPuntLleg' => $varPuntLleg,
                    'intIdTrans' => $intIdTrans,
                    'varNombChof' => $varNombChof,
                    'varNumeChof' => $varNumeChof,
                    'varNumeLicen' => $varNumeLicen,
                    'varPlaca' => $varPlaca,
                    'usua_modi' => $usua_modi,
                    'hora_modi' => $current_date = date('Y/m/d H:i:s')
        ]);
        return $this->successResponse($validar);
    }

}
