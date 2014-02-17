-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 26. Apr 2013 um 12:13
-- Server Version: 5.5.31
-- PHP-Version: 5.3.10-1ubuntu3.6

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
-- Tabellenstruktur für Tabelle `leagueParticipants`
--

CREATE TABLE IF NOT EXISTS `leagueParticipants` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT 'season ID',
  `lid` int(11) DEFAULT NULL COMMENT 'league ID',
  `uid` int(11) NOT NULL COMMENT 'user ID',
  PRIMARY KEY (`pid`),
  KEY `sid` (`sid`,`lid`,`uid`),
  KEY `lid` (`lid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Teilnehmer Tabelle' AUTO_INCREMENT=7;

--
-- Constraints der exportierten Tabellen
--
--
-- Daten für Tabelle `leagueParticipants`
--

INSERT INTO `leagueParticipants` (`pid`, `sid`, `lid`, `uid`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 3),
(3, 1, 1, 5),
(4, 1, 1, 6),
(5, 1, 1, 7),
(6, 1, 1, 8);

--
-- Constraints der Tabelle `leagueParticipants`
--
ALTER TABLE `leagueParticipants`
  ADD CONSTRAINT `leagueParticipants_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `leagueParticipants_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `leagueSeason` (`sid`),
  ADD CONSTRAINT `leagueParticipants_ibfk_2` FOREIGN KEY (`lid`) REFERENCES `leagueLigen` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;