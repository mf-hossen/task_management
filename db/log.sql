\ALTER TABLE `tasks` CHANGE `status` `status` TINYINT(4) NULL DEFAULT NULL COMMENT '1=edit, 2=redesign,3=new ';

/* new column username add*/

ALTER TABLE `users` ADD `username` INT(100) NOT NULL AFTER `id`;

-- shanta
ALTER TABLE `users` CHANGE `username` `username` VARCHAR(100) NOT NULL;
--shanta
ALTER TABLE `tasks` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `tasks` CHANGE `status` `status` VARCHAR(10) NOT NULL COMMENT 'edit, redesign,new ';
ALTER TABLE `tasks` CHANGE `status` `task_type` TINYINT(4) NOT NULL COMMENT '1=edit, 2=redesign,3=new';

-- Emon 18 9 2016
ALTER TABLE `tasks` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '3' COMMENT '1=Complete, 2=Incomplete, 3=Pending; 4=Done; 5=Invalid';
-- Shanta 19 9 2016
ALTER TABLE `tasks` ADD `priority` TINYINT NOT NULL COMMENT '1=high,2=medium,3=low' AFTER `created_at`;
--Emon 20 9 2016
ALTER TABLE `tasks` ADD `site_url` VARCHAR(255) NULL AFTER `priority`;
--Shanta 22 09 2016
ALTER TABLE `tasks` CHANGE `priority` `priority` TINYINT(4) NOT NULL COMMENT '1=high,2=regular';
ALTER TABLE `tasks` CHANGE `task_type` `task_type` TINYINT(4) NOT NULL DEFAULT '3' COMMENT '1 = Edit; 2 = Redesign; 3 = New, 4=live';
ALTER TABLE `tasks` ADD `slack_username` VARCHAR(200) NOT NULL AFTER `site_url`;

--Farhad 22 9 2016--
ALTER TABLE `users` ADD `slack_username` VARCHAR(200) NOT NULL AFTER `role`;


--Farhad  24 9 2016--
ALTER TABLE `comments` ADD `comment_attach` TEXT NULL AFTER `username`;