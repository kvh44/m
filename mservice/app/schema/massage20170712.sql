-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-07-12 00:29:11
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
-- 表的结构 `madmin`
--

CREATE TABLE `madmin` (
  `id` int(11) NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin_password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `encryption_method` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indication` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_connected` datetime DEFAULT NULL,
  `allowed_ip` tinyblob,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mcountry`
--

CREATE TABLE `mcountry` (
  `id` int(11) NOT NULL,
  `country_zh` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `country_fr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `country_en` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mdraft`
--

CREATE TABLE `mdraft` (
  `id` int(11) NOT NULL,
  `other_web` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` blob NOT NULL,
  `is_new_content` tinyint(1) DEFAULT NULL,
  `is_updated_content` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_by_user_id` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mip`
--

CREATE TABLE `mip` (
  `id` int(255) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(255) NOT NULL,
  `is_allowed` tinyint(1) NOT NULL,
  `is_blacked` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mlocation`
--

CREATE TABLE `mlocation` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city_zh` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_fr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city_en` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_number` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `post_number_zh` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mpassword`
--

CREATE TABLE `mpassword` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `encryption_method` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `indication` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mphoto`
--

CREATE TABLE `mphoto` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `photo_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `photo_origin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo_medium` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo_small` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `view_number` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_by_user_id` int(11) DEFAULT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mpost`
--

CREATE TABLE `mpost` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` blob NOT NULL,
  `displayed_home` tinyint(1) DEFAULT NULL,
  `view_number` int(11) DEFAULT NULL,
  `is_from_other_web` tinyint(1) DEFAULT NULL,
  `other_web` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_by_user_id` int(11) DEFAULT NULL,
  `is_synchronized_by_cache` tinyint(1) DEFAULT NULL,
  `is_synchronized_by_search` tinyint(1) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `top_time` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `mtoken`
--

CREATE TABLE `mtoken` (
  `id` int(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `token_type` int(2) NOT NULL,
  `user_id` int(255) NOT NULL,
  `token_expired_time` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `muser`
--

CREATE TABLE `muser` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `wechat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_number` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `skin_color` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `hour_price` int(11) DEFAULT NULL,
  `hour_price_unit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `night_price` int(11) DEFAULT NULL,
  `night_price_unit` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `shop_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` tinyblob,
  `translated_description` tinyblob,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `is_single` tinyint(1) NOT NULL DEFAULT '1',
  `is_shop` tinyint(1) NOT NULL DEFAULT '0',
  `is_zh` tinyint(1) DEFAULT NULL,
  `is_en` tinyint(1) DEFAULT NULL,
  `is_fr` tinyint(1) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_test` tinyint(1) DEFAULT '0',
  `is_synchronized_by_cache` tinyint(1) DEFAULT NULL,
  `is_synchronized_by_search` tinyint(1) DEFAULT NULL,
  `is_from_other_web` tinyint(1) DEFAULT NULL,
  `other_web` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `other_web_reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `draft_id` int(11) DEFAULT NULL,
  `view_number` int(11) DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `internal_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `external_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `top_time` datetime DEFAULT NULL,
  `last_synchronized_from_other_web_time` datetime DEFAULT NULL,
  `payment_expired_time` datetime DEFAULT NULL,
  `allowed_ip` tinyblob,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `madmin`
--
ALTER TABLE `madmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`),
  ADD UNIQUE KEY `admin_username` (`admin_username`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `admin_password` (`admin_password`),
  ADD KEY `encryption_method` (`encryption_method`),
  ADD KEY `indication` (`indication`),
  ADD KEY `salt` (`salt`);

--
-- Indexes for table `mcountry`
--
ALTER TABLE `mcountry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `country_zh` (`country_zh`),
  ADD UNIQUE KEY `country_fr` (`country_fr`),
  ADD UNIQUE KEY `country_en` (`country_en`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `mdraft`
--
ALTER TABLE `mdraft`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `other_web` (`other_web`),
  ADD KEY `link` (`link`),
  ADD KEY `link_id` (`link_id`);

--
-- Indexes for table `mip`
--
ALTER TABLE `mip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mip_unique_index` (`ip`,`user_id`);

--
-- Indexes for table `mlocation`
--
ALTER TABLE `mlocation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_number` (`post_number`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `post_number_zh` (`post_number_zh`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_zh` (`city_zh`),
  ADD KEY `city_fr` (`city_fr`),
  ADD KEY `city_en` (`city_en`),
  ADD KEY `internal_id` (`internal_id`);

--
-- Indexes for table `mpassword`
--
ALTER TABLE `mpassword`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `password` (`password`),
  ADD KEY `salt` (`salt`),
  ADD KEY `encryption_method` (`encryption_method`),
  ADD KEY `indication` (`indication`);

--
-- Indexes for table `mphoto`
--
ALTER TABLE `mphoto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `photo_type` (`photo_type`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `mpost`
--
ALTER TABLE `mpost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `mtoken`
--
ALTER TABLE `mtoken`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `muser`
--
ALTER TABLE `muser`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `internal_token` (`internal_token`),
  ADD UNIQUE KEY `external_token` (`external_token`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `telephone_2` (`telephone`),
  ADD KEY `wechat` (`wechat`),
  ADD KEY `email_2` (`email`),
  ADD KEY `nickname` (`nickname`),
  ADD KEY `FK_DC4EF770A76ED5786876` (`draft_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `location_id` (`location_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `madmin`
--
ALTER TABLE `madmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `mcountry`
--
ALTER TABLE `mcountry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `mdraft`
--
ALTER TABLE `mdraft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `mip`
--
ALTER TABLE `mip`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `mlocation`
--
ALTER TABLE `mlocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `mpassword`
--
ALTER TABLE `mpassword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- 使用表AUTO_INCREMENT `mphoto`
--
ALTER TABLE `mphoto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- 使用表AUTO_INCREMENT `mpost`
--
ALTER TABLE `mpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;
--
-- 使用表AUTO_INCREMENT `mtoken`
--
ALTER TABLE `mtoken`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `muser`
--
ALTER TABLE `muser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- 限制导出的表
--

--
-- 限制表 `mpassword`
--
ALTER TABLE `mpassword`
  ADD CONSTRAINT `FK_1BC5EBE1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `muser` (`id`);

--
-- 限制表 `mphoto`
--
ALTER TABLE `mphoto`
  ADD CONSTRAINT `FK_D73DCE22A76ED395` FOREIGN KEY (`user_id`) REFERENCES `muser` (`id`);

--
-- 限制表 `mpost`
--
ALTER TABLE `mpost`
  ADD CONSTRAINT `FK_DC4EF770A76ED395` FOREIGN KEY (`user_id`) REFERENCES `muser` (`id`);

--
-- 限制表 `muser`
--
ALTER TABLE `muser`
  ADD CONSTRAINT `FK_DC4EF770A76ED5786876` FOREIGN KEY (`draft_id`) REFERENCES `mdraft` (`id`),
  ADD CONSTRAINT `FK_DC4EF77SHFSHFSH5675675` FOREIGN KEY (`country_id`) REFERENCES `mcountry` (`id`),
  ADD CONSTRAINT `FK_GGYUGYGVDF345356` FOREIGN KEY (`location_id`) REFERENCES `mlocation` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
