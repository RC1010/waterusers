-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2025 at 08:44 AM
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
-- Database: `to-do`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`, `user_id`) VALUES
(15, 'School - Project', 'projects', '2025-03-25 05:25:57', '2025-03-25 05:25:57', 0),
(16, 'Work', 'wdqwdwq', '2025-03-25 05:27:15', '2025-03-25 05:27:15', 0),
(20, 'Pokemon', 'toy', '2025-05-27 05:43:08', '2025-06-02 02:46:49', 0),
(21, 'Sword Art Online', 'anime', '2025-05-27 05:43:20', '2025-05-30 05:29:08', 0),
(22, 'Fairy Tail', 'anime', '2025-05-27 05:43:31', '2025-05-27 05:43:31', 0),
(23, 'Genshin Impact', 'effe', '2025-05-27 05:43:51', '2025-05-27 05:43:51', 0),
(24, 'Wuthering Waves', '', '2025-05-27 05:44:00', '2025-05-27 05:44:00', 0),
(25, 'Honkai Impact', '', '2025-05-27 05:44:08', '2025-05-27 05:44:08', 0),
(27, 'Honkai Star Rail', 'efef', '2025-05-27 05:49:55', '2025-05-29 01:35:46', 0),
(28, 'Food', 'a', '2025-05-27 05:55:55', '2025-05-28 06:01:34', 0),
(29, 'Honkai', 'Video Game vwfefwfewfwfwefefqwdqd', '2025-05-29 01:38:04', '2025-05-29 01:57:26', 0),
(30, 'School', 'nnnnnnnnnnnnnnnnnnnnnnn', '2025-05-29 02:29:14', '2025-05-30 05:37:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Not Started','In Progress','Pending','Completed') DEFAULT 'Not Started',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `status`, `priority`, `created_at`, `updated_at`, `category_id`, `due_date`) VALUES
(9, 2, 'Apple', 'fruits', 'Completed', 'medium', '2025-04-07 00:54:44', '2025-06-25 08:50:34', 28, '2025-06-30'),
(31, 2, 'My my', 'aa', 'Completed', 'medium', '2025-05-27 04:49:07', '2025-06-25 08:50:47', 29, '2025-06-25'),
(37, 2, 'My my del', 'efwfewefwef', 'Not Started', 'low', '2025-05-30 05:16:13', '2025-06-25 08:50:22', 30, '2025-07-18'),
(38, 2, 'Raidenqdwdq', 'dqdqwdwqdwqdqwdqwdqwdq', 'Not Started', 'low', '2025-05-30 05:19:38', '2025-06-25 08:50:03', 30, '2025-08-15'),
(39, 2, 'Thesis (Math)', 'no', 'Not Started', 'medium', '2025-05-30 05:20:28', '2025-06-25 11:07:16', 21, '2025-06-27'),
(40, 2, 'fqefeqfwfqwqwfwqfq', 'qfqffwq', 'Completed', 'low', '2025-05-30 06:13:05', '2025-06-25 08:49:32', 30, '2025-06-30'),
(41, 2, 'qwddqw', 'tgrgegthyjdjsjj', 'In Progress', 'medium', '2025-05-30 06:13:15', '2025-06-25 08:49:20', 20, '2025-08-16'),
(44, 2, 'qwdwqdttt', 'hyhyhyhyhhyy', 'Completed', 'low', '2025-05-30 06:31:17', '2025-06-25 08:49:06', 30, '2025-08-08'),
(49, 2, 'prio', 'wefwfef', 'Not Started', 'medium', '2025-05-30 08:18:47', '2025-06-25 08:48:49', 30, '2025-08-08'),
(51, 2, 'oh', 'ahhh', 'Completed', 'low', '2025-06-02 01:08:57', '2025-06-25 08:48:34', 30, '1970-03-27'),
(52, 2, 'reret', 'rererefqbfuefiuewfwfiuwbfwiuwbefiuewbfiuewfbewfiubwfiubqbquifeubqeuf', 'Completed', 'high', '2025-06-20 06:23:25', '2025-06-25 08:48:10', 30, '2025-07-18'),
(53, 2, 'AAAAAAAAAAAAAAA', 'afwfwfwf', 'In Progress', 'medium', '2025-06-20 08:43:13', '2025-06-25 08:57:40', 29, '2025-09-05'),
(54, 2, 'Code Project', 'this and this', 'Not Started', 'low', '2025-06-25 08:40:27', '2025-06-25 08:42:50', 30, '2025-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `task_progress`
--

CREATE TABLE `task_progress` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `status` enum('started','stopped','resumed','completed') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'inactive',
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`, `status`, `profile_image`) VALUES
(1, 'John', 'Doe', 'johndoe@example.com', 'hashedpassword', '2025-03-11 10:18:19', '2025-03-11 10:18:19', 'inactive', NULL),
(2, 'Admina', '011', 'admin@gmail.com', '$2y$10$gzTah6vlz1qVZYll0hw02.Bzve8XMK.e9LYWRS9G3ZwKZjoAZqWAO', '2025-03-11 18:39:30', '2025-06-25 07:30:10', 'active', 'profile_685ba55e4552c7.46098175.png'),
(6, 'Kevin', 'Kaslana', 'kaslana@gmail.com', '$2y$10$e67LNQlrNuKnQFdsqCjP3eGtmnatbcj3Yym..x8XYH6o/kVCe4Xj.', '2025-03-17 22:46:55', '2025-05-13 05:46:02', 'inactive', NULL),
(7, 'Kevin', 'Kaslana', 'apple@gmail.com', '$2y$10$tSMMvc/Yv4a3vrAr2/JzaegXT7BCHkJLe/8SymdxUgKYbeQigw90C', '2025-05-12 23:45:51', '2025-05-12 23:45:51', 'inactive', NULL),
(8, 'Kevin', 'Kaslana', 'raiden@gmail.com', '$2y$10$fz9N2FtzHMAHbxpCjzcoTuuvErdMk7yX1SJL1nEhBkF0x/HCM6D8q', '2025-05-26 20:25:08', '2025-05-27 02:28:50', 'inactive', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_tasks_category` (`category_id`);

--
-- Indexes for table `task_progress`
--
ALTER TABLE `task_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `task_progress`
--
ALTER TABLE `task_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_tasks_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_progress`
--
ALTER TABLE `task_progress`
  ADD CONSTRAINT `task_progress_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
