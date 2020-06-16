<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//$router->get('/regi_usua','UsuarioController@regi_usua');
$router->get('/list_proy', 'GestionProyectosController@list_proy');
$router->post('/vali_proy', 'GestionProyectosController@vali_proy');
$router->post('/actu_proy', 'GestionProyectosController@actu_proy');


$router->post('/detalle_proyecto', 'GestionProyectosController@detalle_proyecto');
$router->post('/obte_maxi_archi_id', 'GestionProyectosController@obte_maxi_archi_id');


//listar cliente 
$router->get('/list_clie', 'GestionProyectosController@list_clie');
//listar Contratista
$router->get('/list_cont', 'GestionProyectosController@list_cont');
$router->get('/list_empr', 'GestionProyectosController@list_empr');




//eleg_proc_estado 
$router->post('/eleg_proc_estado', 'GestionProyectosController@eleg_proc_estado');
$router->post('/gsp_proy_nom', 'GestionProyectosController@gsp_proy_nom');



//Gestion de Tipo Estapas

$router->post('/regi_tipo_etap', 'TipoEtapasController@regi_tipo_etap');
$router->get('/list_tipo_etap', 'TipoEtapasController@list_tipo_etap');
$router->post('/vali_tipo_etap', 'TipoEtapasController@vali_tipo_etap');
$router->post('/actu_tipo_etap', 'TipoEtapasController@actu_tipo_etap');

$router->post('/list_tipo_etapa_segun_agru', 'TipoEtapasController@list_tipo_etapa_segun_agru');




//Agrupador


$router->post('/regi_agru', 'AgrupadorController@regi_agru');
$router->post('/vali_agru', 'AgrupadorController@vali_agru');
$router->get('/list_agru', 'AgrupadorController@list_agru');
$router->post('/actu_agru', 'AgrupadorController@actu_agru');
$router->get('/list_agru_acti', 'AgrupadorController@list_agru_acti');
$router->post('/list_etap_acue_agru', 'AgrupadorController@list_etap_acue_agru');





//listar tipo Producto para el vcombo box 

$router->get('/list_tipo_prod', 'EtapaController@list_tipo_prod');
//lista de medicion de medida para el vcombo box 
$router->get('/list_unid_medi', 'EtapaController@list_unid_medi');
//listar procesos para el vcombo box 

$router->get('/list_proc', 'EtapaController@list_proc');
//listar planta para el vcombo box 
$router->get('/list_plan', 'EtapaController@list_plan');
//listar Tipo de etapa para el vcombo box 
$router->get('/list_t_etap', 'EtapaController@list_t_etap');
$router->post('/elim_etap', 'EtapaController@elim_etap');

$router->post('/list_t_etap_repo_valo', 'EtapaController@list_t_etap_repo_valo');

$router->post('/etap_actu_repo_valo', 'EtapaController@etap_actu_repo_valo');





$router->post('/list_tipo_etap_proy_tipopro', 'EtapaController@list_tipo_etap_proy_tipopro');




//Gestion Etapas (Registrar)
$router->post('/regi_etap', 'EtapaController@regi_etap');
$router->post('/vali_etap', 'EtapaController@vali_etap');
$router->post('/actu_etap', 'EtapaController@actu_etap');
$router->get('/lis_etap', 'EtapaController@lis_etap');

$router->post('/obte_desc_tipo_etapa_acue_etap', 'EtapaController@obte_desc_tipo_etapa_acue_etap');


$router->post('/etapa_asig_al_proy', 'EtapaController@etapa_asig_al_proy');




//armadores
$router->get('/list_arma', 'ArmadoresController@list_arma');
$router->post('/regi_arma', 'ArmadoresController@regi_arma');
$router->post('/vali_arma', 'ArmadoresController@vali_arma');
$router->post('/actu_arma', 'ArmadoresController@actu_arma');
$router->post('/elim_arma', 'ArmadoresController@elim_arma');

//Tipo estructurado

