ALTER TABLE `box_boxes` ADD `note` text COLLATE 'utf8_czech_ci' NULL;
ALTER TABLE `box_boxes` ADD `disabled` INT(1) NOT NULL DEFAULT '0' AFTER `note`;