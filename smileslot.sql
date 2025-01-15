-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 04:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smileslot`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aemail`, `apassword`, `profile_pic`) VALUES
('admin@edoc.com', '123', '../uploads/profile_6746b5b064b162.32155524.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appoid` int(11) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `apponum` int(3) DEFAULT NULL,
  `scheduleid` int(10) DEFAULT NULL,
  `appodate` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appoid`, `pid`, `apponum`, `scheduleid`, `appodate`) VALUES
(1, 1, 1, 1, '2022-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `ID` int(11) NOT NULL,
  `pid` int(50) NOT NULL,
  `PATIENTNAME` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `PHONE` varchar(250) NOT NULL,
  `EMAIL` varchar(250) NOT NULL,
  `did` int(10) DEFAULT NULL,
  `dname` text DEFAULT NULL,
  `dscrptn` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `DATE` date NOT NULL,
  `time_slot` varchar(15) NOT NULL,
  `reminder_sent` tinyint(1) DEFAULT 0,
  `urgent_reminder_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`ID`, `pid`, `PATIENTNAME`, `PHONE`, `EMAIL`, `did`, `dname`, `dscrptn`, `status`, `DATE`, `time_slot`, `reminder_sent`, `urgent_reminder_sent`) VALUES
(74, 8, 'Arsenio Cervera', '09236307600', 'cerveraarsenio115@gmail.com', 1, 'Dra Marrisa Morada', 'brace', 'Confirmed', '2025-01-04', '10:00-11:00', 0, 0),
(75, 9, 'jm morada', '09236307600', 'cerveraarsenio115@gmai.com', 1, 'Dra Marrisa Morada', 'pasta', 'Confirmed', '2025-01-08', '10:00-11:00', 0, 0),
(76, 10, 'jm morada', '09236307600', 'cerveraarsenio30@gmail.com', 1, 'Dra Marrisa Morada', 'bunot', 'Confirmed', '2025-01-08', '12:00-1:00', 0, 0),
(77, 10, 'jm morada', '09236307600', 'cerveraarsenio30@gmail.com', 1, 'Dra Marrisa Morada', 'test', 'pending', '2025-01-08', '1:00-2:00', 0, 0),
(78, 10, 'jm morada', '09236307600', 'cerveraarsenio30@gmail.com', 1, 'Dra Marrisa Morada', 'braces', 'pending', '2025-01-14', '3:00-4:00', 0, 0),
(79, 10, 'jm morada', '09236307600', 'cerveraarsenio30@gmail.com', 1, 'Dra Marrisa Morada', 'bunot', 'Confirmed', '2025-01-14', '10:00-11:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `docid` int(11) NOT NULL,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialties` int(2) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`docid`, `docemail`, `docname`, `docpassword`, `docnic`, `doctel`, `specialties`, `profile_pic`) VALUES
(1, 'Marissa@smileslot.com', 'Dra. Marissa Morada', '123', '000000000', '0110000000', 1, '../uploads/profile_67473c3edd03f0.12826689.jpg'),
(2, 'Athenna@smileslot.com', 'Dra. Athenna Denise Morada', '123', '000000000', '0110000000', NULL, '../uploads/profile_674739652c7657.25156275.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `pid` int(11) NOT NULL,
  `pemail` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `ppassword` varchar(255) DEFAULT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`pid`, `pemail`, `pname`, `ppassword`, `paddress`, `pnic`, `pdob`, `ptel`, `profile_pic`) VALUES
(4, 'carljosephdiego@gmail.com', 'Carl Joseph  Diego', '123', '#19 Jp rizal st. ', NULL, '2001-10-03', '09087070173', '../uploads/profile_6746b6427d6576.64215422.jpg'),
(6, 'fafacods03@gmail.com', 'JC  CODILLO', '12345678', 'DSADSA SANTO NINYO', NULL, '2001-01-03', '09772926533', '../uploads/profile_6746eadd877257.49367048.jpg'),
(7, 'jchrstn0401@gmail.com', 'JOHN  JOHN', '123', 'PARANAQUE', NULL, '2001-04-01', '09772926533', NULL),
(8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', '123', 'navotas', NULL, '2002-01-15', '09236307600', '../uploads/profile_6777ef38b62414.28910478.png'),
(9, 'cerveraarsenio115@gmai.com', 'jm morada', '123', 'paran', NULL, '2002-06-27', '09236307600', NULL),
(10, 'cerveraarsenio30@gmail.com', 'jm morada', '123', 'paran', NULL, '2002-06-07', '09236307600', '../uploads/profile_6784fddbca5ad3.44091581.png');

-- --------------------------------------------------------

--
-- Table structure for table `request-table`
--

CREATE TABLE `request-table` (
  `id` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  `pemail` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `referralto` varchar(255) NOT NULL,
  `did` int(10) NOT NULL,
  `dname` text NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_date` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request-table`
--

INSERT INTO `request-table` (`id`, `pid`, `pemail`, `pname`, `reason`, `status`, `referralto`, `did`, `dname`, `request_date`, `payment_date`, `payment_method`) VALUES
(4, 7, 'jchrstn0401@gmail.com', 'JOHN  JOHN', 'EQWEWQEQWEWQ', 'Approved', 'DIYAN SA TABI', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', NULL, NULL),
(5, 6, 'fafacods03@gmail.com', 'JC  CODILLO', 'fdsfds', 'Approved', 'gsdgsdfs', 2, 'Dra. Athenna Denise Morada', '2025-01-07 16:49:46', NULL, NULL),
(6, 8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', 'change location', 'paid', 'navotas', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', '2025-01-13 06:40:08', 'gcash'),
(7, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'new home', 'Pending', 'navotas clinic', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', NULL, NULL),
(8, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'more cheaper', 'Approved', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', NULL, NULL),
(9, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'sasd', 'Pending', 'navotas', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', NULL, NULL),
(10, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'asdasd', 'Pending', 'ssa', 1, 'Dra. Marissa Morada', '2025-01-07 16:49:46', NULL, NULL),
(11, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'help', 'Pending', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-07 16:53:02', NULL, NULL),
(12, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'asdjas', 'Pending', 'nasdas', 1, 'Dra. Marissa Morada', '2025-01-07 16:57:50', NULL, NULL),
(13, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'adada', 'Approved', 'sfasf', 1, 'Dra. Marissa Morada', '2025-01-07 17:19:13', NULL, NULL),
(14, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'asdasd', 'Rejected', 'asdasd', 1, 'Dra. Marissa Morada', '2025-01-07 17:23:48', NULL, NULL),
(15, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'fsdfasf', 'Pending', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:29:09', NULL, NULL),
(16, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'fsdfasf', 'Pending', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:31:42', NULL, NULL),
(17, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'asdsasadas', 'Pending', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:31:52', NULL, NULL),
(18, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'sadasdas', 'Pending', 'asdasdasd', 1, 'Dra. Marissa Morada', '2025-01-13 11:34:03', NULL, NULL),
(19, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'please', 'Pending', 'navotas hospital', 1, 'Dra. Marissa Morada', '2025-01-13 11:35:53', NULL, NULL),
(20, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'nothing', 'Rejected', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:48:06', NULL, NULL),
(21, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'ddaaa', 'Approved', 'navotas clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:48:18', NULL, NULL),
(22, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'ddaaa', 'paid', 'navotas clinic', 1, 'Dra. Marissa Morada', '2025-01-13 11:49:01', '2025-01-13 06:38:09', 'gcash'),
(23, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'faaf', 'paid', 'navotas hospital', 1, 'Dra. Marissa Morada', '2025-01-13 11:49:07', '2025-01-13 11:49:07', NULL),
(24, 10, 'cerveraarsenio30@gmail.com', 'jm morada', 'way more cheaper\r\n', 'paid', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 13:35:16', '2025-01-13 13:35:16', NULL),
(25, 8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', 'cheaper', 'paid', 'malabon', 1, 'Dra. Marissa Morada', '2025-01-13 13:54:51', '2025-01-13 06:55:34', 'gcash'),
(26, 8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', 'test', 'paid', 'pateros ', 1, 'Dra. Marissa Morada', '2025-01-13 13:58:23', '2025-01-13 06:59:19', 'gcash'),
(27, 8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', 'test1', 'paid', 'navotas clinic', 1, 'Dra. Marissa Morada', '2025-01-13 14:01:01', '2025-01-13 07:01:32', 'gcash'),
(28, 8, 'cerveraarsenio115@gmail.com', 'Arsenio Cervera', 'test3', 'paid', 'Navotas Clinic', 1, 'Dra. Marissa Morada', '2025-01-13 14:03:17', '2025-01-13 07:03:36', 'gcash');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL,
  `docid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `scheduledate` date DEFAULT NULL,
  `scheduletime` time DEFAULT NULL,
  `nop` int(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specialties`
--

CREATE TABLE `specialties` (
  `id` int(2) NOT NULL,
  `sname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `specialties`
--

INSERT INTO `specialties` (`id`, `sname`) VALUES
(1, 'General Dentist'),
(2, 'Orthodontist'),
(3, 'Pediatric Dentist'),
(4, 'Prosthodontist'),
(5, 'Endodontist'),
(6, 'Periodontist');

-- --------------------------------------------------------

--
-- Table structure for table `webuser`
--

CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `webuser`
--

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('admin@edoc.com', 'a'),
('Marissa@smileslot.com', 'd'),
('patient@edoc.com', 'p'),
('emhashenudara@gmail.com', 'p'),
('carljosephdiego@gmail.com', 'p'),
('edward@gmail.com', 'p'),
('fafacods03@gmail.com', 'p'),
('Athenna@smileslot.com', 'd'),
('jchrstn0401@gmail.com', 'p'),
('cerveraarsenio115@gmail.com', 'p'),
('cerveraarsenio115@gmai.com', 'p'),
('cerveraarsenio30@gmail.com', 'p');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aemail`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appoid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `scheduleid` (`scheduleid`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_date_status` (`DATE`,`status`),
  ADD KEY `idx_reminders` (`reminder_sent`,`urgent_reminder_sent`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`docid`),
  ADD KEY `specialties` (`specialties`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `request-table`
--
ALTER TABLE `request-table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `docid` (`docid`);

--
-- Indexes for table `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webuser`
--
ALTER TABLE `webuser`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `request-table`
--
ALTER TABLE `request-table`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
