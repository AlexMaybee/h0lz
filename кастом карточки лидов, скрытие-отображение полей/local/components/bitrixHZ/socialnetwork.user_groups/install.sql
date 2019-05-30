CREATE TABLE `crm_parent_group` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`child_ids` TEXT NULL COLLATE 'utf8_unicode_ci',
	`description` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	`name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`)
);