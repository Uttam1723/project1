-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2025 at 07:00 AM
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
-- Database: `student-management-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `sid` int(10) NOT NULL,
  `date` date NOT NULL,
  `aid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`sid`, `date`, `aid`) VALUES
(2, '2020-05-25', 3),
(1, '2020-05-30', 4),
(2, '2020-05-02', 5),
(2, '1975-09-17', 6),
(3, '2005-06-30', 7),
(10, '2025-08-28', 8),
(10, '2025-08-28', 9),
(11, '2025-08-29', 10),
(12, '2025-08-29', 11),
(13, '2025-09-24', 12);

-- --------------------------------------------------------

--
-- Table structure for table `attendancereport`
--

CREATE TABLE `attendancereport` (
  `aid` int(20) NOT NULL,
  `sid` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendancereport`
--

INSERT INTO `attendancereport` (`aid`, `sid`, `status`) VALUES
(3, 'ST1000010001', 'Absent'),
(3, 'ST1000010002', 'Present'),
(4, 'ST1000010001', 'Present'),
(4, 'ST1000010002', 'Present'),
(10, ' uttam', 'Present'),
(11, ' tushar', 'Present'),
(11, ' uttam', 'Present'),
(12, ' prince', 'Present'),
(12, ' tushar', 'Present'),
(12, ' uttam', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE `classroom` (
  `hno` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `capacity` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`hno`, `title`, `location`, `capacity`) VALUES
('1-A', 'class-1 A', 'first floor-10', 25),
('1-B', 'class-1 B', 'first floor -11', 25),
('2-A', 'class-2 A', 'second floor-23', 30),
('2-B', 'class-2 B', 'second floor', 30);

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `teacher` varchar(50) NOT NULL,
  `classroom` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `stime` time NOT NULL,
  `etime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`id`, `subject`, `teacher`, `classroom`, `date`, `stime`, `etime`) VALUES
