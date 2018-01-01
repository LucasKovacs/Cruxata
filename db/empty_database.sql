-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2014 at 10:48 PM
-- Server version: 5.5.36-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xtremega_c5r9u4x0a9t0a`
--

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_academy`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_academy` (
  `academy_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `academy_current_build` text NOT NULL,
  `academy_warrior` smallint(5) unsigned NOT NULL DEFAULT '0',
  `academy_spearman` smallint(5) unsigned NOT NULL DEFAULT '0',
  `academy_infantryman` smallint(5) unsigned NOT NULL DEFAULT '0',
  `academy_swordsman` smallint(5) unsigned NOT NULL DEFAULT '0',
  `academy_crossbowman` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`academy_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_armies`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_armies` (
  `army_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `army_user_id` int(10) unsigned NOT NULL,
  `army_receptor_id` int(10) unsigned NOT NULL,
  `army_mission` smallint(1) unsigned NOT NULL DEFAULT '0',
  `army_arrival` int(10) unsigned NOT NULL,
  `army_return` int(10) unsigned NOT NULL,
  `army_current` int(10) unsigned NOT NULL DEFAULT '0',
  `army_troops` text NOT NULL,
  `army_gold` int(10) unsigned NOT NULL DEFAULT '0',
  `army_stone` int(10) unsigned NOT NULL DEFAULT '0',
  `army_wood` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`army_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_armory`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_armory` (
  `armory_user_id` int(10) unsigned NOT NULL,
  `armory_current_build` text NOT NULL,
  `armory_gauntlet` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_boot` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_helmet` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_shield` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_breastplate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_hammer` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_spear` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_ax` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_sword` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armory_crossbow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `id_owner` (`armory_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_banned`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_banned` (
  `banned_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banned_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `banned_reason` text NOT NULL,
  `banned_since` int(10) unsigned NOT NULL DEFAULT '0',
  `banned_until` int(10) unsigned NOT NULL DEFAULT '0',
  `banned_by` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`banned_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_buildings`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_buildings` (
  `building_user_id` int(10) unsigned NOT NULL,
  `building_current_build` tinytext NOT NULL,
  `building_academy` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_armory` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_barracks` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_fortified_wall` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_goldmine` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_market` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_sawmill` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_stonemine` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_watchtower` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `building_workshop` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`building_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_market`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_market` (
  `market_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `market_resource` varchar(32) NOT NULL,
  `market_resource_base` double(132,8) unsigned NOT NULL DEFAULT '1000000.00000000',
  `market_resource_actual` double(132,8) unsigned NOT NULL DEFAULT '1000000.00000000',
  `market_resource_previous` double(132,8) unsigned NOT NULL DEFAULT '1000000.00000000',
  `market_resource_ratio` tinyint(1) unsigned NOT NULL,
  `market_resource_factor` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`market_id`),
  UNIQUE KEY `market_resource` (`market_resource`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `c4r2u1x3a2t7a_market`
--

INSERT INTO `c4r2u1x3a2t7a_market` (`market_id`, `market_resource`, `market_resource_base`, `market_resource_actual`, `market_resource_previous`, `market_resource_ratio`, `market_resource_factor`) VALUES
(1, 'resource_wood', 10000000.00000000, 10000000.00000000, 10000000.00000000, 3, 0),
(2, 'resource_stone', 10000000.00000000, 10000000.00000000, 10000000.00000000, 2, 0),
(3, 'resource_gold', 10000000.00000000, 10000000.00000000, 10000000.00000000, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_messages`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_messages` (
  `message_id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_user_id` int(10) unsigned NOT NULL,
  `message_sender` int(10) unsigned NOT NULL,
  `message_date` bigint(11) NOT NULL DEFAULT '0',
  `message_type` tinyint(1) NOT NULL,
  `message_subject` varchar(64) DEFAULT NULL,
  `message_text` text,
  `message_viewed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_resources`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_resources` (
  `resource_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_diamonds` int(10) unsigned NOT NULL DEFAULT '0',
  `resource_gold` double(132,8) unsigned NOT NULL DEFAULT '0.00000000',
  `resource_stone` double(132,8) unsigned NOT NULL DEFAULT '1000.00000000',
  `resource_wood` double(132,8) unsigned NOT NULL DEFAULT '1000.00000000',
  PRIMARY KEY (`resource_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_statistics`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_statistics` (
  `statistic_user_id` int(10) unsigned NOT NULL,
  `statistic_points` double(132,8) unsigned NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`statistic_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_users`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  `user_level` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `user_onlinetime` int(10) unsigned NOT NULL,
  `user_updatetime` int(10) unsigned NOT NULL,
  `user_confederation_id` bigint(11) unsigned NOT NULL DEFAULT '0',
  `user_register_ip` varchar(15) NOT NULL,
  `user_last_ip` varchar(15) NOT NULL,
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_castle_img` text NOT NULL,
  `user_kingdom` varchar(8) NOT NULL,
  `user_feud` smallint(4) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `usuario` (`user_name`),
  UNIQUE KEY `email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_validations`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_validations` (
  `validation_id` bigint(19) unsigned NOT NULL AUTO_INCREMENT,
  `validation_user_id` int(10) unsigned NOT NULL,
  `validation_control_number` int(10) unsigned NOT NULL,
  `validation_hash` varchar(40) NOT NULL,
  PRIMARY KEY (`validation_id`),
  UNIQUE KEY `validation_control_number` (`validation_control_number`,`validation_hash`),
  UNIQUE KEY `validation_user_id` (`validation_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `c4r2u1x3a2t7a_workshop`
--

CREATE TABLE IF NOT EXISTS `c4r2u1x3a2t7a_workshop` (
  `workshop_user_id` int(10) unsigned NOT NULL,
  `workshop_current_build` text NOT NULL,
  `workshop_catapult` smallint(5) unsigned NOT NULL DEFAULT '0',
  `workshop_ram` smallint(5) unsigned NOT NULL DEFAULT '0',
  `workshop_tower` smallint(5) unsigned NOT NULL DEFAULT '0',
  `workshop_trebuchet` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`workshop_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
