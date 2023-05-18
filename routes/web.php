<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
  Artisan::call('cache:clear');
  return "Cache is cleared";
});

// RUTA PARA PROBAR CAMARA EN NAVEGADOR
Route::get('/prueba_camara', function () {
  return view('welcome');
});

Route::get('/', function (App\Sucursal $sucursal) {
  //return view('welcome');
  $sucursales = $sucursal::where('estado_id', 1)->get();
  //dd($sucursales);
  return view('layout.login', compact('sucursales'));
});

Route::resource('Inicio', 'InicioController');

/*USUARIO*/
Route::resource('Usuario', 'Administracion\UsuarioController');
Route::post('IniciarSesion', 'Administracion\UsuarioController@postLogin');
Route::get('BuscarUsuarios', 'Administracion\UsuarioController@buscarUsuarios');
Route::get('ListadoRedes/{id}', 'Administracion\UsuarioController@listadoRedes');
Route::get('ListadoEstablecimientos/{id}', 'Administracion\UsuarioController@listadoEstablecimientos');
Route::get('DatEstablemiento/{id}/{tipoDestino}', 'Administracion\UsuarioController@datoEstablemiento');
Route::get('GenerarUsuario/{id}', 'Administracion\UsuarioController@generarUsuario');
Route::get('PersonasNoHabilitadas', 'Administracion\UsuarioController@personasNoHabilitadas');
Route::delete('HabilitarUsuario/{id}', 'Administracion\UsuarioController@habilitarUsuario');
Route::delete('ResetearUsuario/{id}', 'Administracion\UsuarioController@resetearUsuario');
Route::get('BuscarPersonasNoRegistradas', 'Administracion\UsuarioController@buscarPersonasNoRegistradas');


/*PERSONAS*/
Route::resource('Persona', 'Administracion\PersonaController');
Route::get('BuscarPersonas', 'Administracion\PersonaController@buscarPersonas');
Route::delete('HabilitarPersona/{id}', 'Administracion\PersonaController@habilitarPersona');

/*CATEGORIA CLIENTES */
Route::get('categoria_clientes', 'CategoriaClienteController@index')->name('categoria_clientes.index');
Route::get('categoria_clientes/create', 'CategoriaClienteController@create')->name('categoria_clientes.create');
Route::post('categoria_clientes/store', 'CategoriaClienteController@store')->name('categoria_clientes.store');
Route::get('categoria_clientes/edit/{categoria_cliente}', 'CategoriaClienteController@edit')->name('categoria_clientes.edit');
Route::put('categoria_clientes/update/{categoria_cliente}', 'CategoriaClienteController@update')->name('categoria_clientes.update');
Route::delete('categoria_clientes/destroy/{categoria_cliente}', 'CategoriaClienteController@destroy')->name('categoria_clientes.destroy');

Route::get('clientes_preferenciales', 'CategoriaClienteController@clientes_preferenciales')->name('clientes_preferenciales');
Route::get('obtiene_preferenciales', 'CategoriaClienteController@obtiene_preferenciales')->name('obtiene_preferenciales');

/*CONTRATOS*/
Route::resource('Contrato', 'Contrato\ContratoController');
Route::get('BuscarClientes', 'Contrato\ContratoController@buscarClientes');
Route::get('BuscarContratos', 'Contrato\ContratoController@buscarContratos');
Route::get('BuscarContratosDetalle', 'Contrato\ContratoController@buscarContratosDetalle');
Route::get('ImprimirReporteContrato/{id}', 'Contrato\ContratoController@imprimirReporteContrato');
Route::get('ImprimirDesembolso/{id}', 'Contrato\ContratoController@imprimirDesembolso');
Route::get('ImprimirComprobante/{id}', 'Contrato\ContratoController@ImprimirComprobante');
Route::get('ImprimirComprobante2/{id}', 'Contrato\ContratoController@ImprimirComprobante2');
Route::get('ImprimirBoleta/{id}', 'Contrato\ContratoController@ImprimirBoleta');
Route::get('imprimirContratoCambioMoneda/{id}', 'Contrato\ContratoController@imprimirContratoCambioMoneda');
Route::get('ReImprimirReporteContrato/{id}', 'Contrato\ContratoController@reImprimirReporteContrato');
Route::get('ObtieneCategoria', 'Contrato\ContratoController@ObtieneCategoria');
Route::get('corrige_codigos', 'Contrato\ContratoController@corrige_codigos');