$router->post('/regis_tipo_estru', 'TipoEstructuradoController@regis_tipo_estru');
$router->post('/vali_tipo_estru', 'TipoEstructuradoController@vali_tipo_estru');
$router->post('/actu_tipo_estru', 'TipoEstructuradoController@actu_tipo_estru');
$router->post('/elim_tipo_estru', 'TipoEstructuradoController@elim_tipo_estru');
$router->get('/list_tipo_estru', 'TipoEstructuradoController@list_tipo_estru');
$router->post('/busc_boton_tipo_estru', 'TipoEstructuradoController@busc_boton_tipo_estru');


// function que retorna intIdTipoEstructurado  si existe .En caso contrario 
// devulve un mensaje de error (Para el partlist)
$router->post('/validar_TipoEstructurado', 'TipoEstructuradoController@validar_TipoEstructurado');



//valida si el TIPO DE ESTRUCTURA EXISTE, 
//EN CASO QUE NO EXISTA MANDA UN "ERROR,POR FAVOR TIENE QUE REGISTRARLO."
$router->post('/validar_TipoEstructura', 'TipoEstructuradoController@validar_TipoEstructura');


//tipo estructura
$router->post('/buscar_tipo_Estructura', 'TipoEstructuradoController@buscar_tipo_Estructura');


//periodo valorizacion 

$router->get('/list_peri_valo', 'PeriodovalorizacionController@list_peri_valo');
$router->post('/regi_peri_valo', 'PeriodovalorizacionController@regi_peri_valo');
$router->post('/vali_peri_valo', 'PeriodovalorizacionController@vali_peri_valo');
$router->post('/actu_peri_valo', 'PeriodovalorizacionController@actu_peri_valo');
$router->post('/elim_pedi_valor', 'PeriodovalorizacionController@elim_pedi_valor');

//$router->delete('/elim_pedi_valor','PeriodovalorizacionController@elim_pedi_valor');
$router->get('/list_peri_valo_abie', 'PeriodovalorizacionController@list_peri_valo_abie');
$router->post('/actu_pedi_valor_esta', 'PeriodovalorizacionController@actu_pedi_valor_esta');

$router->get('/list_peri_valo_cerr', 'PeriodovalorizacionController@list_peri_valo_cerr');






//CONTRATISTA 


$router->get('/most_agru_asig_cont', 'ContratistaController@most_agru_asig_cont');
$router->post('/asig_cont_aun_agru', 'ContratistaController@asig_cont_aun_agru');
$router->post('/elim_cont_agru', 'ContratistaController@elim_cont_agru');
$router->post('/list_cont_segun_agru', 'ContratistaController@list_cont_segun_agru');

$router->post('/list_cont_segun_tipo_etapa', 'ContratistaController@list_cont_segun_tipo_etapa');


//COLABORADOR


$router->post('/asig_cola_aun_agru', 'ColaboradorController@asig_cola_aun_agru');
$router->post('/elim_cola_agru', 'ColaboradorController@elim_cola_agru');
$router->post('/list_cola_segun_id_agru', 'ColaboradorController@list_cola_segun_id_agru');
$router->get('/list_cola', 'ColaboradorController@list_cola');
$router->post('/asig_cola_aun_agru_jquery', 'ColaboradorController@asig_cola_aun_agru_jquery');
$router->post('/eliminar_pro', 'ColaboradorController@eliminar_pro');
$router->post('/listar_colaborador_tipo_etapa', 'ColaboradorController@listar_colaborador_tipo_etapa');

//CHOFER

$router->get('/list_chof', 'ChoferController@list_chof');
$router->post('/regi_chof', 'ChoferController@regi_chof');
$router->post('/vali_chof', 'ChoferController@vali_chof');
$router->post('/actu_chof', 'ChoferController@actu_chof');
$router->post('/elim_chof', 'ChoferController@elim_chof');

//TRANSPORTISTA
$router->get('/list_tran', 'TransportistaController@list_tran');
$router->post('/regi_tran', 'TransportistaController@regi_tran');
$router->post('/vali_tran', 'TransportistaController@vali_tran');
$router->post('/actu_tran', 'TransportistaController@actu_tran');
$router->post('/elim_tran', 'TransportistaController@elim_tran');
$router->get('/list_tran_activo', 'TransportistaController@list_tran_activo');
$router->post('/list_chofer_trans', 'TransportistaController@list_chofer_trans');



