<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\DespachoTabla;

class GuiaController extends Controller {

    use \App\Traits\ApiResponser;

    // Illuminate\Support\Facades\DB;
    /**
     * Create a new controSller instance.
     *
     * @return void
     */

    /**
     * @OA\Post(
     *    path="/GestionProyectos/public/index.php/list_guias",
     *     
     *     tags={"Gestion Guia"},
     *     summary="Listar la Guia",
     *     @OA\Parameter(
     *         description="Ingrese el tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *        example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el codigo OT",
     *         in="path",
     *         name="intIdProy",
     *        example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese la fecha inicio",
     *         in="path",
     *         name="fecha_inicio",
     *        example="2020-02-01",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese la fecha final",
     *         in="path",
     *         name="fecha_final",
     *        example="2020-02-18",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el estado",
     *         in="path",
     *         name="intIdEsta",
     *        example="-1",
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
     *                 @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="fecha_inicio",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="fecha_final",
     *                     type="string"
     *                 ) ,
     *                 @OA\Property(
     *                     property="intIdEsta",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdTipoProducto": "1","intIdProy":188,"fecha_inicio":"2020-02-01","fecha_final":"2020-02-18","intIdEsta":"-1"}
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
    public function list_guias(Request $request) {
        $regla = [
            'intIdTipoProducto' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'fecha_inicio' => 'required|max:255',
            'fecha_final' => 'required|max:255',
            'intIdEsta' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $intIdProy = $request->input('intIdProy');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_final = $request->input('fecha_final');
        $intIdEsta = (int) $request->input('intIdEsta');
        $list_guia = DB::select("select g.intIdGuia,g.intIdDesp,g.varContaDocu,(case  when isnull(sum(e.deciPesoNeto))  then 0 else sum(e.deciPesoNeto) end )  as deciPesoNeto ,count(e.varGuia) as cantidad,
                                g.varRefe,es.varDescEsta as varEsta,g.acti_usua,g.acti_hora,case g.varTipoGuia when 2 then 'SERIE' else 'CANTIDAD' end AS tipodocumento,g.varTipoGuia,e.varGuia,
                                g.varArchEmit,g.varArchRecep,m.varDescripcion
                                from guia_remi g 
                                left join motivo m on g.intIdMotiAnul=m.intIdMoti
                                left join elemento e on g.intIdGuia=e.varGuia
                                left join estado es on g.intIdEsta=es.intIdEsta
                                where g.intIdTipoProducto=$intIdTipoProducto and g.intIdProy=$intIdProy and g.dateFechEmis between '$fecha_inicio' and '$fecha_final' and (g.intIdEsta=$intIdEsta or -1=$intIdEsta)
                                group by g.intIdGuia,g.intIdDesp,g.varContaDocu,e.nume_guia order by g.intIdGuia,g.intIdDesp asc ");
        return $this->successResponse($list_guia);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_cantidad_list_guia",
     *     tags={"Gestion Guia"},
     *     summary="listar cantidad de la guia",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de la guia",
     *         in="path",
     *         name="intIdGuia",
     *         example="1",
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
     *                     property="intIdGuia",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": "1"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="listar la cantidad"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_cantidad_list_guia(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdGuia = (int) $request->input('intIdGuia');
        $list_cantidad = DB::select("select count(e.varGuia) as cant,e.varCodiElemento,e.varDescripcion,pt.varDescripTarea,pz.varDescrip,e.deciPesoNeto,e.deciPesoBruto,e.deciArea
                                    from elemento e 
                                    inner join proyecto_zona pz on e.intIdProyZona=pz.intIdProyZona
                                    inner join guia_remi g on e.varGuia=g.intIdGuia
                                    inner join proyecto_tarea pt on pt.intIdProyTarea=e.intIdProyTarea
                                    where g.intIdGuia=$intIdGuia and g.intIdEsta<>29
                                    group by e.varCodiElemento,e.varCodiElemento,e.varDescripcion,pt.varDescripTarea,pz.varDescrip,e.deciPesoNeto,e.deciPesoBruto,e.deciArea");
        return $this->successResponse($list_cantidad);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/list_serie_list_guia",
     *     tags={"Gestion Guia"},
     *     summary="listar serie de la guia",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de la guia",
     *         in="path",
     *         name="intIdGuia",
     *         example="15",
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
     *                     property="intIdGuia",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": "15"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="listar la cantidad"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function list_serie_list_guia(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdGuia = (int) $request->input('intIdGuia');
        $list_cantidad = DB::select("select e.intSerie,e.varCodiElemento,e.varDescripcion,pt.varDescripTarea,pz.varDescrip,e.deciPesoNeto,e.deciPesoBruto,e.deciArea
                                    from elemento e 
                                    inner join proyecto_zona pz on e.intIdProyZona=pz.intIdProyZona
                                    inner join guia_remi g on e.varGuia=g.intIdGuia
                                    inner join proyecto_tarea pt on pt.intIdProyTarea=e.intIdProyTarea
                                    where g.intIdGuia=$intIdGuia and g.intIdEsta<>29");
        return $this->successResponse($list_cantidad);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/anular_guias",
     *     tags={"Gestion Guia"},
     *     summary="Anular una guia",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de la guia",
     *         in="path",
     *         name="intIdGuia",
     *         example="15",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="8",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el codigo del proyecto",
     *         in="path",
     *         name="intIdProy",
     *         example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * 
      @OA\Parameter(
     *         description="Ingrese el codigo del tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el codigo del motivo",
     *         in="path",
     *         name="intIdMotiAnul",
     *         example="14",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
      @OA\Parameter(
     *         description="Ingrese el usuario que va realizar la anulacion",
     *         in="path",
     *         name="usua_anul",
     *         example="andy_ancajima",
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
     *                     property="intIdGuia",
     *                     type="string"
     *                 ) ,
     *                @OA\Property(
     *                     property="intIdDesp",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *               @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdMotiAnul",
     *                     type="string"
     *                 ) ,
     *    *          @OA\Property(
     *                     property="usua_anul",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdGuia": "15","intIdDesp":"8","intIdProy":"188","intIdTipoProducto":"1","intIdMotiAnul":"14","usua_anul":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="listar la cantidad"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function anular_guias(Request $request) {
        $regla = [
            'intIdGuia' => 'required|max:255',
            'intIdDesp' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'intIdMotiAnul' => 'required|max:255',
            'usua_anul' => 'required|max:255',
        ];
        $this->validate($request, $regla);

        date_default_timezone_set('America/Lima'); // CDT

        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $intIdProy = (int) $request->input('intIdProy');
        $intIdGuia = (int) $request->input('intIdGuia');
        $intIdDesp = (int) $request->input('intIdDesp');
        $intIdMotiAnul = (int) $request->input('intIdMotiAnul');
        $usua_anul = $request->input('usua_anul');
        $current_date = date('Y/m/d H:i:s');

        $despacho = DB::select("select count(intIdDesp) as cantidad from guia_remi where intIdDesp=$intIdDesp and intIdProy=$intIdProy and intIdTipoProducto=$intIdTipoProducto and intIdEsta<>29");
        $actualizar = DB::table('guia_remi')
                ->where('intIdGuia', $intIdGuia)
                ->where('intIdProy', $intIdProy)
                ->where('intIdTipoProducto', $intIdTipoProducto)
                ->update(['intIdEsta' => 29, 'intIdMotiAnul' => $intIdMotiAnul, 'usua_anul' => $usua_anul, 'hora_anul' => $current_date]);


        $cantidad_despacho = $despacho[0]->cantidad;
        $actualizar = DB::table('elemento')
                ->where('varGuia', $intIdGuia)
                ->where('intIdProy', $intIdProy)
                ->where('intIdTipoProducto', $intIdTipoProducto)
                ->update(['nume_guia' => '', 'varUnidMedi' => '', 'varGuia' => '', 'intIdEsta' => 4]);
        if ($cantidad_despacho > 1) {
            $actualizar = DB::table('tab_desp')
                    ->where('intIdProy', $intIdProy)
                    ->where('intIdTipoProducto', $intIdTipoProducto)
                    ->where('intIdDesp', $intIdDesp)
                    ->update(['intIdEsta' => 28]);
        } else {
            $actualizar = DB::table('tab_desp')
                    ->where('intIdProy', $intIdProy)
                    ->where('intIdTipoProducto', $intIdTipoProducto)
                    ->where('intIdDesp', $intIdDesp)
                    ->update(['intIdEsta' => 26]);
        }
        $respuesta = '';
        return $this->successResponse($respuesta);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/guia_sin_imprimir",
     *     tags={"Gestion Guia"},
     *     summary="Guia sin imprimir",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de la guia",
     *         in="path",
     *         name="data",
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
     *                     property="data",
     *                     type="string"
     *                 ) ,
     *                 example={"data": "15"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="exitoso"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function guia_sin_imprimir(Request $request) {
        $regla = [
            'data' => 'required',
        ];

        $this->validate($request, $regla);
        $data = $request->input('data');
        $data_guia = json_decode($data);
        for ($i = 0; count($data_guia) > $i; $i++) {
            $actualizar = DB::table('guia_remi')
                    ->where('intIdGuia', $data_guia[$i]->id)
                    ->update(['intIdEsta' => 31]);
        }
        $respuesta = '';
        return $this->successResponse($respuesta);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/guia_imprimir",
     *     tags={"Gestion Guia"},
     *     summary="Guia imprimir",
     *     @OA\Parameter(
     *         description="Ingrese el codigo de la guia",
     *         in="path",
     *         name="data",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el usuario  que realizo ese proceso",
     *         in="path",
     *         name="user",
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
     *                     property="data",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="user",
     *                     type="string"
     *                 ) ,
     *                 example={"data": "15","user":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="exitoso"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function guia_imprimir(Request $request) {
        $regla = [
            'data' => 'required',
            'user' => 'required'
        ];

        $this->validate($request, $regla);
        $data = $request->input('data');
        $user = $request->input('user');
        $data_guia = json_decode($data);
        for ($i = 0; count($data_guia) > $i; $i++) {
            date_default_timezone_set('America/Lima'); // CDT
            $v_fecha = $current_date = date('Y-m-d H:i:s');
            DB::table('guia_remi')
                    ->where('intIdGuia', $data_guia[$i]->id)
                    ->update(['usua_impr' => $user, 'hora_impr' => $v_fecha, 'intIdEsta' => 30]);
            DB::table('elemento')
                    ->where('varGuia', $data_guia[$i]->id)
                    ->update(['intIdEsta' => 2]);
        }
        $respuesta = '';
        return $this->successResponse($respuesta);
    }

    /**
     * @OA\Get(
     *     path="/GestionProyectos/public/index.php/numero_guia",
     *     tags={"Gestion Guia"},
     *     summary="Numero de guia",
     *     
     *     @OA\Response(
     *         response=200,
     *         description=" Se cambia el numero de guia , si el usuario desea"
     *     )
     * )
     */
    /* PARA OBTENER LA SERIE Y EL DOCUMENTO */
    public function numero_guia() {
        $id_guia = DB::select("select ifnull(max(intContaDocu),0)+1  as id,varSerieDocu   from tab_docu where intIdEsta=3 and intIdDocu=1");
        $id_g = (int) $id_guia[0]->id;
        $serie_guia = $id_guia[0]->varSerieDocu;
        $idguia = str_pad($id_g, 7, '0', STR_PAD_LEFT);
        $validar = array('serie' => array(), 'documento' => array());
        $validar['serie'][] .= $serie_guia;
        $validar['documento'][] .= $idguia;
        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/actualizar_docu",
     *     tags={"Gestion Guia"},
     *     summary="para actulizar el numero de documento de la guia emision",
     *     @OA\Parameter(
     *         description="Ingrese el documento",
     *         in="path",
     *         name="docu",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el usuario  que realizo ese proceso",
     *         in="path",
     *         name="user",
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
     *                     property="docu",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="user",
     *                     type="string"
     *                 ) ,
     *                 example={"docu": "15","user":"andy_ancajima"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="exitoso"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    /*     * para actulizar el numero de documento de la guia emision */
    public function actualizar_docu(Request $request) {
        $regla = [
            'docu' => 'required',
            'user' => 'required'
        ];
        $this->validate($request, $regla);
        $user = $request->input('user');
        $docu = (int) $request->input('docu');
        $docu = $docu - 1;
        date_default_timezone_set('America/Lima'); // CDT
        //dd($user,$docu);
        $v_fecha = $current_date = date('Y-m-d H:i:s');
        $id_guia = DB::select("select ifnull(max(intContaDocu),0)  as id,varSerieDocu   from tab_docu where intIdEsta=3 and intIdDocu=1");
        $id_g = (int) $id_guia[0]->id;
        if ($docu < $id_g) {
            $respuesta = 'No puede ser menor a la guías Generadas';
            return $this->successResponse($respuesta);
        } else {
            $actualizar = DB::table('tab_docu')
                    ->where('intIdDocu', '=', 1)
                    ->update(['intContaDocu' => $docu, 'usua_modi' => $user, 'hora_modi' => $v_fecha]);
            $respuesta = '';
            return $this->successResponse($respuesta);
        }
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/repo_foto_despa",
     *     tags={"Gestion Guia"},
     *     summary="Adjuntar archivo (FOTOGRAFIA DESPACHO) a la guia de remision",
     *     @OA\Parameter(
     *         description="Ingrese el codigo despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="10",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo proyecto",
     *         in="path",
     *         name="intIdProy",
     *         example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el nombre del archivo de despacho",
     *         in="path",
     *         name="varArchDesp",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      * @OA\Parameter(
     *         description="Ingrese el usuario que realizo el subir del archivo",
     *         in="path",
     *         name="arch_desp_usua",
     *         example="andy_ancajima",
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
     *                     property="docu",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="user",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdDesp": "100000000","intIdProy":"188","intIdTipoProducto":"1","varArchDesp":"prueba.docx","arch_desp_usua":"ancajima_timana"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="exitoso"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_foto_despa(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdDesp' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varArchDesp' => 'required',
            'arch_desp_usua' => 'required'
        ];
        $this->validate($request, $regla);
        $intIdDesp = $request->input('intIdDesp');
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $varArchDesp = $request->input('varArchDesp');
        $arch_desp_usua = $request->input('arch_desp_usua');
        date_default_timezone_set('America/Lima'); // CDT
        $current_date = date('Y/m/d H:i:s');

        //  dd($intIdDesp,$intIdProy,$intIdTipoProducto,$varArchDesp,$arch_desp_usua,$current_date);


        DespachoTabla::where('intIdDesp', '=', $intIdDesp)
                ->where('intIdProy', '=', $intIdProy)
                ->where('intIdTipoProducto', '=', $intIdTipoProducto)
                ->update([
                    'varArchDesp' => $varArchDesp,
                    'arch_desp_usua' => $varArchDesp,
                    'arch_desp_hora' => $current_date
        ]);


        return $this->successResponse($validar);
    }

    /**
     * @OA\Post(
     *     path="/GestionProyectos/public/index.php/repo_foto_recep",
     *     tags={"Gestion Guia"},
     *     summary="Adjuntar archivo (FOTOGRAFIA RECEPCIONADO) a la guia de remision",
     *     @OA\Parameter(
     *         description="Ingrese el codigo despacho",
     *         in="path",
     *         name="intIdDesp",
     *         example="10",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Ingrese el codigo proyecto",
     *         in="path",
     *         name="intIdProy",
     *         example="188",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *  @OA\Parameter(
     *         description="Ingrese el tipo elemento",
     *         in="path",
     *         name="intIdTipoProducto",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     * @OA\Parameter(
     *         description="Ingrese el nombre del archivo de despacho",
     *         in="path",
     *         name="varArchRece",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *           type="string" 
     *         )
     *     ),
     *      * @OA\Parameter(
     *         description="Ingrese el usuario que realizo el subir del archivo",
     *         in="path",
     *         name="arch_rece_usua",
     *         example="andy_ancajima",
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
     *                     property="intIdDesp",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="intIdProy",
     *                     type="string"
     *                 ) ,
     *              @OA\Property(
     *                     property="intIdTipoProducto",
     *                     type="string"
     *                 ) ,
     *                   @OA\Property(
     *                     property="varArchDesp",
     *                     type="string"
     *                 ) ,
     *                  @OA\Property(
     *                     property="arch_desp_usua",
     *                     type="string"
     *                 ) ,
     *                 example={"intIdDesp": "100000001","intIdProy":"188","intIdTipoProducto":"1","varArchDesp":"prueba-FR.docx","arch_desp_usua":"ancajima_timana"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="exitoso"
     *     ),
     *     
     *     @OA\Response(
     *         response=404,
     *         description="No se encuentra el metodo"
     *     )
     * )
     */
    public function repo_foto_recep(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdDesp' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255',
            'varArchRece' => 'required',
            'arch_rece_usua' => 'required'
        ];
        $this->validate($request, $regla);
        $intIdDesp = $request->input('intIdDesp');
        $intIdProy = $request->input('intIdProy');
        $intIdTipoProducto = $request->input('intIdTipoProducto');
        $varArchRece = $request->input('varArchRece');
        $arch_rece_usua = $request->input('arch_rece_usua');
        date_default_timezone_set('America/Lima'); // CDT
        $current_date = date('Y/m/d H:i:s');

        // dd($intIdDesp,$intIdProy,$intIdTipoProducto,$varArchRece,$arch_rece_usua,$current_date);


        DespachoTabla::where('intIdDesp', '=', $intIdDesp)
                ->where('intIdProy', '=', $intIdProy)
                ->where('intIdTipoProducto', '=', $intIdTipoProducto)
                ->update([
                    'varArchRece' => $varArchRece,
                    'arch_rece_usua' => $arch_rece_usua,
                    'arch_rece_hora' => $current_date
        ]);


        return $this->successResponse($validar);
    }

    public function validar_data(Request $request) {
        $validar = array('mensaje' => '');
        $regla = [
            'intIdDesp' => 'required|max:255',
            'intIdProy' => 'required|max:255',
            'intIdTipoProducto' => 'required|max:255'
        ];
        $this->validate($request, $regla);
        $intIdDesp = (int) $request->input('intIdDesp');
        $intIdProy = (int) $request->input('intIdProy');
        $intIdTipoProducto = (int) $request->input('intIdTipoProducto');
        $list_guia = DB::select("select * from guia_remi where intIdProy=$intIdProy and intIdTipoProducto=$intIdTipoProducto and intIdDesp=$intIdDesp");
        //dd($list_guia);
        if (count($list_guia) > 0) {
            
            $listar_cabecera = DB::select("SELECT g.varIdDistrito,g.varIdProvincia,g.varIdDepa,g.varIdDistritoSali,g.varIdProvinciaSali,g.varIdDepaSali,g.varNombChof,g.varNumeChof,g.varNumeLicen,g.intIdTrans,
                                        g.dateFechEmis,g.dateFechTras,g.varTipoGuia,g.intIdMoti,g.varMotiCome,g.varPuntSali,g.varPuntLleg,g.varPlaca,g.varRefe
                                        ,c.varRazClie,c.varRucClie,g.intIdPlanta,c.intIdClie
                                        FROM guia_remi g 
                                        inner join (select max(intIdGuia) as intIdGuia from guia_remi where  intIdDesp=$intIdDesp and intIdEsta<>29 and intIdProy=$intIdProy and intIdTipoProducto=$intIdTipoProducto) b on g.intIdGuia=b.intIdGuia
                                        inner join cliente c on g.intIdCliente=c.intIdClie
                                        where g.intIdDesp=$intIdDesp and g.intIdEsta<>29 and g.intIdProy=$intIdProy and g.intIdTipoProducto=$intIdTipoProducto ");
           //dd($listar_cabecera); 
            return $this->successResponse($listar_cabecera);
        } else {
            return $this->successResponse($list_guia);
        }
    }

}
