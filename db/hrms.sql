-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2024 at 11:14 AM
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
-- Database: `hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('present','absent') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(5) NOT NULL,
  `name` text NOT NULL,
  `createdate` date NOT NULL,
  `modifiedate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `createdate`, `modifiedate`) VALUES
(1, 'PHP', '2024-07-24', '2024-07-24'),
(2, 'JAVA', '2024-07-24', '2024-07-24'),
(3, 'ASP.NET', '2024-07-24', '2024-07-24'),
(4, 'ANDROID', '2024-07-24', '2024-07-24'),
(5, 'FLUTTER', '2024-09-21', '2024-09-21');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `id` int(5) NOT NULL,
  `name` text NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `modifiedate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`id`, `name`, `createdate`, `modifiedate`) VALUES
(1, 'Sr.Software Engineer', '2024-07-24 10:41:57', '2024-07-24 10:41:57'),
(2, 'Software Engineer', '2024-07-24 10:42:49', '2024-07-24 10:42:49'),
(3, 'Sr.Developer', '2024-07-24 10:43:35', '2024-07-24 10:43:35'),
(4, 'Developer', '2024-07-24 10:44:01', '2024-07-24 10:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(5) NOT NULL,
  `fk_userid` int(5) NOT NULL,
  `fk_departmentid` int(11) NOT NULL,
  `fk_designationid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `createdate` date NOT NULL DEFAULT current_timestamp(),
  `modifiedate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `fk_userid`, `fk_departmentid`, `fk_designationid`, `name`, `first_name`, `last_name`, `email`, `createdate`, `modifiedate`) VALUES
(2, 2, 3, 1, 'Darshil Dabhi', 'Darshil', 'Dabhi', 'darshil@gmail.com', '2024-07-24', '2024-08-07'),
(59, 36, 3, 4, 'Ankur Bhanderi', 'Ankur', 'Bhanderi', 'ankur@gmail.com', '2024-07-29', '2024-08-07'),
(62, 39, 2, 3, 'Rutvik Rangapara', 'Rutvik', 'Rangapara', 'rutvik@gmail.com', '2024-07-29', '2024-07-29'),
(65, 42, 3, 1, 'Jay Moliya', 'Jay', 'Moliya', 'jay@gmail.com', '2024-08-01', '2024-08-01'),
(69, 46, 2, 3, 'Ashish Malviya', 'Ashish', 'Malviya', 'ashish@gmail.com', '2024-08-05', '2024-08-05'),
(70, 47, 2, 3, 'harpal makwana', 'harpal', 'makwana', 'harpal@gmail.com', '2024-08-06', '2024-09-04'),
(71, 48, 3, 1, 'kiran kanzariya', 'kiran', 'kanzariya', 'kiran@gmail.com', '2024-08-06', '2024-08-06'),
(72, 49, 4, 3, 'dishen gajera', 'dishen', 'gajera', 'dishen@gmail.com', '2024-08-07', '2024-08-07'),
(73, 50, 4, 4, 'Ritish Kanzariya', 'RItish', 'Kanzariya', 'ritish@gmail.com', '2024-08-12', '2024-08-12'),
(74, 51, 3, 1, 'Shivang kanzariya', 'shivang', 'kanzariya', 'shivang@gmail.com', '2024-08-12', '2024-08-12');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start_date`, `end_date`, `description`, `created_at`, `start_time`, `end_time`) VALUES
(1, 'Conference', '2024-10-01', '2024-10-03', 'Annual tech conference featuring keynotes and workshops.', '2024-09-22 08:47:15', '09:00:00', '17:00:00'),
(2, 'Workshop', '2024-10-10', '2024-10-10', 'Hands-on workshop on modern web development techniques.', '2024-09-22 08:47:15', '13:00:00', '15:00:00'),
(3, 'Networking Event', '2024-10-15', '2024-10-15', 'An evening for professionals to connect and network.', '2024-09-22 08:47:15', '18:00:00', '20:00:00'),
(4, 'Webinar', '2024-10-20', '2024-10-20', 'Online webinar on best practices for remote work.', '2024-09-22 08:47:15', '11:00:00', '12:00:00'),
(5, 'Hackathon', '2024-10-25', '2024-10-26', '24-hour coding event with prizes for top projects.', '2024-09-22 08:47:15', '09:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `start_date`, `end_date`, `reason`, `status`) VALUES
(1, 65, '2024-09-25', '2024-09-27', 'Sick Leave', 'Approved'),
(2, 2, '2024-10-01', '2024-10-03', 'Medical Issue', NULL),
(3, 2, '2024-10-02', '2024-10-03', 'Medical Issue', 'Approved'),
(4, 59, '2024-09-30', '2024-10-04', 'Family Function', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `month` varchar(20) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `month`, `salary`) VALUES
(11, 65, '2024-01', 15000.00),
(13, 62, '09/2024', 150000.00),
(14, 2, '2024/10', 50000.00),
(15, 59, '2024/08', 33000.00),
(16, 2, '2024/09', 35000.00),
(17, 2, '2024/08', 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL,
  `istemppasschange` tinyint(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `istemppasschange`, `email`, `password`, `role`) VALUES
(2, 1, 'darshil@gmail.com', 'Darshil@2005', 'employee'),
(13, 1, 'jaydev@gmail.com', 'Jaydev@2004', 'admin'),
(36, 1, 'ankur@gmail.com', 'Ankur@2005', 'employee'),
(39, 0, 'rutvik@gmail.com', 'Rutvik@2005', 'employee'),
(42, 0, 'jay@gmail.com', ',2fS0#Lf', 'employee'),
(46, 0, 'ashish@gmail.com', 'nCMP|3EX', 'employee'),
(47, 0, 'harpal@gmail.com', '$2y$10$BAAJOPQxBTG3IxbW/u5nkuOrXk34o/9oCJELNXlfFKS', 'employee'),
(48, 0, 'kiran@gmail.com', 'T2tDbmpQR1lTTlVwdm0zMVNvcmV1dz09OjpE9fN1RvDolttE66', 'employee'),
(49, 0, 'dishen@gmail.com', 'Z21IMEgrSUlCaTRVTmo1TVBBTnp0dz09OjpxDYYa3GbYUTKDZ6', 'employee'),
(50, 0, 'ritish@gmail.com', 'QTFyRzFDR0xSOVMzSm0ybHV5WEdSUT09OjqJVO6/dV28Tln2z7', 'employee'),
(51, 0, 'shivang@gmail.com', 'OVQ2dXpxZjRkb3Nvbjhzakw2bktHQT09OjpQIdl+p2AR3NDRCO', 'employee'),
(53, 0, 'rutvik1@gmail.com', 'dGhRbGVaM1lXaW1FK29sWlJoMS9QUT09Ojo6HxwzECzL+4b3kl', 'employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
