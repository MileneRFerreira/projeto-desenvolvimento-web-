-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 30-Set-2018 às 20:21
-- Versão do servidor: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(20) NOT NULL,
  `categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`) VALUES
(1, 'Primeira Categoria'),
(2, 'Segunda Categoria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `forums`
--

CREATE TABLE `forums` (
  `id` int(20) NOT NULL,
  `categoria` varchar(200) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `status` int(200) NOT NULL,
  `permissao` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `forums`
--

INSERT INTO `forums` (`id`, `categoria`, `titulo`, `descricao`, `status`, `permissao`) VALUES
(1, '1', 'My First Forum', 'This is my first forum', 1, 1),
(2, '1', 'MY secound forum', 'This is my second forum', 0, 1),
(3, '2', 'My First post of me secound category', 'Description of my secound category post', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `respostas`
--

CREATE TABLE `respostas` (
  `id` int(20) NOT NULL,
  `id_topico` int(20) NOT NULL,
  `id_forum` int(200) NOT NULL,
  `id_categoria` int(200) NOT NULL,
  `postador` varchar(200) NOT NULL,
  `resposta` text NOT NULL,
  `data` varchar(200) NOT NULL,
  `curtidas` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `respostas`
--

INSERT INTO `respostas` (`id`, `id_topico`, `id_forum`, `id_categoria`, `postador`, `resposta`, `data`, `curtidas`) VALUES
(17, 3, 1, 1, 'user_11', 'Achei legal!', '14-11-2020 23:19:04', 0),
(19, 1, 1, 1, 'user_11', 'Eu também!', '15-11-2020 00:56:15', 0),
(20, 3, 1, 1, 'user_11', 'Eu também! kkk', '15-11-2020 00:59:26', 0),
(21, 5, 3, 2, 'user_11', 'Achei legal !', '15-11-2018 01:05:16', 0),
(22, 4, 1, 1, 'user_11', 'Tlg...', '15-11-2020 01:22:25', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `topicos`
--

CREATE TABLE `topicos` (
  `id` int(20) NOT NULL,
  `forum` int(20) NOT NULL,
  `categoria` int(200) NOT NULL,
  `visitas` int(200) NOT NULL DEFAULT '0',
  `titulo` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `postador` varchar(200) NOT NULL,
  `data` varchar(200) NOT NULL,
  `status` int(20) NOT NULL,
  `curtidas` int(200) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `topicos`
--

INSERT INTO `topicos` (`id`, `forum`, `categoria`, `visitas`, `titulo`, `mensagem`, `postador`, `data`, `status`, `curtidas`) VALUES
(3, 1, 1, 277, 'My first topic', 'This is my first tipic, i\'m so happy. Are we happy?', 'user_11', '14-11-2020 15:35:22', 0, 15),
(4, 1, 1, 43, 'Minha segunda publicação', 'Esta é a minha descrição', 'user_11', '14-09-2018 16:11:03', 1, 9),
(5, 3, 2, 22, 'O que acha?', 'fçlas çlfajslçk fjas', 'rafael065', '15-11-2020 22:34:54', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(20) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `usuario` varchar(200) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `nivel` int(200) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `foto`, `senha`, `email`, `nivel`) VALUES
(1, 'User_1', 'user_11', '', 'wsbws8g5', 'user_1@hotmail.com', 2),
(2, 'User_2', 'user_22', '', '123', 'dasdsa', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `respostas`
--
ALTER TABLE `respostas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topicos`
--
ALTER TABLE `topicos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `respostas`
--
ALTER TABLE `respostas`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `topicos`
--
ALTER TABLE `topicos`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
