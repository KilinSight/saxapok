-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 02 2018 г., 17:00
-- Версия сервера: 5.5.53
-- Версия PHP: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `AGUProject`
--

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `region_code` int(11) NOT NULL,
  `title` text NOT NULL,
  `creation_date` int(11) NOT NULL,
  `ogrn` text NOT NULL,
  `inn` text NOT NULL,
  `kpp` text NOT NULL,
  `adress_code` text NOT NULL,
  `rate_company` text NOT NULL,
  `business_size` text NOT NULL,
  `date_modify` text NOT NULL,
  `deleted` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `region_code` text NOT NULL,
  `name` text NOT NULL,
  `home` double NOT NULL,
  `economic_growth` double NOT NULL,
  `kindergarten_count` int(11) NOT NULL,
  `avg_salary` double NOT NULL,
  `price_level` double NOT NULL,
  `unemployment_level` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stat_citizens`
--

CREATE TABLE `stat_citizens` (
  `id` int(11) NOT NULL,
  `region_code` int(11) NOT NULL,
  `name` text NOT NULL,
  `resume_count` int(11) NOT NULL,
  `currency` text NOT NULL,
  `avg_salary` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stat_company`
--

CREATE TABLE `stat_company` (
  `id` int(11) NOT NULL,
  `region_code` int(11) NOT NULL,
  `name` text NOT NULL,
  `all_count` int(11) NOT NULL,
  `micro50_count` int(11) NOT NULL,
  `small100_count` int(11) NOT NULL,
  `middle250_count` int(11) NOT NULL,
  `big500_count` int(11) NOT NULL,
  `large_over500_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `vacancy`
--

CREATE TABLE `vacancy` (
  `id` int(11) NOT NULL,
  `region` int(11) NOT NULL,
  `organization` text NOT NULL,
  `industry` text NOT NULL,
  `profession` text NOT NULL,
  `creationDate` date NOT NULL,
  `datePosted` datetime NOT NULL,
  `identifier` text NOT NULL,
  `hiringOrganization` text NOT NULL,
  `baseSalary` double NOT NULL,
  `title` text NOT NULL,
  `employmentType` text NOT NULL,
  `workHours` text NOT NULL,
  `responsibilities` text NOT NULL,
  `incentiveCompensation` text NOT NULL,
  `requirements` text NOT NULL,
  `socialProtecteds` text NOT NULL,
  `metroStations` text NOT NULL,
  `source` text NOT NULL,
  `workPlaces` int(11) NOT NULL,
  `additionalInfo` text NOT NULL,
  `deleted` text NOT NULL,
  `vacUrl` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stat_citizens`
--
ALTER TABLE `stat_citizens`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stat_company`
--
ALTER TABLE `stat_company`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `vacancy`
--
ALTER TABLE `vacancy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region` (`region`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `stat_citizens`
--
ALTER TABLE `stat_citizens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `stat_company`
--
ALTER TABLE `stat_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `vacancy`
--
ALTER TABLE `vacancy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `vacancy`
--
ALTER TABLE `vacancy`
  ADD CONSTRAINT `vacancy_ibfk_1` FOREIGN KEY (`region`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
ALTER TABLE `organizations` ADD  FOREIGN KEY (`region_code`) REFERENCES `regions`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `stat_citizens` ADD  FOREIGN KEY (`region_code`) REFERENCES `regions`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `stat_company` ADD  FOREIGN KEY (`region_code`) REFERENCES `regions`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
