CREATE TABLE `crm_warehouse` (
	`item_count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`check` TINYINT(2) UNSIGNED NULL DEFAULT NULL,
	`item_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`owner_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`item_id`),
	INDEX `owner_id` (`owner_id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;

CREATE TABLE `crm_product_ref_owner` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`item_id` INT(11) NULL DEFAULT '0',
	`owner_id` INT(11) NULL DEFAULT '0',
	`type` ENUM('deal','lead','enather') NULL DEFAULT 'enather' COLLATE 'utf8_unicode_ci',
	`reserve_count` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `item_id_owner_id_type` (`item_id`, `owner_id`, `type`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;

CREATE TABLE `crm_deal_type_ref_stage` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`deal_type_id` INT(10) UNSIGNED NOT NULL,
	`deal_stage_ids` TEXT NOT NULL COLLATE 'utf8_unicode_ci',
	`delivery_id` INT(10) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_unicode_ci'
ENGINE=InnoDB;





