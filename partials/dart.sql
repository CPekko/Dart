-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Vert: mysql.stud.ntnu.no
-- Generert den: 06. Sep, 2015 20:58 PM
-- Tjenerversjon: 5.5.44
-- PHP-Versjon: 5.3.10-1ubuntu3.19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eiriknf_dart`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Games`
--

CREATE TABLE IF NOT EXISTS `Games` (
  `GameID` int(11) NOT NULL AUTO_INCREMENT,
  `PlayerID` tinyint(4) NOT NULL,
  `Started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Finished` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Abandoned` tinyint(1) NOT NULL,
  PRIMARY KEY (`GameID`),
  KEY `Players` (`PlayerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- RELASJONER FOR TABELLEN `Games`:
--   `PlayerID`
--       `Players` -> `PlayerID`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Players`
--

CREATE TABLE IF NOT EXISTS `Players` (
  `PlayerID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Image` text,
  PRIMARY KEY (`PlayerID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `Throws`
--

CREATE TABLE IF NOT EXISTS `Throws` (
  `GameID` int(11) NOT NULL,
  `ThrowNumber` int(11) NOT NULL AUTO_INCREMENT,
  `DistanceFromTarget` tinyint(4) DEFAULT NULL,
  `Target` tinyint(4) NOT NULL,
  `Hit` tinyint(4) NOT NULL,
  `Streak` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ThrowNumber`),
  KEY `Game` (`GameID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=719 ;

--
-- RELASJONER FOR TABELLEN `Throws`:
--   `GameID`
--       `Games` -> `GameID`
--

--
-- Begrensninger for dumpede tabeller
--

--
-- Begrensninger for tabell `Games`
--
ALTER TABLE `Games`
  ADD CONSTRAINT `Games_ibfk_1` FOREIGN KEY (`PlayerID`) REFERENCES `Players` (`PlayerID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Begrensninger for tabell `Throws`
--
ALTER TABLE `Throws`
  ADD CONSTRAINT `Throws_ibfk_4` FOREIGN KEY (`GameID`) REFERENCES `Games` (`GameID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
