-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: ultracel_db
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taller_id` bigint unsigned NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `clientes_taller_id_foreign` (`taller_id`),
  CONSTRAINT `clientes_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,1,'a','a','1',NULL,'2026-03-17 01:24:20','2026-03-17 01:24:20','2026-03-17 01:24:20'),(2,1,'Jose','Perez','12345678',NULL,'2026-03-17 16:25:15','2026-03-17 16:25:15','2026-03-17 16:25:15'),(3,1,'Eduardete','JImenete','122345789',NULL,'2026-03-23 02:43:43','2026-03-23 02:43:43','2026-03-23 02:43:43');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos` (
  `id_equipo` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` bigint unsigned NOT NULL,
  `tipo_equipo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marca` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imei_o_serie` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clave_acceso` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_equipo`),
  KEY `equipos_id_cliente_foreign` (`id_cliente`),
  CONSTRAINT `equipos_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
INSERT INTO `equipos` VALUES (1,1,'Celular','Samsung','S21 ultra',NULL,NULL,'2026-03-17 01:24:20',NULL),(2,2,'Celular','Iphone','13','12345678',NULL,'2026-03-17 16:25:15',NULL),(3,3,'Celular','Samsung','S26 ULTRA','12356776323',NULL,'2026-03-23 02:43:43',NULL);
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id_producto` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taller_id` bigint unsigned NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_producto` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `tipo_producto` enum('Refacción','Venta Directa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Refacción',
  `marca_compatible` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modelo_compatible` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `ubicacion_almacen` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `inventario_taller_id_foreign` (`taller_id`),
  CONSTRAINT `inventario_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (1,1,'123AA','MICA','SI','Refacción','IPHONE','13',1,120.00,150.00,'1','2026-03-17 01:07:46','2026-03-17 01:07:46'),(2,1,'asdf|','c','yes','Refacción','c','csdf',12,123.00,1234.00,'1','2026-03-17 16:22:55','2026-03-17 16:22:55');
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_02_16_173301_create_saas_tables',1),(2,'2026_03_13_031746_add_ultracel_fields_to_users_table',1),(3,'2026_03_16_203047_create_ultracel_core_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planes`
--

DROP TABLE IF EXISTS `planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `planes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_mensual` decimal(10,2) NOT NULL,
  `max_usuarios` int NOT NULL DEFAULT '1',
  `max_sucursales` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planes`
--