// MOTIVO 

$router->get('/list_moti', 'MotivoController@list_moti');

$router->post('/regi_moti', 'MotivoController@regi_moti');
$router->post('/actu_moti', 'MotivoController@actu_moti');
$router->get('/list_tipo_moti_act', 'MotivoController@list_tipo_moti_act');

$router->post('/elim_mot', 'MotivoController@elim_mot');
$router->post('/list_motivo_id', 'MotivoController@list_motivo_id');

//unidad de negocio

$router->get('/list_unid_nego_acti', 'UnidadNegocioController@list_unid_nego_acti');
$router->post('/regi_unid_nego', 'UnidadNegocioController@regi_unid_nego');
$router->post('/actu_unid_nego', 'UnidadNegocioController@actu_unid_nego');
$router->get('/list_unid_nego_todo', 'UnidadNegocioController@list_unid_nego_todo');
$router->post('/elim_unid_nego', 'UnidadNegocioController@elim_unid_nego');
$router->post('/combox_list_unid_nego_proy', 'UnidadNegocioController@combox_list_unid_nego_proy');

//maquina

$router->get('/list_maqui_todo', 'MaquinaController@list_maqui_todo');
$router->get('/list_maqu_acti', 'MaquinaController@list_maqu_acti');
$router->post('/regi_maqui', 'MaquinaController@regi_maqui');
$router->post('/actu_maqui', 'MaquinaController@actu_maqui');
$router->post('/elim_maqui', 'MaquinaController@elim_maqui');
$router->get('/list_agru_acti', 'MaquinaController@list_agru_acti');


//****************** LISTAR TIPO ESTRUCTURA ******************

$router->get('/listar_tipo_Estructura', 'TipoEstructuraController@list_tipo_estructura');
$router->post('/registrar_tipo_estructura', 'TipoEstructuraController@regis_tipo_estructura');
$router->post('/actualizar_tipo_estructura', 'TipoEstructuraController@actu_tipo_estructura');


//****************** LISTAR TIPO GRUPO ******************

$router->get('/listar_tipo_grupo', 'TipoGrupoController@list_tipo_grupo');
$router->post('/registrar_tipo_grupo', 'TipoGrupoController@regis_tipo_grupo');
$router->post('/actualizar_tipo_grupo', 'TipoGrupoController@actu_tipo_grupo');

//****************** LISTAR TABLA DEFECTO ******************

$router->get('/lista_defecto', 'DefectoController@lista_defecto');
$router->post('/registrar_defecto', 'DefectoController@registrar_defecto');
$router->post('/actualizar_defecto', 'DefectoController@actualizar_defecto');
$router->post('/lista_defecto_etapa', 'DefectoController@lista_defecto_etapa');

//****************** LISTAR TABLA CAUSA ******************
$router->get('/list_causa', 'CausaController@list_causa');
$router->get('/list_caus', 'CausaController@list_caus');
$router->post('/regi_caus', 'CausaController@regi_caus');
$router->post('/edit_caus', 'CausaController@edit_caus');
$router->post('/dele_caus', 'CausaController@dele_caus');

