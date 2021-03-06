CREATE TABLE `mip` (
  `id` int(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `is_allowed` tinyint(1) NOT NULL,
  `is_blacked` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `mtoken` (
  `id` int(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `token_type` int(2) NOT NULL,
  `user_id` int(255) NOT NULL,
  `token_expired_time` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `mip`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mip_unique_index` (`ip`,`user_id`);


ALTER TABLE `mtoken`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);


ALTER TABLE `mip`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE `mtoken`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;



ALTER TABLE `muser`
ADD COLUMN `shop_name` varchar(255) COLLATE utf8_unicode_ci NULL AFTER `shop_address`;

ALTER TABLE `muser`
ADD COLUMN `other_web_reference` varchar(255) COLLATE utf8_unicode_ci NULL AFTER `other_web`;

ALTER TABLE `muser`
ADD COLUMN `draft_id` int(11) COLLATE utf8_unicode_ci NULL AFTER `other_web_reference`;

ALTER TABLE `muser`
  ADD CONSTRAINT `FK_DC4EF770A76ED5786876` FOREIGN KEY (`draft_id`) REFERENCES `mdraft` (`id`);

ALTER TABLE `muser`
  ADD KEY `country_id` (`country_id`),
  ADD KEY `location_id` (`location_id`);

ALTER TABLE `muser` CHANGE `draft_id` `draft_id` INT(11) DEFAULT NULL;
ALTER TABLE `muser` CHANGE `country_id` `country_id` INT(11) DEFAULT NULL;
ALTER TABLE `muser` CHANGE `location_id` `location_id` INT(11) DEFAULT NULL;

ALTER TABLE `muser`
  ADD CONSTRAINT `FK_DC4EF77SHFSHFSH5675675` FOREIGN KEY (`country_id`) REFERENCES `mcountry` (`id`);

ALTER TABLE `muser`
  ADD CONSTRAINT `FK_GGYUGYGVDF345356` FOREIGN KEY (`location_id`) REFERENCES `mlocation` (`id`);