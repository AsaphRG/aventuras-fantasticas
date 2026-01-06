-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/11/2025 às 10:05
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
-- Banco de dados: `aventuras_fantasticas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `enemies`
--

CREATE TABLE `enemies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `ability` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `enemies`
--

INSERT INTO `enemies` (`id`, `name`, `ability`, `energy`) VALUES
(1, 'Gark', 7, 11),
(2, 'Fera das garras', 9, 14),
(3, 'Homem-Aranha', 7, 5),
(4, 'Cobra de esgoto', 6, 7),
(5, 'Gárgula', 9, 10),
(6, 'Gark', 5, 5),
(7, 'Macaco-Cachorro', 7, 4),
(8, 'Cachorro-Macaco', 6, 6),
(9, 'Golem', 8, 10),
(10, 'Homem alto', 8, 8),
(11, 'Homem baixo', 7, 6),
(12, 'Anão', 5, 6),
(13, 'Goblin', 6, 4),
(14, 'Orca', 7, 5),
(15, 'Primeiro Gira', 7, 6),
(16, 'Segundo Gira', 6, 5),
(17, 'Calacorm', 9, 8),
(18, 'Homem-Rino', 8, 9),
(19, 'Elfo negro', 8, 4),
(20, 'Homem-Rino', 4, 7),
(21, 'Balthusdire', 12, 19),
(22, 'Hidra', 10, 17),
(23, 'Elfo negro', 4, 4);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `enemies`
--
ALTER TABLE `enemies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `enemies`
--
ALTER TABLE `enemies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
