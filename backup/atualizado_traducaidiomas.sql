CREATE DATABASE  IF NOT EXISTS `traducaidiomas` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `traducaidiomas`;
-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
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
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_06_141209_add_link_teams_to_aulas_table',2),(5,'2026_06_11_074531_create_reagendamentos_table',3);
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
  `motivo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resposta_professor` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente',
  `notificado_professor` tinyint(1) NOT NULL DEFAULT '0',
  `notificado_aluno` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirmado_em` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reagendamentos`
--

LOCK TABLES `reagendamentos` WRITE;
/*!40000 ALTER TABLE `reagendamentos` DISABLE KEYS */;
INSERT INTO `reagendamentos` VALUES (8,1,4,1,'2026-12-20 00:31:00',NULL,'2026-12-25 14:20:00','Preciso reagenda minha aula',NULL,'confirmado',1,0,'2026-06-11 20:17:19','2026-06-11 20:18:17',NULL);
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
INSERT INTO `sessions` VALUES ('5e0tR0ZuoqgmfYDYe69i7fI8mI66sM2jJNzUVg4J',1,'172.18.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','eyJfdG9rZW4iOiJ6TmYxa3Y5aTd2UWw0Q0dUOHl1UkNZZ3lEZlpkRFJIampPSjA3ZW5lIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwODFcL2FsdW5vXC9wZXJmaWwiLCJyb3V0ZSI6ImFsdW5vLnBlcmZpbCJ9LCJsb2dpbl9hbHVub181OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==',1781221082);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_agenda`
--

LOCK TABLES `tbl_agenda` WRITE;
/*!40000 ALTER TABLE `tbl_agenda` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_alunos`
--

LOCK TABLES `tbl_alunos` WRITE;
/*!40000 ALTER TABLE `tbl_alunos` DISABLE KEYS */;
INSERT INTO `tbl_alunos` VALUES (1,'Caio Ferreira','caioferreira@gmail.com','$2y$12$fr8P4A/2SugUi1VIdBJsCOWL8DKRLetpyRZDTGg6zC2RuIxWwAooq','(11)94002-4582','Inglês','2010-05-17','Intermediário','caio-ferreira.png','EM CURSO','2026-03-17 09:05:05','2026-06-11 18:03:17'),(2,'Paulo Vicente','paulovicente@gmail.com','4132','(11)99972-7631','Italiano','2003-04-23','Intermediário','paulo-vicente.png','INATIVO','2026-03-17 09:11:06','2026-06-07 20:14:26'),(3,'Lorena Marques','lorenamarques@gmail.com','$2y$12$miOtovjMk2d5fg6NacHP/OgCZS8SAXEH2ESaNihz6p/TJVxidbQgm','(11)99345-0123','Inglês','2016-10-07','Avançado','lorena-marques.png','EM CURSO','2026-03-17 09:28:46','2026-06-04 18:01:36'),(4,'Biatriz silva','bia.s@gmail.com','789456','1196699988','Inglês','2019-01-02','Iniciante','biatriz-silva.png','INATIVO','2026-06-01 22:29:53','2026-06-07 15:19:17'),(5,'Cesar Marcelino','cesar.m@gmail.com','$2y$12$4ZB6mZGFDItTt/Dn3ttO1eN/XcZa8k0fbwDNe4.i10boLSSa0BWGK','117777-7785','Italiano','1965-02-11','Iniciante','cesar-marcelino.png','CONCLUIDO','2026-06-07 14:53:36','2026-06-07 20:14:49');
/*!40000 ALTER TABLE `tbl_alunos` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_aulas`
--

LOCK TABLES `tbl_aulas` WRITE;
/*!40000 ALTER TABLE `tbl_aulas` DISABLE KEYS */;
INSERT INTO `tbl_aulas` VALUES (4,1,'Italiano','Textos narrativos','2026-12-25','14:20:00',NULL,'Italiano','ATIVO','2026-06-07 19:33:19','2026-06-07 19:33:19',3);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_configuracoes_painel`
--

LOCK TABLES `tbl_configuracoes_painel` WRITE;
/*!40000 ALTER TABLE `tbl_configuracoes_painel` DISABLE KEYS */;
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
  `curso_materiais` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nivel_material` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `criado_em_materiais` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em_materiais` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_curso` int NOT NULL,
  PRIMARY KEY (`id_materiais`),
  KEY `fk_materiais_professor` (`id_professor`),
  KEY `fk_materiais_curso` (`id_curso`),
  CONSTRAINT `fk_materiais_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_materiais_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_materiais`
