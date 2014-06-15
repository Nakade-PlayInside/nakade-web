-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2013 um 15:24
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
-- Tabellenstruktur für Tabelle `leagueLigen`
--

CREATE TABLE IF NOT EXISTS `leagueLigen` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'League ID',
  `sid` int(11) NOT NULL COMMENT 'Season ID',
  `order` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Ordnungszahl, zB 1',
  `division` varchar(15) DEFAULT NULL COMMENT 'optionale Teilung der Liga',
  `title` varchar(15) DEFAULT NULL COMMENT 'Liganame, zB Top-Liga',
  `ruleId` int(10) unsigned DEFAULT NULL COMMENT 'Ruleset ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Alle Ligen aller Saisons' AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `leagueLigen`
--

INSERT INTO `leagueLigen` (`id`, `sid`, `order`, `division`, `title`, `ruleId`) VALUES
(1, 1, 1, NULL, 'Top-Liga', NULL);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `leagueLigen`
--
ALTER TABLE `leagueLigen`
  ADD CONSTRAINT `leagueLigen_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `leagueSeason` (`sid`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;