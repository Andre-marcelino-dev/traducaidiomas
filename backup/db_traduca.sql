-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: db_traduca
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `tbl_agenda`
--

DROP TABLE IF EXISTS `tbl_agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_agenda` (
  `id_agenda` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `titulo_agenda` varchar(100) NOT NULL,
  `descricao_agenda` text NOT NULL,
  `data_evento_agenda` date NOT NULL,
  `hora_inicio_agenda` time NOT NULL,
  `hora_fim_agenda` time NOT NULL,
  `status_agenda` varchar(50) NOT NULL,
  `solicitacao_reagendamento` tinyint(1) DEFAULT NULL,
  `link_aula_agenda` varchar(500) DEFAULT NULL,
  `criado_em_agenda` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_agenda` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
  `id_aluno` int(11) NOT NULL AUTO_INCREMENT,
  `nome_aluno` varchar(100) NOT NULL,
  `email_aluno` varchar(80) NOT NULL,
  `senha_aluno` varchar(255) NOT NULL,
  `telefone_aluno` varchar(14) NOT NULL,
  `curso_aluno` varchar(100) NOT NULL,
  `data_nasc_aluno` date NOT NULL,
  `nivel_aluno` varchar(50) NOT NULL,
  `foto_aluno` varchar(80) NOT NULL,
  `status_aluno` varchar(10) NOT NULL DEFAULT 'ATIVO',
  `criado_em_aluno` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_aluno` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_aluno`),
  UNIQUE KEY `email_aluno` (`email_aluno`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_alunos`
--

LOCK TABLES `tbl_alunos` WRITE;
/*!40000 ALTER TABLE `tbl_alunos` DISABLE KEYS */;
INSERT INTO `tbl_alunos` VALUES (1,'Caio Ferreira','caioferreira@gmail.com','4582','(11)94002-4582','Inglês','2010-05-17','Iniciante','alunos/caio-ferreira.png','EM CURSO','2026-03-17 09:05:05','2026-03-17 09:11:37'),(2,'Paulo Vicente','paulovicente@gmail.com','4132','(11)99972-7631','Espanhol','2003-04-23','Intermediário','alunos/paulo-vicente.png','EM CURSO','2026-03-17 09:11:06','2026-03-17 09:11:37'),(3,'Lorena Marques','lorenamarques@gmail.com','2520','(11)99345-0123','Inglês','2016-10-07','Iniciante','alunos/lorena-marques.png','EM CURSO','2026-03-17 09:28:46','2026-03-17 09:28:46');
/*!40000 ALTER TABLE `tbl_alunos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_aulas`
--

DROP TABLE IF EXISTS `tbl_aulas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_aulas` (
  `id_aulas` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` int(11) NOT NULL,
  `titulo_aulas` varchar(100) NOT NULL,
  `descricao_aulas` text NOT NULL,
  `data_aulas` date NOT NULL,
  `hora_aulas` time NOT NULL,
  `cursos_aulas` varchar(100) NOT NULL,
  `status_aulas` varchar(10) NOT NULL DEFAULT 'ATIVO',
  `criado_em_aulas` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_aulas` datetime NOT NULL DEFAULT current_timestamp(),
  `id_curso` int(11) NOT NULL,
  PRIMARY KEY (`id_aulas`),
  KEY `fk_aulas_professor` (`id_professor`),
  KEY `fk_aulas_curso` (`id_curso`),
  CONSTRAINT `fk_aulas_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_aulas_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_aulas`
--

LOCK TABLES `tbl_aulas` WRITE;
/*!40000 ALTER TABLE `tbl_aulas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_aulas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_configuracoes_painel`
--

DROP TABLE IF EXISTS `tbl_configuracoes_painel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_configuracoes_painel` (
  `id_configuracoes_painel` int(11) NOT NULL AUTO_INCREMENT,
  `chave_configuracoes_painel` varchar(100) NOT NULL,
  `valor_configuracoes_painel` text NOT NULL,
  `update_at_configuracoes_painel` timestamp NOT NULL DEFAULT current_timestamp(),
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
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `nome_curso` varchar(100) NOT NULL,
  `descricao_curso` text NOT NULL,
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
  `id_materiais` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` int(11) NOT NULL,
  `titulo_materiais` varchar(255) NOT NULL,
  `descricao_materiais` text NOT NULL,
  `arquivo_materiais` varchar(255) NOT NULL,
  `curso_materiais` varchar(100) NOT NULL,
  `nivel_material` varchar(50) NOT NULL,
  `criado_em_materiais` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_materiais` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_curso` int(11) NOT NULL,
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
  `id_matricula` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `data_matricula` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_matricula`),
  KEY `fk_matricula_aluno` (`id_aluno`),
  KEY `fk_matricula_curso` (`id_curso`),
  KEY `fk_matricula_nivel` (`id_nivel`),
  CONSTRAINT `fk_matricula_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_matricula_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  CONSTRAINT `fk_matricula_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `tbl_niveis` (`id_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_matricula`
--

LOCK TABLES `tbl_matricula` WRITE;
/*!40000 ALTER TABLE `tbl_matricula` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_matricula` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_niveis`
--

DROP TABLE IF EXISTS `tbl_niveis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_niveis` (
  `id_nivel` int(11) NOT NULL AUTO_INCREMENT,
  `nome_nivel` varchar(50) NOT NULL,
  PRIMARY KEY (`id_nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_niveis`
--

LOCK TABLES `tbl_niveis` WRITE;
/*!40000 ALTER TABLE `tbl_niveis` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_niveis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_notificacoes`
--

DROP TABLE IF EXISTS `tbl_notificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_notificacoes` (
  `id_notificacoes` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `id_materiais` int(11) NOT NULL,
  `mensagem_notificacoes` text NOT NULL,
  `link_notificacoes` varchar(255) NOT NULL,
  `lida_notificacoes` tinyint(1) NOT NULL,
  `data_criacao_notificacoes` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_notificacoes`),
  KEY `fk_notificacoes_aluno` (`id_aluno`),
  KEY `fk_notificacoes_professor` (`id_professor`),
  KEY `fk_notificacoes_materiais` (`id_materiais`),
  CONSTRAINT `fk_notificacoes_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  CONSTRAINT `fk_notificacoes_materiais` FOREIGN KEY (`id_materiais`) REFERENCES `tbl_materiais` (`id_materiais`),
  CONSTRAINT `fk_notificacoes_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_notificacoes`
--

LOCK TABLES `tbl_notificacoes` WRITE;
/*!40000 ALTER TABLE `tbl_notificacoes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_notificacoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_presenca`
--

DROP TABLE IF EXISTS `tbl_presenca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_presenca` (
  `id_presenca` int(11) NOT NULL AUTO_INCREMENT,
  `id_aulas` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `status_presenca` varchar(10) NOT NULL DEFAULT 'PRESENTE',
  `data_pregistro_presenca` datetime NOT NULL DEFAULT current_timestamp(),
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
  `id_professor` int(11) NOT NULL AUTO_INCREMENT,
  `nome_professor` varchar(100) NOT NULL,
  `especialidade_professor` varchar(100) NOT NULL,
  `experiencia_professor` varchar(50) NOT NULL,
  `bio_professor` text NOT NULL,
  `foto_professor` varchar(255) NOT NULL,
  `email_professor` varchar(100) NOT NULL,
  `curso_professor` varchar(50) NOT NULL,
  `nivel_professor` varchar(20) NOT NULL,
  `telefone_professor` varchar(14) NOT NULL,
  `senha_professor` varchar(255) NOT NULL,
  `criado_em_professor` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_professor` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_professor`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_professor`
--

LOCK TABLES `tbl_professor` WRITE;
/*!40000 ALTER TABLE `tbl_professor` DISABLE KEYS */;
INSERT INTO `tbl_professor` VALUES (1,'Renato Caetano','Aulas de inglês','10 anos','Sou Renato Caetano, consultor e professor trilíngue formado em Letras, com experiência em ensino, tradução e design instrucional. Atualmente, curso Design Instrucional no Senac-SP.','professor/renato-caetano.png','contato@traduca.com.br','Inglês','Avançado','(11)97582-0019','123','2026-03-17 08:55:19','2026-03-17 08:55:19');
/*!40000 ALTER TABLE `tbl_professor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_progresso_materiais`
--

DROP TABLE IF EXISTS `tbl_progresso_materiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_progresso_materiais` (
  `id_progresso` int(11) NOT NULL AUTO_INCREMENT,
  `id_aluno` int(11) NOT NULL,
  `id_materiais` int(11) NOT NULL,
  `status_progresso` varchar(15) NOT NULL DEFAULT 'EM ANDAMENTO',
  `progresso_materiais` int(11) NOT NULL,
  `data_acesso_progresso_materiais` timestamp NOT NULL DEFAULT current_timestamp(),
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
  `id_servico` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` int(11) NOT NULL,
  `titulo_servico` varchar(100) NOT NULL,
  `subtitulo_servico` varchar(100) NOT NULL,
  `lista_beneficios_servico` text NOT NULL,
  `cta_titulo_servico` varchar(255) NOT NULL,
  `cta_texto_servico` varchar(255) NOT NULL,
  `link_whatsapp` varchar(255) NOT NULL,
  `classe_estilo_servico` varchar(50) NOT NULL,
  `lingua_servico` varchar(100) NOT NULL,
  `titulo_professor_servico` varchar(255) NOT NULL,
  `conteudo_servico` text NOT NULL,
  `preco_servico` varchar(100) NOT NULL,
  `contato_text_servico` varchar(255) NOT NULL,
  `ordenar_servico` int(11) NOT NULL,
  `imagem_servico` varchar(255) NOT NULL,
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-29 11:36:59
