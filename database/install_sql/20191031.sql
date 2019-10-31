INSERT INTO `kppw_config` (`alias`, `rule`, `type`, `title`, `desc`) VALUES ('wx_appid', 'wxc91dff3d90d14c13', 'weixin', '微信appid', '微信appid'), ('wx_secret', 'be8cfbba1effe57abb0b08ce6d3e0834', 'weixin', '微信secret', '微信secret');

alter table `kppw_user_detail` modify column `wechat` varchar(100);