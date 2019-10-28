CREATE TABLE IF NOT EXISTS `kppw_help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章编号',
  `uid` int(11) NOT NULL COMMENT '用户编号',
  `user_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户名',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '文章状态',
  `content` text COLLATE utf8_unicode_ci COMMENT '文字内容',
  `view` int(11) DEFAULT NULL COMMENT '文章阅读浏览次数',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


alter table `kppw_realname_auth` add `account` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '银行账户';