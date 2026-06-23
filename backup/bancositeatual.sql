CREATE DATABASE  IF NOT EXISTS `traducaidiomas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `traducaidiomas`;
-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: traducaidiomas
-- ------------------------------------------------------
-- Server version	8.4.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aula_links`
--

DROP TABLE IF EXISTS `aula_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aula_links` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aluno_id` bigint unsigned DEFAULT NULL,
  `turma_id` bigint unsigned DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aula_links`
--

LOCK TABLES `aula_links` WRITE;
/*!40000 ALTER TABLE `aula_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `aula_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_06_141209_add_link_teams_to_aulas_table',2),(5,'2026_06_11_074531_create_reagendamentos_table',3),(6,'2026_06_13_152924_create_presenca_table',4),(7,'2026_06_22_000000_create_feedbacks_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presenca`
--

DROP TABLE IF EXISTS `presenca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presenca` (
  `id_presenca` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_aulas` int NOT NULL,
  `id_aluno` int NOT NULL,
  `status_presenca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_registro_presenca` date NOT NULL,
  PRIMARY KEY (`id_presenca`),
  KEY `presenca_id_aulas_foreign` (`id_aulas`),
  KEY `presenca_id_aluno_foreign` (`id_aluno`),
  CONSTRAINT `presenca_id_aluno_foreign` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`) ON DELETE CASCADE,
  CONSTRAINT `presenca_id_aulas_foreign` FOREIGN KEY (`id_aulas`) REFERENCES `tbl_aulas` (`id_aulas`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presenca`
--

LOCK TABLES `presenca` WRITE;
/*!40000 ALTER TABLE `presenca` DISABLE KEYS */;
INSERT INTO `presenca` VALUES (1,4,2,'presente','2026-06-13'),(2,5,3,'PRESENTE','2026-06-07'),(3,5,3,'PRESENTE','2026-06-08'),(4,5,3,'FALTA','2026-06-09'),(5,4,3,'PRESENTE','2026-06-10'),(6,4,3,'FALTA','2026-06-11');
/*!40000 ALTER TABLE `presenca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reagendamentos`
--

DROP TABLE IF EXISTS `reagendamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reagendamentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `aluno_id` bigint unsigned NOT NULL,
  `aula_id` bigint unsigned NOT NULL,
  `professor_id` bigint unsigned NOT NULL,
  `data_original` datetime DEFAULT NULL,
  `data_sugerida` datetime DEFAULT NULL,
  `data_nova` datetime DEFAULT NULL,
  `motivo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resposta_professor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente',
  `notificado_professor` tinyint(1) NOT NULL DEFAULT '0',
  `notificado_aluno` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirmado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reagendamentos`
--

LOCK TABLES `reagendamentos` WRITE;
/*!40000 ALTER TABLE `reagendamentos` DISABLE KEYS */;
INSERT INTO `reagendamentos` VALUES (8,1,4,1,'2026-12-20 00:31:00',NULL,'2026-12-25 14:20:00','Preciso reagenda minha aula',NULL,'confirmado',1,0,'2026-06-11 20:17:19','2026-06-11 20:18:17',NULL),(10,3,4,1,'2026-08-22 18:30:00',NULL,'2026-08-18 14:30:00','Motivo particular',NULL,'confirmado',1,0,'2026-06-12 20:11:31','2026-06-12 20:12:53',NULL),(11,1,5,1,'2026-12-12 18:00:00',NULL,'2026-06-30 12:00:00','Preciso ir ao medico',NULL,'confirmado',1,0,'2026-06-16 10:07:38','2026-06-16 10:26:30',NULL),(12,1,5,1,'2026-12-12 18:00:00',NULL,'2026-08-22 19:00:00','preciso viajar',NULL,'confirmado',1,0,'2026-06-19 11:47:01','2026-06-19 11:47:53',NULL);
/*!40000 ALTER TABLE `reagendamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('X45H9HRgMuQdIET4s1XHZgYG6uDTNd3wX1lJ8qQY',NULL,'172.19.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','eyJfdG9rZW4iOiJKeHRmVjhEcHRBckJHcDNDWFcyVnY2dlN1UE4zUUJKOTFNVWFzVUpiIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODFcL3NvYnJlIiwicm91dGUiOiJzb2JyZSJ9LCJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJsYXN0X2FjdGl2aXR5X2FkbWluIjoxNzgyMTM3NjU3LCJsb2dpbl9hbHVub181OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJsYXN0X2FjdGl2aXR5X2FsdW5vIjoxNzgyMTM4NjcxfQ==',1782138703),('XyBCilQWg9PHWiAk7N54gcTEEHc8bWpkXNX9HNiT',NULL,'172.19.0.1','curl/8.5.0','eyJfdG9rZW4iOiJmVXZ2RE4zTHUwYWloVmdhMG01Y1kxN25ybThVaXNnUDZDZ214SVVCIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODFcL2FsdW5vIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODA4MVwvYWx1bm8iLCJyb3V0ZSI6ImFsdW5vLmRhc2gifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1782137945),('xYFbEyMrJU5K6nZAyQxO1Pq4KM74wWG7Nj4TQIVh',NULL,'172.19.0.1','curl/8.5.0','eyJfdG9rZW4iOiJveEk1d0FXVjE2dzNUVUpzd0FWaklUa3pWZGtITkRmZTdRV3J0U29OIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODFcL2FsdW5vIn0sIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODA4MVwvYWx1bm8iLCJyb3V0ZSI6ImFsdW5vLmRhc2gifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1782137945);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_agenda`
--

DROP TABLE IF EXISTS `tbl_agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_agenda` (
  `id_agenda` int NOT NULL AUTO_INCREMENT,
  `id_aluno` int NOT NULL,
  `id_professor` int NOT NULL,
  `titulo_agenda` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao_agenda` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_evento_agenda` date NOT NULL,
  `hora_inicio_agenda` time NOT NULL,
  `hora_fim_agenda` time NOT NULL,
  `status_agenda` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `solicitacao_reagendamento` tinyint(1) DEFAULT NULL,
  `link_aula_agenda` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `criado_em_agenda` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_agenda` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_agenda`),
  KEY `fk_agenda_aluno` (`id_aluno`),
  KEY `fk_agenda_professor` (`id_professor`),
  CONSTRAINT `fk_agenda_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_agenda_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_agenda`
--

LOCK TABLES `tbl_agenda` WRITE;
/*!40000 ALTER TABLE `tbl_agenda` DISABLE KEYS */;
INSERT INTO `tbl_agenda` VALUES (2,3,1,'Italiano','Textos narrativos','2026-08-18','14:30:00','15:30:00','reagendado',NULL,NULL,'2026-06-12 20:12:53','2026-06-12 20:12:53'),(3,4,1,'Ingles','Aulas pratica verbos etc','2026-09-18','12:55:00','13:50:00','confirmado',NULL,'https://meet.google.com/jds-afuj-fhw','2026-06-13 16:37:37','2026-06-16 10:45:39'),(5,1,1,'Ingles','Aulas de ingles','2026-08-22','19:00:00','20:00:00','confirmado',NULL,NULL,'2026-06-19 11:49:09','2026-06-19 11:49:09');
/*!40000 ALTER TABLE `tbl_agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_alunos`
--

DROP TABLE IF EXISTS `tbl_alunos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_alunos` (
  `id_aluno` int NOT NULL AUTO_INCREMENT,
  `nome_aluno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_aluno` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha_aluno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefone_aluno` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `curso_aluno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_nasc_aluno` date NOT NULL,
  `nivel_aluno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_aluno` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_aluno` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ATIVO',
  `criado_em_aluno` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_aluno` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_aluno`),
  UNIQUE KEY `email_aluno` (`email_aluno`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_alunos`
--

LOCK TABLES `tbl_alunos` WRITE;
/*!40000 ALTER TABLE `tbl_alunos` DISABLE KEYS */;
INSERT INTO `tbl_alunos` VALUES (1,'Caio Ferreira','caioferreira@gmail.com','$2y$12$fr8P4A/2SugUi1VIdBJsCOWL8DKRLetpyRZDTGg6zC2RuIxWwAooq','(11)94002-4582','Inglês','2010-05-17','Intermediário','caio-ferreira.png','EM CURSO','2026-03-17 09:05:05','2026-06-22 10:57:08'),(2,'Paulo Vicente','paulovicente@gmail.com','4132','(11)99972-7631','Italiano','2003-04-23','Intermediário','paulo-vicente.png','EM CURSO','2026-03-17 09:11:06','2026-06-18 12:50:54'),(3,'Lorena Marques','lorenamarques@gmail.com','$2y$12$hvLrdyREtZdgVOiGdYQ9p.m2IrZ/Gx6Nw/RlaReDOCg46ixxWPdGK','(11)99345-0123','Italiano','2016-10-07','Intermediário','lorena-marques.png','EM CURSO','2026-03-17 09:28:46','2026-06-18 12:52:44'),(4,'Biatriz silva','bia.s@gmail.com','$2y$12$Cjc5kN3Aj8D9qwVsArIQle4Wd87Mx0EwYtaCnLqYxA5HD.66mxPrG','1196699988','Inglês','2019-01-02','Iniciante','biatriz-silva.png','EM CURSO','2026-06-01 22:29:53','2026-06-13 19:45:21'),(5,'Cesar Marcelino','cesar.m@gmail.com','$2y$12$4ZB6mZGFDItTt/Dn3ttO1eN/XcZa8k0fbwDNe4.i10boLSSa0BWGK','117777-7785','Italiano','1965-02-11','Iniciante','cesar-marcelino.png','CONCLUIDO','2026-06-07 14:53:36','2026-06-07 20:14:49'),(6,'Francisca Silva','francisca.s@gmail.com','$2y$12$av6jjCMScWlyOU25v7qv6OKRdIE1q/N/lp2iwQAz3dt7iVU92KXOG','(11)99345-0123','Inglês','2002-12-14','Iniciante','francisca-silva.jpg','EM CURSO','2026-06-19 13:38:59','2026-06-19 14:01:31'),(7,'Nicolas Silva','nicolas.s@gmail.com','$2y$12$Hg099e8TPtf9OOqPA6Jf.eEoA9Edndsg/AaG4Pdx4vEWJPcc/E99W','11988745621','Italiano','2022-08-14','Iniciante','nicolas-silva.jpg','EM CURSO','2026-06-22 13:48:06','2026-06-22 13:49:04');
/*!40000 ALTER TABLE `tbl_alunos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_atividade_questoes`
--

DROP TABLE IF EXISTS `tbl_atividade_questoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_atividade_questoes` (
  `id_questao` int NOT NULL AUTO_INCREMENT,
  `id_atividade` int NOT NULL,
  `enunciado` text NOT NULL,
  `tipo_questao` enum('multipla_escolha','texto') NOT NULL,
  `opcao_a` varchar(300) DEFAULT NULL,
  `opcao_b` varchar(300) DEFAULT NULL,
  `opcao_c` varchar(300) DEFAULT NULL,
  `opcao_d` varchar(300) DEFAULT NULL,
  `resposta_correta` char(1) DEFAULT NULL,
  `ordem` int DEFAULT '1',
  PRIMARY KEY (`id_questao`),
  KEY `id_atividade` (`id_atividade`),
  CONSTRAINT `tbl_atividade_questoes_ibfk_1` FOREIGN KEY (`id_atividade`) REFERENCES `tbl_atividades` (`id_atividade`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_atividade_questoes`
--

LOCK TABLES `tbl_atividade_questoes` WRITE;
/*!40000 ALTER TABLE `tbl_atividade_questoes` DISABLE KEYS */;
INSERT INTO `tbl_atividade_questoes` VALUES (1,1,'What is the correct form of \"to be\" for \"She _____ a teacher\"','multipla_escolha','am','are','is','were','C',1);
/*!40000 ALTER TABLE `tbl_atividade_questoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_atividade_resposta_questoes`
--

DROP TABLE IF EXISTS `tbl_atividade_resposta_questoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_atividade_resposta_questoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_resposta` int NOT NULL,
  `id_questao` int NOT NULL,
  `resposta_aluno` text,
  `correta` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_resposta` (`id_resposta`),
  KEY `id_questao` (`id_questao`),
  CONSTRAINT `tbl_atividade_resposta_questoes_ibfk_1` FOREIGN KEY (`id_resposta`) REFERENCES `tbl_atividade_respostas` (`id_resposta`) ON DELETE CASCADE,
  CONSTRAINT `tbl_atividade_resposta_questoes_ibfk_2` FOREIGN KEY (`id_questao`) REFERENCES `tbl_atividade_questoes` (`id_questao`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_atividade_resposta_questoes`
--

LOCK TABLES `tbl_atividade_resposta_questoes` WRITE;
/*!40000 ALTER TABLE `tbl_atividade_resposta_questoes` DISABLE KEYS */;
INSERT INTO `tbl_atividade_resposta_questoes` VALUES (1,1,1,'C',1);
/*!40000 ALTER TABLE `tbl_atividade_resposta_questoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_atividade_respostas`
--

DROP TABLE IF EXISTS `tbl_atividade_respostas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_atividade_respostas` (
  `id_resposta` int NOT NULL AUTO_INCREMENT,
  `id_atividade` int NOT NULL,
  `id_aluno` int NOT NULL,
  `status_resposta` enum('PENDENTE','ENVIADA','CORRIGIDA') DEFAULT 'PENDENTE',
  `nota` decimal(4,1) DEFAULT NULL,
  `feedback_professor` text,
  `data_envio` timestamp NULL DEFAULT NULL,
  `data_correcao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_resposta`),
  KEY `id_atividade` (`id_atividade`),
  KEY `id_aluno` (`id_aluno`),
  CONSTRAINT `tbl_atividade_respostas_ibfk_1` FOREIGN KEY (`id_atividade`) REFERENCES `tbl_atividades` (`id_atividade`),
  CONSTRAINT `tbl_atividade_respostas_ibfk_2` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_atividade_respostas`
--

LOCK TABLES `tbl_atividade_respostas` WRITE;
/*!40000 ALTER TABLE `tbl_atividade_respostas` DISABLE KEYS */;
INSERT INTO `tbl_atividade_respostas` VALUES (1,1,1,'CORRIGIDA',10.0,'Parabéns continue evoluindo','2026-06-14 15:20:03','2026-06-14 15:21:36');
/*!40000 ALTER TABLE `tbl_atividade_respostas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_atividades`
--

DROP TABLE IF EXISTS `tbl_atividades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_atividades` (
  `id_atividade` int NOT NULL AUTO_INCREMENT,
  `id_professor` int NOT NULL,
  `id_curso` int NOT NULL,
  `titulo_atividade` varchar(200) NOT NULL,
  `descricao_atividade` text,
  `tipo_atividade` enum('multipla_escolha','texto','misto') DEFAULT 'misto',
  `data_entrega` date DEFAULT NULL,
  `status_atividade` enum('ATIVA','INATIVA') DEFAULT 'ATIVA',
  `criado_em` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_atividade`),
  KEY `id_professor` (`id_professor`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `tbl_atividades_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`),
  CONSTRAINT `tbl_atividades_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_atividades`
--

LOCK TABLES `tbl_atividades` WRITE;
/*!40000 ALTER TABLE `tbl_atividades` DISABLE KEYS */;
INSERT INTO `tbl_atividades` VALUES (1,1,2,'Atividade de Ingles','Responda as questões sobre o Present Simple','misto','2026-12-12','ATIVA','2026-06-14 15:16:16');
/*!40000 ALTER TABLE `tbl_atividades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_aulas`
--

DROP TABLE IF EXISTS `tbl_aulas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_aulas` (
  `id_aulas` int NOT NULL AUTO_INCREMENT,
  `id_professor` int NOT NULL,
  `titulo_aulas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao_aulas` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_aulas` date NOT NULL,
  `hora_aulas` time NOT NULL,
  `link_teams` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cursos_aulas` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status_aulas` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ATIVO',
  `criado_em_aulas` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_aulas` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_curso` int NOT NULL,
  PRIMARY KEY (`id_aulas`),
  KEY `fk_aulas_professor` (`id_professor`),
  KEY `fk_aulas_curso` (`id_curso`),
  CONSTRAINT `fk_aulas_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_aulas_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_aulas`
--

LOCK TABLES `tbl_aulas` WRITE;
/*!40000 ALTER TABLE `tbl_aulas` DISABLE KEYS */;
INSERT INTO `tbl_aulas` VALUES (4,1,'Italiano','Textos narrativos','2026-08-18','14:30:00',NULL,'Italiano','ATIVO','2026-06-07 19:33:19','2026-06-07 19:33:19',3),(5,1,'Inglês','Aula focada em fala escrita e pronuncia','2026-12-12','18:00:00','https://meet.google.com/kxm-xpbb-chb','Inglês','ATIVO','2026-06-13 19:49:20','2026-06-13 19:49:20',2);
/*!40000 ALTER TABLE `tbl_aulas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_configuracoes_painel`
--

DROP TABLE IF EXISTS `tbl_configuracoes_painel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_configuracoes_painel` (
  `id_configuracoes_painel` int NOT NULL AUTO_INCREMENT,
  `chave_configuracoes_painel` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valor_configuracoes_painel` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `update_at_configuracoes_painel` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_configuracoes_painel`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_configuracoes_painel`
--

LOCK TABLES `tbl_configuracoes_painel` WRITE;
/*!40000 ALTER TABLE `tbl_configuracoes_painel` DISABLE KEYS */;
INSERT INTO `tbl_configuracoes_painel` VALUES (1,'banner1_titulo','Inglês profissional com método claro, foco em resultado e consistencia.','2026-06-14 15:29:19'),(2,'banner1_subtitulo','Treinamento para reuniões, entrevistas e apresentações.','2026-06-14 15:29:19'),(3,'banner1_eyebrow','TraducaIdiomas · English & Professional Skills','2026-06-14 15:29:19'),(4,'banner2_titulo','Da aula aplicação real: comunicação eficiente no seu contexto.','2026-06-14 15:29:19'),(5,'banner2_subtitulo','Metodologia pratica, material objetivo e feedback continuo.','2026-06-14 15:29:19'),(6,'banner2_eyebrow','Expertise · Idiomas com estrategia','2026-06-14 15:29:19'),(7,'info_titulo','Transforme suas ideias em textos de excelência','2026-06-14 15:29:19'),(8,'info_subtitulo','Oferecemos serviços especializados de consultoria, revisão e experiência.','2026-06-14 15:29:19'),(9,'sobre_titulo','Biografia','2026-06-14 15:29:19'),(10,'sobre_texto','Sou Renato Caetano, consultor e professor trilíngue formado em Letras.','2026-06-14 15:29:19'),(11,'cor_primaria','#0d6efd','2026-06-14 15:29:19'),(12,'cor_secundaria','#198754','2026-06-14 15:29:19'),(13,'logo_painel','logo_painel.png','2026-06-14 15:30:25'),(14,'sobre_foto','sobre_1781451559.jpg','2026-06-14 15:39:19'),(15,'sobre_pagina_texto','Atuo no setor corporativo e academico desenvolvendo projetos educacionais, treinamentos e materiais didaticos. Ja traduzi conteudos educacionais, institucionais e publicitarios para clientes como Mercedes-Benz e Enel, e trabalho como Tradutor Senior de ingles e italiano. Passei por cargos de lideranca pedagogica no Yazigi e na Bridge English Brasil, onde coordenei programas de ingles para multinacionais como Walmart, BASF, FMGlobal e 3M.','2026-06-14 16:30:08'),(16,'logo_site','logo_site.png','2026-06-17 11:54:40');
/*!40000 ALTER TABLE `tbl_configuracoes_painel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_cursos`
--

DROP TABLE IF EXISTS `tbl_cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_cursos` (
  `id_curso` int NOT NULL AUTO_INCREMENT,
  `nome_curso` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao_curso` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_cursos`
--

LOCK TABLES `tbl_cursos` WRITE;
/*!40000 ALTER TABLE `tbl_cursos` DISABLE KEYS */;
INSERT INTO `tbl_cursos` VALUES (1,'Português','Curso focado no ensino do português para estrangeiros, com ênfase em conversação, compreensão auditiva e situações do dia a dia.'),(2,'Inglês','Curso completo de inglês que desenvolve fala, escuta, leitura e escrita de forma integrada. Indicado para iniciantes até níveis avançados, com foco em comunicação real e prática.'),(3,'Italiano','Curso de italiano que abrange desde o nível iniciante até o avançado, com foco em comunicação, cultura e pronúncia.');
/*!40000 ALTER TABLE `tbl_cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_feedbacks`
--

DROP TABLE IF EXISTS `tbl_feedbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_feedbacks` (
  `id_feedback` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_aluno` bigint unsigned NOT NULL,
  `id_professor` bigint unsigned NOT NULL,
  `id_curso` bigint unsigned NOT NULL,
  `nota` tinyint unsigned NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_feedback`),
  UNIQUE KEY `tbl_feedbacks_id_aluno_id_curso_unique` (`id_aluno`,`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_feedbacks`
--

LOCK TABLES `tbl_feedbacks` WRITE;
/*!40000 ALTER TABLE `tbl_feedbacks` DISABLE KEYS */;
INSERT INTO `tbl_feedbacks` VALUES (1,1,1,2,4,'Professor excelente','2026-06-22 11:24:44','2026-06-22 11:24:44');
/*!40000 ALTER TABLE `tbl_feedbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_materiais`
--

DROP TABLE IF EXISTS `tbl_materiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_materiais` (
  `id_materiais` int NOT NULL AUTO_INCREMENT,
  `id_professor` int NOT NULL,
  `titulo_materiais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descricao_materiais` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `arquivo_materiais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `curso_materiais` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nivel_material` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `criado_em_materiais` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_materiais` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_curso` int NOT NULL,
  PRIMARY KEY (`id_materiais`),
  KEY `fk_materiais_professor` (`id_professor`),
  KEY `fk_materiais_curso` (`id_curso`),
  CONSTRAINT `fk_materiais_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_materiais_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_materiais`
--

LOCK TABLES `tbl_materiais` WRITE;
/*!40000 ALTER TABLE `tbl_materiais` DISABLE KEYS */;
INSERT INTO `tbl_materiais` VALUES (1,1,'Apostila Italiano','Escrita fala','traducaidiomas/materiais/1781304117_1000 frases úteis do dia a dia em italiano.pdf','Italiano','Básico','2026-06-12 19:41:14','2026-06-12 19:42:36',3),(3,1,'Apostila de Inglês','Pronuncia verbos fala','traducaidiomas/materiais/1781704989_apostila_ingles_basico.pdf','Inglês','Básico','2026-06-17 11:03:09','2026-06-17 11:03:09',2);
/*!40000 ALTER TABLE `tbl_materiais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_matricula`
--

DROP TABLE IF EXISTS `tbl_matricula`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_matricula` (
  `id_matricula` int NOT NULL AUTO_INCREMENT,
  `id_aluno` int NOT NULL,
  `id_curso` int NOT NULL,
  `id_nivel` int NOT NULL,
  `data_matricula` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_matricula` enum('ATIVO','CONGELADO','CANCELADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ATIVO',
  PRIMARY KEY (`id_matricula`),
  KEY `fk_matricula_aluno` (`id_aluno`),
  KEY `fk_matricula_curso` (`id_curso`),
  KEY `fk_matricula_nivel` (`id_nivel`),
  CONSTRAINT `fk_matricula_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_matricula_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_matricula_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `tbl_niveis` (`id_nivel`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_matricula`
--

LOCK TABLES `tbl_matricula` WRITE;
/*!40000 ALTER TABLE `tbl_matricula` DISABLE KEYS */;
INSERT INTO `tbl_matricula` VALUES (1,4,2,3,'2024-10-17 00:00:00','ATIVO'),(2,1,2,3,'2020-10-17 00:00:00','ATIVO'),(3,2,3,2,'2020-10-17 00:00:00','ATIVO'),(5,5,3,1,'1980-10-17 00:00:00','ATIVO'),(6,3,3,1,'2026-10-18 00:00:00','ATIVO'),(7,7,3,1,'2026-06-22 00:00:00','CONGELADO');
/*!40000 ALTER TABLE `tbl_matricula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_niveis`
--

DROP TABLE IF EXISTS `tbl_niveis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_niveis` (
  `id_nivel` int NOT NULL AUTO_INCREMENT,
  `nome_nivel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_nivel`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_niveis`
--

LOCK TABLES `tbl_niveis` WRITE;
/*!40000 ALTER TABLE `tbl_niveis` DISABLE KEYS */;
INSERT INTO `tbl_niveis` VALUES (1,'iniciante'),(2,'intermediario'),(3,'avancado');
/*!40000 ALTER TABLE `tbl_niveis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_notificacoes`
--

DROP TABLE IF EXISTS `tbl_notificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_notificacoes` (
  `id_notificacoes` int NOT NULL AUTO_INCREMENT,
  `id_aluno` int NOT NULL,
  `id_professor` int NOT NULL,
  `id_materiais` int DEFAULT NULL,
  `mensagem_notificacoes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link_notificacoes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lida_notificacoes` tinyint(1) NOT NULL,
  `data_criacao_notificacoes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacoes`),
  KEY `fk_notificacoes_aluno` (`id_aluno`),
  KEY `fk_notificacoes_professor` (`id_professor`),
  KEY `fk_notificacoes_materiais` (`id_materiais`),
  CONSTRAINT `fk_notificacoes_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_notificacoes_materiais` FOREIGN KEY (`id_materiais`) REFERENCES `tbl_materiais` (`id_materiais`),
  CONSTRAINT `fk_notificacoes_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_notificacoes`
--

LOCK TABLES `tbl_notificacoes` WRITE;
/*!40000 ALTER TABLE `tbl_notificacoes` DISABLE KEYS */;
INSERT INTO `tbl_notificacoes` VALUES (1,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 16:27:38'),(2,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 16:27:48'),(3,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 17:10:35'),(4,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 18:00:20'),(5,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:19:07'),(6,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:47:48'),(7,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:56:15'),(8,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-20 às 00:31','/aluno',0,'2026-06-11 20:14:18'),(9,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-25 às 14:20','/aluno',0,'2026-06-11 20:18:17'),(10,3,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-08-22 às 18:30','/aluno',0,'2026-06-12 19:47:10'),(11,3,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-08-18 às 14:30','/aluno',0,'2026-06-12 20:12:53');
/*!40000 ALTER TABLE `tbl_notificacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_presenca`
--

DROP TABLE IF EXISTS `tbl_presenca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_presenca` (
  `id_presenca` int NOT NULL AUTO_INCREMENT,
  `id_aulas` int NOT NULL,
  `id_aluno` int NOT NULL,
  `status_presenca` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PRESENTE',
  `data_pregistro_presenca` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_presenca`),
  KEY `fk_presenca_aulas` (`id_aulas`),
  KEY `fk_presenca_alunos` (`id_aluno`),
  CONSTRAINT `fk_presenca_alunos` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_presenca_aulas` FOREIGN KEY (`id_aulas`) REFERENCES `tbl_aulas` (`id_aulas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_presenca`
--

LOCK TABLES `tbl_presenca` WRITE;
/*!40000 ALTER TABLE `tbl_presenca` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_presenca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_professor`
--

DROP TABLE IF EXISTS `tbl_professor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_professor` (
  `id_professor` int NOT NULL AUTO_INCREMENT,
  `nome_professor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `especialidade_professor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `experiencia_professor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bio_professor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_professor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_professor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `curso_professor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nivel_professor` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefone_professor` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha_professor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `criado_em_professor` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_professor` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_professor`
--

LOCK TABLES `tbl_professor` WRITE;
/*!40000 ALTER TABLE `tbl_professor` DISABLE KEYS */;
INSERT INTO `tbl_professor` VALUES (1,'Renato Caetano','Aulas de inglês escrita','15 anos','Sou Renato Caetano, consultor e professor trilíngue formado em Letras, com experiência em ensino, tradução e design instrucional. Atualmente, curso Design Instrucional no Senac-SP.','1781442351_6a2ea72f3fd5c.png','contato@traduca.com.br','Frances','Avançado','(11)99961-2140','$2y$12$NtH8KXEvE5/mBFywdw.Fjej1lOckxucQwywYqPh/bq9YEUeqt1sde','2026-03-17 08:55:19','2026-06-22 09:14:25');
/*!40000 ALTER TABLE `tbl_professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_progresso_materiais`
--

DROP TABLE IF EXISTS `tbl_progresso_materiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_progresso_materiais` (
  `id_progresso` int NOT NULL AUTO_INCREMENT,
  `id_aluno` int NOT NULL,
  `id_materiais` int NOT NULL,
  `status_progresso` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'EM ANDAMENTO',
  `progresso_materiais` int NOT NULL,
  `data_acesso_progresso_materiais` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_progresso`),
  KEY `fk_progresso_materiais_materiais` (`id_materiais`),
  KEY `fk_progresso_materiais_aluno` (`id_aluno`),
  CONSTRAINT `fk_progresso_materiais_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_progresso_materiais_materiais` FOREIGN KEY (`id_materiais`) REFERENCES `tbl_materiais` (`id_materiais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_progresso_materiais`
--

LOCK TABLES `tbl_progresso_materiais` WRITE;
/*!40000 ALTER TABLE `tbl_progresso_materiais` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_progresso_materiais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_servicos`
--

DROP TABLE IF EXISTS `tbl_servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_servicos` (
  `id_servico` int NOT NULL AUTO_INCREMENT,
  `id_professor` int NOT NULL,
  `titulo_servico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subtitulo_servico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lista_beneficios_servico` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cta_titulo_servico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cta_texto_servico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `link_whatsapp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `classe_estilo_servico` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lingua_servico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `titulo_professor_servico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `conteudo_servico` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `preco_servico` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contato_text_servico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ordenar_servico` int NOT NULL,
  `imagem_servico` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_servico`),
  KEY `fk_servicos_professor` (`id_professor`),
  CONSTRAINT `fk_servicos_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_servicos`
--

LOCK TABLES `tbl_servicos` WRITE;
/*!40000 ALTER TABLE `tbl_servicos` DISABLE KEYS */;
INSERT INTO `tbl_servicos` VALUES (1,1,'Aulas de Português','Especialista em Inglês, Italiano e Português','Aulas particulares personalizadas (todos os níveis), Preparação para exames de proficiência, Português para negócios e entrevistas, Conversação fluente e pronúncia, Aulas online via Zoom/Google Meet','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-pt','pt','Profº Renato Caetano','Foco em fluência e gramática aplicada.','R$ 190/hora | 1ª aula grátis','Dúvidas? Chame no zap',1,'traducaidiomas/servicos/brazil.png'),(2,1,'Curso de Inglês Profissional','Especialista em Business English e Exames','Aulas focadas em carreira e negócios, Preparatório TOEFL / IELTS / Cambridge, Pronúncia e redução de sotaque, Material internacional de apoio, Conversação para situações reais','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-en','en','Profº Renato Caetano','Desenvolva sua confiança para falar inglês no mundo globalizado.','R$ 80/hora | 1ª aula grátis','Dúvidas? Chame no zap',2,'traducaidiomas/servicos/estados-unidos.jpg'),(3,1,'Língua e Cultura Italiana','Imersão no Idioma com Método Natural','Italiano prático para viagens e turismo, Preparação para exames de cidadania, Foco total em conversação e gramática, Cultura, gastronomia e costumes locais, Aulas dinâmicas e personalizadas','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-it','it','Profº Renato Caetano','Aprenda italiano de forma leve e divertida, do básico ao avançado.','R$ 70/hora | Grupos reduzidos','Dúvidas? Chame no zap',3,'traducaidiomas/servicos/italia.png');
/*!40000 ALTER TABLE `tbl_servicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-22 11:35:20
