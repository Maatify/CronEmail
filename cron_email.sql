-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 17, 2024 at 08:37 AM
-- Server version: 8.3.0
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maatify`
--

-- --------------------------------------------------------

--
-- Table structure for table `cron_email`
--

CREATE TABLE `cron_email` (
  `id` int NOT NULL,
  `type_id` int NOT NULL DEFAULT '1' COMMENT '1=message; 2=confirm; 3=promotion',
  `ct_id` int NOT NULL DEFAULT '0',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `subject` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `record_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sent_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_email_block`
--

CREATE TABLE `cron_email_block` (
  `block_id` int NOT NULL,
  `ct_id` int NOT NULL DEFAULT '0',
  `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `admin_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_email_off`
--

CREATE TABLE `cron_email_off` (
  `off_id` int NOT NULL,
  `ct_id` int NOT NULL DEFAULT '0',
  `email` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:01',
  `admin_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_email`
--
ALTER TABLE `cron_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_email_block`
--
ALTER TABLE `cron_email_block`
  ADD PRIMARY KEY (`block_id`);

--
-- Indexes for table `cron_email_off`
--
ALTER TABLE `cron_email_off`
  ADD PRIMARY KEY (`off_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron_email`
--
ALTER TABLE `cron_email`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_email_block`
--
ALTER TABLE `cron_email_block`
  MODIFY `block_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_email_off`
--
ALTER TABLE `cron_email_off`
  MODIFY `off_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
