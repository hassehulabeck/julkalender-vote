-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Tid vid skapande: 04 dec 2018 kl 16:45
-- Serverversion: 5.7.24-0ubuntu0.16.04.1
-- PHP-version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `julkalender`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `gnomeway`
--

CREATE TABLE `gnomeway` (
  `day` tinyint(4) NOT NULL,
  `gift` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `gnomeway`
--

INSERT INTO `gnomeway` (`day`, `gift`) VALUES
(1, 3),
(2, 3),
(3, 0),
(4, 1),
(5, 2),
(6, 0),
(7, 2),
(8, 1),
(9, 3),
(10, 2),
(11, 3),
(12, 1),
(13, 3),
(14, 1),
(15, 0),
(16, 2),
(17, 0),
(18, 0),
(19, 0),
(20, 3),
(21, 2),
(22, 2),
(23, 2),
(24, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `options`
--

CREATE TABLE `options` (
  `wishListNumber` tinyint(4) NOT NULL DEFAULT '0',
  `shortlistNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `options`
--

INSERT INTO `options` (`wishListNumber`, `shortlistNumber`) VALUES
(28, 1000);

-- --------------------------------------------------------

--
-- Tabellstruktur `selections`
--

CREATE TABLE `selections` (
  `day` tinyint(4) NOT NULL,
  `leftGift` tinyint(4) NOT NULL,
  `rightGift` tinyint(4) NOT NULL,
  `selectedGift` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `selections`
--

INSERT INTO `selections` (`day`, `leftGift`, `rightGift`, `selectedGift`) VALUES
(1, 2, 3, 3),
(2, 0, 3, 3),
(3, 0, 2, 0),
(4, 0, 1, 1),
(5, 0, 2, 2),
(6, 0, 3, 0),
(7, 4, 1, 4),
(8, 1, 2, 1),
(9, 1, 3, 3),
(10, 0, 2, 2),
(11, 4, 2, 4),
(12, 0, 1, 1),
(13, 3, 4, 3),
(14, 0, 1, 1),
(15, 4, 0, 0),
(16, 2, 1, 2),
(17, 0, 2, 0),
(18, 2, 0, 0),
(19, 0, 3, 0),
(20, 4, 3, 3),
(21, 1, 2, 2),
(22, 1, 2, 2),
(23, 2, 0, 2),
(24, 1, 3, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `namn` varchar(30) COLLATE utf8_swedish_ci DEFAULT NULL,
  `shortlists` varchar(1000) COLLATE utf8_swedish_ci NOT NULL,
  `wishlists` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `lastVisited` datetime NOT NULL,
  `lastVote` varchar(1) COLLATE utf8_swedish_ci NOT NULL,
  `points` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `votes`
--

CREATE TABLE `votes` (
  `day` tinyint(4) NOT NULL,
  `votesForLeft` bigint(20) NOT NULL,
  `votesForRight` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumpning av Data i tabell `votes`
--

INSERT INTO `votes` (`day`, `votesForLeft`, `votesForRight`) VALUES
(1, -1, 1),
(2, 0, 1),
(3, 0, 0),
(4, 1, 3),
(5, 3, 4),
(6, 2, 0),
(7, 3, 0),
(8, 3, 1),
(9, 3, 5),
(10, 2, 3),
(11, 2, 0),
(12, 2, 4),
(13, 4, 3),
(14, 2, 6),
(15, 1, 3),
(16, 4, 0),
(17, 3, 2),
(18, 0, 4),
(19, 5, 1),
(20, 1, 5),
(21, 0, 1),
(22, 2, 3),
(23, 4, 1),
(24, 0, 4);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `gnomeway`
--
ALTER TABLE `gnomeway`
  ADD PRIMARY KEY (`day`),
  ADD UNIQUE KEY `day` (`day`);

--
-- Index för tabell `selections`
--
ALTER TABLE `selections`
  ADD PRIMARY KEY (`day`),
  ADD UNIQUE KEY `day` (`day`);

--
-- Index för tabell `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- Index för tabell `votes`
--
ALTER TABLE `votes`
  ADD UNIQUE KEY `day_2` (`day`),
  ADD UNIQUE KEY `day_3` (`day`),
  ADD KEY `day` (`day`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=852;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
