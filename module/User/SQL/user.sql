-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Jun 2013 um 18:52
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
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(20) NOT NULL COMMENT 'first name',
  `nachname` varchar(30) NOT NULL COMMENT 'family name',
  `nick` varchar(20) DEFAULT NULL COMMENT 'nick name',
  `anonym` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'flag: name=nick',
  `titel` varchar(10) DEFAULT NULL COMMENT 'title: dr, graf, sir',
  `sex` char(1) DEFAULT NULL COMMENT 'sex (m | f)',
  `geburtsdatum` date DEFAULT NULL COMMENT 'birthday',
  `username` varchar(50) NOT NULL COMMENT 'credential',
  `password` varchar(32) NOT NULL,
  `role` varchar(15) DEFAULT NULL,
  `email` varchar(120) NOT NULL,
  `verifyString` varchar(16) NOT NULL,
  `verified` tinyint(3) unsigned NOT NULL COMMENT 'flag (1|0)',
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'flag (1|0)',
  `created` datetime DEFAULT NULL,
  `due` datetime DEFAULT NULL COMMENT 'due date for expiring verification',
  `edit` datetime DEFAULT NULL COMMENT 'last profile editing date',
  `pwdChange` datetime DEFAULT NULL COMMENT 'last password editing date',
  `firstLogin` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='registered users ' AUTO_INCREMENT=9 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;