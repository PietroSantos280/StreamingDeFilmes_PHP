-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/11/2026 às 18:18
-- Versão do servidor: 10.11.16-MariaDB
-- Versão do PHP: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_streaming`
--
CREATE DATABASE IF NOT EXISTS `sistema_streaming` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_streaming`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `filme_id` int(11) NOT NULL,
  `Score` int(5) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `usuario_id`, `filme_id`, `Score`, `comentario`) VALUES
(5, 1, 7, 5, 'Conceito incrível e reviravoltas espetaculares na história!'),
(6, 1, 8, 5, 'Uma verdadeira obra-prima da fantasia e aventura.'),
(7, 2, 9, 4, 'Muito fofo, divertido e emocionante para todas as idades.'),
(8, 3, 11, 5, 'Animação revolucionária e uma banda sonora perfeita.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `filmes`
--

CREATE TABLE `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `sinopse` text NOT NULL,
  `ano_lancamento` year(4) NOT NULL,
  `avaliacao` decimal(3,2) NOT NULL,
  `duracao_minutos` int(11) NOT NULL,
  `genero` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `filmes`
--

INSERT INTO `filmes` (`id`, `titulo`, `sinopse`, `ano_lancamento`, `avaliacao`, `duracao_minutos`, `genero`) VALUES
(1, 'Interestelar', 'Um grupo de astronautas atravessa um buraco de minhoca em busca de um novo lar para a humanidade.', '2014', 4.80, 169, 'Ficção científica'),
(2, 'O Poderoso Chefão', 'O patriarca de uma família mafiosa passa o comando dos negócios ao filho mais novo.', '1972', 4.90, 175, 'Drama'),
(3, 'Cidade de Deus', 'Dois jovens crescem em uma favela do Rio de Janeiro e seguem caminhos opostos.', '2002', 4.70, 130, 'Drama'),
(4, 'Toy Story', 'Os brinquedos de um garoto ganham vida quando ninguém está olhando.', '1995', 4.50, 81, 'Animação'),
(5, 'Vingadores: Ultimato', 'Os heróis restantes se unem para desfazer as ações de Thanos.', '2019', 4.40, 181, 'Ação'),
(6, 'Corra!', 'Um jovem visita a família da namorada e descobre segredos perturbadores.', '2017', 4.20, 104, 'Terror'),
(7, 'A Origem', 'Um ladrão que rouba segredos através dos sonhos recebe a missão de plantar uma ideia na mente de um CEO.', '2010', 4.80, 148, 'Ficção científica'),
(8, 'O Senhor dos Anéis: A Sociedade do Anel', 'Um jovem hobbit herda um anel mágico e parte numa jornada épica para destruí-lo.', '2001', 4.90, 178, 'Fantasia'),
(9, 'Divertida Mente', 'As emoções dentro da mente de uma menina trabalham juntas para ajudá-la a adaptar-se a uma nova cidade.', '2015', 4.60, 95, 'Animação'),
(10, 'Interestelar', 'Um grupo de astronautas atravessa um buraco de minhoca em busca de um novo lar para a humanidade.', '2014', 4.80, 169, 'Ficção científica'),
(11, 'Homem-Aranha: No Aranhaverso', 'Um jovem torna-se o Homem-Aranha do seu universo e une-se a heróis de outras dimensões.', '2018', 4.70, 117, 'Animação');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'admin', 'admin@email.com', '123456'),
(2, 'Maria Silva', 'maria@email.com', 'senha123'),
(3, 'João Souza', 'joao@email.com', 'abc456'),
(4, 'Ana Costa', 'ana@email.com', 'xyz789');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `filme_id` (`filme_id`);

--
-- Índices de tabela `filmes`
--
ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `filmes`
--
ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
