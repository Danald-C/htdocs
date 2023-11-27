-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2023 at 04:42 AM
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
-- Database: `nkb_test_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_1`
--

CREATE TABLE `data_1` (
  `id` int(11) NOT NULL,
  `f_id` int(11) NOT NULL,
  `gender` text NOT NULL,
  `dob` date NOT NULL,
  `nationality` text NOT NULL,
  `occupation` varchar(200) NOT NULL,
  `marital_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_2`
--

CREATE TABLE `data_2` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `o_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_3`
--

CREATE TABLE `data_3` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `dedi_date` date DEFAULT NULL,
  `dedi_by` varchar(200) NOT NULL,
  `bapt_date` date DEFAULT NULL,
  `bapt_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_4`
--

CREATE TABLE `data_4` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_5`
--

CREATE TABLE `data_5` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_6`
--

CREATE TABLE `data_6` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `nation` varchar(150) NOT NULL,
  `state_province` varchar(150) NOT NULL,
  `city` varchar(150) NOT NULL,
  `h_address` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_7`
--

CREATE TABLE `data_7` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `taken_by` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_8`
--

CREATE TABLE `data_8` (
  `id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `auto_off` tinyint(4) NOT NULL,
  `data_progress` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_1`
--
ALTER TABLE `data_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_2`
--
ALTER TABLE `data_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_3`
--
ALTER TABLE `data_3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_4`
--
ALTER TABLE `data_4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_5`
--
ALTER TABLE `data_5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_6`
--
ALTER TABLE `data_6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_7`
--
ALTER TABLE `data_7`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_8`
--
ALTER TABLE `data_8`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_1`
--
ALTER TABLE `data_1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_2`
--
ALTER TABLE `data_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_3`
--
ALTER TABLE `data_3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_4`
--
ALTER TABLE `data_4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_5`
--
ALTER TABLE `data_5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_6`
--
ALTER TABLE `data_6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_7`
--
ALTER TABLE `data_7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_8`
--
ALTER TABLE `data_8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
