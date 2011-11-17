-- phpMyAdmin SQL Dump
-- version 2.11.5
-- http://www.phpmyadmin.net
--
-- Host: mysql42.kontrollpanelen.se:3306
-- Generation Time: Nov 15, 2011 at 06:57 PM
-- Server version: 5.0.77
-- PHP Version: 5.2.4-2ubuntu5.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `web75757_sandbox`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_bookings`
--

CREATE TABLE IF NOT EXISTS `timeslot_bookings` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `duration` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `timeslot_bookings`
--

INSERT INTO `timeslot_bookings` (`id`, `user_id`, `resource_id`, `start`, `duration`) VALUES
(4, 1, 1, '2011-10-24 00:00:00', 86400),
(7, 2, 1, '2011-10-26 00:00:00', 86400),
(8, 1, 1, '2011-10-28 00:00:00', 86400);

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_resources`
--

CREATE TABLE IF NOT EXISTS `timeslot_resources` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `timeslot_resources`
--

INSERT INTO `timeslot_resources` (`id`, `name`) VALUES
(1, 'Tvättmaskin'),
(2, 'Torktumlare');

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_resources_schedule`
--

CREATE TABLE IF NOT EXISTS `timeslot_resources_schedule` (
  `resource_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  KEY `schedule_id` (`schedule_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `timeslot_resources_schedule`
--

INSERT INTO `timeslot_resources_schedule` (`resource_id`, `schedule_id`) VALUES
(1, 1),
(2, 2),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_schedule`
--

CREATE TABLE IF NOT EXISTS `timeslot_schedule` (
  `id` int(11) NOT NULL auto_increment,
  `start` date NOT NULL,
  `duration` int(11) NOT NULL,
  `repeat` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `timeslot_schedule`
--

INSERT INTO `timeslot_schedule` (`id`, `start`, `duration`, `repeat`) VALUES
(1, '2011-10-24', 86400, 2),
(2, '2011-10-28', 86400, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `timeslot_bookings`
--
ALTER TABLE `timeslot_bookings`
  ADD CONSTRAINT `timeslot_bookings_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `timeslot_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timeslot_resources_schedule`
--
ALTER TABLE `timeslot_resources_schedule`
  ADD CONSTRAINT `timeslot_resources_schedule_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `timeslot_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `timeslot_resources_schedule_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `timeslot_schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
