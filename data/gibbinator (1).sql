-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Jan 2019 um 09:04
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gibbinator`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abteilung`
--

CREATE TABLE `abteilung` (
  `id` int(10) NOT NULL,
  `bezeichnung` varchar(255) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dates`
--

CREATE TABLE `dates` (
  `id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `all_day` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebaeude`
--

CREATE TABLE `gebaeude` (
  `id` int(10) NOT NULL,
  `bezeichnung` varchar(255) CHARACTER SET latin1 NOT NULL,
  `strasse` varchar(150) CHARACTER SET latin1 NOT NULL,
  `nr` varchar(10) CHARACTER SET latin1 NOT NULL,
  `plz` int(6) NOT NULL,
  `ort` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Daten für Tabelle `gebaeude`
--

INSERT INTO `gebaeude` (`id`, `bezeichnung`, `strasse`, `nr`, `plz`, `ort`) VALUES
(1, 'IET', 'Teststrasse', '10', 3000, 'Bern');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klasse`
--

CREATE TABLE `klasse` (
  `id` int(10) NOT NULL,
  `name` varchar(150) COLLATE latin1_general_cs NOT NULL,
  `klassen_lp` int(10) NOT NULL,
  `abteilungs_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klasse_nachricht`
--

CREATE TABLE `klasse_nachricht` (
  `nachrichten_ID` int(10) NOT NULL,
  `klassen_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lektion`
--

CREATE TABLE `lektion` (
  `id` int(10) NOT NULL,
  `klassen_ID` int(10) NOT NULL,
  `lehrer_ID` int(10) NOT NULL,
  `titel` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `zimmer` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lektion_dates`
--

CREATE TABLE `lektion_dates` (
  `id` int(10) NOT NULL,
  `lektions_ID` int(10) NOT NULL,
  `dates_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lektion_nachricht`
--

CREATE TABLE `lektion_nachricht` (
  `nachrichten_ID` int(10) NOT NULL,
  `lektion_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachricht`
--

CREATE TABLE `nachricht` (
  `id` int(10) NOT NULL,
  `titel` varchar(100) COLLATE latin1_general_cs NOT NULL,
  `text` varchar(255) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stockwerk`
--

CREATE TABLE `stockwerk` (
  `id` int(10) NOT NULL,
  `gebaeude_ID` int(10) NOT NULL,
  `bezeichnung` varchar(150) COLLATE latin1_general_cs NOT NULL,
  `nummer` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Daten für Tabelle `stockwerk`
--

INSERT INTO `stockwerk` (`id`, `gebaeude_ID`, `bezeichnung`, `nummer`) VALUES
(1, 1, 'Erster Stock', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stockwerk_zimmer`
--

CREATE TABLE `stockwerk_zimmer` (
  `id` int(10) NOT NULL,
  `stockwerk_ID` int(10) NOT NULL,
  `zimmer_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `vorname` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `nachname` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `email` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `password` varchar(255) COLLATE latin1_general_cs NOT NULL,
  `user_type` int(10) NOT NULL,
  `initial_pw` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `vorname`, `nachname`, `email`, `password`, `user_type`, `initial_pw`) VALUES
(1, 'Dimitri', 'Waber', 'dimitri.waber@gmail.com', 'gibbiX12345', 1, 1),
(4, 'Max', 'Muster', 'max@muster.ch', '$2y$10$JAstOekDYmoVP3iVa0Hta.NgwqrV9.6znGrPpbNzJ7ST6VBzr0caO', 2, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usertype`
--

CREATE TABLE `usertype` (
  `id` int(10) NOT NULL,
  `bezeichnung` varchar(100) COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Daten für Tabelle `usertype`
--

INSERT INTO `usertype` (`id`, `bezeichnung`) VALUES
(1, 'Admin'),
(2, 'Lehrperson'),
(3, 'Lernende');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_klasse`
--

CREATE TABLE `user_klasse` (
  `id` int(10) NOT NULL,
  `user_ID` int(10) NOT NULL,
  `klassen_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zimmer`
--

CREATE TABLE `zimmer` (
  `id` int(10) NOT NULL,
  `bezeichnung` varchar(150) COLLATE latin1_general_cs NOT NULL,
  `optional_text` text COLLATE latin1_general_cs
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_cs;

--
-- Daten für Tabelle `zimmer`
--

INSERT INTO `zimmer` (`id`, `bezeichnung`, `optional_text`) VALUES
(1, 'IET110', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `abteilung`
--
ALTER TABLE `abteilung`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `gebaeude`
--
ALTER TABLE `gebaeude`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `klasse`
--
ALTER TABLE `klasse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klassen_lp` (`klassen_lp`),
  ADD KEY `abteilungs_ID` (`abteilungs_ID`);

--
-- Indizes für die Tabelle `klasse_nachricht`
--
ALTER TABLE `klasse_nachricht`
  ADD PRIMARY KEY (`nachrichten_ID`,`klassen_ID`),
  ADD KEY `klasse_nachricht_ibfk_1` (`klassen_ID`);

--
-- Indizes für die Tabelle `lektion`
--
ALTER TABLE `lektion`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `zimmer` (`zimmer`),
  ADD KEY `klassen_ID` (`klassen_ID`),
  ADD KEY `lehrer_ID` (`lehrer_ID`);

--
-- Indizes für die Tabelle `lektion_dates`
--
ALTER TABLE `lektion_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lektions_ID` (`lektions_ID`),
  ADD KEY `dates_ID` (`dates_ID`);

--
-- Indizes für die Tabelle `lektion_nachricht`
--
ALTER TABLE `lektion_nachricht`
  ADD PRIMARY KEY (`nachrichten_ID`,`lektion_ID`) USING BTREE,
  ADD KEY `lektion_nachricht_ibfk_2` (`lektion_ID`);

--
-- Indizes für die Tabelle `nachricht`
--
ALTER TABLE `nachricht`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `stockwerk`
--
ALTER TABLE `stockwerk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gebaeude_ID` (`gebaeude_ID`);

--
-- Indizes für die Tabelle `stockwerk_zimmer`
--
ALTER TABLE `stockwerk_zimmer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stockwerk_ID` (`stockwerk_ID`),
  ADD KEY `zimmer_ID` (`zimmer_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userType` (`user_type`);

--
-- Indizes für die Tabelle `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user_klasse`
--
ALTER TABLE `user_klasse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `klassen_ID` (`klassen_ID`);

--
-- Indizes für die Tabelle `zimmer`
--
ALTER TABLE `zimmer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `abteilung`
--
ALTER TABLE `abteilung`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dates`
--
ALTER TABLE `dates`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `gebaeude`
--
ALTER TABLE `gebaeude`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `klasse`
--
ALTER TABLE `klasse`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `lektion`
--
ALTER TABLE `lektion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `lektion_dates`
--
ALTER TABLE `lektion_dates`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `nachricht`
--
ALTER TABLE `nachricht`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `stockwerk`
--
ALTER TABLE `stockwerk`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `stockwerk_zimmer`
--
ALTER TABLE `stockwerk_zimmer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `usertype`
--
ALTER TABLE `usertype`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `user_klasse`
--
ALTER TABLE `user_klasse`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `zimmer`
--
ALTER TABLE `zimmer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `klasse`
--
ALTER TABLE `klasse`
  ADD CONSTRAINT `klasse_ibfk_1` FOREIGN KEY (`klassen_lp`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `klasse_ibfk_2` FOREIGN KEY (`abteilungs_ID`) REFERENCES `abteilung` (`ID`);

--
-- Constraints der Tabelle `klasse_nachricht`
--
ALTER TABLE `klasse_nachricht`
  ADD CONSTRAINT `klasse_nachricht_ibfk_1` FOREIGN KEY (`klassen_ID`) REFERENCES `klasse` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klasse_nachricht_ibfk_2` FOREIGN KEY (`nachrichten_ID`) REFERENCES `nachricht` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lektion`
--
ALTER TABLE `lektion`
  ADD CONSTRAINT `lektion_ibfk_1` FOREIGN KEY (`klassen_ID`) REFERENCES `klasse` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lektion_ibfk_2` FOREIGN KEY (`lehrer_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `lektion_ibfk_3` FOREIGN KEY (`zimmer`) REFERENCES `zimmer` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `lektion_dates`
--
ALTER TABLE `lektion_dates`
  ADD CONSTRAINT `lektion_dates_ibfk_1` FOREIGN KEY (`lektions_ID`) REFERENCES `lektion` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lektion_dates_ibfk_2` FOREIGN KEY (`dates_ID`) REFERENCES `dates` (`ID`);

--
-- Constraints der Tabelle `lektion_nachricht`
--
ALTER TABLE `lektion_nachricht`
  ADD CONSTRAINT `lektion_nachricht_ibfk_1` FOREIGN KEY (`nachrichten_ID`) REFERENCES `nachricht` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lektion_nachricht_ibfk_2` FOREIGN KEY (`lektion_ID`) REFERENCES `lektion` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stockwerk`
--
ALTER TABLE `stockwerk`
  ADD CONSTRAINT `stockwerk_ibfk_1` FOREIGN KEY (`gebaeude_ID`) REFERENCES `gebaeude` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stockwerk_zimmer`
--
ALTER TABLE `stockwerk_zimmer`
  ADD CONSTRAINT `stockwerk_zimmer_ibfk_1` FOREIGN KEY (`stockwerk_ID`) REFERENCES `stockwerk` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stockwerk_zimmer_ibfk_2` FOREIGN KEY (`zimmer_ID`) REFERENCES `zimmer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_type`) REFERENCES `usertype` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints der Tabelle `user_klasse`
--
ALTER TABLE `user_klasse`
  ADD CONSTRAINT `user_klasse_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_klasse_ibfk_2` FOREIGN KEY (`klassen_ID`) REFERENCES `klasse` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
