-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2013 um 15:28
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
-- Tabellenstruktur für Tabelle `leagueResult`
--

CREATE TABLE IF NOT EXISTS `leagueResult` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `result` varchar(25) NOT NULL COMMENT 'Resultat in englisch',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Ergebnisbeschreibung' AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `leagueResult`
--

INSERT INTO `leagueResult` (`id`, `result`) VALUES
(1, 'Resignation'),
(2, 'Points'),
(3, 'Jigo'),
(4, 'Forfeit'),
(5, 'Suspended');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;