//******************** LISTAR COMBOS DE DESPACHO  **********************
$router->post('/list_bulto', 'RotuloController@list_bulto');
$router->post('/list_modelo', 'RotuloController@list_modelo');
$router->post('/list_bulto_total', 'RotuloController@list_bulto_total');
$router->post('/listar_bulto_detalle', 'RotuloController@listar_bulto_detalle');
$router->post('/listar_por_codigo_bulto', 'RotuloController@listar_por_codigo_bulto');
$router->post('/list_codigo', 'RotuloController@list_codigo');
$router->post('/list_bulto_total_despacho', 'RotuloController@list_bulto_total_despacho');
$router->post('/list_modelo_2', 'RotuloController@list_modelo_2');
$router->post('/list_bulto_2', 'RotuloController@list_bulto_2');
$router->post('/lista_despacho', 'RotuloController@lista_despacho');
$router->post('/list_bulto_codigo', 'RotuloController@list_bulto_codigo');
$router->post('/list_tipo_grupo', 'RotuloController@list_tipo_grupo');
$router->post('/list_tipo_zona', 'RotuloController@list_tipo_zona');
$router->post('/list_despc_codigo', 'RotuloController@list_despc_codigo');
$router->post('/list_despc_serie', 'RotuloController@list_despc_serie');
$router->post('/listar_guia', 'RotuloController@listar_guia');
$router->post('/detalle_guia', 'RotuloController@detalle_guia');
$router->post('/detalle_guia_cantidad', 'RotuloController@detalle_guia_cantidad');
//******************* CLIENTES  **************************/
$router->post('/listar_cliente', 'ClienteController@listar_cliente');
$router->get('/list_departamento', 'ClienteController@list_departamento');
$router->post('/list_provincia', 'ClienteController@list_provincia');
$router->post('/list_distrito', 'ClienteController@list_distrito');
/* * *****************GUIAS ********************************* */
$router->post('/list_guias', 'GuiaController@list_guias');
$router->post('/anular_guias', 'GuiaController@anular_guias');
$router->post('/guia_sin_imprimir', 'GuiaController@guia_sin_imprimir');
$router->post('/guia_imprimir', 'GuiaController@guia_imprimir');
$router->post('/list_cantidad_list_guia', 'GuiaController@list_cantidad_list_guia');
$router->post('/list_serie_list_guia', 'GuiaController@list_serie_list_guia');
$router->get('/numero_guia', 'GuiaController@numero_guia');
$router->post('/actualizar_docu', 'GuiaController@actualizar_docu');
// ANDY COLOCO REPORTE DE FOTOGRAFIA DESPACHO 
$router->post('/repo_foto_despa', 'GuiaController@repo_foto_despa');
$router->post('/repo_foto_recep', 'GuiaController@repo_foto_recep');
$router->post('/validar_data', 'GuiaController@validar_data');


//COLOCO ANDY 

$router->post('/guia_emitida', 'RotuloController@guia_emitida');
$router->post('/guia_recibida', 'RotuloController@guia_recibida');
$router->post('/obtn_info_idGuia', 'RotuloController@obtn_info_idGuia');
$router->post('/editar_idguia', 'RotuloController@editar_idguia');
$router->post('/list_modelo_codigo', 'RotuloController@list_modelo_codigo');

///UNIDAD DE MEDIDA
$router->get('/list_unid_medida', 'UnidadMedidaController@list_unid_medida');

$router->get('/obtener_os_erp', 'ProyectosERP@obtener_os_erp');
$router->get('/obtener_ot_erp', 'ProyectosERP@obtener_ot_erp');
$router->post('/crear_galvanizado', 'GalvanizadoController@crear_galvanizado');
$router->post('/listar_galvanizado', 'GalvanizadoController@listar_galvanizado');
$router->post('/listar_os', 'GalvanizadoController@listar_os');
$router->post('/crear_detalle', 'GalvanizadoController@crear_detalle');

$router->get('/obtener_ot_os_g', 'ProyectosERP@obtener_ot_os_g');
$router->post('/obtener_os_id', 'ProyectosERP@obtener_os_id');
$router->post('/listar_guias_ot', 'GalvanizadoController@listar_guias_ot');
$router->post('/listar_galvanizado_detalle', 'GalvanizadoController@listar_galvanizado_detalle');
$router->post('/actualizar_detalle_galvanizado', 'GalvanizadoController@actualizar_detalle_galvanizado');
$router->post('/editar_cabecera', 'GalvanizadoController@editar_cabecera');
$router->post('/reporte_galvanizado_cabecera_detalle', 'GalvanizadoController@reporte_galvanizado_cabecera_detalle');
/* PERSONA TIPO ETAPA */
$router->post('/listar_personas_por_tipo_etapa', 'PersonaTipoEtapaController@listar_personas_por_tipo_etapa');
$router->post('/crear_personas_por_tipo_etapa', 'PersonaTipoEtapaController@crear_personas_por_tipo_etapa');
$router->post('/actualizar_personas_por_tipo_etapa', 'PersonaTipoEtapaController@actualizar_personas_por_tipo_etapa');