(3, ' phy-23', ' phy-1', '2-B', '2006-06-20', '10:00:00', '11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `examresult`
--

CREATE TABLE `examresult` (
  `exam` int(11) NOT NULL,
  `student` varchar(50) NOT NULL,
  `marks` int(10) NOT NULL,
  `grade` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examresult`
--

INSERT INTO `examresult` (`exam`, `student`, `marks`, `grade`) VALUES
(3, ' tushar', 25, 'B+'),
(3, ' uttam', 30, 'B+');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `notice` varchar(1500) NOT NULL,
  `odience` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `notice`, `odience`, `date`) VALUES
(7, 'tomorrow is  holiday', 'Student', '2025-08-28 16:21:04');

-- --------------------------------------------------------

--
-- Table structure for table `parent`
--

CREATE TABLE `parent` (
  `pid` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `job` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `gender` varchar(25) NOT NULL,
  `nic` int(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parent`
--

INSERT INTO `parent` (`pid`, `fname`, `lname`, `contact`, `job`, `address`, `gender`, `nic`, `email`) VALUES
(1, ' rameshbhai', ' sondagar', ' 97925689860', ' brick work', 'borla talaja bhavnagar', 'Male', 2140083647, 'rameshbhai221@gmail.com'),
(2, ' rameshbhai', ' gondaliya', ' 9725688454', ' bricks work', 'Laxminagar Society\r\nDhamel Road', 'Male', 2147483647, 'rameshbhai746@gmail.com'),
(7, ' mukeshbhai ', ' ghoghari ', ' 9394689513', ' bricks work', ' amreli pratapara', 'Male', 2147483647, 'mukehghoghari123@gmail.com'),
(8, 'Bipinbhai', ' Sarvaiya', ' 9654782135', ' iron work', ' kharavedha , jamnagar', 'Male', 2147483647, 'bipinbhai123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `teacher` varchar(50) NOT NULL,
  `day` varchar(50) NOT NULL,
  `stime` time NOT NULL,
  `class` varchar(50) NOT NULL,
  `etime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `subject`, `teacher`, `day`, `stime`, `class`, `etime`) VALUES
(9, ' phy-23', ' phy-1', 'Monday', '10:00:00', '1-A', '11:00:00'),
(10, ' Hi-27', 'Andrew', 'Monday', '10:00:00', '1-B', '11:00:00'),
(11, 'ENG-28', ' ENG-2', 'Select Day', '11:00:00', '2-B', '12:00:00'),
(12, ' phy-23', ' phy-1', 'Wendsday', '01:00:00', '2-B', '02:00:00'),
(13, 'ENG-28', ' ENG-2', 'Tuesday', '11:00:00', '2-B', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `sid` varchar(25) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `bday` date NOT NULL,
  `address` varchar(250) NOT NULL,
  `parent` int(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `classroom` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`sid`, `fname`, `lname`, `bday`, `address`, `parent`, `gender`, `classroom`, `email`) VALUES
(' harshil', ' Harshil', ' Sarvaiya', '2004-06-14', ' kharavedha , jamnagar', 8, 'Male', '2-B', 'harshilsarvaiya123@gmail.com'),
(' prince', ' prince ', ' ghoghari ', '2006-02-14', ' amreli pratapara ', 7, 'Male', '2-B', 'ghoghariprince123@gmail.com'),
(' tushar', ' tushar', ' sondagar', '2006-01-22', 'borla talaja , bhavnagar', 1, 'Male', '2-B', 'tusharsondagar221@gmail.com'),
(' uttam', ' Uttam ', ' gondaliya', '2006-06-20', 'Laxminagar Society\r\nDhamel Road', 2, 'Male', '2-B', 'gondaliyaravi746@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `sid` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`sid`, `title`, `description`) VALUES
(' Hi-27', ' Hindi', ' Hindil full course'),
(' phy-23', ' Physics', ' physics syllabus'),
('ENG-28', ' English', ' Full english syllabus'),
('maths-24 ', 'maths', 'maths full syllabus'),
('sci-26', 'science', 'full course of science'),
('ss-24', 'social science', 'full course of social science');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `tid` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `bday` date NOT NULL,
  `skill` varchar(500) NOT NULL,
  `gender` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`tid`, `fname`, `lname`, `address`, `contact`, `bday`, `skill`, `gender`, `email`) VALUES
(' ENG-2', ' nitin', ' trivedi', ' dhasa , botad , gujrat', ' 9725689816', '1995-02-26', ' english', 'Male', 'nitintrivedi12@gmail.com'),
(' Hin-3', 'rajni ', ' maheta', ' lathi , sahajanad socirty', ' 9889354589', '1993-06-07', 'hindi  ', 'Male', 'mahetarajni55@gmail.com'),
(' phy-1', ' karadam ', ' sathvara', ' ahemdabad', ' 97925689860', '1982-06-28', ' physics', 'Male', 'kardamsathvara17@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `role` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`role`, `email`, `password`) VALUES
('Student', 'gondaliyaravi746@gmail.com', 'ccf8486cfd1f76323cb1c684b5d254fd'),
('Teacher', 'kardamsathvara17@gmail.com', 'ff03dff00dd36bccc6ee0e413596d061'),
('Parent', 'parent@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Parent', 'rameshbhai221@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Parent', 'rameshbhai746@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Student', 'student@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Teacher', 'teacher@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Student', 'tusharsondagar221@gmail.com', 'df7c905d9ffebe7cda405cf1c82a3add');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `attendancereport`
--
ALTER TABLE `attendancereport`
  ADD PRIMARY KEY (`aid`,`sid`);

--
-- Indexes for table `classroom`
--
ALTER TABLE `classroom`
  ADD PRIMARY KEY (`hno`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examresult`
--
ALTER TABLE `examresult`
  ADD PRIMARY KEY (`exam`,`student`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent`
--
ALTER TABLE `parent`
  ADD PRIMARY KEY (`pid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`tid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
