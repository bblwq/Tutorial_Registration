-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2018 at 03:19 AM
-- Server version: 10.1.23-MariaDB-9+deb9u1
-- PHP Version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bblwq`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocation`
--

CREATE TABLE `allocation` (
  `emailAddress` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sessionID` int(11) DEFAULT NULL,
  `question` int(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `sessionID` int(11) NOT NULL,
  `day` varchar(50) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `capability` int(11) DEFAULT NULL,
  `remainPlace` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`sessionID`, `day`, `time`, `capability`, `remainPlace`) VALUES
(11, 'Monday', '9:00-10:00', 2, 2),
(12, 'Monday', '11:00-12:00', 2, 2),
(21, 'Tuesday', '9:00-10:00', 2, 2),
(22, 'Tuesday', '14:00-15:00', 2, 2),
(31, 'Wednesday', '9:00-10:00', 2, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocation`
--
ALTER TABLE `allocation`
  ADD PRIMARY KEY (`emailAddress`),
  ADD KEY `sessionID` (`sessionID`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`sessionID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
