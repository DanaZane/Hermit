-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 30-Out-2018 às 22:25
-- Versão do servidor: 10.1.31-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id7493925_tcc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `contato`
--

CREATE TABLE `contato` (
  `id_contato` int(11) NOT NULL,
  `tipo` varchar(64) NOT NULL,
  `cont` varchar(128) NOT NULL,
  `id_pessoa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro`
--

CREATE TABLE `livro` (
  `id_livro` int(11) NOT NULL,
  `titulo` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `autor` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `isbn` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `editora` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `status` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id_notificacoes` int(11) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `tipo` varchar(32) NOT NULL,
  `id_pessoa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `id_pessoa` int(11) NOT NULL,
  `nome` varchar(256) NOT NULL,
  `hash` varchar(256) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `troca`
--

CREATE TABLE `troca` (
  `id_troca` int(11) NOT NULL,
  `id_livro1` int(11) NOT NULL,
  `id_livro2` int(11) NOT NULL,
  `id_pessoa1` int(11) NOT NULL,
  `id_pessoa2` int(11) NOT NULL,
  `status1` varchar(256) NOT NULL,
  `status2` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contato`
--
ALTER TABLE `contato`
  ADD PRIMARY KEY (`id_contato`),
  ADD KEY `fk_contato_pessoa` (`id_pessoa`);

--
-- Indexes for table `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id_livro`),
  ADD KEY `fk_livro_pessoa` (`id_pessoa`);

--
-- Indexes for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id_notificacoes`),
  ADD KEY `pk_notificacoes_pessoas` (`id_pessoa`);

--
-- Indexes for table `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id_pessoa`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `troca`
--
ALTER TABLE `troca`
  ADD PRIMARY KEY (`id_troca`),
  ADD KEY `fk_troca_livro1` (`id_livro1`),
  ADD KEY `fk_troca_livro2` (`id_livro2`),
  ADD KEY `fk_troca_pessoa1` (`id_pessoa1`),
  ADD KEY `fk_troca_pessoa2` (`id_pessoa2`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contato`
--
ALTER TABLE `contato`
  MODIFY `id_contato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `livro`
--
ALTER TABLE `livro`
  MODIFY `id_livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id_notificacoes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id_pessoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `troca`
--
ALTER TABLE `troca`
  MODIFY `id_troca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `contato`
--
ALTER TABLE `contato`
  ADD CONSTRAINT `fk_contato_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `fk_livro_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `pk_notificacoes_pessoas` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `troca`
--
ALTER TABLE `troca`
  ADD CONSTRAINT `fk_troca_livro1` FOREIGN KEY (`id_livro1`) REFERENCES `livro` (`id_livro`),
  ADD CONSTRAINT `fk_troca_livro2` FOREIGN KEY (`id_livro2`) REFERENCES `livro` (`id_livro`),
  ADD CONSTRAINT `fk_troca_pessoa1` FOREIGN KEY (`id_pessoa1`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `fk_troca_pessoa2` FOREIGN KEY (`id_pessoa2`) REFERENCES `pessoa` (`id_pessoa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
