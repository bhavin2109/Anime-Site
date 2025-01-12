-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2025 at 04:58 AM
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
-- Database: `anime_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `anime`
--

CREATE TABLE `anime` (
  `anime_id` int(100) NOT NULL,
  `anime_name` varchar(100) NOT NULL,
  `anime_image` varchar(255) NOT NULL,
  `anime_type` varchar(100) NOT NULL,
  `episodes` int(200) NOT NULL,
  `genre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anime`
--

INSERT INTO `anime` (`anime_id`, `anime_name`, `anime_image`, `anime_type`, `episodes`, `genre`) VALUES
(1, 'Dan Da Dan', 'Dan Da Dan - S01.jpg', 'TV', 0, 'Action'),
(2, 'Your Name', 'your name.jpg', 'Movie', 0, 'Fantasy'),
(3, 'Wind Breaker', 'Wind Breaker - S01.jpeg', 'TV', 0, 'Action'),
(4, 'I want to Eat Your Pancreas', 'i want to eat your pancreas.jpg', 'Movie', 0, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `episode_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `episode_title` varchar(255) DEFAULT NULL,
  `episode_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `episodes`
--

INSERT INTO `episodes` (`episode_id`, `anime_id`, `episode_title`, `episode_url`) VALUES
(1, 1, 'The Begining', '1o-6-aR4eGyxbmF2PJ3XT5M9EV1J_STm0'),
(2, 2, 'Kimi No Namayva', '1yeirUYkEhVPXSWq-xhA7hlhCxqzWLbM3'),
(3, 1, 'Second', '1yet2FV8rDV5HZkW7c85K0rwT0jWHt2Eu'),
(4, 1, 'Third', '1bf4sF5X2xdxgefbRkxCuEoOpVdbE5mft'),
(5, 1, 'Fourth', '1rU2kuj0NdPMtlXHoNit903Hw5Nbiv3J_'),
(6, 3, 'The Begining of Gang', '1rTmSckwKJgPN6gzkTnxaONXjyewYImAT'),
(7, 3, 'Second Finale', '1LM9RJeEm4CIgsuM59GA3KpB8SCHJgsZK');

-- --------------------------------------------------------

--
-- Table structure for table `highlight_videos`
--

CREATE TABLE `highlight_videos` (
  `video_id` int(100) NOT NULL,
  `video_name` varchar(100) NOT NULL,
  `video_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `highlight_videos`
--

INSERT INTO `highlight_videos` (`video_id`, `video_name`, `video_file`) VALUES
(1, 'Demon Slayer', 'Demon Slayer.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'bhavin', 'opbhavin21@gmail.com', 'bhavin2109'),
(2, 'heet', 'heetvelani@gmail.com', 'heet');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anime`
--
ALTER TABLE `anime`
  ADD PRIMARY KEY (`anime_id`);

--
-- Indexes for table `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`episode_id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Indexes for table `highlight_videos`
--
ALTER TABLE `highlight_videos`
  ADD PRIMARY KEY (`video_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anime`
--
ALTER TABLE `anime`
  MODIFY `anime_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `episode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `anime` (`anime_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
