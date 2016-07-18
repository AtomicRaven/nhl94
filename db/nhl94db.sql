-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2016 at 12:22 AM
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
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `ID` int(11) NOT NULL,
  `H_Team_ID` int(11) NOT NULL,
  `A_Team_ID` int(11) NOT NULL,
  `H_Score` int(11) NOT NULL,
  `A_Score` int(11) NOT NULL,
  `OT` int(11) NOT NULL,
  `Confirm_Time` date NOT NULL,
  `Game_ID` int(11) NOT NULL,
  `Series_ID` int(11) NOT NULL,
  `H_User_ID` int(11) NOT NULL,
  `A_User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`ID`, `H_Team_ID`, `A_Team_ID`, `H_Score`, `A_Score`, `OT`, `Confirm_Time`, `Game_ID`, `Series_ID`, `H_User_ID`, `A_User_ID`) VALUES
(150, 10, 8, 3, 4, 0, '2016-07-18', 197, 20, 1, 2),
(151, 10, 8, 3, 4, 0, '2016-07-18', 198, 20, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
