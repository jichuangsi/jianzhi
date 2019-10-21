alter table `kppw_users` add `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户类型 1-个人 2-企业';

alter table `kppw_task_type` add `pid` int(11) NOT NULL COMMENT '父级任务类型ID';
alter table `kppw_task_type` add `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '任务类型路径';
alter table `kppw_task_type` add `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序，指的是任务类型在本级的排序';
alter table `kppw_task_type` add `choose_num` int(11) NOT NULL DEFAULT '0' COMMENT '任务类型被点击次数';
alter table `kppw_task_type` add `updated_at` timestamp NULL DEFAULT NULL;

update `kppw_task_type` set status = 0;
INSERT INTO `kppw_task_type` (`name`, `pid`, `path`, `sort`, `choose_num`, `created_at`, `updated_at`, `status`) VALUES
('信息技术服务', 0, '-0-', 5, 0, '2019-07-27 06:01:20', '2019-07-27 06:01:17', 1),
('生活服务', 0, '-0-', 4, 0, '2019-07-20 09:18:17', '2019-07-20 09:18:18', 1),
('信息咨询', 0, '-0-', 3, 0, '2019-07-29 09:33:22', '2019-07-29 09:33:23', 1),
('设计制作', 0, '-0-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('推广服务', 0, '-0-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('会展服务', 0, '-0-', 7, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('其它服务', 0, '-0-', 8, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1);

INSERT INTO `kppw_task_type` (`name`, `pid`, `path`, `sort`, `choose_num`, `created_at`, `updated_at`, `status`) VALUES
('产品推广', 6, '-0-6-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('形象推广', 6, '-0-6-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('设计素材', 5, '-0-5-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('平面设计', 5, '-0-5-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('专题调查', 4, '-0-4-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('流程管理', 4, '-0-4-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('信息增值服务', 4, '-0-4-', 3, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('清洁清运服务', 3, '-0-3-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('技术维修服务', 3, '-0-3-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('其它生活服务', 3, '-0-3-', 3, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('信息服务', 2, '-0-2-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('技术服务', 2, '-0-2-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('布展服务', 7, '-0-7-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('会务服务', 7, '-0-7-', 2, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1),
('其它服务', 8, '-0-8-', 1, 0, '2019-06-22 08:28:20', '2019-06-22 08:28:20', 1);


alter table `kppw_task` add `sub_type_id` int(11) NOT NULL COMMENT '任务类型';

CREATE TABLE IF NOT EXISTS `kppw_tag_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL COMMENT '标签编号',
  `task_id` int(11) NOT NULL COMMENT '任务ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

alter table `kppw_task` add `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '任务服务地址';

alter table `kppw_work` add `agreement` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0表示不同意协议 1表示同意协议';