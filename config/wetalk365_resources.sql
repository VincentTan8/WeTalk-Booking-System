-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 13, 2025 at 09:04 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wetalk365_resources`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `encoded_by` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `schedule_id`, `student_id`, `encoded_by`) VALUES
(15, 24, 1, 'CS Person - 2025-03-13 10:35:29'),
(16, 12, 1, 'CS Person - 2025-03-13 13:24:59'),
(17, 9, 1, 'CS Person - 2025-03-13 13:25:35'),
(18, 31, 1, 'CS Person - 2025-03-13 13:50:41'),
(19, 3, 2, 'CS Person - 2025-03-13 14:55:44'),
(20, 28, 2, 'CS Person - 2025-03-13 15:04:20'),
(21, 1, 5, 'CS Person - 2025-03-13 15:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL DEFAULT '',
  `details` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `code`, `details`) VALUES
(1, 'ZH', 'Chinese'),
(2, 'EN', 'English'),
(3, 'FIL', 'Filipino');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `scheddate` date DEFAULT NULL,
  `schedstarttime` time DEFAULT NULL,
  `schedendtime` time DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `platform` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `scheddate`, `schedstarttime`, `schedendtime`, `teacher_id`, `language_id`, `booking_id`, `platform`) VALUES
(1, '2025-03-05', '09:00:00', '10:00:00', 1, 2, 21, 0),
(2, '2025-03-10', '13:00:00', '14:00:00', 1, 2, NULL, 0),
(3, '2025-03-01', '11:00:00', '12:00:00', 1, 2, 19, 0),
(4, '2025-03-05', '11:00:00', '12:00:00', 1, 2, NULL, 1),
(5, '2025-03-26', '09:00:00', '10:00:00', 1, 2, NULL, 0),
(6, '2025-03-01', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(7, '2025-03-27', '11:00:00', '12:00:00', 1, 2, NULL, 0),
(8, '2025-03-12', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(9, '2025-03-11', '13:00:00', '14:00:00', 1, 2, 17, 0),
(10, '2025-03-16', '12:00:00', '13:00:00', 1, 2, NULL, 0),
(11, '2025-03-25', '12:00:00', '13:00:00', 1, 2, NULL, 1),
(12, '2025-03-03', '13:00:00', '14:00:00', 1, 2, 16, 0),
(13, '2025-03-25', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(14, '2025-04-01', '08:00:00', '09:00:00', 1, 2, NULL, 1),
(15, '2025-03-26', '11:00:00', '12:00:00', 1, 2, NULL, 0),
(16, '2025-05-08', '15:00:00', '16:00:00', 1, 2, NULL, 0),
(17, '2025-03-12', '12:00:00', '13:00:00', 1, 2, NULL, 0),
(18, '2025-05-01', '08:00:00', '09:00:00', 1, 2, NULL, 1),
(19, '2025-05-20', '08:00:00', '09:00:00', 1, 2, NULL, 0),
(20, '2025-03-26', '08:00:00', '09:00:00', 1, 2, NULL, 1),
(21, '2025-03-31', '08:00:00', '09:00:00', 1, 2, NULL, 0),
(22, '2025-03-31', '21:00:00', '22:00:00', 1, 2, NULL, 1),
(23, '2025-03-26', '14:00:00', '15:00:00', 1, 2, NULL, 0),
(24, '2025-03-13', '08:00:00', '09:00:00', 1, 2, 15, 0),
(25, '2025-03-27', '11:00:00', '12:00:00', 1, 2, NULL, 0),
(26, '2025-03-13', '09:00:00', '10:00:00', 1, 2, NULL, 0),
(27, '2025-03-13', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(28, '2025-03-07', '12:00:00', '13:00:00', 1, 2, 20, 0),
(29, '2025-03-13', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(30, '2025-03-26', '13:00:00', '14:00:00', 1, 2, NULL, 0),
(31, '2025-03-19', '08:00:00', '09:00:00', 1, 2, 18, 1),
(32, '2025-03-13', '10:00:00', '11:00:00', 1, 2, NULL, 0),
(33, '2025-03-17', '18:00:00', '19:00:00', 1, 2, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `fname`, `mname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `password`) VALUES
(1, 'Harry2s', NULL, 'Macaraig', 'h@test.com', 'cavite', '999939', '2009-10-06', 'Male', 'test'),
(2, 'Jeff', NULL, 'Uy', 'jeffuy@test.com', 'manila', '09191991', '2012-02-01', 'Male', 'try'),
(3, 'Girl', NULL, 'Girl', 'girl@yahoo.com', 'cavite', '0999999', '2014-06-24', 'Female', ''),
(4, 'John', NULL, 'Tan', 'johntan23@yahoo.com', '', '123', NULL, '', 'test'),
(5, 'Rodel', NULL, 'Den', 'rodel@test.com', '', '099919', NULL, '', 'try');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(20) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `fname`, `mname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `language_id`, `password`) VALUES
(1, 'Kats', NULL, 'Gecolea', 'kat@wetalk.com', 'Cavite', '+6398765432101', '1990-05-01', 'Female', 2, 'test2'),
(3, 'Kat', NULL, 'Gecolea', 'bruh@gmail.com', 'Cavite', '+639876543210', '1990-05-01', 'Female', 2, ''),
(5, 'Kat', NULL, 'Gecolea', 'test@mail.com', 'Cavite', '+639876543210', '1990-05-01', 'Female', 2, ''),
(6, '', NULL, 'Gecoleaaa', '', 'Cavitee', '', '1990-05-01', '', NULL, ''),
(12, 'Kat', NULL, 'Gecolea', 'kat@wetalk.coms', 'Cavite', '+639876543210', '1990-05-01', 'Female', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `teachers_in_sched`
--

CREATE TABLE `teachers_in_sched` (
  `id` int(11) NOT NULL,
  `scheddate` date DEFAULT NULL,
  `schedstarttime` time DEFAULT NULL,
  `schedendtime` time DEFAULT NULL,
  `teacher_ids` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers_in_sched`
