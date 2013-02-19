-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2013 um 15:26
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
-- Tabellenstruktur für Tabelle `leagueSeason`
--

CREATE TABLE IF NOT EXISTS `leagueSeason` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(10) unsigned NOT NULL COMMENT 'einzigartige Saisonnummer',
  `title` varchar(20) DEFAULT NULL COMMENT 'Bezeichner, wie Winter',
  `abbr` varchar(3) DEFAULT NULL COMMENT 'Abkürzung',
  `active` tinyint(1) NOT NULL COMMENT 'Flag',
  `year` date NOT NULL COMMENT 'Jahr der Saison',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Saison der Ligen' AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `leagueSeason`
--

INSERT INTO `leagueSeason` (`sid`, `number`, `title`, `abbr`, `active`, `year`) VALUES
(1, 1, 'Sommer', 'SS', 1, '2013-02-01');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;