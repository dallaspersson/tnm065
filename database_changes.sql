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
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `timeslot_bookings_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `timeslot_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;


-- --------------------------------------------------------

--
-- Table structure for table `timeslot_resources`
--

CREATE TABLE IF NOT EXISTS `timeslot_resources` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


-- ---------------------------------------------------------------

--
-- Alter `timeslot_bookings` to include only start time and duration
--


ALTER TABLE `timeslot_bookings`
	DROP COLUMN `user_id`;

ALTER TABLE `timeslot_bookings`
	DROP FOREIGN KEY `timeslot_bookings_ibfk_1`;
	
ALTER TABLE `timeslot_bookings`
	DROP COLUMN `resource_id`;
	
-- ---------------------------------------------------------------

--
-- Change name of timeslot_bookings to timeslot_slots
--
ALTER TABLE `wordpress`.`timeslot_bookings` RENAME TO  `wordpress`.`timeslot_slots` ;

drop table `wordpress`.`timeslot_resources_schedule`;
drop table `wordpress`.`timeslot_bookings`;

-- ---------------------------------------------------------------

--
-- Create new table, timeslot_bookings, containing bookings
--
CREATE TABLE IF NOT EXISTS `timeslot_bookings` (
  `id` int(11) NOT NULL auto_increment,
  `slots` VARCHAR(1024) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE timeslot_bookings
ADD COLUMN resource_id int(11),
ADD CONSTRAINT FK_timeslot_bookings
FOREIGN KEY (resource_id) REFERENCES timeslot_resources(id)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE wordpress.timeslot_bookings CHANGE slots slot_id int(11);

ALTER TABLE timeslot_bookings
ADD CONSTRAINT FK_timeslot_bookings_slot_id
FOREIGN KEY (slot_id) REFERENCES timeslot_slots(id)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE `wordpress`.`timeslot_bookings` DROP FOREIGN KEY `resource_id` ;

-- ------------------------------------------

--
-- Create table timeslot_schedules
--

CREATE TABLE IF NOT EXISTS `timeslot_schedules` (
  `id` int(11) NOT NULL auto_increment,
  `start` datetime NOT NULL,
  `duration` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- -------------------------------------------

--
-- Create many-to-many relation table between timeslot_schedules and timeslot_slots
--

CREATE TABLE IF NOT EXISTS `timeslot_schedules_slots` (
	`schedule_id` int(11) NOT NULL,
	`slot_id` int(11) NOT NULL,
	KEY `schedule_id` (`schedule_id`),
	KEY `slot_id` (`slot_id`),
	CONSTRAINT `timeslot_schedules_fk`
		FOREIGN KEY (`schedule_id`)
		REFERENCES `timeslot_schedules` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT `timeslot_slot_fk`
		FOREIGN KEY (`slot_id`)
		REFERENCES `timeslot_slots` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------

--
-- Make start time and duration a unique key in timeslot_slots
--

ALTER TABLE `timeslot_slots`
ADD UNIQUE `unique_start_duration` (`start`, `duration`);

-- ----------------------------

--
-- Create relation table between timeslot_resources and timeslot_schedules
--

CREATE TABLE IF NOT EXISTS `timeslot_schedules_resources` (
	`schedule_id` int(11) NOT NULL,
	`resource_id` int(11) NOT NULL,
	UNIQUE `unique_resource_schedule` (`schedule_id`, `resource_id`),
	CONSTRAINT `timeslot_schedules_fk1`
		FOREIGN KEY (`schedule_id`)
		REFERENCES `timeslot_schedules` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	CONSTRAINT `timeslot_resources_fk1`
		FOREIGN KEY (`resource_id`)
		REFERENCES `timeslot_resources` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------------

--
-- Add schedule_id to timeslot_resources
--

ALTER TABLE `timeslot_resources`
	ADD COLUMN `schedule_id` int(11),
	ADD CONSTRAINT `timeslot_schedule_id_in_resources`
	FOREIGN KEY (`schedule_id`)
	REFERENCES `timeslot_schedules` (`id`)
	ON UPDATE CASCADE
	ON DELETE CASCADE;


-- -----------------------------------

--
-- Add `repetition` to `timeslot_bookings`
--

ALTER TABLE `timeslot_bookings`
	ADD COLUMN `repetition` INT(11) NOT NULL AFTER `resource_id`;