/* PINTURA */
$router->post('/listar_sistema_pintura', 'PinturaController@listar_sistema_pintura');
$router->post('/list_metrado_pintura', 'PinturaController@list_metrado_pintura');
$router->post('/crear_pintura', 'PinturaController@crear_pintura');
$router->post('/listar_pintura', 'PinturaController@listar_pintura');
$router->post('/list_detalle_pintura', 'PinturaController@list_detalle_pintura');
$router->post('/list_tab_pintura_id', 'PinturaController@list_tab_pintura_id');
$router->post('/combo_pintura', 'PinturaController@combo_pintura');
$router->post('/editar_pintura', 'PinturaController@editar_pintura');
$router->post('/listar_pintores', 'PinturaController@listar_pintores');
$router->get('/combo_contratista', 'PinturaController@combo_contratista');
$router->post('/cabecera_seguimiento', 'PinturaController@cabecera_seguimiento');
$router->post('/detalle_pintura', 'PinturaController@detalle_pintura');  
$router->get('/lote_pintura_serie', 'PinturaController@lote_pintura_serie');
/* CABINA */
$router->get('/listar_cabina', 'CabinaController@listar_cabina');

/* INSPECCION */
$router->post('/listar_detalle_inspeccion', 'InspeccionController@listar_detalle_inspeccion');
$router->post('/listar_detalle_causas', 'InspeccionController@listar_detalle_causas');


/*ESPECIFICACION*/
$router->post('/crear_especificaion', 'EspecificacionesController@crear_especificaion');
$router->get('/listar_especificaciones', 'EspecificacionesController@listar_especificaciones');
$router->post('/actualizar_especificacion', 'EspecificacionesController@actualizar_especificacion');
$router->post('/estado_especificacion', 'EspecificacionesController@estado_especificacion');
$router->post('/get_especificacion', 'EspecificacionesController@get_especificacion');
$router->get('/listar_especificaciones_lista', 'EspecificacionesController@listar_especificaciones_lista');
$router->post('/editar_especificacion', 'EspecificacionesController@editar_especificacion');
$router->post('/listar_especificaciones_id', 'EspecificacionesController@listar_especificaciones_id');


/*INSPECCION GALVANIZADO*/
$router->post('/lista_insp_galvanizado', 'InspeccionGalvanizadoController@lista_insp_galvanizado');
$router->post('/crear_inspeccion_galvanizado', 'InspeccionGalvanizadoController@crear_inspeccion_galvanizado');
$router->post('/crear_observacion_galvanizado', 'InspeccionGalvanizadoController@crear_observacion_galvanizado');
$router->post('/dni_data', 'dni@dni_data');
$router->post('/llistar_pesos_ot','PesosController@llistar_pesos_ot');
$router->post('/listqar_pesos_sub_ot','PesosController@listqar_pesos_sub_ot');
$router->post('/listar_ot_pesos','PesosController@listar_ot_pesos');
$router->post('/cantidad_inspeccion','InspeccionGalvanizadoController@cantidad_inspeccion');
$router->post('/detalle_inspeccion','InspeccionGalvanizadoController@detalle_inspeccion');
/*CONTRATO*/
$router->post('/crear_contrato','ContratoController@crear_contrato');
$router->get('/listar_contratista_contrato','ContratoController@listar_contratista');
$router->post('/listar_contrato','ContratoController@listar_contrato');
$router->get('/listar_contratistas_editar','ContratoController@listar_contratistas_editar');
$router->post('/contrato_valorizado','ContratoController@contrato_valorizado');
$router->post('/editar_contrato','ContratoController@editar_contrato');
$router->post('/listar_tarifa','Contrato_tarifaControllres@listar_tarifa');
$router->post('/crear_tarifa','Contrato_tarifaControllres@crear_tarifa');
$router->post('/editar_tarifa','Contrato_tarifaControllres@editar_tarifa');
$router->post('/contrato_id','ContratoController@contrato_id');
$router->post('/contrato_tarifa','Contrato_tarifaControllres@contrato_tarifa');
$router->post('/valorizacion_contrato','ContratoController@valorizacion_contrato');

