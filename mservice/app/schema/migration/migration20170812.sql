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


--mdraft--
ALTER TABLE `mdraft` CHANGE `content` `content` TEXT NOT NULL;

ALTER TABLE `mdraft` ADD `category_id` INT(3) DEFAULT NULL AFTER `internal_id`;

ALTER TABLE `mdraft`
  ADD CONSTRAINT `FK_CA56GGFYFUY8678RS` FOREIGN KEY (`category_id`) REFERENCES `mcategory` (`id`);

ALTER TABLE `mdraft` ADD `is_zh` TINYINT(1) NULL DEFAULT NULL AFTER `content`, 
ADD `is_fr` TINYINT(1) NULL DEFAULT NULL AFTER `is_zh`, 
ADD `is_en` TINYINT(1) NULL DEFAULT NULL AFTER `is_fr`;