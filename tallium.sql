-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 20 2016 г., 09:12
-- Версия сервера: 5.7.13
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tallium`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(10) unsigned NOT NULL,
  `sector` varchar(5) NOT NULL,
  `row` int(10) unsigned NOT NULL,
  `seat` int(10) unsigned NOT NULL,
  `ip` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `sector`, `row`, `seat`, `ip`) VALUES
(1, 'A', 10, 10, '127.0.0.1'),
(2, 'A', 10, 11, '127.0.0.1'),
(3, 'A', 12, 9, '185.200.105.224'),
(4, 'A', 12, 10, '185.200.105.224'),
(5, 'A', 12, 11, '185.200.105.224'),
(6, 'A', 12, 12, '185.200.105.224'),
(7, 'B', 5, 4, '185.200.105.224'),
(8, 'B', 5, 7, '185.200.105.224'),
(9, 'B', 6, 5, '185.200.105.224'),
(10, 'B', 6, 6, '185.200.105.224'),
(11, 'B', 7, 6, '185.200.105.224'),
(12, 'B', 7, 5, '185.200.105.224'),
(13, 'B', 8, 7, '185.200.105.224'),
(14, 'B', 8, 4, '185.200.105.224');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
