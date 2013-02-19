-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2013 um 15:30
-- Server Version: 5.5.29
-- PHP-Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `nakade`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leaguePositions`
--

CREATE TABLE IF NOT EXISTS `leaguePositions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `lid` int(11) NOT NULL COMMENT 'Liga Id',
  `uid` int(11) NOT NULL COMMENT 'User Id',
  `GP` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'games played',
  `W` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'wins',
  `L` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'losses',
  `J` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'jigo',
  `GS` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'games suspended',
  `Tiebreaker_1` decimal(3,1) unsigned NOT NULL DEFAULT '0.0' COMMENT 'primary tiebreak condition',
  `Tiebreaker_2` decimal(3,1) unsigned NOT NULL DEFAULT '0.0' COMMENT 'second tiebreak condition',
  PRIMARY KEY (`id`),
  KEY `lid` (`lid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabellenstand und Ergebnisübersicht' AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `leaguePositions`
--

INSERT INTO `leaguePositions` (`id`, `lid`, `uid`, `GP`, `W`, `L`, `J`, `GS`, `Tiebreaker_1`, `Tiebreaker_2`) VALUES
(1, 1, 1, 1, 0, 1, 0, 0, 3.5, 0.0),
(2, 1, 3, 1, 0, 1, 0, 0, 0.0, 0.0),
(3, 1, 5, 1, 1, 0, 0, 0, 16.5, 0.0),
(4, 1, 8, 1, 1, 0, 0, 0, 40.0, 0.0),
(5, 1, 6, 0, 0, 0, 0, 0, 0.0, 0.0),
(6, 1, 7, 0, 0, 0, 0, 0, 0.0, 0.0);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `leaguePositions`
--
ALTER TABLE `leaguePositions`
  ADD CONSTRAINT `leaguePositions_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `leaguePositions_ibfk_1` FOREIGN KEY (`lid`) REFERENCES `leagueLigen` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;