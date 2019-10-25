alter table `kppw_menu` add `ban` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否禁用';

alter table `kppw_users` add `mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号码';

ALTER TABLE `kppw_users` ADD UNIQUE KEY `users_mobile_unique` (`mobile`);

ALTER TABLE `kppw_users` drop index `users_email_unique`;