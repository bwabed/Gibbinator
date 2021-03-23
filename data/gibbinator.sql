-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Mrz 2021 um 16:25
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gibbinator`
--
CREATE DATABASE IF NOT EXISTS `gibbinator` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `gibbinator`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `date`
--

DROP TABLE IF EXISTS `date`;
CREATE TABLE IF NOT EXISTS `date` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `all_day` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fach`
--

DROP TABLE IF EXISTS `fach`;
CREATE TABLE IF NOT EXISTS `fach` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) NOT NULL,
  `klassen_id` int(10) NOT NULL,
  `lehrer_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `klassen_id` (`klassen_id`),
  KEY `lehrer_id` (`lehrer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebaeude`
--

DROP TABLE IF EXISTS `gebaeude`;
CREATE TABLE IF NOT EXISTS `gebaeude` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `strasse` varchar(255) NOT NULL,
  `nr` varchar(50) NOT NULL,
  `plz` int(11) NOT NULL,
  `ort` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klasse`
--

DROP TABLE IF EXISTS `klasse`;
CREATE TABLE IF NOT EXISTS `klasse` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `klassen_lp` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_klasse` (`klassen_lp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lektion`
--

DROP TABLE IF EXISTS `lektion`;
CREATE TABLE IF NOT EXISTS `lektion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `programm_themen` varchar(255) NOT NULL,
  `termine_aufgaben` varchar(255) NOT NULL,
  `date_id` int(10) NOT NULL,
  `zimmer` int(10) NOT NULL,
  `fach_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date_id` (`date_id`),
  KEY `zimmer` (`zimmer`),
  KEY `fach_id` (`fach_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachricht`
--

DROP TABLE IF EXISTS `nachricht`;
CREATE TABLE IF NOT EXISTS `nachricht` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `erstellt_am` date NOT NULL,
  `erfasser_id` int(10) NOT NULL,
  `klassen_id` int(10) DEFAULT NULL,
  `lektion_id` int(10) DEFAULT NULL,
  `fach_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `erfasser_id` (`erfasser_id`),
  KEY `klassen_id` (`klassen_id`),
  KEY `lektion_id` (`lektion_id`),
  KEY `fach_id` (`fach_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stockwerk`
--

DROP TABLE IF EXISTS `stockwerk`;
CREATE TABLE IF NOT EXISTS `stockwerk` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `nummer` int(10) NOT NULL,
  `gebaeude_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gebaeude_stockwerk` (`gebaeude_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `vorname` varchar(255) NOT NULL,
  `nachname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(10) NOT NULL,
  `initial_pw` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_type` (`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `vorname`, `nachname`, `email`, `password`, `user_type`, `initial_pw`) VALUES
(1, 'Admin', 'Admin', 'admin@gibbinator.ch', 'Welcome$20', 1, '1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usertype`
--

DROP TABLE IF EXISTS `usertype`;
CREATE TABLE IF NOT EXISTS `usertype` (
  `id` int(10) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `usertype`
--

INSERT INTO `usertype` (`id`, `bezeichnung`) VALUES
(1, 'Admin'),
(2, 'Lehrperson'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_klasse`
--

DROP TABLE IF EXISTS `user_klasse`;
CREATE TABLE IF NOT EXISTS `user_klasse` (
  `klassen_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`klassen_id`,`user_id`),
  KEY `fk_user_user_klasse` (`user_id`) USING BTREE,
  KEY `fk_klasse_user_klasse` (`klassen_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zimmer`
--

DROP TABLE IF EXISTS `zimmer`;
CREATE TABLE IF NOT EXISTS `zimmer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) NOT NULL,
  `optional_text` varchar(255) NOT NULL,
  `stockwerk_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stockwer_zimmer` (`stockwerk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `fach`
--
ALTER TABLE `fach`
  ADD CONSTRAINT `fk_fach_klasse` FOREIGN KEY (`klassen_id`) REFERENCES `klasse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fach_user` FOREIGN KEY (`lehrer_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klasse`
--
ALTER TABLE `klasse`
  ADD CONSTRAINT `fk_user_klasse` FOREIGN KEY (`klassen_lp`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lektion`
--
ALTER TABLE `lektion`
  ADD CONSTRAINT `fk_lektion_date` FOREIGN KEY (`date_id`) REFERENCES `date` (`id`),
  ADD CONSTRAINT `fk_lektion_fach` FOREIGN KEY (`fach_id`) REFERENCES `fach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lektion_zimmer` FOREIGN KEY (`zimmer`) REFERENCES `zimmer` (`id`);

--
-- Constraints der Tabelle `nachricht`
--
ALTER TABLE `nachricht`
  ADD CONSTRAINT `fk_nachricht_fach` FOREIGN KEY (`fach_id`) REFERENCES `fach` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nachricht_klasse` FOREIGN KEY (`klassen_id`) REFERENCES `klasse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nachricht_lektion` FOREIGN KEY (`lektion_id`) REFERENCES `lektion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nachricht_user` FOREIGN KEY (`erfasser_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stockwerk`
--
ALTER TABLE `stockwerk`
  ADD CONSTRAINT `fk_gebaeude_stockwerk` FOREIGN KEY (`gebaeude_id`) REFERENCES `gebaeude` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_usertype` FOREIGN KEY (`user_type`) REFERENCES `usertype` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user_klasse`
--
ALTER TABLE `user_klasse`
  ADD CONSTRAINT `fk_klasse_user_klasse` FOREIGN KEY (`klassen_id`) REFERENCES `klasse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_user_klasse` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zimmer`
--
ALTER TABLE `zimmer`
  ADD CONSTRAINT `fk_stockwer_zimmer` FOREIGN KEY (`stockwerk_id`) REFERENCES `stockwerk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
