update `kppw_config` set rule = '{"size":"5","extension":"pdf|jpg|jpeg|png|bmp|gif|zar|txt|xlsm|xls|xlsx|doc|docx|ppt|pptx","number":"6"}' where alias = 'attachment';

CREATE TABLE `kppw_channel_distribution` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT '0' COMMENT '企业id',
  `mid` int(11) DEFAULT '0' COMMENT '渠道商id',
  `createtime` timestamp NULL DEFAULT NULL COMMENT '分配时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='渠道商分配表';