--

LOCK TABLES `tbl_materiais` WRITE;
/*!40000 ALTER TABLE `tbl_materiais` DISABLE KEYS */;
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
  PRIMARY KEY (`id_matricula`),
  KEY `fk_matricula_aluno` (`id_aluno`),
  KEY `fk_matricula_curso` (`id_curso`),
  KEY `fk_matricula_nivel` (`id_nivel`),
  CONSTRAINT `fk_matricula_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_matricula_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_matricula_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `tbl_niveis` (`id_nivel`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_matricula`
--

LOCK TABLES `tbl_matricula` WRITE;
/*!40000 ALTER TABLE `tbl_matricula` DISABLE KEYS */;
INSERT INTO `tbl_matricula` VALUES (1,4,2,3,'2024-10-17 00:00:00'),(2,1,2,1,'2020-10-17 00:00:00'),(3,2,3,2,'2020-10-17 00:00:00'),(4,3,2,3,'2000-10-17 00:00:00'),(5,5,3,1,'1980-10-17 00:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_notificacoes`
--

LOCK TABLES `tbl_notificacoes` WRITE;
/*!40000 ALTER TABLE `tbl_notificacoes` DISABLE KEYS */;
INSERT INTO `tbl_notificacoes` VALUES (1,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 16:27:38'),(2,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 16:27:48'),(3,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 17:10:35'),(4,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 18:00:20'),(5,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:19:07'),(6,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:47:48'),(7,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-22 às 18:40:00','/aluno',0,'2026-06-11 19:56:15'),(8,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-20 às 00:31','/aluno',0,'2026-06-11 20:14:18'),(9,1,1,NULL,'Sua aula foi reagendada: Italiano — Nova data: 2026-12-25 às 14:20','/aluno',0,'2026-06-11 20:18:17');
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
INSERT INTO `tbl_professor` VALUES (1,'Renato Caetano','Aulas de inglês escrita','10 anos','Sou Renato Caetano, consultor e professor trilíngue formado em Letras, com experiência em ensino, tradução e design instrucional. Atualmente, curso Design Instrucional no Senac-SP.','renato-caetano.png','contato@traduca.com.br','Inglês','Avançado','(11)97582-1177','$2y$12$NtH8KXEvE5/mBFywdw.Fjej1lOckxucQwywYqPh/bq9YEUeqt1sde','2026-03-17 08:55:19','2026-06-07 17:15:16');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_servicos`
--

LOCK TABLES `tbl_servicos` WRITE;
/*!40000 ALTER TABLE `tbl_servicos` DISABLE KEYS */;
INSERT INTO `tbl_servicos` VALUES (1,1,'Aulas de Português','Especialista em Inglês, Italiano e Português','Aulas particulares personalizadas (todos os níveis), Preparação para exames de proficiência, Português para negócios e entrevistas, Conversação fluente e pronúncia, Aulas online via Zoom/Google Meet','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-pt','pt','Profº Renato Caetano','Foco em fluência e gramática aplicada.','R$ 80/hora | 1ª aula grátis','Dúvidas? Chame no zap',1,'img/flags/brazil.png'),(2,1,'Curso de Inglês Profissional','Especialista em Business English e Exames','Aulas focadas em carreira e negócios, Preparatório TOEFL / IELTS / Cambridge, Pronúncia e redução de sotaque, Material internacional de apoio, Conversação para situações reais','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-en','en','Profº Renato Caetano','Desenvolva sua confiança para falar inglês no mundo globalizado.','R$ 80/hora | 1ª aula grátis','Dúvidas? Chame no zap',2,'img/flags/uk.png'),(3,1,'Língua e Cultura Italiana','Imersão no Idioma com Método Natural','Italiano prático para viagens e turismo, Preparação para exames de cidadania, Foco total em conversação e gramática, Cultura, gastronomia e costumes locais, Aulas dinâmicas e personalizadas','Agende sua aula experimental!','WhatsApp Agora','https://wa.me/5511999999999','card-it','it','Profº Renato Caetano','Aprenda italiano de forma leve e divertida, do básico ao avançado.','R$ 70/hora | Grupos reduzidos','Dúvidas? Chame no zap',3,'img/flags/italy.png');
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

-- Dump completed on 2026-06-11 20:40:54
