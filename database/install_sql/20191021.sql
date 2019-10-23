alter table `kppw_menu` add `style` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '后台菜单样式';

alter table `kppw_enterprise_auth` add `company_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '企业邮箱';

update `kppw_config` set rule = '{"size":"5","extension":"pdf|jpg|jpeg|png|bmp|gif|zar|txt|xlsm|xls|xlsx"}' where alias = 'attachment';