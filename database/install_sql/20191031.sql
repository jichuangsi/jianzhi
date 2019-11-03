INSERT INTO `kppw_config` (`alias`, `rule`, `type`, `title`, `desc`) VALUES ('wx_appid', 'wxc91dff3d90d14c13', 'weixin', '微信appid', '微信appid'), ('wx_secret', '73ccda962d59c3d9c14c09f13019522d', 'weixin', '微信secret', '微信secret');

alter table `kppw_user_detail` modify column `wechat` varchar(100);