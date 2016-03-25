-- phpMyAdmin SQL Dump
-- version 4.4.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.01
-- Время создания: Мар 25 2016 г., 19:56
-- Версия сервера: 5.5.47-0ubuntu0.14.04.1
-- Версия PHP: 5.6.19-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `tz6`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Banners`
--

CREATE TABLE `Banners` (
  `id` int(11) NOT NULL,
  `Campaigns_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `Campaigns`
--

CREATE TABLE `Campaigns` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `Phrases`
--

CREATE TABLE `Phrases` (
  `id` bigint(20) NOT NULL,
  `Banners_id` int(11) NOT NULL,
  `phrase` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Banners`
--
ALTER TABLE `Banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Campaigns_id` (`Campaigns_id`);

--
-- Индексы таблицы `Campaigns`
--
ALTER TABLE `Campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Phrases`
--
ALTER TABLE `Phrases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Banners_id` (`Banners_id`);

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Banners`
--
ALTER TABLE `Banners`
  ADD CONSTRAINT `fk_camp` FOREIGN KEY (`Campaigns_id`) REFERENCES `Campaigns` (`id`);

--
-- Ограничения внешнего ключа таблицы `Phrases`
--
ALTER TABLE `Phrases`
  ADD CONSTRAINT `fk_banners` FOREIGN KEY (`Banners_id`) REFERENCES `Banners` (`id`);

