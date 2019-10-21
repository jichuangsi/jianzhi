alter table `kppw_work` add `delivered_at` timestamp NULL DEFAULT NULL COMMENT '提交验收时间';
alter table `kppw_work` add `payment` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '任务金额';
alter table `kppw_work` add `checked_at` timestamp NULL DEFAULT NULL COMMENT '验收时间';
alter table `kppw_work` add `settle_at` timestamp NULL DEFAULT NULL COMMENT '结算时间';

alter table `kppw_feedback` add `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '反馈标题';

CREATE TABLE IF NOT EXISTS `kppw_feedback_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `feedback_id` int(11) NOT NULL DEFAULT '0' COMMENT '反馈ID',
  `attachment_id` int(11) NOT NULL DEFAULT '0' COMMENT '附件ID',
  `type` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '附件类型',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

alter table `kppw_enterprise_auth` add `owner` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '法人姓名';
alter table `kppw_enterprise_auth` add `contactor` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系人姓名';
alter table `kppw_enterprise_auth` add `contactor_mobile` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系人电话';
alter table `kppw_enterprise_auth` add `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '企业电话';
alter table `kppw_enterprise_auth` add `tax_code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '纳税人识别号';
alter table `kppw_enterprise_auth` add `bank` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '开户行';
alter table `kppw_enterprise_auth` add `account` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '开户行账户';