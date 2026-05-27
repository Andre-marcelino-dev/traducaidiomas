-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/05/2026 às 15:51
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_traduca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_agenda`
--

CREATE TABLE `tbl_agenda` (
  `id_agenda` int(11) NOT NULL,
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
  `atualizado_em_agenda` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_alunos`
--

CREATE TABLE `tbl_alunos` (
  `id_aluno` int(11) NOT NULL,
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
  `atualizado_em_aluno` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_alunos`
--

INSERT INTO `tbl_alunos` (`id_aluno`, `nome_aluno`, `email_aluno`, `senha_aluno`, `telefone_aluno`, `curso_aluno`, `data_nasc_aluno`, `nivel_aluno`, `foto_aluno`, `status_aluno`, `criado_em_aluno`, `atualizado_em_aluno`) VALUES
(1, 'Caio Ferreira', 'caioferreira@gmail.com', '4582', '(11)94002-4582', 'Inglês', '2010-05-17', 'Iniciante', 'alunos/caio-ferreira.png', 'EM CURSO', '2026-03-17 09:05:05', '2026-03-17 09:11:37'),
(2, 'Paulo Vicente', 'paulovicente@gmail.com', '4132', '(11)99972-7631', 'Espanhol', '2003-04-23', 'Intermediário', 'alunos/paulo-vicente.png', 'EM CURSO', '2026-03-17 09:11:06', '2026-03-17 09:11:37'),
(3, 'Lorena Marques', 'lorenamarques@gmail.com', '2520', '(11)99345-0123', 'Inglês', '2016-10-07', 'Iniciante', 'alunos/lorena-marques.png', 'EM CURSO', '2026-03-17 09:28:46', '2026-03-17 09:28:46');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_aulas`
--

CREATE TABLE `tbl_aulas` (
  `id_aulas` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `titulo_aulas` varchar(100) NOT NULL,
  `descricao_aulas` text NOT NULL,
  `data_aulas` date NOT NULL,
  `hora_aulas` time NOT NULL,
  `cursos_aulas` varchar(100) NOT NULL,
  `status_aulas` varchar(10) NOT NULL DEFAULT 'ATIVO',
  `criado_em_aulas` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_aulas` datetime NOT NULL DEFAULT current_timestamp(),
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_configuracoes_painel`
--

CREATE TABLE `tbl_configuracoes_painel` (
  `id_configuracoes_painel` int(11) NOT NULL,
  `chave_configuracoes_painel` varchar(100) NOT NULL,
  `valor_configuracoes_painel` text NOT NULL,
  `update_at_configuracoes_painel` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_cursos`
--

CREATE TABLE `tbl_cursos` (
  `id_curso` int(11) NOT NULL,
  `nome_curso` varchar(100) NOT NULL,
  `descricao_curso` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_materiais`
--

CREATE TABLE `tbl_materiais` (
  `id_materiais` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `titulo_materiais` varchar(255) NOT NULL,
  `descricao_materiais` text NOT NULL,
  `arquivo_materiais` varchar(255) NOT NULL,
  `curso_materiais` varchar(100) NOT NULL,
  `nivel_material` varchar(50) NOT NULL,
  `criado_em_materiais` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em_materiais` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_matricula`
--

CREATE TABLE `tbl_matricula` (
  `id_matricula` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `data_matricula` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_niveis`
--

CREATE TABLE `tbl_niveis` (
  `id_nivel` int(11) NOT NULL,
  `nome_nivel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_notificacoes`
--

CREATE TABLE `tbl_notificacoes` (
  `id_notificacoes` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_professor` int(11) NOT NULL,
  `id_materiais` int(11) NOT NULL,
  `mensagem_notificacoes` text NOT NULL,
  `link_notificacoes` varchar(255) NOT NULL,
  `lida_notificacoes` tinyint(1) NOT NULL,
  `data_criacao_notificacoes` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_presenca`
--

CREATE TABLE `tbl_presenca` (
  `id_presenca` int(11) NOT NULL,
  `id_aulas` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `status_presenca` varchar(10) NOT NULL DEFAULT 'PRESENTE',
  `data_pregistro_presenca` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_professor`
--

CREATE TABLE `tbl_professor` (
  `id_professor` int(11) NOT NULL,
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
  `atualizado_em_professor` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_professor`
--

INSERT INTO `tbl_professor` (`id_professor`, `nome_professor`, `especialidade_professor`, `experiencia_professor`, `bio_professor`, `foto_professor`, `email_professor`, `curso_professor`, `nivel_professor`, `telefone_professor`, `senha_professor`, `criado_em_professor`, `atualizado_em_professor`) VALUES
(1, 'Renato Caetano', 'Aulas de inglês', '10 anos', 'Sou Renato Caetano, consultor e professor trilíngue formado em Letras, com experiência em ensino, tradução e design instrucional. Atualmente, curso Design Instrucional no Senac-SP.', 'professor/renato-caetano.png', 'contato@traduca.com.br', 'Inglês', 'Avançado', '(11)97582-0019', '123', '2026-03-17 08:55:19', '2026-03-17 08:55:19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_progresso_materiais`
--

CREATE TABLE `tbl_progresso_materiais` (
  `id_progresso` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_materiais` int(11) NOT NULL,
  `status_progresso` varchar(15) NOT NULL DEFAULT 'EM ANDAMENTO',
  `progresso_materiais` int(11) NOT NULL,
  `data_acesso_progresso_materiais` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_servicos`
--

CREATE TABLE `tbl_servicos` (
  `id_servico` int(11) NOT NULL,
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
  `imagem_servico` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_agenda`
--
ALTER TABLE `tbl_agenda`
  ADD PRIMARY KEY (`id_agenda`),
  ADD KEY `fk_agenda_aluno` (`id_aluno`),
  ADD KEY `fk_agenda_professor` (`id_professor`);

--
-- Índices de tabela `tbl_alunos`
--
ALTER TABLE `tbl_alunos`
  ADD PRIMARY KEY (`id_aluno`),
  ADD UNIQUE KEY `email_aluno` (`email_aluno`);

--
-- Índices de tabela `tbl_aulas`
--
ALTER TABLE `tbl_aulas`
  ADD PRIMARY KEY (`id_aulas`),
  ADD KEY `fk_aulas_professor` (`id_professor`),
  ADD KEY `fk_aulas_curso` (`id_curso`);

--
-- Índices de tabela `tbl_configuracoes_painel`
--
ALTER TABLE `tbl_configuracoes_painel`
  ADD PRIMARY KEY (`id_configuracoes_painel`);

--
-- Índices de tabela `tbl_cursos`
--
ALTER TABLE `tbl_cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Índices de tabela `tbl_materiais`
--
ALTER TABLE `tbl_materiais`
  ADD PRIMARY KEY (`id_materiais`),
  ADD KEY `fk_materiais_professor` (`id_professor`),
  ADD KEY `fk_materiais_curso` (`id_curso`);

--
-- Índices de tabela `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  ADD PRIMARY KEY (`id_matricula`),
  ADD KEY `fk_matricula_aluno` (`id_aluno`),
  ADD KEY `fk_matricula_curso` (`id_curso`),
  ADD KEY `fk_matricula_nivel` (`id_nivel`);

--
-- Índices de tabela `tbl_niveis`
--
ALTER TABLE `tbl_niveis`
  ADD PRIMARY KEY (`id_nivel`);

--
-- Índices de tabela `tbl_notificacoes`
--
ALTER TABLE `tbl_notificacoes`
  ADD PRIMARY KEY (`id_notificacoes`),
  ADD KEY `fk_notificacoes_aluno` (`id_aluno`),
  ADD KEY `fk_notificacoes_professor` (`id_professor`),
  ADD KEY `fk_notificacoes_materiais` (`id_materiais`);

--
-- Índices de tabela `tbl_presenca`
--
ALTER TABLE `tbl_presenca`
  ADD PRIMARY KEY (`id_presenca`),
  ADD KEY `fk_presenca_aulas` (`id_aulas`),
  ADD KEY `fk_presenca_alunos` (`id_aluno`);

--
-- Índices de tabela `tbl_professor`
--
ALTER TABLE `tbl_professor`
  ADD PRIMARY KEY (`id_professor`);

--
-- Índices de tabela `tbl_progresso_materiais`
--
ALTER TABLE `tbl_progresso_materiais`
  ADD PRIMARY KEY (`id_progresso`),
  ADD KEY `fk_progresso_materiais_materiais` (`id_materiais`),
  ADD KEY `fk_progresso_materiais_aluno` (`id_aluno`);

--
-- Índices de tabela `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  ADD PRIMARY KEY (`id_servico`),
  ADD KEY `fk_servicos_professor` (`id_professor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_agenda`
--
ALTER TABLE `tbl_agenda`
  MODIFY `id_agenda` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_alunos`
--
ALTER TABLE `tbl_alunos`
  MODIFY `id_aluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tbl_aulas`
--
ALTER TABLE `tbl_aulas`
  MODIFY `id_aulas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_configuracoes_painel`
--
ALTER TABLE `tbl_configuracoes_painel`
  MODIFY `id_configuracoes_painel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_cursos`
--
ALTER TABLE `tbl_cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_materiais`
--
ALTER TABLE `tbl_materiais`
  MODIFY `id_materiais` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  MODIFY `id_matricula` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_niveis`
--
ALTER TABLE `tbl_niveis`
  MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_notificacoes`
--
ALTER TABLE `tbl_notificacoes`
  MODIFY `id_notificacoes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_presenca`
--
ALTER TABLE `tbl_presenca`
  MODIFY `id_presenca` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_professor`
--
ALTER TABLE `tbl_professor`
  MODIFY `id_professor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbl_progresso_materiais`
--
ALTER TABLE `tbl_progresso_materiais`
  MODIFY `id_progresso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbl_agenda`
--
ALTER TABLE `tbl_agenda`
  ADD CONSTRAINT `fk_agenda_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  ADD CONSTRAINT `fk_agenda_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`);

--
-- Restrições para tabelas `tbl_aulas`
--
ALTER TABLE `tbl_aulas`
  ADD CONSTRAINT `fk_aulas_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  ADD CONSTRAINT `fk_aulas_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`);

--
-- Restrições para tabelas `tbl_materiais`
--
ALTER TABLE `tbl_materiais`
  ADD CONSTRAINT `fk_materiais_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  ADD CONSTRAINT `fk_materiais_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`);

--
-- Restrições para tabelas `tbl_matricula`
--
ALTER TABLE `tbl_matricula`
  ADD CONSTRAINT `fk_matricula_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  ADD CONSTRAINT `fk_matricula_curso` FOREIGN KEY (`id_curso`) REFERENCES `tbl_cursos` (`id_curso`),
  ADD CONSTRAINT `fk_matricula_nivel` FOREIGN KEY (`id_nivel`) REFERENCES `tbl_niveis` (`id_nivel`);

--
-- Restrições para tabelas `tbl_notificacoes`
--
ALTER TABLE `tbl_notificacoes`
  ADD CONSTRAINT `fk_notificacoes_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  ADD CONSTRAINT `fk_notificacoes_materiais` FOREIGN KEY (`id_materiais`) REFERENCES `tbl_materiais` (`id_materiais`),
  ADD CONSTRAINT `fk_notificacoes_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`);

--
-- Restrições para tabelas `tbl_presenca`
--
ALTER TABLE `tbl_presenca`
  ADD CONSTRAINT `fk_presenca_alunos` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  ADD CONSTRAINT `fk_presenca_aulas` FOREIGN KEY (`id_aulas`) REFERENCES `tbl_aulas` (`id_aulas`);

--
-- Restrições para tabelas `tbl_progresso_materiais`
--
ALTER TABLE `tbl_progresso_materiais`
  ADD CONSTRAINT `fk_progresso_materiais_aluno` FOREIGN KEY (`id_aluno`) REFERENCES `tbl_alunos` (`id_aluno`),
  ADD CONSTRAINT `fk_progresso_materiais_materiais` FOREIGN KEY (`id_materiais`) REFERENCES `tbl_materiais` (`id_materiais`);

--
-- Restrições para tabelas `tbl_servicos`
--
ALTER TABLE `tbl_servicos`
  ADD CONSTRAINT `fk_servicos_professor` FOREIGN KEY (`id_professor`) REFERENCES `tbl_professor` (`id_professor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
