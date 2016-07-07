-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2016 at 09:42 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nhl94db`
--

-- --------------------------------------------------------

--
-- Table structure for table `nhlteam`
--

CREATE TABLE `nhlteam` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Team_ID` int(11) NOT NULL,
  `ABV` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nhlteam`
--

INSERT INTO `nhlteam` (`ID`, `Name`, `Team_ID`, `ABV`) VALUES
(1, 'Boston Bruins', 2, 'BOS'),
(2, 'Quebec Nordiques', 19, 'QUE'),
(3, 'Montreal Canadiens', 12, 'MTL'),
(4, 'Buffalo Sabres', 3, 'BUF'),
(5, 'Hartford Whalers', 10, 'HFD'),
(6, 'Ottawa Senators', 16, 'OTW'),
(7, 'Pittsburgh Penguins', 18, 'PIT'),
(8, 'Washington Capitals', 25, 'WSH'),
(9, 'New York Islanders', 14, 'NYI'),
(10, 'New Jersey Devils', 13, 'NJ'),
(11, 'Philadelphia Flyers', 17, 'PHI'),
(12, 'New York Rangers', 15, 'NYR'),
(13, 'Chicago Blackhawks', 5, 'CHI'),
(14, 'Detroit Red Wings', 7, 'DET'),
(15, 'Toronto Maple Leafs', 23, 'TOR'),
(16, 'St. Louis Blues', 21, 'STL'),
(17, 'Dallas Stars', 6, 'DAL'),
(18, 'Tampa Bay Lightning', 22, 'TB'),
(19, 'Vancouver Canucks', 24, 'VAN'),
(20, 'Calgary Flames', 4, 'CGY'),
(21, 'Los Angeles Kings', 11, 'LA'),
(22, 'Winnipeg Jets', 26, 'WPG'),
(23, 'Edmonton Oilers', 8, 'EDM'),
(24, 'San Jose Sharks', 20, 'SJ'),
(25, 'Anaheim Mighty Ducks', 1, 'ANH'),
(26, 'Florida Panthers', 9, 'FLA'),
(27, 'All Stars East', 27, 'ASE'),
(28, 'All Stars West', 28, 'ASW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nhlteam`
--
ALTER TABLE `nhlteam`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nhlteam`
--
ALTER TABLE `nhlteam`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
