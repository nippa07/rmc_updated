-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 18, 2021 at 05:38 AM
-- Server version: 5.7.31
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clients`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uidUsers` varchar(50) DEFAULT NULL,
  `pwdUsers` varchar(100) DEFAULT NULL,
  `bname` varchar(100) DEFAULT NULL,
  `fname` varchar(20) DEFAULT NULL,
  `lname` varchar(20) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `emailUsers` varchar(50) DEFAULT NULL,
  `web` varchar(50) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `profile_image` varchar(250) DEFAULT 'placeholder.png',
  `bio` varchar(500) DEFAULT NULL,
  `lic` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `certified` tinyint(4) DEFAULT '0',
  `paying` tinyint(4) DEFAULT '0',
  `claimed` tinyint(4) DEFAULT '0',
  `userAdd` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uidUsers`, `pwdUsers`, `bname`, `fname`, `lname`, `phone`, `emailUsers`, `web`, `city`, `state`, `zip`, `type`, `profile_image`, `bio`, `lic`, `address`, `certified`, `paying`, `claimed`, `userAdd`) VALUES
(1, 'test', '$2y$10$hPJityVKSTzk3qT86rCDOeyAjYaNUX5je3391YGVnYgwIlcxtwvku', 'test painting', 'test', 'test', '(310) 255-6565', 'test@test.com', 'none', 'los angeles', 'ca', '90016', NULL, 'placeholder.png', NULL, NULL, NULL, 0, 0, 1, 0),
(2, 'sean', '$2y$10$/deHqL4Dojmdac0KyBuCje7BkJEiqcYoen70ciF47pcBlaa.av3qG', 'sean painting', 'sean', 'sean', '(310) 650-6565', 'sean@gmail.com', 'none', 'los angeles', 'ca', '90016', NULL, 'placeholder.png', NULL, NULL, NULL, 0, 0, 1, 0),
(3, 'john', '$2y$10$sZHj5Vm9nEBUsNdaZUMx5ejePFkQd4lIZ8Z8WFc3QwrsWF75tN3jW', 'john painting ', 'john', 'john', '(310) 653-6565', 'john@gmail.com', 'none', 'los angeles', 'ca', '90016', NULL, 'placeholder.png', NULL, NULL, NULL, 0, 0, 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
