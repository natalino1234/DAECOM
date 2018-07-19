-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 02-Out-2017 às 14:03
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id2108103_bdacademia`
--
CREATE DATABASE IF NOT EXISTS `id2108103_bdacademia` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `id2108103_bdacademia`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbcliente`
--

CREATE TABLE `tbcliente` (
  `cdCliente` int(10) UNSIGNED NOT NULL,
  `nmCliente` varchar(255) NOT NULL,
  `CPF` varchar(14) NOT NULL,
  `RG` varchar(10) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo` int(11) NOT NULL,
  `valorMensalidade` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbcliente`
--

INSERT INTO `tbcliente` (`cdCliente`, `nmCliente`, `CPF`, `RG`, `senha`, `email`, `tipo`, `valorMensalidade`) VALUES
(39, 'PB', '7.716.617-5', '9551422422', '9551422422', 'pbruno@gmail.com', 2, 123),
(42, 'Feijão', '13349165605', 'asfa', '81dc9bdb52d04dc20036dbd8313ed055', 'l@gmail.com', 1, 0),
(44, 'Flavia', '123.123.123-12', '173871283', '3590cb8af0bbb9e78c343b52b93773c9', 'flavia@GMAISSL', 2, 150.5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbclienteavaliacao`
--

CREATE TABLE `tbclienteavaliacao` (
  `tbItemAvaliacaoFisica_cdItem` int(10) UNSIGNED NOT NULL,
  `tbTipoTreino_cdTreino` int(10) UNSIGNED NOT NULL,
  `dt` int(10) UNSIGNED DEFAULT NULL,
  `vl` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbconta`
--

CREATE TABLE `tbconta` (
  `cdConta` int(10) UNSIGNED NOT NULL,
  `vlConta` double NOT NULL,
  `nmConta` varchar(255) NOT NULL,
  `dtVencimento` date NOT NULL,
  `dtPagamento` date DEFAULT NULL,
  `vlPago` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbexercicio`
--

CREATE TABLE `tbexercicio` (
  `cdExercicio` int(10) UNSIGNED NOT NULL,
  `nmExercicio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbexercicio`
--

INSERT INTO `tbexercicio` (`cdExercicio`, `nmExercicio`) VALUES
(1, 'Leg Press'),
(2, 'Abdominal'),
(4, 'Supino Reto'),
(5, 'Abdominal2'),
(6, 'Bispeira');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbexerciciotreino`
--

CREATE TABLE `tbexerciciotreino` (
  `cdExercicio` int(10) UNSIGNED NOT NULL,
  `cdTreino` int(10) UNSIGNED NOT NULL,
  `qtd` int(10) UNSIGNED NOT NULL,
  `repeticao` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `tbexerciciotreino`
--

INSERT INTO `tbexerciciotreino` (`cdExercicio`, `cdTreino`, `qtd`, `repeticao`) VALUES
(1, 3, 51, 6),
(4, 3, 51, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbitemavaliacaofisica`
--

CREATE TABLE `tbitemavaliacaofisica` (
  `cdItem` int(10) UNSIGNED NOT NULL,
  `nmItem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbitensa`
--

CREATE TABLE `tbitensa` (
  `cdItensa` int(11) NOT NULL,
  `nmItensa` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbitensa`
--

INSERT INTO `tbitensa` (`cdItensa`, `nmItensa`) VALUES
(3, 'Braço');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbmensalidade`
--

CREATE TABLE `tbmensalidade` (
  `cdMensalidade` int(10) UNSIGNED NOT NULL,
  `TbCliente_cdCliente` int(10) UNSIGNED NOT NULL,
  `vlMensalidade` double NOT NULL,
  `dtMensalidade` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbmensalidade`
--

INSERT INTO `tbmensalidade` (`cdMensalidade`, `TbCliente_cdCliente`, `vlMensalidade`, `dtMensalidade`) VALUES
(15, 40, 123, '2017-09-07'),
(16, 39, 343546457657, '2017-09-13'),
(18, 42, 144, '2017-09-22');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbobjetivo`
--

CREATE TABLE `tbobjetivo` (
  `cdObjetivo` int(11) NOT NULL,
  `nmObjetivo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbobjetivo`
--

INSERT INTO `tbobjetivo` (`cdObjetivo`, `nmObjetivo`) VALUES
(3, 'Pernas'),
(4, 'Braçose');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbtipotreino`
--

CREATE TABLE `tbtipotreino` (
  `cdTreino` int(10) UNSIGNED NOT NULL,
  `cdCliente` int(11) UNSIGNED NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  `cdObjetivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbtipotreino`
--

INSERT INTO `tbtipotreino` (`cdTreino`, `cdCliente`, `dataInicio`, `dataFim`, `cdObjetivo`) VALUES
(3, 39, '2017-01-10', '2017-03-10', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `cdUsuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbusuario`
--

INSERT INTO `tbusuario` (`cdUsuario`, `email`, `senha`) VALUES
(23, 'pbruno@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
(26, 'l@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbcliente`
--
ALTER TABLE `tbcliente`
  ADD PRIMARY KEY (`cdCliente`);

--
-- Indexes for table `tbclienteavaliacao`
--
ALTER TABLE `tbclienteavaliacao`
  ADD PRIMARY KEY (`tbItemAvaliacaoFisica_cdItem`),
  ADD KEY `TbCliente_has_tbItemAvaliacaoFisica_FKIndex2` (`tbItemAvaliacaoFisica_cdItem`),
  ADD KEY `TbCliente_has_tbItemAvaliacaoFisica_FKIndex3` (`tbTipoTreino_cdTreino`);

--
-- Indexes for table `tbconta`
--
ALTER TABLE `tbconta`
  ADD PRIMARY KEY (`cdConta`);

--
-- Indexes for table `tbexercicio`
--
ALTER TABLE `tbexercicio`
  ADD PRIMARY KEY (`cdExercicio`);

--
-- Indexes for table `tbexerciciotreino`
--
ALTER TABLE `tbexerciciotreino`
  ADD PRIMARY KEY (`cdExercicio`,`cdTreino`),
  ADD KEY `tbExercicio_has_tbTipoTreino_FKIndex1` (`cdExercicio`),
  ADD KEY `tbExercicio_has_tbTipoTreino_FKIndex2` (`cdTreino`);

--
-- Indexes for table `tbitemavaliacaofisica`
--
ALTER TABLE `tbitemavaliacaofisica`
  ADD PRIMARY KEY (`cdItem`);

--
-- Indexes for table `tbitensa`
--
ALTER TABLE `tbitensa`
  ADD PRIMARY KEY (`cdItensa`);

--
-- Indexes for table `tbmensalidade`
--
ALTER TABLE `tbmensalidade`
  ADD PRIMARY KEY (`cdMensalidade`),
  ADD KEY `TbMensalidade_FKIndex1` (`TbCliente_cdCliente`);

--
-- Indexes for table `tbobjetivo`
--
ALTER TABLE `tbobjetivo`
  ADD PRIMARY KEY (`cdObjetivo`);

--
-- Indexes for table `tbtipotreino`
--
ALTER TABLE `tbtipotreino`
  ADD PRIMARY KEY (`cdTreino`),
  ADD KEY `tbTipoTreino_FKIndex1` (`cdCliente`),
  ADD KEY `Objetivo` (`cdObjetivo`);

--
-- Indexes for table `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`cdUsuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbcliente`
--
ALTER TABLE `tbcliente`
  MODIFY `cdCliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `tbconta`
--
ALTER TABLE `tbconta`
  MODIFY `cdConta` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbexercicio`
--
ALTER TABLE `tbexercicio`
  MODIFY `cdExercicio` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbitemavaliacaofisica`
--
ALTER TABLE `tbitemavaliacaofisica`
  MODIFY `cdItem` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbitensa`
--
ALTER TABLE `tbitensa`
  MODIFY `cdItensa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbmensalidade`
--
ALTER TABLE `tbmensalidade`
  MODIFY `cdMensalidade` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tbobjetivo`
--
ALTER TABLE `tbobjetivo`
  MODIFY `cdObjetivo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbtipotreino`
--
ALTER TABLE `tbtipotreino`
  MODIFY `cdTreino` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `cdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
