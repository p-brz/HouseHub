-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 04-Dez-2013 às 15:17
-- Versão do servidor: 5.5.32
-- versão do PHP: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `househubtest`
--
CREATE DATABASE IF NOT EXISTS `househubtest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `househubtest`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups_elements`
--

CREATE TABLE IF NOT EXISTS `groups_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `addition_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups_visuals`
--

CREATE TABLE IF NOT EXISTS `groups_visuals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `group_name` varchar(20) DEFAULT NULL,
  `group_image_id` int(11) NOT NULL DEFAULT '0',
  `set_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `iconpacks`
--

CREATE TABLE IF NOT EXISTS `iconpacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `folder` varchar(66) NOT NULL,
  `target` varchar(30) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `resource` varchar(35) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `address` varchar(66) NOT NULL,
  `scheme_name` varchar(44) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `parent_index` int(11) NOT NULL DEFAULT '-1',
  `validated` tinyint(4) NOT NULL DEFAULT '0',
  `connected` tinyint(4) NOT NULL DEFAULT '1',
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `objects`
--

INSERT INTO `objects` (`id`, `type`, `address`, `scheme_name`, `parent_id`, `parent_index`, `validated`, `connected`, `registration_date`) VALUES
(1, 'metalamp', '127.0.0.1', '', 0, -1, 1, 1, '2013-12-03 19:56:34'),
(2, 'lamp', '127.0.0.1/objects/0', 'basicLamp', 1, 0, 1, 1, '2013-12-03 19:56:35'),
(3, 'lamp', '127.0.0.1/objects/1', 'basicLamp', 1, 1, 1, 1, '2013-12-03 19:56:35'),
(4, 'lamp', '127.0.0.1/objects/2', 'basicLamp', 1, 2, 1, 1, '2013-12-03 19:56:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects_iconpacks`
--

CREATE TABLE IF NOT EXISTS `objects_iconpacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `iconpack_id` int(11) NOT NULL,
  `set_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects_intents`
--

CREATE TABLE IF NOT EXISTS `objects_intents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `address` varchar(44) NOT NULL,
  `scheme_name` varchar(66) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_index` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Extraindo dados da tabela `objects_intents`
--

INSERT INTO `objects_intents` (`id`, `type`, `address`, `scheme_name`, `parent_id`, `parent_index`, `request_date`) VALUES
(34, 'metalamp', '127.0.0.1', '', 0, -1, '2013-12-04 04:12:21'),
(35, 'lamp', '127.0.0.1/objects/0', 'basicLamp', 34, 0, '2013-12-04 04:12:21'),
(36, 'lamp', '127.0.0.1/objects/1', 'basicLamp', 34, 1, '2013-12-04 04:12:21'),
(37, 'lamp', '127.0.0.1/objects/2', 'basicLamp', 34, 2, '2013-12-04 04:12:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects_names`
--

CREATE TABLE IF NOT EXISTS `objects_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_name` varchar(25) NOT NULL,
  `set_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects_services`
--

CREATE TABLE IF NOT EXISTS `objects_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `objects_services`
--

INSERT INTO `objects_services` (`id`, `object_id`, `name`) VALUES
(1, 2, 'ligar'),
(2, 2, 'desligar'),
(3, 3, 'ligar'),
(4, 3, 'desligar'),
(5, 4, 'ligar'),
(6, 4, 'desligar');

-- --------------------------------------------------------

--
-- Estrutura da tabela `objects_status`
--

CREATE TABLE IF NOT EXISTS `objects_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `objects_status`
--

INSERT INTO `objects_status` (`id`, `object_id`, `name`, `value`) VALUES
(1, 2, 'ligada', 0),
(2, 3, 'ligada', 0),
(3, 4, 'ligada', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `system_logs`
--

CREATE TABLE IF NOT EXISTS `system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `method` varchar(30) NOT NULL,
  `message` varchar(50) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Extraindo dados da tabela `system_logs`
--

INSERT INTO `system_logs` (`id`, `user_id`, `status`, `method`, `message`, `log_date`) VALUES
(1, -1, 0, 'register', '@empty', '2013-12-03 19:52:49'),
(2, -1, 0, 'cougar', '@empty', '2013-12-03 19:52:59'),
(3, -1, 0, 'login', '@login_null_username', '2013-12-03 19:53:06'),
(4, -1, 0, 'login', '@login_wrong', '2013-12-03 19:53:22'),
(5, 0, 1, 'login', '@success', '2013-12-03 19:53:28'),
(6, 0, 1, 'connect', '@success', '2013-12-03 19:54:46'),
(7, 0, 0, 'validate', '@bad_parameters', '2013-12-03 19:55:55'),
(8, 0, 0, 'validate', '@bad_parameters', '2013-12-03 19:56:06'),
(9, 0, 1, 'validate', '@success', '2013-12-03 19:56:35'),
(10, 0, 1, 'list_objects', '@success', '2013-12-03 20:14:11'),
(11, 0, 1, 'list_objects', '@success', '2013-12-03 20:23:48'),
(12, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:22'),
(13, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:31'),
(14, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:54'),
(15, 0, 1, 'list_objects', '@success', '2013-12-03 20:25:21'),
(16, 0, 1, 'list_objects', '@success', '2013-12-03 20:25:30'),
(17, 0, 1, 'connect', '@success', '2013-12-04 04:12:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `nickname` varchar(25) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `username` varchar(22) NOT NULL,
  `password` blob NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `nickname`, `gender`, `username`, `password`, `registration_date`) VALUES
(0, 'Administrator', 'Administrator', 'M', 'adm', 0x37633461386430396361333736326166363165353935323039343364633236343934663839343162, '2013-12-03 19:51:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users_view_permissions`
--

CREATE TABLE IF NOT EXISTS `users_view_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `grant_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users_visuals`
--

CREATE TABLE IF NOT EXISTS `users_visuals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `set_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
