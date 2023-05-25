-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Maj 2023, 18:34
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `example`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `client_info`
--

CREATE TABLE `client_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `post_code` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `order_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `client_info`
--

INSERT INTO `client_info` (`id`, `name`, `surname`, `city`, `post_code`, `address`, `order_number`) VALUES
(1, 'PersonA', 'PersonA', 'Example', '123-456', 'Example 3/1', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `crud`
--

CREATE TABLE `crud` (
  `id` int(11) NOT NULL,
  `order_number` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` double NOT NULL,
  `stock_level` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `crud`
--

INSERT INTO `crud` (`id`, `order_number`, `product_name`, `product_price`, `stock_level`, `description`) VALUES
(1, NULL, 'Product 1', 10, 2, 'Example 1'),
(2, NULL, 'Product2', 12, 0, 'Example A');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230524162954', '2023-05-24 18:30:01', 754);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'user@example.pl', '[]', '$2y$13$Q5dBwuh8YxsBBpoAIoxHRukHtYaMRP/WsKwKy/0SwAJtr97I0gTiK'),
(2, 'admin@example.pl', '[\"ROLE_ADMIN\"]', '$2y$13$grZ3eRATF2MD4HTvivE17.mx/L0MAVrqnYl3213wDNrVjudpDYPL2');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `client_info`
--
ALTER TABLE `client_info`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `crud`
--
ALTER TABLE `crud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4735133D551F0F81` (`order_number`);

--
-- Indeksy dla tabeli `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `client_info`
--
ALTER TABLE `client_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `crud`
--
ALTER TABLE `crud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `crud`
--
ALTER TABLE `crud`
  ADD CONSTRAINT `FK_4735133D551F0F81` FOREIGN KEY (`order_number`) REFERENCES `client_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
