-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2025 at 06:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `fname`, `lname`, `email`, `phone`, `created_at`) VALUES
(1, 'System', 'Admin', 'admin@school.com', NULL, '2025-09-25 16:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `attendance1`
--

CREATE TABLE `attendance1` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `class_id` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `recorded_by` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance1`
--

INSERT INTO `attendance1` (`id`, `student_id`, `class_id`, `date`, `status`, `recorded_by`, `timestamp`) VALUES
(1, ' deep', '1', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:11'),
(2, ' harshil', '1', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:11'),
(3, ' prince', '1', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:11'),
(4, ' tushar', '2', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:39'),
(5, ' uttam', '2', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:39'),
(6, 'prince', '2', '2025-09-26', 'Present', ' karadam   sathvara', '2025-09-26 11:01:39'),
(7, ' deep', '1', '2025-09-27', 'Present', ' karadam   sathvara', '2025-09-27 04:32:25'),
(8, ' harshil', '1', '2025-09-27', 'Absent', ' karadam   sathvara', '2025-09-27 04:32:25'),
(9, ' prince', '1', '2025-09-27', 'Present', ' karadam   sathvara', '2025-09-27 04:32:25'),
(10, ' tushar', '2', '2025-09-27', 'Present', ' karadam   sathvara', '2025-09-27 04:35:59'),
(11, ' uttam', '2', '2025-09-27', 'Present', ' karadam   sathvara', '2025-09-27 04:35:59'),
(12, 'prince', '2', '2025-09-27', 'Absent', ' karadam   sathvara', '2025-09-27 04:35:59'),
(13, ' tushar', '2', '2025-09-30', 'Present', ' karadam   sathvara', '2025-09-30 14:41:04'),
(14, ' uttam', '2', '2025-09-30', 'Present', ' karadam   sathvara', '2025-09-30 14:41:04'),
(15, 'prince', '2', '2025-09-30', 'Present', ' karadam   sathvara', '2025-09-30 14:41:04'),
(16, ' tushar', '2', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 09:24:43'),
(17, ' uttam', '2', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 09:24:43'),
(18, 'prince', '2', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 09:24:43'),
(19, ' deep', '1', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 11:31:36'),
(20, ' harshil', '1', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 11:31:36'),
(21, ' prince', '1', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 11:31:36'),
(22, ' yug', '3', '2025-10-01', 'Present', ' karadam   sathvara', '2025-10-01 11:56:57'),
(23, ' tushar', '2', '2025-10-04', 'Present', ' karadam   sathvara', '2025-10-04 03:51:14'),
(24, ' uttam', '2', '2025-10-04', 'Present', ' karadam   sathvara', '2025-10-04 03:51:14'),
(25, 'prince', '2', '2025-10-04', 'Absent', ' karadam   sathvara', '2025-10-04 03:51:14');

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
('1', 'class-1', 'ground floor', 20),
('2', 'class 2', 'ground floor', 20),
('3', 'class 3', 'first floor', 30),
('4', 'class 4', 'first floor', 30),
('5', 'class 5', 'second floor', 30),
('6', 'class 6', 'second floor', 30),
('7', 'class 7', 'third floor', 30),
('8', 'class 8 ', 'third floor', 30);

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
(8, 'hey students , last date for school fees is 05/10/2025', 'Student', '2025-09-30 19:33:28');

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
(8, 'Bipinbhai', ' Sarvaiya', ' 9654782135', ' iron work', ' kharavedha , jamnagar', 'Male', 2147483647, 'bipinbhai123@gmail.com'),
(9, ' Raghavdas', ' Hariyani', '9532568914 ', ' diamond worker', 'maliyasan , rajkot ', 'Male', 2147483647, 'raghavdas123@gmail.com'),
(10, ' mahesh', ' narola', ' 95689846', ' nill', ' somnath', 'Male', 66565656, 'mahesg123@gmail.com'),
(11, ' vikrambhai', ' vadukar', '985623147', ' business owner ', ' veraval', 'Male', 56981478, 'vikrambhai123@gmail.com');

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
(' deep', ' Deep', ' Hariyani', '2005-10-16', ' maliyasan , Rajkot', 9, 'Male', '1', 'hariyanideep123@gmail.com'),
(' harshil', ' Harshil', ' Sarvaiya', '2004-06-14', ' kharavedha ', 8, 'Male', '1', 'harshilsarvaiya123@gmail.com'),
(' prince', ' prince ', ' ghoghari ', '2006-02-14', ' amreli pratapara ', 7, 'Male', '1', 'ghoghariprince123@gmail.com'),
(' tushar', ' tushar', ' sondagar', '2006-01-22', 'borla talaja , bhavnagar', 1, 'Male', '2', 'tusharsondagar221@gmail.com'),
(' uttam', ' Uttam ', ' gondaliya', '2006-06-20', 'Laxminagar Society\r\nDhamel Road', 2, 'Male', '2', 'gondaliyaravi746@gmail.com'),
(' yug', ' Yug', 'Vadukar', '2007-04-24', ' verval somnath', 11, 'Male', '3', 'yugvadukar123@gmail.com'),
('prince', ' Prince', ' narola', '2007-02-06', ' rajkot , mavdi', 10, 'Male', '2', 'prince123@gmail.com');

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
(' deep', ' Deep', ' Hariyani', ' maliyasan', ' 98565481696', '2000-10-16', ' maths teacher ', 'Male', 'deephariya123@gmail.com'),
(' ENG-2', ' nitin', ' trivedi', ' dhasa , botad , gujrat', ' 9725689816', '1995-02-26', ' english', 'Male', 'nitintrivedi12@gmail.com'),
(' Hin-3', 'rajni ', ' maheta', ' lathi , sahajanad socirty', ' 9889354589', '1993-06-07', 'hindi  ', 'Male', 'mahetarajni55@gmail.com'),
(' phy-1', ' karadam ', ' sathvara', ' ahemdabad', ' 97925689860', '1982-06-28', ' physics', 'Male', 'kardamsathvara17@gmail.com'),
(' ss-2', ' tushar', ' sondagar', 'talaja borla', ' 9725689816', '2025-09-25', ' social science', 'Male', 'tusharsondagar123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `class_id` varchar(50) NOT NULL,
  `subject_id` varchar(50) NOT NULL,
  `day` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `teacher_id` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `class_id`, `subject_id`, `day`, `start_time`, `end_time`, `teacher_id`, `created_at`) VALUES
(23, '1', ' Hi-27', 'Monday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:25:46'),
(24, '1', ' phy-23', 'Tuesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:26:13'),
(25, '1', 'ENG-28', 'Wednesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:26:41'),
(26, '1', 'maths-24 ', 'Thursday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:27:40'),
(27, '1', 'sci-26', 'Friday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:28:14'),
(28, '1', 'ss-24', 'Saturday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-26 13:29:00'),
(34, '2', 'sci-26', 'Saturday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:59:15'),
(35, '1', ' phy-23', 'Monday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:07:32'),
(36, '1', 'ENG-28', 'Tuesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:08:08'),
(37, '1', 'maths-24 ', 'Wednesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:09:10'),
(38, '1', 'sci-26', 'Thursday', '08:30:00', '09:00:00', ' Deep  Hariyani', '2025-09-27 07:19:47'),
(39, '2', ' Hi-27', 'Monday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-30 12:25:01'),
(40, '2', ' phy-23', 'Tuesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-30 12:25:13'),
(41, '2', 'ENG-28', 'Wednesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-30 12:25:39'),
(42, '2', 'maths-24 ', 'Thursday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-30 12:26:53'),
(43, '2', 'sci-26', 'Friday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-30 12:27:18'),
(44, '2', ' phy-23', 'Monday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-30 12:46:33'),
(45, '2', 'ENG-28', 'Tuesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-30 12:46:53'),
(46, '2', 'maths-24 ', 'Wednesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-10-04 03:53:59'),
(47, '2', 'sci-26', 'Thursday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-10-04 03:54:16'),
(48, '2', 'ss-24', 'Friday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-10-04 03:54:47'),
(49, '2', ' Hi-27', 'Saturday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-10-04 03:55:06'),
(50, '2', 'sci-26', 'Monday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:55:36'),
(51, '2', ' phy-23', 'Tuesday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:55:51'),
(52, '2', ' Hi-27', 'Wednesday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:56:36'),
(53, '2', 'ss-24', 'Thursday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:56:55'),
(54, '2', 'maths-24 ', 'Friday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:57:18'),
(55, '2', 'maths-24 ', 'Saturday', '09:30:00', '10:00:00', ' karadam   sathvara', '2025-10-04 03:58:40');

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
('Admin', 'admin@school.com', '0192023a7bbd73250516f069df18b500'),
('Teacher', 'deephariya123@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Student', 'gondaliyaravi746@gmail.com', 'ccf8486cfd1f76323cb1c684b5d254fd'),
('Teacher', 'kardamsathvara17@gmail.com', 'ff03dff00dd36bccc6ee0e413596d061'),
('Parent', 'mukehghoghari123@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Parent', 'parent@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Parent', 'rameshbhai221@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Parent', 'rameshbhai746@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Student', 'student@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Teacher', 'teacher@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Teacher', 'tusharsondagar123@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055'),
('Student', 'tusharsondagar221@gmail.com', 'df7c905d9ffebe7cda405cf1c82a3add'),
('Student', 'yugvadukar123@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `attendance1`
--
ALTER TABLE `attendance1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`);

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
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance1`
--
ALTER TABLE `attendance1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `parent`
--
ALTER TABLE `parent`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classroom` (`hno`),
  ADD CONSTRAINT `timetable_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`sid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
