-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-11-2023 a las 20:37:27
-- Versión del servidor: 8.0.30
-- Versión de PHP: 7.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sdsafrwl_bdprendasol_oficial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int NOT NULL,
  `cod_caja` varchar(22) DEFAULT NULL,
  `No_caja` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambio_dolars`
--

CREATE TABLE `cambio_dolars` (
  `id` bigint UNSIGNED NOT NULL,
  `sucursal_id` int NOT NULL,
  `fecha` date NOT NULL,
  `cliente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` int NOT NULL,
  `monto` decimal(24,2) NOT NULL,
  `equivalencia` decimal(24,2) NOT NULL,
  `modo_cambio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `compra_venta_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambio_monedas`
--

CREATE TABLE `cambio_monedas` (
  `id` bigint UNSIGNED NOT NULL,
  `valor_sus` decimal(24,2) NOT NULL,
  `valor_bs` decimal(24,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catalogos_generico`
--

CREATE TABLE `catalogos_generico` (
  `id` int NOT NULL,
  `entidadsalud_id` smallint DEFAULT NULL,
  `tabla_id` int DEFAULT NULL,
  `catalogoid` int DEFAULT NULL,
  `catalogodescripcion` varchar(200) DEFAULT NULL,
  `catalogovigente` smallint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_clientes`
--

CREATE TABLE `categoria_clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(155) NOT NULL,
  `numero_contratos` int NOT NULL,
  `porcentaje` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` int NOT NULL,
  `persona_id` int DEFAULT NULL,
  `codigo` varchar(44) DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_copa`
--

CREATE TABLE `clientes_copa` (
  `id_cliente` int UNSIGNED NOT NULL,
  `cod_cli` varchar(44) DEFAULT NULL,
  `CI` varchar(32) DEFAULT NULL,
  `CI_exp` varchar(11) DEFAULT NULL,
  `CI_emi` date DEFAULT NULL,
  `Tipo_doc` varchar(22) DEFAULT NULL,
  `Ap` varchar(40) DEFAULT NULL,
  `Am` varchar(50) DEFAULT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Fec_nac` date DEFAULT NULL,
  `Lug_nac` varchar(4) DEFAULT NULL,
  `sex` varchar(2) DEFAULT NULL,
  `nacio` varchar(44) DEFAULT NULL,
  `Num_dep` int DEFAULT NULL,
  `Est_civ` varchar(11) DEFAULT NULL,
  `prof` varchar(111) DEFAULT NULL,
  `empleado_en` varchar(128) DEFAULT NULL,
  `Tipo_de_emp` varchar(33) DEFAULT NULL,
  `cargo` varchar(128) DEFAULT NULL,
  `empleado_desde` date DEFAULT NULL,
  `foto` varchar(510) DEFAULT NULL,
  `Zona` varchar(111) DEFAULT NULL,
  `Direc` varchar(256) DEFAULT NULL,
  `No_dir` varchar(16) DEFAULT NULL,
  `ref` varchar(128) DEFAULT NULL,
  `Tel` varchar(64) DEFAULT NULL,
  `urb_rur` varchar(111) DEFAULT NULL,
  `ciudad` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_categorias`
--

CREATE TABLE `cliente_categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos`
--

CREATE TABLE `codigos` (
  `codigo` varchar(255) DEFAULT NULL,
  `ci` varchar(255) DEFAULT NULL,
  `idcliente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_1`
--

CREATE TABLE `codigos_1` (
  `codigo` varchar(255) DEFAULT NULL,
  `ci` varchar(255) DEFAULT NULL,
  `idcliente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_2`
--

CREATE TABLE `codigos_2` (
  `codigo` varchar(255) DEFAULT NULL,
  `ci` varchar(255) DEFAULT NULL,
  `idcliente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_todo`
--

CREATE TABLE `codigos_todo` (
  `codigo` varchar(255) DEFAULT NULL,
  `ci` varchar(255) DEFAULT NULL,
  `idcliente` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_venta_dolars`
--

CREATE TABLE `compra_venta_dolars` (
  `id` int NOT NULL,
  `venta_sus` decimal(24,2) NOT NULL,
  `venta_bs` decimal(24,2) NOT NULL,
  `compra_sus` decimal(24,2) NOT NULL,
  `compra_bs` decimal(24,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conta_deno`
--

CREATE TABLE `conta_deno` (
  `id` int UNSIGNED NOT NULL,
  `tipo` varchar(44) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `cod_deno` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conta_denominacion`
--

CREATE TABLE `conta_denominacion` (
  `id` int NOT NULL,
  `cod_deno` varchar(111) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `nocuenta` int DEFAULT NULL,
  `numerocod` int DEFAULT NULL,
  `grupo` varchar(11) DEFAULT NULL,
  `subgrupo` varchar(11) DEFAULT NULL,
  `subgrupo2` int DEFAULT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conta_diario`
--

CREATE TABLE `conta_diario` (
  `id` int UNSIGNED NOT NULL,
  `contrato_id` int DEFAULT NULL,
  `pagos_id` int DEFAULT NULL,
  `sucursal_id` int DEFAULT NULL,
  `periodo` varchar(111) DEFAULT NULL,
  `fecha_a` date DEFAULT NULL,
  `fecha_b` datetime DEFAULT NULL,
  `glosa` varchar(255) DEFAULT NULL,
  `a` int DEFAULT NULL,
  `b` int DEFAULT NULL,
  `c` int DEFAULT NULL,
  `cod_deno` int DEFAULT NULL,
  `cuenta` varchar(255) DEFAULT NULL,
  `debe` double(20,2) DEFAULT NULL,
  `haber` double(20,2) DEFAULT NULL,
  `contrato` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `ci` int DEFAULT NULL,
  `num_comprobante` int NOT NULL,
  `nom` varchar(111) DEFAULT NULL,
  `tcom` varchar(111) DEFAULT NULL,
  `ref` varchar(111) DEFAULT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `amortizacion` double DEFAULT NULL,
  `diasM` int DEFAULT NULL,
  `intM` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `anterior` double DEFAULT NULL,
  `totalcapital` double DEFAULT NULL,
  `aprobado` int DEFAULT NULL,
  `gestion` int DEFAULT NULL,
  `correlativo` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conta_diario_copa`
--

CREATE TABLE `conta_diario_copa` (
  `id_diario` int UNSIGNED NOT NULL,
  `fecha_a` date DEFAULT NULL,
  `fecha_b` datetime DEFAULT NULL,
  `glosa` varchar(255) DEFAULT NULL,
  `a` int DEFAULT NULL,
  `b` int DEFAULT NULL,
  `c` int DEFAULT NULL,
  `cod_deno` int DEFAULT NULL,
  `cuenta` varchar(255) DEFAULT NULL,
  `debe` double(20,2) DEFAULT NULL,
  `haber` double(20,2) DEFAULT NULL,
  `contrato` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `ci` int DEFAULT NULL,
  `nom` varchar(111) DEFAULT NULL,
  `tcom` varchar(111) DEFAULT NULL,
  `ref` varchar(111) DEFAULT NULL,
  `periodo` varchar(111) DEFAULT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `amortizacion` double DEFAULT NULL,
  `diasM` int DEFAULT NULL,
  `intM` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `anterior` double DEFAULT NULL,
  `totalcapital` double DEFAULT NULL,
  `aprobado` int DEFAULT NULL,
  `numdiario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conta_diario_temp`
--

CREATE TABLE `conta_diario_temp` (
  `id` int UNSIGNED NOT NULL,
  `contrato_id` int DEFAULT NULL,
  `pagos_id` int DEFAULT NULL,
  `sucursal_id` int DEFAULT NULL,
  `periodo` varchar(111) DEFAULT NULL,
  `fecha_a` date DEFAULT NULL,
  `fecha_b` datetime DEFAULT NULL,
  `glosa` varchar(255) DEFAULT NULL,
  `a` int DEFAULT NULL,
  `b` int DEFAULT NULL,
  `c` int DEFAULT NULL,
  `cod_deno` int DEFAULT NULL,
  `cuenta` varchar(255) DEFAULT NULL,
  `debe` double(20,2) DEFAULT NULL,
  `haber` double(20,2) DEFAULT NULL,
  `contrato` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `ci` int DEFAULT NULL,
  `num_comprobante` int NOT NULL,
  `nom` varchar(111) DEFAULT NULL,
  `tcom` varchar(111) DEFAULT NULL,
  `ref` varchar(111) DEFAULT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `amortizacion` double DEFAULT NULL,
  `diasM` int DEFAULT NULL,
  `intM` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `anterior` double DEFAULT NULL,
  `totalcapital` double DEFAULT NULL,
  `aprobado` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contrato`
--

CREATE TABLE `contrato` (
  `id` int UNSIGNED NOT NULL,
  `cliente_id` int DEFAULT NULL,
  `sucursal_id` int DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `peso_total` double DEFAULT NULL,
  `fecha_contrato` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `plazo` int DEFAULT NULL,
  `cuota_mora` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `p_interes` double NOT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `gestion` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `estado_pago` varchar(255) DEFAULT NULL,
  `estado_pago_2` varchar(255) DEFAULT NULL,
  `estado_entrega` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL,
  `totalTasacion` double DEFAULT NULL,
  `codigo_num` bigint UNSIGNED DEFAULT NULL,
  `moneda_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_contrato`
--

CREATE TABLE `detalle_contrato` (
  `id` int UNSIGNED NOT NULL,
  `contrato_id` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `descripcion` text,
  `peso` double DEFAULT NULL,
  `dies` double DEFAULT NULL,
  `catorce` double DEFAULT NULL,
  `dieciocho` double DEFAULT NULL,
  `veinticuatro` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int UNSIGNED NOT NULL,
  `persona_id` int DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `observacion` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado_id` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantia_uno`
--

CREATE TABLE `garantia_uno` (
  `id_garantia` int UNSIGNED NOT NULL,
  `cod_garantia` varchar(255) DEFAULT NULL,
  `cant` double NOT NULL,
  `descrip` varchar(255) NOT NULL,
  `peso` double NOT NULL,
  `dies` double NOT NULL,
  `cato` double NOT NULL,
  `diesio` double NOT NULL,
  `veintecuatro` double NOT NULL,
  `cero` double DEFAULT NULL,
  `cero_uno` double DEFAULT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `cod_cliente` varchar(255) NOT NULL,
  `CI` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `No_credito` varchar(255) DEFAULT NULL,
  `fec_dif` datetime DEFAULT NULL,
  `fec_fin` datetime DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `plazo` int DEFAULT NULL,
  `ciclo` int DEFAULT NULL,
  `pagos` int DEFAULT NULL,
  `dias_atraso` int DEFAULT NULL,
  `dias_atraso_total` int DEFAULT NULL,
  `cutoa_mora` double DEFAULT NULL,
  `capital` double NOT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantia_uno_copa`
--

CREATE TABLE `garantia_uno_copa` (
  `id_garantia` int UNSIGNED NOT NULL,
  `cod_garantia` varchar(255) DEFAULT NULL,
  `cant` double NOT NULL,
  `descrip` varchar(255) NOT NULL,
  `peso` double NOT NULL,
  `dies` double NOT NULL,
  `cato` double NOT NULL,
  `diesio` double NOT NULL,
  `veintecuatro` double NOT NULL,
  `cero` double DEFAULT NULL,
  `cero_uno` double DEFAULT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `cod_cliente` varchar(255) NOT NULL,
  `CI` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `No_credito` varchar(255) DEFAULT NULL,
  `fec_dif` datetime DEFAULT NULL,
  `fec_fin` datetime DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `plazo` int DEFAULT NULL,
  `ciclo` int DEFAULT NULL,
  `pagos` int DEFAULT NULL,
  `dias_atraso` int DEFAULT NULL,
  `dias_atraso_total` int DEFAULT NULL,
  `cutoa_mora` double DEFAULT NULL,
  `capital` double NOT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inicio_fin_caja`
--

CREATE TABLE `inicio_fin_caja` (
  `id` int UNSIGNED NOT NULL,
  `sucursal_id` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `inicio_caja_bs` double DEFAULT NULL,
  `fin_caja_bs` double DEFAULT NULL,
  `inicio_caja_s` double DEFAULT NULL,
  `fin_caja_s` double DEFAULT NULL,
  `ingreso_bs` double DEFAULT NULL,
  `ingreso_s` double DEFAULT NULL,
  `egreso_bs` double DEFAULT NULL,
  `egreso_s` double DEFAULT NULL,
  `tipo_de_movimiento` varchar(255) NOT NULL,
  `usuarioIniciado` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL,
  `moneda_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inicio_fin_caja_detalle`
--

CREATE TABLE `inicio_fin_caja_detalle` (
  `id` int UNSIGNED NOT NULL,
  `inicio_fin_caja_id` int DEFAULT NULL,
  `contrato_id` int DEFAULT NULL,
  `pago_id` int DEFAULT NULL,
  `sucursal_id` int DEFAULT NULL,
  `persona_id` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `cod_caja_n` varchar(11) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `inicio_caja_bs` double DEFAULT NULL,
  `fin_caja_bs` double DEFAULT NULL,
  `inicio_caja_s` double DEFAULT NULL,
  `fin_caja_s` double DEFAULT NULL,
  `ingreso_bs` double DEFAULT NULL,
  `ingreso_s` double DEFAULT NULL,
  `egreso_bs` double DEFAULT NULL,
  `egreso_s` double DEFAULT NULL,
  `tipo_de_movimiento` varchar(255) NOT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `correlativo` int DEFAULT NULL,
  `gestion` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL,
  `moneda_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_seguimiento`
--

CREATE TABLE `log_seguimiento` (
  `id` int UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `metodo` longtext NOT NULL,
  `accion` longtext NOT NULL,
  `detalle` longtext NOT NULL,
  `modulo` longtext NOT NULL,
  `consulta` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id` int NOT NULL,
  `modulo` longtext,
  `imagen` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `monedas`
--

CREATE TABLE `monedas` (
  `id` bigint UNSIGNED NOT NULL,
  `moneda` varchar(155) NOT NULL,
  `desc_corta` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `id` int NOT NULL,
  `modulo_id` int NOT NULL,
  `opcion` longtext,
  `url` longtext,
  `orden` int DEFAULT NULL,
  `imagen` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int NOT NULL,
  `contrato_id` int DEFAULT NULL,
  `sucursal_id` int DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `fecha_inio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `dias_atraso` int DEFAULT NULL,
  `dias_atraso_total` double DEFAULT NULL,
  `cuota_mora` double DEFAULT NULL,
  `capital` double DEFAULT NULL,
  `interes` double DEFAULT NULL,
  `comision` double DEFAULT NULL,
  `total_ai` double(8,2) DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL,
  `moneda_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id` int NOT NULL,
  `rol_id` int NOT NULL,
  `opcion_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id` int UNSIGNED NOT NULL,
  `tipodocumento_genericoid` int DEFAULT NULL,
  `nrodocumento` varchar(15) DEFAULT NULL,
  `expedido_id` int DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `primerapellido` varchar(50) DEFAULT NULL,
  `segundoapellido` varchar(50) DEFAULT NULL,
  `fechanacimiento` date DEFAULT NULL,
  `sexo_genericoid` int DEFAULT NULL,
  `estadocivil_genericoid` int DEFAULT NULL,
  `telefonodomicilio` varchar(15) DEFAULT NULL,
  `correoelectronico` varchar(100) DEFAULT NULL,
  `nacionalidad_genericoid` int DEFAULT NULL,
  `direcciontrabajo` varchar(100) DEFAULT NULL,
  `telefonotrabajo` varchar(15) DEFAULT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `celular` varchar(120) DEFAULT NULL,
  `celular2` varchar(120) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `zona` varchar(255) DEFAULT NULL,
  `numero_direccion` varchar(15) DEFAULT NULL,
  `referencia_domicilio` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona1`
--

CREATE TABLE `persona1` (
  `id_cliente` varchar(255) DEFAULT NULL,
  `cod_cli` varchar(255) DEFAULT NULL,
  `CI` varchar(255) DEFAULT NULL,
  `CI_exp` varchar(255) DEFAULT NULL,
  `CI_emi` varchar(255) DEFAULT NULL,
  `Tipo_doc` varchar(255) DEFAULT NULL,
  `Ap` varchar(255) DEFAULT NULL,
  `Am` varchar(255) DEFAULT NULL,
  `Nom` varchar(255) DEFAULT NULL,
  `Fec_nac` varchar(255) DEFAULT NULL,
  `Lug_nac` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `nacio` varchar(255) DEFAULT NULL,
  `Num_dep` varchar(255) DEFAULT NULL,
  `Est_civ` varchar(255) DEFAULT NULL,
  `prof` varchar(255) DEFAULT NULL,
  `empleado_en` varchar(255) DEFAULT NULL,
  `Tipo_de_emp` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `empleado_desde` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `Zona` varchar(255) DEFAULT NULL,
  `Direc` varchar(255) DEFAULT NULL,
  `No_dir` varchar(255) DEFAULT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `Tel` varchar(255) DEFAULT NULL,
  `urb_rur` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plazo_pagos`
--

CREATE TABLE `plazo_pagos` (
  `id` bigint UNSIGNED NOT NULL,
  `contrato_id` bigint UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_proximo_pago` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precio_oro`
--

CREATE TABLE `precio_oro` (
  `id` int UNSIGNED NOT NULL,
  `dies` int NOT NULL,
  `catorce` int NOT NULL,
  `diesiocho` int NOT NULL,
  `veinticuatro` int NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reti_vend`
--

CREATE TABLE `reti_vend` (
  `id_r` int UNSIGNED NOT NULL,
  `cod_r` varchar(111) DEFAULT NULL,
  `nombre` varchar(111) NOT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `cant` double DEFAULT NULL,
  `No_credito` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `reti_vend1` varchar(255) DEFAULT NULL,
  `reti_vend2` varchar(255) DEFAULT NULL,
  `reti_vend3` varchar(255) DEFAULT NULL,
  `reti_vend4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reti_vend_copa`
--

CREATE TABLE `reti_vend_copa` (
  `id_r` int UNSIGNED NOT NULL,
  `cod_r` varchar(111) DEFAULT NULL,
  `nombre` varchar(111) NOT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `cant` double DEFAULT NULL,
  `No_credito` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `reti_vend1` varchar(255) DEFAULT NULL,
  `reti_vend2` varchar(255) DEFAULT NULL,
  `reti_vend3` varchar(255) DEFAULT NULL,
  `reti_vend4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int NOT NULL,
  `rol` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usuario_id` int NOT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_retiros`
--

CREATE TABLE `solicitud_retiros` (
  `id` bigint UNSIGNED NOT NULL,
  `contrato_id` bigint UNSIGNED NOT NULL,
  `sucursal_id` bigint UNSIGNED NOT NULL,
  `estado` varchar(155) NOT NULL,
  `observaciones` varchar(255) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id` int NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `nuevo_codigo` varchar(255) DEFAULT NULL,
  `ciudad` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `zona` varchar(255) DEFAULT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `genera_codigo` bigint DEFAULT NULL,
  `codigo_inicial` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal_usuario`
--

CREATE TABLE `sucursal_usuario` (
  `id` int UNSIGNED NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_sucursal` int DEFAULT NULL,
  `caja` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `totales`
--

CREATE TABLE `totales` (
  `id_totales` int UNSIGNED NOT NULL,
  `cod_totales` varchar(255) DEFAULT NULL,
  `CI` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `fec_fin` datetime DEFAULT NULL,
  `cant` double DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `dies` double DEFAULT NULL,
  `cato` double DEFAULT NULL,
  `diesio` double DEFAULT NULL,
  `veintecuatro` double DEFAULT NULL,
  `cero` double DEFAULT NULL,
  `cero_uno` double DEFAULT NULL,
  `ciclo` int DEFAULT NULL,
  `plazo` int DEFAULT NULL,
  `pagos` double DEFAULT NULL,
  `dias_atraso` int DEFAULT NULL,
  `dias_atraso_total` double DEFAULT NULL,
  `cuota_mora` double DEFAULT NULL,
  `capital` double NOT NULL,
  `interes` double NOT NULL,
  `comision` double DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `No_credito` int NOT NULL,
  `caja` int DEFAULT NULL,
  `intere` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `totales_copa`
--

CREATE TABLE `totales_copa` (
  `id_totales` int UNSIGNED NOT NULL,
  `cod_totales` varchar(255) DEFAULT NULL,
  `CI` int NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `fec_hor` datetime DEFAULT NULL,
  `fec_fin` datetime DEFAULT NULL,
  `cant` double DEFAULT NULL,
  `descrip` varchar(255) DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `dies` double DEFAULT NULL,
  `cato` double DEFAULT NULL,
  `diesio` double DEFAULT NULL,
  `veintecuatro` double DEFAULT NULL,
  `cero` double DEFAULT NULL,
  `cero_uno` double DEFAULT NULL,
  `ciclo` int DEFAULT NULL,
  `plazo` int DEFAULT NULL,
  `pagos` double DEFAULT NULL,
  `dias_atraso` int DEFAULT NULL,
  `dias_atraso_total` double DEFAULT NULL,
  `cuota_mora` double DEFAULT NULL,
  `capital` double NOT NULL,
  `interes` double NOT NULL,
  `comision` double DEFAULT NULL,
  `total_capital` double DEFAULT NULL,
  `No_credito` int NOT NULL,
  `caja` int DEFAULT NULL,
  `intere` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `total_reporte`
--

CREATE TABLE `total_reporte` (
  `id_garantia_total` int UNSIGNED NOT NULL,
  `cod_garantia_total` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `cod_credi` varchar(255) DEFAULT NULL,
  `cod_men` varchar(255) DEFAULT NULL,
  `movimiento` varchar(255) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `saldo` double DEFAULT NULL,
  `No_caja` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int UNSIGNED NOT NULL,
  `persona_id` int NOT NULL,
  `usuario` longtext,
  `clave` longtext,
  `clave_texto` longtext NOT NULL,
  `login_usu` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `id` int UNSIGNED NOT NULL,
  `usuario_id` int NOT NULL,
  `rol_id` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `login_usu` int NOT NULL,
  `estado_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valor_oros`
--

CREATE TABLE `valor_oros` (
  `id` bigint UNSIGNED NOT NULL,
  `dies` int NOT NULL,
  `catorce` int NOT NULL,
  `diesiocho` int NOT NULL,
  `veinticuatro` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`) USING BTREE;

--
-- Indices de la tabla `cambio_dolars`
--
ALTER TABLE `cambio_dolars`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cambio_monedas`
--
ALTER TABLE `cambio_monedas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `catalogos_generico`
--
ALTER TABLE `catalogos_generico`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `categoria_clientes`
--
ALTER TABLE `categoria_clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `clientes_ibfk_1` (`persona_id`) USING BTREE;

--
-- Indices de la tabla `clientes_copa`
--
ALTER TABLE `clientes_copa`
  ADD PRIMARY KEY (`id_cliente`) USING BTREE;

--
-- Indices de la tabla `cliente_categorias`
--
ALTER TABLE `cliente_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compra_venta_dolars`
--
ALTER TABLE `compra_venta_dolars`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `conta_deno`
--
ALTER TABLE `conta_deno`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `conta_denominacion`
--
ALTER TABLE `conta_denominacion`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `conta_diario`
--
ALTER TABLE `conta_diario`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `conta_diario_copa`
--
ALTER TABLE `conta_diario_copa`
  ADD PRIMARY KEY (`id_diario`) USING BTREE;

--
-- Indices de la tabla `conta_diario_temp`
--
ALTER TABLE `conta_diario_temp`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `detalle_contrato`
--
ALTER TABLE `detalle_contrato`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `garantia_uno`
--
ALTER TABLE `garantia_uno`
  ADD PRIMARY KEY (`id_garantia`) USING BTREE;

--
-- Indices de la tabla `garantia_uno_copa`
--
ALTER TABLE `garantia_uno_copa`
  ADD PRIMARY KEY (`id_garantia`) USING BTREE;

--
-- Indices de la tabla `inicio_fin_caja`
--
ALTER TABLE `inicio_fin_caja`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `inicio_fin_caja_detalle`
--
ALTER TABLE `inicio_fin_caja_detalle`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `log_seguimiento`
--
ALTER TABLE `log_seguimiento`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `monedas`
--
ALTER TABLE `monedas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `plazo_pagos`
--
ALTER TABLE `plazo_pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `precio_oro`
--
ALTER TABLE `precio_oro`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `reti_vend`
--
ALTER TABLE `reti_vend`
  ADD PRIMARY KEY (`id_r`) USING BTREE;

--
-- Indices de la tabla `reti_vend_copa`
--
ALTER TABLE `reti_vend_copa`
  ADD PRIMARY KEY (`id_r`) USING BTREE;

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `solicitud_retiros`
--
ALTER TABLE `solicitud_retiros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `sucursal_usuario`
--
ALTER TABLE `sucursal_usuario`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `totales`
--
ALTER TABLE `totales`
  ADD PRIMARY KEY (`id_totales`) USING BTREE;

--
-- Indices de la tabla `totales_copa`
--
ALTER TABLE `totales_copa`
  ADD PRIMARY KEY (`id_totales`) USING BTREE;

--
-- Indices de la tabla `total_reporte`
--
ALTER TABLE `total_reporte`
  ADD PRIMARY KEY (`id_garantia_total`) USING BTREE;

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `valor_oros`
--
ALTER TABLE `valor_oros`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cambio_dolars`
--
ALTER TABLE `cambio_dolars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cambio_monedas`
--
ALTER TABLE `cambio_monedas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria_clientes`
--
ALTER TABLE `categoria_clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes_copa`
--
ALTER TABLE `clientes_copa`
  MODIFY `id_cliente` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente_categorias`
--
ALTER TABLE `cliente_categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `compra_venta_dolars`
--
ALTER TABLE `compra_venta_dolars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conta_deno`
--
ALTER TABLE `conta_deno`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conta_diario`
--
ALTER TABLE `conta_diario`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conta_diario_copa`
--
ALTER TABLE `conta_diario_copa`
  MODIFY `id_diario` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conta_diario_temp`
--
ALTER TABLE `conta_diario_temp`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_contrato`
--
ALTER TABLE `detalle_contrato`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `garantia_uno`
--
ALTER TABLE `garantia_uno`
  MODIFY `id_garantia` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `garantia_uno_copa`
--
ALTER TABLE `garantia_uno_copa`
  MODIFY `id_garantia` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inicio_fin_caja`
--
ALTER TABLE `inicio_fin_caja`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inicio_fin_caja_detalle`
--
ALTER TABLE `inicio_fin_caja_detalle`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_seguimiento`
--
ALTER TABLE `log_seguimiento`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plazo_pagos`
--
ALTER TABLE `plazo_pagos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `precio_oro`
--
ALTER TABLE `precio_oro`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reti_vend`
--
ALTER TABLE `reti_vend`
  MODIFY `id_r` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reti_vend_copa`
--
ALTER TABLE `reti_vend_copa`
  MODIFY `id_r` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_retiros`
--
ALTER TABLE `solicitud_retiros`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sucursal_usuario`
--
ALTER TABLE `sucursal_usuario`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `totales`
--
ALTER TABLE `totales`
  MODIFY `id_totales` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `totales_copa`
--
ALTER TABLE `totales_copa`
  MODIFY `id_totales` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `total_reporte`
--
ALTER TABLE `total_reporte`
  MODIFY `id_garantia_total` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `valor_oros`
--
ALTER TABLE `valor_oros`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
