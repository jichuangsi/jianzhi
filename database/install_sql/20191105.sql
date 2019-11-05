update `kppw_config` set rule = '{"size":"5","extension":"pdf|jpg|jpeg|png|bmp|gif|zar|txt|xlsm|xls|xlsx","number":"6"}' where alias = 'attachment';

alter table `kppw_work` add `end_at` timestamp NULL DEFAULT NULL COMMENT '终止时间';
alter table `kppw_work` add `reject_at` timestamp NULL DEFAULT NULL COMMENT '驳回时间';