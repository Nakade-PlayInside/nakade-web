-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Feb 2013 um 15:33
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
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(20) NOT NULL COMMENT 'first name',
  `nachname` varchar(30) NOT NULL COMMENT 'family name',
  `nick` varchar(10) DEFAULT NULL COMMENT 'nick name',
  `anonym` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'flag: name=nick ',
  `titel` varchar(10) DEFAULT NULL COMMENT 'title: dr, graf, sir',
  `sex` char(1) DEFAULT NULL COMMENT 'sex (m | f)',
  `geburtsdatum` date DEFAULT NULL COMMENT 'birthday',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='registered users ' AUTO_INCREMENT=9 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`uid`, `vorname`, `nachname`, `nick`, `titel`, `sex`, `geburtsdatum`) VALUES
(1, 'Holger', 'Maerz', 'Mumm', 'Dr.', 'm', '1965-06-03'),
(3, 'Martina', 'Maerz', 'Tina', NULL, 'f', '1969-01-04'),
(5, 'Maurice', 'Wohabi', 'Mo', NULL, 'm', NULL),
(6, 'Robert', 'Deutschmann', NULL, NULL, 'm', NULL),
(7, 'Pandau', 'Ting', NULL, NULL, 'm', NULL),
(8, 'Marco', 'Dellermann', NULL, NULL, 'm', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;