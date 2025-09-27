-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 09:30 AM
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
(13, '2025-09-24', 12),
(14, '2025-09-25', 13),
(13, '2025-09-26', 14);

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
(12, 'prince', '2', '2025-09-27', 'Absent', ' karadam   sathvara', '2025-09-27 04:35:59');

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
(12, ' uttam', 'Present'),
(13, ' deep', 'Present'),
(13, ' harshil', 'Present'),
(13, ' prince', 'Present'),
(13, ' tushar', 'Absent'),
(13, ' uttam', 'Absent'),
(14, ' prince', 'Present'),
(14, ' tushar', 'Present'),
(14, ' uttam', 'Present');

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
(8, 'Bipinbhai', ' Sarvaiya', ' 9654782135', ' iron work', ' kharavedha , jamnagar', 'Male', 2147483647, 'bipinbhai123@gmail.com'),
(9, ' Raghavdas', ' Hariyani', '9532568914 ', ' diamond worker', 'maliyasan , rajkot ', 'Male', 2147483647, 'raghavdas123@gmail.com'),
(10, ' mahesh', ' narola', ' 95689846', ' nill', ' somnath', 'Male', 66565656, 'mahesg123@gmail.com');

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
(16, ' phy-23', ' nitin  trivedi', 'Tuesday', '10:00:00', '1-A', '11:00:00');

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
('prince', ' Prince', ' narola', '2007-02-06', ' rajkot , mavdi', 10, 'Male', '2', 'prince123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `student1`
--

CREATE TABLE `student1` (
  `sid` varchar(50) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `bday` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `classroom` varchar(50) DEFAULT NULL,
  `parent` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student1`
--

INSERT INTO `student1` (`sid`, `fname`, `lname`, `bday`, `gender`, `email`, `address`, `classroom`, `parent`) VALUES
(' uttam', ' Uttam', ' gondaliya', '2006-06-20', 'Male', 'gondaliyauttam33@gmail.com', ' damnagar', '3', '2');

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
(29, '2', ' Hi-27', 'Monday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:57:07'),
(30, '2', ' phy-23', 'Tuesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:57:53'),
(31, '2', 'ENG-28', 'Wednesday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:58:11'),
(32, '2', 'maths-24 ', 'Thursday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:58:31'),
(33, '2', 'ss-24', 'Friday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:58:54'),
(34, '2', 'sci-26', 'Saturday', '08:00:00', '08:30:00', ' karadam   sathvara', '2025-09-27 05:59:15'),
(35, '1', ' phy-23', 'Monday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:07:32'),
(36, '1', 'ENG-28', 'Tuesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:08:08'),
(37, '1', 'maths-24 ', 'Wednesday', '08:30:00', '09:00:00', ' karadam   sathvara', '2025-09-27 06:09:10'),
(38, '1', 'sci-26', 'Thursday', '08:30:00', '09:00:00', ' Deep  Hariyani', '2025-09-27 07:19:47');

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
('Student', 'tusharsondagar221@gmail.com', 'df7c905d9ffebe7cda405cf1c82a3add');

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
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `attendance1`
--
ALTER TABLE `attendance1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`);

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
-- Indexes for table `student1`
--
ALTER TABLE `student1`
  ADD PRIMARY KEY (`sid`);

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
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `aid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attendance1`
--
ALTER TABLE `attendance1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
