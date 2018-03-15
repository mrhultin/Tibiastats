-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Tid vid skapande: 21 jun 2016 kl 12:01
-- Serverversion: 5.7.12-0ubuntu1
-- PHP-version: 7.0.4-7ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `Tibiascanner`
--
CREATE DATABASE IF NOT EXISTS `Tibiascanner` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Tibiascanner`;

-- --------------------------------------------------------

--
-- Tabellstruktur `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `cronlog`
--

DROP TABLE IF EXISTS `cronlog`;
CREATE TABLE `cronlog` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `experiencehistory`
--

DROP TABLE IF EXISTS `experiencehistory`;
CREATE TABLE `experiencehistory` (
  `id` int(11) NOT NULL,
  `characterid` int(32) NOT NULL,
  `date` int(11) NOT NULL,
  `experience` bigint(32) NOT NULL,
  `worldid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `experiencechange` bigint(20) NOT NULL,
  `rankchange` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `post` longtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `vocation` int(11) DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '0',
  `sex` int(11) NOT NULL DEFAULT '0',
  `worldid` int(11) NOT NULL DEFAULT '0',
  `deleted` int(11) NOT NULL DEFAULT '0',
  `magic` int(11) NOT NULL DEFAULT '0',
  `magicrank` int(11) NOT NULL DEFAULT '0',
  `sword` int(11) NOT NULL DEFAULT '0',
  `swordrank` int(11) NOT NULL DEFAULT '0',
  `distance` int(11) NOT NULL DEFAULT '0',
  `distancerank` int(11) NOT NULL DEFAULT '0',
  `axe` int(11) NOT NULL DEFAULT '0',
  `axerank` int(11) NOT NULL DEFAULT '0',
  `club` int(11) NOT NULL DEFAULT '0',
  `clubrank` int(11) NOT NULL DEFAULT '0',
  `shielding` int(11) NOT NULL DEFAULT '0',
  `shieldingrank` int(11) NOT NULL DEFAULT '0',
  `profileupdated` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `players_deleted`
--

DROP TABLE IF EXISTS `players_deleted`;
CREATE TABLE `players_deleted` (
  `id` int(11) NOT NULL,
  `charid` int(11) NOT NULL,
  `deleteddate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `player_deaths`
--

DROP TABLE IF EXISTS `player_deaths`;
CREATE TABLE `player_deaths` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `reason` text NOT NULL,
  `level` int(11) NOT NULL,
  `charid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellstruktur `worlds`
--

DROP TABLE IF EXISTS `worlds`;
CREATE TABLE `worlds` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `updated` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `expupdated` int(32) DEFAULT NULL,
  `location` varchar(64) DEFAULT NULL,
  `magicupdated` int(11) DEFAULT NULL,
  `swordupdated` int(11) DEFAULT NULL,
  `distanceupdated` int(11) DEFAULT NULL,
  `axeupdated` int(11) DEFAULT NULL,
  `clubupdated` int(11) DEFAULT NULL,
  `shieldingupdated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `cronlog`
--
ALTER TABLE `cronlog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index för tabell `experiencehistory`
--
ALTER TABLE `experiencehistory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_3` (`id`);

--
-- Index för tabell `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index för tabell `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `players_deleted`
--
ALTER TABLE `players_deleted`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `player_deaths`
--
ALTER TABLE `player_deaths`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index för tabell `worlds`
--
ALTER TABLE `worlds`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT för tabell `cronlog`
--
ALTER TABLE `cronlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4936;
--
-- AUTO_INCREMENT för tabell `experiencehistory`
--
ALTER TABLE `experiencehistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=663384;
--
-- AUTO_INCREMENT för tabell `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT för tabell `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97714;
--
-- AUTO_INCREMENT för tabell `players_deleted`
--
ALTER TABLE `players_deleted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1195;
--
-- AUTO_INCREMENT för tabell `player_deaths`
--
ALTER TABLE `player_deaths`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=480755;
--
-- AUTO_INCREMENT för tabell `worlds`
--
ALTER TABLE `worlds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
