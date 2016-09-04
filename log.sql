ALTER TABLE `tasks` CHANGE `status` `status` TINYINT(4) NULL DEFAULT NULL COMMENT '1=edit, 2=redesign,3=new ';

/* new column username add*/

ALTER TABLE `users` ADD `username` INT(100) NOT NULL AFTER `id`;
