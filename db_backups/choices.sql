-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/12/2025 às 01:43
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
-- Estrutura para tabela `choices`
--

CREATE TABLE `choices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_story_node_id` bigint(20) UNSIGNED DEFAULT NULL,
  `choice_description` text NOT NULL,
  `to_story_node_id` bigint(20) UNSIGNED DEFAULT NULL,
  `required_flag` varchar(255) DEFAULT NULL,
  `set_flag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `choices`
--

INSERT INTO `choices` (`id`, `from_story_node_id`, `choice_description`, `to_story_node_id`, `required_flag`, `set_flag`) VALUES
(4, 401, 'Continuar', 0, NULL, NULL),
(5, 1, 'Você se apresentará como um especialista em plantas medicinais?', 261, NULL, NULL),
(6, 1, 'Você dirá que é um comerciante?', 230, NULL, NULL),
(7, 1, 'Você pedirá abrigo para pernoitar?', 20, NULL, NULL),
(8, 2, 'Você tentará abrir a porta', 142, NULL, NULL),
(9, 2, 'Continuará seguindo a passagem', 343, NULL, NULL),
(10, 3, 'Uma Miríade de Bolso?', 327, 'miriade_de_bolso', NULL),
(11, 3, 'Uma Aranha em um Vidro?', 59, 'aranha_em_um_vidro', NULL),
(12, 3, 'Um punhado de Pequenas Amoras?', 236, 'punhado_de_pequenas_amoras', NULL),
(13, 3, 'Desembainhar a sua espada', 286, NULL, NULL),
(14, 3, 'Se dirigir para a porta mais distante', 366, NULL, NULL),
(15, 4, 'Lançar rapidamente um Encanto de Cópia de Criatura', 190, NULL, NULL),
(16, 4, 'Desembainhar a sua espada', 303, NULL, NULL),
(17, 5, 'Você toca a campainha conforme indicado', 40, NULL, NULL),
(18, 5, 'Experimenta a maçaneta da porta', 361, NULL, NULL),
(19, 6, 'Você segue o caminho por algum tempo', 367, NULL, NULL),
(20, 7, 'Tentar pô-la abaixo, batendo nela com o ombro', 268, NULL, NULL),
(21, 7, 'Lançar um Encanto da Força sobre você mesmo e tentar arrancar a porta', 116, NULL, NULL),
(22, 0, 'Continuar', 1, NULL, NULL),
(23, 8, 'Correr para a entrada principal da Cidadela', 218, NULL, NULL),
(24, 9, 'Sair do aposento sem mais perda de tempo', 31, NULL, NULL),
(25, 10, 'Investigar a porta', 249, NULL, NULL),
(26, 11, 'Usar um Encanto do Ouro dos Tolos', 36, NULL, NULL),
(27, 11, 'Usar um Encanto de Cópia de Criatura', 262, NULL, NULL),
(28, 11, 'Usar um Encanto da Percepção Extra-Sensorial', 128, NULL, NULL),
(29, 11, 'Usar um Encanto da Fraqueza', 152, NULL, NULL),
(30, 11, 'Desembainhar a sua espada e lutar', 16, NULL, NULL),
(31, 12, 'Deslocar-se rapidamente para o armário das armas', 274, NULL, NULL),
(32, 12, 'Pular para debaixo da mesa', 335, NULL, NULL),
(33, 12, 'Correr para a janela', 78, NULL, NULL),
(34, 17, 'Sair pela porta do lado oposto', 93, NULL, NULL),
(35, 18, 'Procurar outro livro útil que possa ajudá-lo', 84, NULL, NULL),
(36, 18, 'Tentar sair da biblioteca pela porta atrás dele', 31, NULL, NULL),
(37, 19, 'Usar um Encanto da Levitação', 363, NULL, NULL),
(38, 19, 'Continuar escorregando', 254, NULL, NULL),
(39, 20, 'Resignar-se a lutar', 288, NULL, NULL),
(40, 20, 'Usar Encanto do Ouro dos Tolos', 96, NULL, NULL),
(41, 21, 'Seguir adiante', 6, NULL, NULL),
(42, 22, 'Seguir pelo corredor', 188, NULL, NULL),
(43, 23, 'Caminhar na direção do aposento e entrar nele', 169, NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `choices_from_story_node_id_foreign` (`from_story_node_id`),
  ADD KEY `choices_to_story_node_id_foreign` (`to_story_node_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `choices`
--
ALTER TABLE `choices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `choices_from_story_node_id_foreign` FOREIGN KEY (`from_story_node_id`) REFERENCES `story_nodes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `choices_to_story_node_id_foreign` FOREIGN KEY (`to_story_node_id`) REFERENCES `story_nodes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
