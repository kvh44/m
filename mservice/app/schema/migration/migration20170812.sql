ALTER TABLE `mpost` CHANGE `description` `description` TEXT NOT NULL;

ALTER TABLE `mpost` ADD `category_id` INT(3) DEFAULT NULL AFTER `internal_id`;

ALTER TABLE `mpost`
  ADD CONSTRAINT `FK_CA565GYFDTYGYG454GUIG` FOREIGN KEY (`category_id`) REFERENCES `mcategory` (`id`);



ALTER TABLE `mpost` ADD `draft_id` INT(11) DEFAULT NULL AFTER `category_id`;

ALTER TABLE `mpost`
  ADD CONSTRAINT `FK_CA5FYFUYDTRTD5675HUH` FOREIGN KEY (`draft_id`) REFERENCES `mdraft` (`id`);

ALTER TABLE `mpost` ADD `is_zh` TINYINT(1) NULL DEFAULT NULL AFTER `description`, 
ADD `is_fr` TINYINT(1) NULL DEFAULT NULL AFTER `is_zh`, 
ADD `is_en` TINYINT(1) NULL DEFAULT NULL AFTER `is_fr`;



ALTER TABLE `mdraft` CHANGE `content` `content` TEXT NOT NULL;

ALTER TABLE `mdraft` ADD `category_id` INT(3) DEFAULT NULL AFTER `internal_id`;

ALTER TABLE `mdraft`
  ADD CONSTRAINT `FK_CA56GGFYFUY8678RS` FOREIGN KEY (`category_id`) REFERENCES `mcategory` (`id`);

ALTER TABLE `mdraft` ADD `is_zh` TINYINT(1) NULL DEFAULT NULL AFTER `content`, 
ADD `is_fr` TINYINT(1) NULL DEFAULT NULL AFTER `is_zh`, 
ADD `is_en` TINYINT(1) NULL DEFAULT NULL AFTER `is_fr`;






CREATE TABLE `mmessage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `internal_id` varchar(255) NOT NULL,
  `category_id` int(3) DEFAULT NULL,
  `content` text NOT NULL,
  `is_zh` tinyint(1) DEFAULT NULL,
  `is_fr` tinyint(1) DEFAULT NULL,
  `is_en` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_by_user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `mmessage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `internal_id` (`internal_id`),
  ADD KEY `user_id` (`user_id`);


ALTER TABLE `mmessage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `mmessage`
  ADD CONSTRAINT `FK_CA5FYGIUGIU45456BHUH` FOREIGN KEY (`user_id`) REFERENCES `muser` (`id`);