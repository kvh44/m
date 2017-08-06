-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-08-07 00:49:04
-- 服务器版本： 5.7.17
-- PHP Version: 5.5.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `massage`
--

-- --------------------------------------------------------

--
-- 表的结构 `mcategory`
--

CREATE TABLE `mcategory` (
  `id` int(3) NOT NULL,
  `category_zh` varchar(20) CHARACTER SET latin1 NOT NULL,
  `category_en` varchar(50) CHARACTER SET latin1 NOT NULL,
  `category_fr` varchar(50) CHARACTER SET latin1 NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_defaut` tinyint(1) NOT NULL,
  `score` int(3) NOT NULL,
  `internal_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `slug` varchar(255) CHARACTER SET latin1 NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mcategory`
--
ALTER TABLE `mcategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_en` (`category_en`),
  ADD UNIQUE KEY `category_zh` (`category_zh`),
  ADD UNIQUE KEY `category_fr` (`category_fr`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `internal_id` (`internal_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `mcategory`
--
ALTER TABLE `mcategory`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;






CREATE TABLE `murl` (
  `id` int(255) NOT NULL,
  `internal_id` varchar(255) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `url` tinytext NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_defaut` tinyint(1) NOT NULL,
  `is_normal` tinyint(1) NOT NULL,
  `score` int(3) NOT NULL,
  `is_zh` tinyint(1) NOT NULL,
  `is_en` tinyint(1) NOT NULL,
  `is_fr` tinyint(1) NOT NULL,
  `category_id` int(3) NOT NULL,
  `first_read` datetime DEFAULT NULL,
  `last_read` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `murl`
--
ALTER TABLE `murl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `domain` (`domain`),
  ADD KEY `FK_CA565GYFTF456FDT` (`category_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `murl`
--
ALTER TABLE `murl`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- 限制导出的表
--

--
-- 限制表 `murl`
--
ALTER TABLE `murl`
  ADD CONSTRAINT `FK_CA565GYFTF456FDT` FOREIGN KEY (`category_id`) REFERENCES `mcategory` (`id`);