LOCK TABLES `planes` WRITE;
/*!40000 ALTER TABLE `planes` DISABLE KEYS */;
INSERT INTO `planes` VALUES (1,'Plan Ilimitado',0.00,99,99,'2026-03-16 20:41:47','2026-03-16 20:41:47');
/*!40000 ALTER TABLE `planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reparacion_piezas`
--

DROP TABLE IF EXISTS `reparacion_piezas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reparacion_piezas` (
  `id_reparacion_pieza` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_reparacion` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad_usada` int NOT NULL DEFAULT '1',
  `precio_en_reparacion` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_reparacion_pieza`),
  KEY `reparacion_piezas_id_reparacion_foreign` (`id_reparacion`),
  KEY `reparacion_piezas_id_producto_foreign` (`id_producto`),
  CONSTRAINT `reparacion_piezas_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE CASCADE,
  CONSTRAINT `reparacion_piezas_id_reparacion_foreign` FOREIGN KEY (`id_reparacion`) REFERENCES `reparaciones` (`id_reparacion`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reparacion_piezas`
--

LOCK TABLES `reparacion_piezas` WRITE;
/*!40000 ALTER TABLE `reparacion_piezas` DISABLE KEYS */;
/*!40000 ALTER TABLE `reparacion_piezas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reparaciones`
--

DROP TABLE IF EXISTS `reparaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reparaciones` (
  `id_reparacion` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_equipo` bigint unsigned NOT NULL,
  `id_tecnico_asignado` bigint unsigned DEFAULT NULL,
  `taller_id` bigint unsigned NOT NULL,
  `fecha_recepcion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `problema_reportado` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnostico_tecnico` text COLLATE utf8mb4_unicode_ci,
  `presupuesto` decimal(10,2) DEFAULT NULL,
  `estado` enum('Recibido','En Diagnóstico','Esperando Aprobación','En Reparación','Reparado','No Reparado','Entregado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Recibido',
  `fecha_entrega_estimada` date DEFAULT NULL,
  `fecha_entrega_real` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_reparacion`),
  KEY `reparaciones_id_equipo_foreign` (`id_equipo`),
  KEY `reparaciones_id_tecnico_asignado_foreign` (`id_tecnico_asignado`),
  KEY `reparaciones_taller_id_foreign` (`taller_id`),
  CONSTRAINT `reparaciones_id_equipo_foreign` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE,
  CONSTRAINT `reparaciones_id_tecnico_asignado_foreign` FOREIGN KEY (`id_tecnico_asignado`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reparaciones_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reparaciones`
--

LOCK TABLES `reparaciones` WRITE;
/*!40000 ALTER TABLE `reparaciones` DISABLE KEYS */;
INSERT INTO `reparaciones` VALUES (1,1,NULL,1,'2026-03-17 01:24:20','NO jala',NULL,NULL,'Recibido',NULL,NULL,NULL,NULL),(2,2,NULL,1,'2026-03-17 16:25:15','yes',NULL,NULL,'Recibido',NULL,NULL,NULL,NULL),(3,3,NULL,1,'2026-03-23 02:43:43','El dispositivo dejo de dar señal de la noche a la mañana',NULL,NULL,'Reparado',NULL,'2026-03-23 02:51:06',NULL,NULL);
/*!40000 ALTER TABLE `reparaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_material`
--

DROP TABLE IF EXISTS `solicitudes_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_material` (
  `id_solicitud` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_tecnico_solicitante` bigint unsigned NOT NULL,
  `taller_id` bigint unsigned NOT NULL,
  `nombre_producto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `cantidad_solicitada` int unsigned NOT NULL DEFAULT '1',
  `estado_solicitud` enum('Pendiente','Aprobada','Comprado','Rechazada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `notas_admin` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_solicitud`),
  KEY `solicitudes_material_id_tecnico_solicitante_foreign` (`id_tecnico_solicitante`),
  KEY `solicitudes_material_taller_id_foreign` (`taller_id`),
  CONSTRAINT `solicitudes_material_id_tecnico_solicitante_foreign` FOREIGN KEY (`id_tecnico_solicitante`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_material_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_material`
--

LOCK TABLES `solicitudes_material` WRITE;
/*!40000 ALTER TABLE `solicitudes_material` DISABLE KEYS */;
INSERT INTO `solicitudes_material` VALUES (1,2,1,'Pantalla Iphone 13','Pantalla Iphone 13 para nuevas versiones',2,'Aprobada','2026-03-16 23:09:53',NULL);
/*!40000 ALTER TABLE `solicitudes_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talleres`
--

DROP TABLE IF EXISTS `talleres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talleres` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` bigint unsigned NOT NULL,
  `nombre_negocio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfc_tax_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuracion` json DEFAULT NULL,
  `fecha_vencimiento_licencia` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `talleres_plan_id_foreign` (`plan_id`),
  CONSTRAINT `talleres_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talleres`
--

LOCK TABLES `talleres` WRITE;
/*!40000 ALTER TABLE `talleres` DISABLE KEYS */;
INSERT INTO `talleres` VALUES (1,1,'Ultracel Matriz',NULL,NULL,NULL,1,'2026-03-16 20:41:47','2026-03-16 20:41:47');
/*!40000 ALTER TABLE `talleres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taller_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especialidad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permitido` tinyint(1) NOT NULL DEFAULT '1',
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vendedor',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_taller_id_foreign` (`taller_id`),
  CONSTRAINT `users_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1,'Admin Ultracel','admin@ultracel.com',NULL,'$2y$12$UJd3TZ8JXXABlbx0hTHTUulNcTpAFErYspTlqjixfCZF4tcTfB2vK','Dormir como desquiciado',1,'Admin',1,NULL,'2026-03-16 20:41:47','2026-03-16 21:01:25'),(2,1,'Carlos Técnico','tecnico@ultracel.com',NULL,'$2y$12$8mBCLFV.oO1kX05n6UmLvOOyh2JRWkldUUFsjaDO0t49AfyxovHJC','Hardware',1,'Tecnico',1,NULL,'2026-03-16 21:03:05','2026-03-16 21:03:05'),(3,1,'Jose','vendedor@ultracel.com',NULL,'$2y$12$vanCTLV3hQ.vUHHxxNyFJuou2juhgkUmjAN1Koj5/05fLc210IKeG','Vender',1,'Vendedor',1,NULL,'2026-03-17 01:22:46','2026-03-23 02:15:22'),(4,1,'Pancho','werever@gmail.com',NULL,'$2y$12$zO1PxulxoQTLy.1tsE/yPeulN3SGSGC9wPU8l6Bv2kMKL2l.8hji.','vender',0,'Vendedor',1,NULL,'2026-03-23 02:02:18','2026-03-23 02:15:25');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta_detalles`
--

DROP TABLE IF EXISTS `venta_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta_detalles` (
  `id_detalle` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned DEFAULT NULL,
  `id_reparacion` bigint unsigned DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descripcion_linea` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_detalle`),
  KEY `venta_detalles_id_venta_foreign` (`id_venta`),
  KEY `venta_detalles_id_producto_foreign` (`id_producto`),
  KEY `venta_detalles_id_reparacion_foreign` (`id_reparacion`),
  CONSTRAINT `venta_detalles_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE SET NULL,
  CONSTRAINT `venta_detalles_id_reparacion_foreign` FOREIGN KEY (`id_reparacion`) REFERENCES `reparaciones` (`id_reparacion`) ON DELETE SET NULL,
  CONSTRAINT `venta_detalles_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta_detalles`
--

LOCK TABLES `venta_detalles` WRITE;
/*!40000 ALTER TABLE `venta_detalles` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id_venta` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` bigint unsigned DEFAULT NULL,
  `id_vendedor` bigint unsigned NOT NULL,
  `taller_id` bigint unsigned NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto_total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `ventas_id_cliente_foreign` (`id_cliente`),
  KEY `ventas_id_vendedor_foreign` (`id_vendedor`),
  KEY `ventas_taller_id_foreign` (`taller_id`),
  CONSTRAINT `ventas_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL,
  CONSTRAINT `ventas_id_vendedor_foreign` FOREIGN KEY (`id_vendedor`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ventas_taller_id_foreign` FOREIGN KEY (`taller_id`) REFERENCES `talleres` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-23  4:06:50
