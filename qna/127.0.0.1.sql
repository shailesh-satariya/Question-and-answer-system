-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2013 at 04:58 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qna`
--
CREATE DATABASE `qna` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `qna`;

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE IF NOT EXISTS `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(45, 0),
(46, 1),
(46, 2),
(47, 3),
(47, 4),
(48, 5),
(48, 6),
(49, 7),
(49, 8);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('answer','question') NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `type`, `title`, `content`, `created`, `modified`, `user_id`) VALUES
(1, 'answer', '', '', '2013-04-27 01:33:00', '2013-04-27 01:33:00', 0),
(2, 'question', 'Blog 6', 'dfsdsvgs', '2013-04-27 01:37:40', '2013-04-27 01:37:40', 1),
(3, 'question', 'Blog 6', 'dfsdsvgs', '2013-04-27 01:42:24', '2013-04-27 01:42:24', 1),
(4, 'question', 'Blog 6', 'dfsdsvgs', '2013-04-27 01:42:51', '2013-04-27 01:42:51', 1),
(5, 'question', 'Blog 6', 'dfsdsvgs', '2013-04-27 01:43:53', '2013-04-27 01:43:53', 1),
(6, 'question', 'Blog 7', 'sankx', '2013-04-27 01:48:26', '2013-04-27 01:48:26', 1),
(7, 'question', 'Blog 8', 'sankx', '2013-04-27 01:50:15', '2013-04-27 01:50:15', 1),
(8, 'answer', 'New Test', 'New Test', '2013-04-27 13:08:20', '2013-04-27 13:08:20', 0),
(9, 'answer', 'New Test', 'New Test', '2013-04-27 13:11:55', '2013-04-27 13:11:55', 0),
(10, 'question', 'Test Question', 'Test Question', '2013-04-27 14:24:35', '2013-04-27 14:24:35', 1),
(11, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:09:44', '2013-04-27 22:09:44', 1),
(12, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:10:10', '2013-04-27 22:10:10', 1),
(13, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:15:42', '2013-04-27 22:15:42', 1),
(14, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:20:17', '2013-04-27 22:20:17', 1),
(15, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:20:51', '2013-04-27 22:20:51', 1),
(16, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:21:44', '2013-04-27 22:21:44', 1),
(17, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:25:58', '2013-04-27 22:25:58', 1),
(18, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 22:26:45', '2013-04-27 22:26:45', 1),
(19, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 23:35:00', '2013-04-27 23:35:00', 1),
(20, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 23:35:49', '2013-04-27 23:35:49', 1),
(21, 'question', 'Test Question 2', 'Test Question 2', '2013-04-27 23:59:34', '2013-04-27 23:59:34', 1),
(22, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 00:45:19', '2013-04-28 00:45:19', 1),
(23, 'question', 'Blog 7', 'Blog 7', '2013-04-28 00:58:33', '2013-04-28 00:58:33', 1),
(24, 'question', 'Blog 7', 'Blog 7', '2013-04-28 01:01:28', '2013-04-28 01:01:28', 1),
(25, 'question', 'Blog 7', 'Blog 7', '2013-04-28 01:03:39', '2013-04-28 01:03:39', 1),
(26, 'question', 'Blog 7', 'Blog 7', '2013-04-28 01:04:17', '2013-04-28 01:04:17', 1),
(27, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 01:54:08', '2013-04-28 01:54:08', 2),
(28, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 01:55:49', '2013-04-28 01:55:49', 2),
(29, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 01:58:44', '2013-04-28 01:58:44', 2),
(30, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 02:14:41', '2013-04-28 02:14:41', 2),
(31, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 02:15:06', '2013-04-28 02:15:06', 2),
(32, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 02:23:26', '2013-04-28 02:23:26', 2),
(33, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:02:48', '2013-04-28 14:02:48', 2),
(34, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:42:49', '2013-04-28 14:42:49', 2),
(35, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:44:59', '2013-04-28 14:44:59', 2),
(36, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:45:48', '2013-04-28 14:45:48', 2),
(37, 'answer', 'Test Question 2', 'Test Question 2', '2013-04-28 14:48:42', '2013-04-28 14:48:42', 0),
(38, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:53:42', '2013-04-28 14:53:42', 2),
(39, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 14:57:42', '2013-04-28 14:57:42', 2),
(40, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:03:01', '2013-04-28 15:03:01', 2),
(41, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:03:30', '2013-04-28 15:03:30', 2),
(42, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:04:07', '2013-04-28 15:04:07', 2),
(43, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:05:04', '2013-04-28 15:05:04', 2),
(44, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:26:38', '2013-04-28 15:26:38', 2),
(45, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:48:00', '2013-04-28 15:48:01', 2),
(46, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 15:50:06', '2013-04-28 15:50:06', 2),
(47, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 16:00:53', '2013-04-28 16:00:53', 2),
(48, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 16:27:39', '2013-04-28 16:27:39', 2),
(49, 'question', 'Test Question 2', 'Test Question 2', '2013-04-28 16:29:23', '2013-04-28 16:29:23', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`, `slug`) VALUES
(1, 'php', ''),
(2, 'java', ''),
(3, 'php', ''),
(4, 'java', ''),
(5, 'php', ''),
(6, 'java', ''),
(7, 'php', ''),
(8, 'java', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created`) VALUES
(1, 'shailesh.satariya', 'shailesh.satariya@gmail.com', 'dbb51ed761e630095e4baf01517d8373a6b2d8dd', '2013-04-23 00:35:09'),
(2, 'sankyz', 'shailesh.satariya@gmail.com', '1a79c01a373dba54afc650dfcf2ae91ac82120dd', '2013-04-28 01:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voted` datetime NOT NULL,
  `type` enum('up','down','flag','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
