

CREATE TABLE `mcategory` (
  `id` int(3) NOT NULL,
  `category_zh` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `category_en` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category_fr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_defaut` tinyint(1) NOT NULL,
  `score` int(3) NOT NULL,
  `internal_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `mcategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_en` (`category_en`),
  ADD UNIQUE KEY `category_zh` (`category_zh`),
  ADD UNIQUE KEY `category_fr` (`category_fr`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `internal_id` (`internal_id`);


ALTER TABLE `mcategory`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;




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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `murl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `domain` (`domain`),
  ADD KEY `FK_CA565GYFTF456FDT` (`category_id`);


ALTER TABLE `murl`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `murl`
  ADD CONSTRAINT `FK_CA565GYFTF456FDT` FOREIGN KEY (`category_id`) REFERENCES `mcategory` (`id`);
