-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 29, 2025 at 04:37 PM
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
-- Database: `mymoneymate`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `msg` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `email`, `username`, `msg`, `submitted_at`) VALUES
(1, 7, 'abcd@gmail.com', 'abcd', 'good', '2025-07-29 08:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `category`, `amount`, `description`, `date`, `created_at`) VALUES
(1, 7, 'income', 'food', 200.00, 'chats', '2024-11-25', '2025-07-28 17:24:10'),
(2, 7, 'income', 'earnings', 200.00, 'dress', '2025-07-23', '2025-07-28 17:44:28'),
(9, 7, 'expense', 'food', 200.00, '', '2025-07-16', '2025-07-28 18:27:55'),
(10, 10, 'income', 'earnings', 1200.00, '', '2025-07-11', '2025-07-29 04:23:38'),
(11, 7, 'income', 'Salary', 20000.00, '', '2025-07-10', '2025-07-29 08:28:36'),
(12, 7, 'expense', 'shopping', 500.00, '', '2025-07-02', '2025-07-29 08:29:02'),
(13, 11, 'income', 'Salary', 15000.00, '', '2025-06-29', '2025-07-29 08:55:46'),
(14, 11, 'expense', 'Food', 500.00, 'dress', '2025-04-11', '2025-07-29 08:56:17'),
(15, 11, 'expense', 'Electricity', 400.00, '', '2025-05-11', '2025-07-29 08:57:28'),
(16, 7, 'income', 'Salary', 20000.00, '', '2024-11-25', '2025-07-29 09:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(6, 'Admin', 'admin@example.com', '$2y$10$o1yAqDiFijgLPl1t5xOamudshCd0Uqg5n63aEhCvk2dGbiQlpidkG', 'admin', '2025-07-28 10:49:06'),
(7, 'abcd', 'abcd@gmail.com', '$2y$10$XybJjcerLsSplAnqvr5vqOgFMmbp8CStZNMvazttH6zWootSwXAL.', 'user', '2025-07-28 17:03:40'),
(10, 'test', 'test@gmail.com', '$2y$10$M76LajFytOAr9ouKP0s3zu/8jfPmgxl/nNOJYwjRADXHEqqofAw06', 'user', '2025-07-29 03:23:41'),
(11, 'harsha', 'harsha@gmail.com', '$2y$10$hELbBqure9d0WleZadelhOXXOMLUBwwmua4LLzsw8hDhjZHxAr6NO', 'user', '2025-07-29 08:54:08'),
(12, 'dfg', 'adcd@gmail.com', '$2y$10$GPL/Jv7LHi7mFD7a0CgBOuunVFB4uZSkV2wKpZm6Ml.zYwiIxH5KK', 'user', '2025-07-29 09:04:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
