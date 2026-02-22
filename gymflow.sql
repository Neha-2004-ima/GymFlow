-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 06:03 AM
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
-- Database: `gymflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` enum('active','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `class_id`, `booking_date`, `status`, `created_at`) VALUES
(1, 15, 5, '2026-02-21', 'cancelled', '2026-02-22 10:23:05'),
(2, 15, 31, '2026-02-22', 'active', '2026-02-22 05:00:19'),
(3, 16, 31, '2026-02-22', 'active', '2026-02-22 05:01:25');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `instructor` varchar(255) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` time NOT NULL,
  `total_spots` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `instructor`, `day`, `time`, `total_spots`, `created_at`) VALUES
(5, 'Yoga', 'Zara Fernando', 'Monday', '06:00:00', 25, '2026-02-21 09:15:55'),
(6, 'HIIT Training', 'Ramesh Kodithuwakku', 'Monday', '09:00:00', 10, '2026-02-21 09:17:14'),
(7, 'Strength Training', 'Prageeth De Silva', 'Monday', '17:00:00', 15, '2026-02-21 09:18:06'),
(8, 'Stretch & Relax', 'Hirushi Wijesinghe', 'Monday', '19:00:00', 20, '2026-02-21 09:20:07'),
(9, 'Spinning', 'Taniya Gunathilaka', 'Tuesday', '06:00:00', 10, '2026-02-21 09:22:24'),
(10, 'Core & Abs Workout', 'Savidu Damsara', 'Tuesday', '10:00:00', 20, '2026-02-21 09:23:49'),
(11, 'Zumba Fitness', 'Vindi Wijesiri', 'Tuesday', '17:00:00', 25, '2026-02-21 09:24:50'),
(12, 'Pilates', 'Rasidu Perera', 'Tuesday', '19:00:00', 10, '2026-02-21 09:25:41'),
(13, 'Strength Training', 'Sasindu Ranathunga', 'Wednesday', '06:00:00', 15, '2026-02-21 09:27:22'),
(14, 'HIIT Advanced', 'Sithum Wijesooriya', 'Wednesday', '09:00:00', 15, '2026-02-21 09:28:35'),
(15, 'Kickboxing', 'Lasan Godakanda', 'Wednesday', '17:00:00', 10, '2026-02-21 09:29:19'),
(16, 'Power Yoga', 'Ranya De Silva', 'Wednesday', '19:00:00', 25, '2026-02-21 09:30:06'),
(17, 'Yoga', 'Zara Fernando', 'Thursday', '06:00:00', 25, '2026-02-21 09:31:00'),
(18, 'Bodyweight Training', 'Dinoth Jayasooriya', 'Thursday', '10:00:00', 15, '2026-02-21 09:35:33'),
(19, 'Zumba', 'Vindi Wijesiri', 'Thursday', '17:00:00', 25, '2026-02-21 09:36:08'),
(20, 'Stretching & Mobility', 'Hirushi Wijesinghe', 'Thursday', '19:00:00', 20, '2026-02-21 09:37:46'),
(21, 'HIIT Fat Burn', 'Sajee Amarasinghe', 'Friday', '06:00:00', 15, '2026-02-21 09:38:26'),
(22, 'Spinning', 'Taniya Gunathilaka', 'Friday', '09:00:00', 15, '2026-02-21 09:39:06'),
(23, 'Boxing Fitness', 'Lasan Godakanda', 'Friday', '17:00:00', 15, '2026-02-21 09:39:52'),
(24, 'Strength Training', 'Prageeth De Silva', 'Friday', '19:00:00', 15, '2026-02-21 09:40:28'),
(25, 'Zumba Party Class', 'Vindi Wijesiri', 'Saturday', '06:00:00', 20, '2026-02-21 09:41:19'),
(26, 'Pilates', 'Rasidu Perera', 'Saturday', '09:00:00', 20, '2026-02-21 09:41:54'),
(27, 'Muscle Building', 'Manuja Rathnayake', 'Saturday', '16:00:00', 15, '2026-02-21 09:42:40'),
(28, 'Kick Boxing', 'Lasan Godakanda', 'Saturday', '18:00:00', 10, '2026-02-21 09:43:36'),
(29, 'Relaxation Yoga', 'Zara Fernando', 'Sunday', '07:00:00', 25, '2026-02-21 09:44:19'),
(30, 'Core Stability', 'Anjalee Matheows', 'Sunday', '09:00:00', 10, '2026-02-21 09:45:38'),
(31, 'Functional Training', 'Hasidu Jayasinghe', 'Sunday', '05:00:00', 2, '2026-02-21 09:46:35');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE `membership_plans` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subtitle` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `features` text DEFAULT NULL,
  `is_popular` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `title`, `subtitle`, `price`, `features`, `is_popular`, `created_at`) VALUES
(1, 'Basic', 'Perfect for beginners', 2000.00, 'Access to gym equipment,Locker room access,Mobile app access,1 guest pass per month,Basic fitness assessment', 0, '2026-02-19 14:56:31'),
(2, 'Pro', 'Most popular choice', 3000.00, 'Everything in Basic,Unlimited group classes,Personal training (2 sessions/month),Nutrition consultation,Free gym merchandise,Priority booking', 1, '2026-02-19 14:56:31'),
(3, 'Elite', 'Ultimate fitness experience', 5000.00, 'Everything in Pro,Personal training (8 sessions/month),Custom meal plans,Private locker,24/7 gym access,Exclusive member events,Free guest passes (unlimited)', 0, '2026-02-19 14:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `age` int(11) NOT NULL,
  `plan` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(10) DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `number`, `age`, `plan`, `password`, `role`, `created_at`) VALUES
(1, 'System', 'Admin', 'admin@gymflow.com', '0771234567', 30, 'Elite', 'admin123', 'admin', '2026-02-19 14:45:01'),
(11, 'Nethmi', 'Sewwandika', 'nethmisewwandika@gmail.com', '0705206343', 21, 'Basic', 'nethmi', 'member', '2026-02-21 08:56:20'),
(12, 'Senanthi', 'Hirunima', 'senanthihirunima@gmail.com', '0723484120', 23, 'Basic', 'hiru', 'member', '2026-02-21 08:57:35'),
(13, 'Neha', 'Imashi', 'nehaimashi@gmail.com', '0719574846', 20, 'Elite', 'neha', 'member', '2026-02-21 08:58:53'),
(14, 'Chamodi', 'Anjalee', 'chamodianjalee@gmail.com', '0752198676', 24, 'Elite', 'chamodi', 'member', '2026-02-21 09:00:12'),
(15, 'Thisuri', 'Sudasinghe', 'thisurisudasinghe@gmail.com', '0783657729', 22, 'Pro', 'thisu123', 'member', '2026-02-21 09:02:38'),
(16, 'Senanthi', 'S', 'thisuri@gmail.com', '0705206343', 2, 'Basic', '123456', 'member', '2026-02-21 12:39:07'),
(18, 'rasidu', 'gimhana', 'rasidu@gmail.com', '1234567890', 25, 'Basic', '123456', 'member', '2026-02-21 12:43:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
