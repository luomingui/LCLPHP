-- ----------------------------
-- Records of lcl_common_setting
-- ----------------------------
INSERT INTO `lcl_common_setting` VALUES ('bbname', 'LCL for');
INSERT INTO `lcl_common_setting` VALUES ('sitename', 'PHP');
INSERT INTO `lcl_common_setting` VALUES ('boardlicensed', '0');
INSERT INTO `lcl_common_setting` VALUES ('bbclosed', '0');
INSERT INTO `lcl_common_setting` VALUES ('siteurl', 'http://localhost');
INSERT INTO `lcl_common_setting` VALUES ('adminemail', 'minguiluo@163.coom');
INSERT INTO `lcl_common_setting` VALUES ('site_qq', '271391233');
INSERT INTO `lcl_common_setting` VALUES ('icp', '');
INSERT INTO `lcl_common_setting` VALUES ('statcode', '');
-- ----------------------------
-- Records of lcl_admincp_group
-- ----------------------------
LOCK TABLES `lcl_admincp_group` WRITE;
insert  into `lcl_admincp_group`(`cpgroupid`,`cpgroupname`) values (1,'系统管理员'),(2,'新闻管理员'),(3,'活动管理员'),(4,'商品管理员');
UNLOCK TABLES;
-- ----------------------------
-- Records of lcl_admincp_member
-- ----------------------------
LOCK TABLES `lcl_admincp_member` WRITE;
insert  into `lcl_admincp_member`(`uid`,`username`,`password`,`cpgroupid`,`customperm`,`dateline`) values (1,'admin','123456',0,'',UNIX_TIMESTAMP()),(2,'luomingui','123456',0,'',UNIX_TIMESTAMP());
UNLOCK TABLES;
-- ----------------------------
-- Records of lcl_portal_categry
-- ----------------------------
LOCK TABLES `lcl_portal_categry` WRITE;
insert  into `lcl_portal_categry`(`catid`,`upid`,`catname`,`displayorder`,`closed`,`uid`,`dateline`)
 values (1,0,'新闻动态',0,0,1,UNIX_TIMESTAMP()),(2,1,'行业动态',0,0,1,UNIX_TIMESTAMP()),(3,1,'站内公告',0,0,1,UNIX_TIMESTAMP());
UNLOCK TABLES;