Route::get('contratos_cancelados', 'Contrato\ContratoController@contratos_cancelados')->name('contrato.contratos_cancelados');

Route::get('contratos_cancelados/contratos_cancelados_pdf', 'Contrato\ContratoController@contratos_cancelados_pdf')->name('contrato.contratos_cancelados_pdf');

Route::get('contratos_cancelados/contratos_cancelados_excel', 'Contrato\ContratoController@contratos_cancelados_excel')->name('contrato.contratos_cancelados_excel');

Route::get('Contrato/EnviaRenovacion/{contrato}', 'Contrato\ContratoController@EnviaRenovacion')->name('contratos.EnviaRenovacion');

Route::get('contratos_vigentes', 'Contrato\ContratoController@contratos_vigentes')->name('contrato.contratos_vigentes');
Route::get('contratos_vigentes/contratos_vigentes_pdf', 'Contrato\ContratoController@contratos_vigentes_pdf')->name('contrato.contratos_vigentes_pdf');
Route::get('contratos_vigentes/contratos_vigentes_excel', 'Contrato\ContratoController@contratos_vigentes_excel')->name('contrato.contratos_vigentes_excel');

Route::get('resumen_prestamos', 'Contrato\ContratoController@resumen_prestamos')->name('contrato.resumen_prestamos');
Route::get('resumen_prestamos/resumen_prestamos_pdf', 'Contrato\ContratoController@resumen_prestamos_pdf')->name('contrato.resumen_prestamos_pdf');
Route::get('resumen_prestamos/resumen_prestamos_excel', 'Contrato\ContratoController@resumen_prestamos_excel')->name('contrato.resumen_prestamos_excel');

Route::get('resumen_ingresos', 'Contrato\ContratoController@resumen_ingresos')->name('contrato.resumen_ingresos');
Route::get('resumen_ingresos/resumen_ingresos_pdf', 'Contrato\ContratoController@resumen_ingresos_pdf')->name('contrato.resumen_ingresos_pdf');
Route::get('resumen_ingresos/resumen_ingresos_excel', 'Contrato\ContratoController@resumen_ingresos_excel')->name('contrato.resumen_ingresos_excel');

/* MÓDULO DE CAMBIO DE DÓLARES */
Route::get('cambio_dolares', 'CambioDolarController@index')->name('cambios.index');
Route::post('cambio_dolares/store', 'CambioDolarController@store')->name('cambios.store');
Route::get('cambio_dolares/reporte', 'CambioDolarController@reporte')->name('cambios.reporte');
Route::get('cambio_dolares/reporte_pdf', 'CambioDolarController@reporte_pdf')->name('cambios.reporte_pdf');
Route::get('cambio_dolares/cambio_pdf/{cambio}', 'CambioDolarController@cambio_pdf')->name('cambios.cambio_pdf');

/*SOLICITUDES PRENDAS*/
Route::get('solicitud_retiros', 'SolicitudRetiroController@index')->name('solicitud_retiros.index');