--

INSERT INTO `teachers_in_sched` (`id`, `scheddate`, `schedstarttime`, `schedendtime`, `teacher_ids`) VALUES
(1, '2025-03-01', '10:00:00', '11:00:00', '1'),
(2, '2025-03-05', '09:00:00', '10:00:00', '1'),
(3, '2025-03-05', '11:00:00', '12:00:00', '1'),
(4, '2025-03-10', '13:00:00', '14:00:00', '1'),
(5, '2025-03-12', '10:00:00', '11:00:00', '1'),
(6, '2025-03-12', '12:00:00', '13:00:00', '1'),
(7, '2025-03-13', '09:00:00', '10:00:00', '1'),
(8, '2025-03-13', '10:00:00', '11:00:00', '1'),
(9, '2025-03-16', '12:00:00', '13:00:00', '1'),
(10, '2025-03-17', '18:00:00', '19:00:00', '1'),
(11, '2025-03-25', '10:00:00', '11:00:00', '1'),
(12, '2025-03-25', '12:00:00', '13:00:00', '1'),
(13, '2025-03-26', '08:00:00', '09:00:00', '1'),
(14, '2025-03-26', '09:00:00', '10:00:00', '1'),
(15, '2025-03-26', '11:00:00', '12:00:00', '1'),
(16, '2025-03-26', '13:00:00', '14:00:00', '1'),
(17, '2025-03-26', '14:00:00', '15:00:00', '1'),
(18, '2025-03-27', '11:00:00', '12:00:00', '1'),
(19, '2025-03-31', '08:00:00', '09:00:00', '1'),
(20, '2025-03-31', '21:00:00', '22:00:00', '1'),
(21, '2025-04-01', '08:00:00', '09:00:00', '1'),
(22, '2025-05-01', '08:00:00', '09:00:00', '1'),
(23, '2025-05-08', '15:00:00', '16:00:00', '1'),
(24, '2025-05-20', '08:00:00', '09:00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` int(11) NOT NULL,
  `starttime` time DEFAULT NULL,
  `endtime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `starttime`, `endtime`) VALUES
(1, '08:00:00', '09:00:00'),
(2, '09:00:00', '10:00:00'),
(3, '10:00:00', '11:00:00'),
(4, '11:00:00', '12:00:00'),
(5, '12:00:00', '13:00:00'),
(6, '13:00:00', '14:00:00'),
(7, '14:00:00', '15:00:00'),
(8, '15:00:00', '16:00:00'),
(9, '16:00:00', '17:00:00'),
(10, '17:00:00', '18:00:00'),
(11, '18:00:00', '19:00:00'),
(12, '19:00:00', '20:00:00'),
(13, '20:00:00', '21:00:00'),
(14, '21:00:00', '22:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `teachers_in_sched`
--
ALTER TABLE `teachers_in_sched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teachers_in_sched`
--
ALTER TABLE `teachers_in_sched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
