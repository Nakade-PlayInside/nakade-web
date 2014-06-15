-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 18. Feb 2013 um 15:29
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
-- Tabellenstruktur für Tabelle `leaguePairings`
--

CREATE TABLE IF NOT EXISTS `leaguePairings` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'index',
  `lid` int(11) NOT NULL COMMENT 'League Id',
  `black` int(11) NOT NULL COMMENT 'black userId',
  `white` int(11) NOT NULL COMMENT 'white UserId',
  `resultId` int(11) DEFAULT NULL COMMENT 'result Id',
  `winner` int(11) DEFAULT NULL COMMENT 'winner User Id',
  `points` varchar(5) DEFAULT NULL COMMENT 'punkte zB 3.5',
  `date` datetime NOT NULL COMMENT 'Datum und Uhrzeit',
  PRIMARY KEY (`id`),
  KEY `lid` (`lid`),
  KEY `black` (`black`),
  KEY `white` (`white`),
  KEY `resultId` (`resultId`),
  KEY `winner` (`winner`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Paarungen, Ergebnisse, Datum' AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `leaguePairings`
--

INSERT INTO `leaguePairings` (`id`, `lid`, `black`, `white`, `resultId`, `winner`, `points`, `date`) VALUES
(1, 1, 5, 1, 2, 5, '16.5', '2013-02-05 18:00:00'),
(2, 1, 3, 8, 1, 8, '40', '2013-02-12 18:00:00'),
(3, 1, 8, 1, NULL, NULL, NULL, '2013-02-19 18:00:00'),
(4, 1, 7, 6, NULL, NULL, NULL, '2013-02-26 18:00:00'),
(5, 1, 3, 5, NULL, NULL, NULL, '2013-03-05 18:00:00'),
(6, 1, 6, 1, NULL, NULL, NULL, '2013-03-12 18:00:00'),
(7, 1, 8, 7, NULL, NULL, NULL, '2013-03-19 18:00:00'),
(8, 1, 3, 6, NULL, NULL, NULL, '2013-03-26 18:00:00'),
(9, 1, 3, 7, NULL, NULL, NULL, '2013-04-02 18:00:00'),
(10, 1, 8, 6, NULL, NULL, NULL, '2013-04-09 18:00:00'),
(11, 1, 3, 1, NULL, NULL, NULL, '2013-04-16 18:00:00'),
(12, 1, 6, 5, NULL, NULL, NULL, '2013-04-23 18:00:00'),
(13, 1, 3, 7, NULL, NULL, NULL, '2013-04-30 18:00:00'),
(14, 1, 5, 8, NULL, NULL, NULL, '2013-05-07 18:00:00'),
(15, 1, 7, 1, NULL, NULL, NULL, '2013-05-14 18:00:00');

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `leaguePairings`
--
ALTER TABLE `leaguePairings`
  ADD CONSTRAINT `leaguePairings_ibfk_5` FOREIGN KEY (`winner`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `leaguePairings_ibfk_1` FOREIGN KEY (`lid`) REFERENCES `leagueLigen` (`id`),
  ADD CONSTRAINT `leaguePairings_ibfk_2` FOREIGN KEY (`black`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `leaguePairings_ibfk_3` FOREIGN KEY (`white`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `leaguePairings_ibfk_4` FOREIGN KEY (`resultId`) REFERENCES `leagueResult` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;