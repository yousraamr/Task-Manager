-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 05:40 AM
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
-- Database: `task_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `due_date` date DEFAULT NULL,
  `assignee` varchar(100) DEFAULT NULL,
  `status` enum('To Do','In Progress','Done') DEFAULT 'To Do',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `priority`, `due_date`, `assignee`, `status`, `created_at`) VALUES
(11, 13, 'task1', 'test task1', 'Medium', '2222-02-22', 'Mariam', 'In Progress', '2025-05-19 21:03:38'),
(14, 15, 'task2', 'test task2', 'Low', '2222-02-22', 'Hamza', 'Pending', '2025-05-19 21:11:34'),
(15, 13, 'task3', 'test task3', 'High', '5555-05-05', 'Poula', 'Done', '2025-05-19 21:12:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'Hamza test 2', 'hamza2@gmail.com', '$2y$10$2j1DFyiGEXx8plY5P6TzFOFX6T9hViVvjyonidwUehJcwqKdPwArq', 'user', '2025-05-17 21:25:11'),
(4, 'Poula Labib Yehya Labib', 'polalabib@gmail.com', '$2y$10$FvevnZg3RfrE4qGNd7iCDeBz1fqYlLHeFjNyz7O8knT/PZ2ZwRAzG', 'admin', '2025-05-18 18:25:52'),
(7, 'poul', 'poula@gmail.com', '$2y$10$097tPq4Xer72JGT63SAS8uE.cHuVIxZjrDJ02opAq7aHz6gxRu5v.', 'admin', '2025-05-19 14:21:01'),
(10, 'Pnefi', 'ppp@gmail.com', '$2y$10$z2p0AHU0ttwChLEUMhdYG.C9ZtROTuhEdnmoF9FjFXnyZ2wgjO.02', 'user', '2025-05-19 14:53:11'),
(13, 'Mariam jan', 'Mariamjan268@gmail.com', '$2y$10$O7kejiUgr5Xgqvp8gd7DeOHyQ2LhkdO/Vf0asJ2TrYZ25B5s8jZCu', 'user', '2025-05-19 18:02:55'),
(15, 'Mariam George', 'Mariamjan123@gmail.com', '$2y$10$bjkpefKJToOH23r3AaFk4uKd8otM62jFZJ4c3clZYAEU6sPyZxDXK', 'user', '2025-05-19 18:11:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
