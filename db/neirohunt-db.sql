-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 27 2024 г., 17:50
-- Версия сервера: 10.8.4-MariaDB
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `neirohunt-db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `email_suggests`
--

CREATE TABLE `email_suggests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggest_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `gluewords`
--

CREATE TABLE `gluewords` (
  `id` int(11) NOT NULL,
  `word` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regex` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suspicion` int(11) NOT NULL DEFAULT 100,
  `sample` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `gluewords`
--

INSERT INTO `gluewords` (`id`, `word`, `regex`, `suspicion`, `sample`) VALUES
(1, 'let', 'позволя[ею]?ть?', 25, 'позволять / позволяют'),
(2, 'very_important', 'особенно важно', 90, 'особенно важно'),
(5, 'thus', 'таким образом, ', 100, 'Таким образом, '),
(6, 'also', 'также', 5, 'также'),
(7, 'playrole', ' игра[^\\.]*роль', 100, 'играет важную роль / играют ключевую роль'),
(8, 'such', 'так[^ ]+ как', 35, 'таких как / такие как'),
(9, 'provide', ' обеспечи[^ ]*', 10, 'обеспечить / обеспечивает'),
(10, 'one_of', ' од[инаойму]* из ', 70, 'один из / одной из'),
(11, 'wideuse', 'широко прим[^ ]*|широко исп[^ ]*', 85, 'широко применяются | широко используется'),
(12, 'with_dev_tech', 'С .*развитием технологий', 100, 'С развитием технологий'),
(13, 'include', ' включа[^ ]* в себя', 85, 'Включая в себя / включают в себя'),
(16, 'key', ' ключев[^ ]*', 75, 'ключевой / ключевым'),
(17, 'is', ' явля[^ ]*', 15, 'является / являться'),
(18, 'aspect', 'аспект[^ ]*', 85, 'аспектами / аспект'),
(19, 'have', 'облада[^ ]*', 35, 'обладают, обладает'),
(20, 'reply', 'отвеча[^ ]* за ', 45, 'отвечают за, отвечать за'),
(21, 'that', '[^тоил], что ', 85, '..., что...'),
(22, 'consist', 'состо[^ ]* из ', 15, 'состоит из, состоять из'),
(25, 'besides', 'кроме того, ', 70, 'Кроме того, ...'),
(26, 'important', 'особенно важн[^ ]', 30, 'Важно, важное'),
(27, 'equipped', 'оснащ[^ ]*н[^ ]?', 45, 'оснащены, оснащен'),
(28, 'present', 'представля[^ ]* собой', 70, 'Представляют собой'),
(29, 'can_be', 'могут [^ ]*ть', 60, 'могут вызывать'),
(30, 'identificate', 'выяв[^ ]*', 85, 'выявить, вывление'),
(32, 'lets_go', 'давайте', 100, 'давайте'),
(33, 'lets_check', 'ра[сз][^ ]*р[еи]м', 90, 'Разберём, рассмотрим'),
(34, 'every', 'кажд[^ ]* из них', 80, 'каждый из них, каждому из них'),
(35, 'exists', '\\nсуществу[^ ]*|\\. существу[^ ]*', 125, '... Существует.'),
(36, 'advs_and_limits', 'свои преимущества и ограничения', 150, 'свои преимущества и ограничения'),
(37, 'and_choose', ', и .*выбор .*зависит', 150, '..., и их выбор между ними зависит'),
(38, 'this_can_be', ' может быть', 20, ' может быть');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hasRemovedLimit` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `icon`, `hasRemovedLimit`) VALUES
(1, 'user', '', 0),
(2, 'admin', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nickname` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `cash` int(11) NOT NULL DEFAULT 0,
  `is_verified` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `pass`, `role`, `cash`, `is_verified`) VALUES
(20, 'bloodacote', '', '$2y$10$vpPGF/FeBeCPXgCe6KbLN.NMvPsctWZHGamC2LFZrbF3sQHJblftS', 'admin', 9999, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `email_suggests`
--
ALTER TABLE `email_suggests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gluewords`
--
ALTER TABLE `gluewords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`word`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `email_suggests`
--
ALTER TABLE `email_suggests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `gluewords`
--
ALTER TABLE `gluewords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
