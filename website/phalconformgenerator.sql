-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2015 at 04:42 PM
-- Server version: 5.6.17
-- PHP Version: 5.6.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `phalconformgenerator`
--
CREATE DATABASE IF NOT EXISTS `phalconformgenerator` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `phalconformgenerator`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(65) NOT NULL,
  `lastname` varchar(65) NOT NULL,
  `email` varchar(105) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `biography` text NOT NULL,
  `short_description` varchar(1000) NOT NULL,
  `user_status_id` int(11) DEFAULT NULL,
  `last_date_login` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_status_id` (`user_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Truncate table before insert `user_status`
--

TRUNCATE TABLE `user_status`;
--
-- Dumping data for table `user_status`
--

INSERT INTO `user_status` (`id`, `name`) VALUES
(1, 'Account created'),
(2, 'Active'),
(3, 'Banned'),
(4, 'Muted');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_status_id`) REFERENCES `user_status` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