Route::post('solicitud_retiros/store', 'SolicitudRetiroController@store')->name('solicitud_retiros.
store');

Route::get('solicitud_retiros/reporte_pdf', 'SolicitudRetiroController@reporte_pdf')->name('solicitud_retiros.reporte_pdf');

Route::get('solicitud_retiros/reporte_excel', 'SolicitudRetiroController@reporte_excel')->name('solicitud_retiros.reporte_excel');

Route::get('retiros_pendientes/reporte', 'SolicitudRetiroController@retiros_pendientes')->name('retiros_pendientes.reporte');
Route::get('retiros_pendientes/reporte/pdf', 'SolicitudRetiroController@retiros_pendientes_pdf')->name('retiros_pendientes_pdf.retiros_pendientes_pdf');
Route::get('retiros_pendientes/reporte/excel', 'SolicitudRetiroController@retiros_pendientes_excel')->name('retiros_pendientes_excel.retiros_pendientes_excel');

/*PRECIO ORO*/
Route::resource('PrecioOro', 'Parametro\PrecioOroController');
Route::get('ObtnerPrecioOro', 'Parametro\PrecioOroController@obtnerPrecioOro');

/* VALOR ORO*/
Route::GET('ValorOro', 'ValorOroController@valor_oro')->name('valorOro.valor_oro');

Route::PUT('ValorOro/update', 'ValorOroController@update')->name('valorOro.update');

/*USUARIO SUCURSAL*/
Route::resource('UsuarioSucursal', 'Administracion\UsuarioSucursalController');
Route::get('ListadoSucursalesNoAsignados/{id}', 'Administracion\UsuarioSucursalController@listadoSucursalesNoAsignados');
Route::delete('HabilitarUsuarioSucursal/{id}', 'Administracion\UsuarioSucursalController@habilitarUsuarioSucursal');

/*PAGOS*/
Route::resource('Pagos', 'Pagos\PagosController');
Route::get('BuscarPagosDetalle', 'Pagos\PagosController@buscarPagosDetalle');
Route::get('BuscarContratosPagos', 'Pagos\PagosController@buscarContratosPagos');
Route::get('BuscarPagosDetalleUltimo', 'Pagos\PagosController@buscarPagosDetalleUltimo');
Route::post('PagoContratoTotal', 'Pagos\PagosController@pagoContratoTotal');
Route::post('PagoContratoInteres', 'Pagos\PagosController@pagoContratoInteres');
Route::post('PagoContratoAmortizacion', 'Pagos\PagosController@pagoContratoAmortizacion');
Route::post('PagoContratoAmortizacionInteres', 'Pagos\PagosController@pagoContratoAmortizacionInteres');
Route::get('ImprimirReporteInteres/{id}', 'Pagos\PagosController@imprimirReporteInteres');
Route::get('ReImprimirReporteInteres/{id}', 'Pagos\PagosController@reImprimirReporteInteres');
Route::get('ImprimirReportePagoTotal/{id}', 'Pagos\PagosController@imprimirReportePagoTotal');
Route::get('ReImprimirReportePagoTotal/{id}', 'Pagos\PagosController@reImprimirReportePagoTotal');
Route::get('imprimirFactura/{id}', 'Pagos\PagosController@imprimirFactura');
Route::get('imprimirCambioPago/{id}', 'Pagos\PagosController@imprimirCambioPago');

Route::get('ImprimirReporteAmortizacion/{id}', 'Pagos\PagosController@imprimirReporteAmortizacion');
Route::get('ReImprimirReporteAmortizacion/{id}', 'Pagos\PagosController@reImprimirReporteAmortizacion');
Route::get('ImprimirReporteAmortizacionInteres/{id}', 'Pagos\PagosController@imprimirReporteAmortizacionInteres');
Route::get('ImprimirReporteContratoEntregado/{id}', 'Pagos\PagosController@imprimirReporteContratoEntregado');
Route::post('PagoRemate', 'Pagos\PagosController@pagoRemate');
Route::get('ImprimirReporteRemate/{id}', 'Pagos\PagosController@imprimirReporteRemate');
Route::get('ReImprimirReporteRemate/{id}', 'Pagos\PagosController@reImprimirReporteRemate');
Route::get('BuscarContratosCodigo', 'Pagos\PagosController@buscarContratosCodigo');

Route::get('Pagos/lista/Mora', 'Pagos\PagosController@listaMora')->name('pagos.listaMora');
Route::get('Pagos/lista/listadoMoras', 'Pagos\PagosController@listadoMoras')->name('pagos.listadoMoras');
Route::get('Pagos/lista/listadoMorasExcel', 'Pagos\PagosController@listadoMorasExcel')->name('pagos.listadoMorasExcel');


/*INICIO FIN CAJA*/
Route::get('lista_cierres', 'InicioFinCaja\InicioFinCajaController@lista_cierres')->name("InicioFinCaja.lista_cierres");
Route::resource('InicioFinCaja', 'InicioFinCaja\InicioFinCajaController');
Route::resource('DatoInicioFinCaja', 'InicioFinCaja\DatoInicioFinCajaController');
Route::get('ImprimirInicioFinCaja', 'InicioFinCaja\InicioFinCajaController@imprimirInicioFinCaja');

Route::get('saldos_caja', 'InicioFinCaja\InicioFinCajaController@saldos_caja');
Route::get('saldos_caja/get_saldos_caja', 'InicioFinCaja\InicioFinCajaController@get_saldos_caja')->name('cajas.get_saldos_caja');

Route::get('rectificarPagosAmortizacion', 'InicioFinCaja\InicioFinCajaController@rectificarPagosAmortizacion');

Route::get('rectificarPagosAmortizacion2', 'Pagos\PagosController@rectificarPagosAmortizacion2');

Route::get('rectificacionCierres', 'Pagos\PagosController@rectificacionCierres');

/*INGRESO CAJA*/
Route::resource('IngresoCaja', 'IngresoCaja\IngresoCajaController');
Route::get('ImprimirReporteIngreso/{idContaDiario}/{idInicioCaja}', 'IngresoCaja\IngresoCajaController@imprimirReporteIngreso');
Route::get('ReImprimirReporteIngreso/{id}', 'IngresoCaja\IngresoCajaController@reImprimirReporteIngreso');


/*EGRESO CAJA*/
Route::resource('EgresoCaja', 'EgresoCaja\EgresoCajaController');
Route::get('ImprimirReporteEgreso/{idContaDiario}/{idInicioCaja}', 'EgresoCaja\EgresoCajaController@imprimirReporteEgreso');
Route::get('ImprimirReporteEgresoReimpresion/{id}', 'EgresoCaja\EgresoCajaController@imprimirReporteEgresoReimpresion');

/*CONTABILIDAD*/
Route::resource('ContaDiario', 'Contabilidad\ContaDiarioController');
Route::get('ExportarContaDiario/{fechaI}/{fechaF}', 'Contabilidad\ContaDiarioController@exportarContaDiario');
Route::get('BuscarContaDiario', 'Contabilidad\ContaDiarioController@buscarContaDiario');

/*REGISTROS CONTABLES*/
Route::resource('RegistroContable', 'Contabilidad\RegistroContableController');
Route::get('CargarCuentas', 'Contabilidad\RegistroContableController@cargarCuentas');

/*CONTA INICIO FIN CAJA*/
Route::resource('ContaInicioFinCaja', 'Contabilidad\ContaInicioFinCajaController');
Route::get('BuscarContaInicioCaja', 'Contabilidad\ContaInicioFinCajaController@buscarContaInicioCaja');
Route::get('ExportarInicioFinCaja/{fechaI}/{fechaF}', 'Contabilidad\ContaInicioFinCajaController@exportarInicioFinCaja');

/* REIMPRESIONES */
Route::resource('Reimpresiones', 'Reimpresiones\ReimpresionesVacunalController');
Route::get('BuscarReimpresiones', 'Reimpresiones\ReimpresionesVacunalController@buscarReimpresiones');

/* REIMPRESIONES */
Route::resource('Remates', 'Remates\RematesController');
Route::get('BuscarRemates', 'Remates\RematesController@buscarRemates');
Route::get('ImprimirRemate/{filtro}/{fecha_ini}/{fecha_fin}', 'Remates\RematesController@imprimirRemate');

/* REPORTES DE CIERRE DE CAJA */
Route::resource('RepInicioFinCaja', 'RepInicioFinCaja\ReInicioFinCajaController');
Route::get('BuscarReInicioFinCaja', 'RepInicioFinCaja\ReInicioFinCajaController@buscarReInicioFinCaja');
Route::get('ImprimirReInicioFinCaja/{fechaC}/{id_sucursal}/{caja}', 'RepInicioFinCaja\ReInicioFinCajaController@imprimirReInicioFinCaja');


/* SUBIR COMPROBANTES */
Route::resource('Comprobante', 'Contabilidad\ComprobanteController');
Route::post('StoreTemp', 'Contabilidad\ComprobanteController@storeTemp');


/* BRINKS */
Route::resource('Brinks', 'Reportes\BrinksController');
Route::get('BuscarContratosBrinks', 'Reportes\BrinksController@buscarContratosBrinks');
Route::get('ImprmirContratosBrinks/{fechaI}/{fechaF}/{sucursal}', 'Reportes\BrinksController@imprmirContratosBrinks');
Route::get('ExportarContratosBrinks/{fechaI}/{fechaF}/{sucursal}', 'Reportes\BrinksController@exportarContratosBrinks');

/* MONEDAS */
Route::get('Monedas', 'MonedaController@index')->name('monedas.index');
Route::put('Monedas/update', 'MonedaController@update')->name('monedas.update');
Route::put('Monedas/actualizaCambio', 'MonedaController@actualizaCambio')->name('monedas.actualizaCambio');
Route::get('Monedas/valor', 'MonedaController@valor')->name('monedas.valor');

/* COMPRA VENTA DÓLAR */
Route::get('compra_ventas', 'CompraVentaDolarController@index')->name('compra_ventas.index');
Route::put('compra_ventas/update', 'CompraVentaDolarController@update')->name('compra_ventas.update');

/* REPORTES CONTABLES */
Route::resource('ReportesContables', 'Reportes\ReportesContablesController');
Route::get('ImprmirLibroDiario/{fechaI}/{fechaF}', 'Reportes\ReportesContablesController@imprmirLibroDiario');
Route::get('ImprmirLibroDiarioExcel/{fechaI}/{fechaF}', 'Reportes\ReportesContablesController@imprmirLibroDiarioExcel');
Route::get('ImprmirSumaySaldos/{fechaF}', 'Reportes\ReportesContablesController@imprmirSumaySaldos');
Route::get('ImprmirLibroMayor/{fechaF}', 'Reportes\ReportesContablesController@imprmirLibroMayor');
Route::get('ImprmirLibroMayorExcel/{fechaF}', 'Reportes\ReportesContablesController@imprmirLibroMayorExcel');
Route::get('ImprimirEstadoResultados/{fechaI}/{fechaF}', 'Reportes\ReportesContablesController@imprimirEstadoResultados');
Route::get('ImprimirBalanceGeneral/{fechaF}', 'Reportes\ReportesContablesController@imprimirBalanceGeneral');

/* REPORTES COMPROBANTES */
Route::resource('RepComprobantes', 'Reportes\ComprobantesController');
Route::get('ImprmirComprobante/{fechaI}/{id_sucursal}/{caja}', 'Reportes\ComprobantesController@imprmirComprobante');

/* REPORTES HISTORAIL DE PAGOS */
Route::resource('HistorialPagos', 'Reportes\HistorialPagosController');
Route::get('ExportarExcelHistorialPagos/{fechaI}/{fechaF}', 'Reportes\HistorialPagosController@exportarExcelHistorialPagos');

/* PERFIL */
Route::resource('Perfil', 'Perfil\PerfilController');

/*CERRAR SESIÓN*/
Route::get('/Salir', [
  'as' => 'salirSesion',
  'uses' => 'Administracion\UsuarioController@salirSesion'
]);
