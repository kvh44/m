ALTER TABLE `mphoto`
ADD COLUMN `photo_icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL AFTER `photo_small`;