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
ALTER TABLE `tasks` CHANGE `status` `status` TINYINT(4) NOT NULL DEFAULT '3' COMMENT '1=Complete, 2=Incomplete, 3=Pending; 4=Invalid; 5=Done';
