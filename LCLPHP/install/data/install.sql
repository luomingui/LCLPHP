/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 60002
Source Host           : localhost:3306
Source Database       : lclphp

Target Server Type    : MYSQL
Target Server Version : 60002
File Encoding         : 65001

Date: 2017-05-24 11:18:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lcl_admincp_cmenu
-- ----------------------------
DROP TABLE IF EXISTS `lcl_admincp_cmenu`;
CREATE TABLE `lcl_admincp_cmenu` (
  `cmenuid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(255) NOT NULL COMMENT '名称',
  `url` varchar(255) NOT NULL COMMENT '地址',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `displayorder` tinyint(3) NOT NULL COMMENT '显示顺序',
  `clicks` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '点击数',
  `uid` mediumint(8) unsigned NOT NULL COMMENT '添加用户',
  `dateline` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`cmenuid`),
  KEY `uid` (`uid`),
  KEY `displayorder` (`displayorder`)
);

-- ----------------------------
-- Table structure for lcl_admincp_group
-- ----------------------------
DROP TABLE IF EXISTS `lcl_admincp_group`;
CREATE TABLE `lcl_admincp_group` (
  `cpgroupid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '后台组编号',
  `cpgroupname` varchar(255) NOT NULL COMMENT '后台组名称',
  PRIMARY KEY (`cpgroupid`)
);

-- ----------------------------
-- Table structure for lcl_admincp_member
-- ----------------------------
DROP TABLE IF EXISTS `lcl_admincp_member`;
CREATE TABLE `lcl_admincp_member` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `username` char(15) NOT NULL DEFAULT '' COMMENT '用户名称',
  `password` char(15) NOT NULL COMMENT '用户密码',
  `cpgroupid` int(11) DEFAULT NULL COMMENT '成员组编号',
  `customperm` text NOT NULL COMMENT '自定义管理权限',
  `dateline` int(14) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`uid`)
);

-- ----------------------------
-- Table structure for lcl_admincp_perm
-- ----------------------------
DROP TABLE IF EXISTS `lcl_admincp_perm`;
CREATE TABLE `lcl_admincp_perm` (
  `cpgroupid` smallint(6) unsigned NOT NULL COMMENT ' 后台组编号',
  `perm` varchar(255) NOT NULL COMMENT '后台组权限',
  UNIQUE KEY `cpgroupperm` (`cpgroupid`,`perm`)
);

-- ----------------------------
-- Table structure for lcl_common_cron
-- ----------------------------
DROP TABLE IF EXISTS `lcl_common_cron`;
CREATE TABLE `lcl_common_cron` (
  `cronid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '计划任务编号',
  `available` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否启用',
  `type` enum('user','system','plugin') NOT NULL DEFAULT 'user' COMMENT '类型',
  `name` char(50) NOT NULL DEFAULT '' COMMENT '名称',
  `filename` char(50) NOT NULL DEFAULT '' COMMENT '对应文件',
  `lastrun` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次执行时间',
  `nextrun` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下次执行时间',
  `weekday` tinyint(1) NOT NULL DEFAULT '0' COMMENT '周计划',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '日计划',
  `hour` tinyint(2) NOT NULL DEFAULT '0' COMMENT '小时计划',
  `minute` char(36) NOT NULL DEFAULT '' COMMENT '分计划',
  PRIMARY KEY (`cronid`),
  KEY `nextrun` (`available`,`nextrun`)
);

-- ----------------------------
-- Table structure for lcl_common_district
-- ----------------------------
DROP TABLE IF EXISTS `lcl_common_district`;
CREATE TABLE `lcl_common_district` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区编号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '地区名称',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '地区等级',
  `usetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '使用对象',
  `upid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级地区编号',
  `displayorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  PRIMARY KEY (`id`),
  KEY `upid` (`upid`,`displayorder`)
);
-- ----------------------------
-- Table structure for lcl_common_process
-- ----------------------------
DROP TABLE IF EXISTS `lcl_common_process`;
CREATE TABLE `lcl_common_process` (
  `processid` char(32) NOT NULL COMMENT '锁名称的md5',
  `expiry` int(10) DEFAULT NULL COMMENT '锁的过期时间',
  `extra` int(10) DEFAULT NULL COMMENT '锁的附属信息',
  PRIMARY KEY (`processid`),
  KEY `expiry` (`expiry`)
);

-- ----------------------------
-- Table structure for lcl_common_setting
-- ----------------------------
DROP TABLE IF EXISTS `lcl_common_setting`;
CREATE TABLE `lcl_common_setting` (
  `skey` varchar(255) NOT NULL DEFAULT '' COMMENT '变量名',
  `svalue` text NOT NULL COMMENT '变量值',
  PRIMARY KEY (`skey`)
);

-- ----------------------------
-- Table structure for lcl_common_syscache
-- ----------------------------
DROP TABLE IF EXISTS `lcl_common_syscache`;
CREATE TABLE `lcl_common_syscache` (
  `cname` varchar(32) NOT NULL COMMENT '缓存名称',
  `ctype` tinyint(3) unsigned NOT NULL COMMENT '缓存类型',
  `dateline` int(10) unsigned NOT NULL COMMENT '缓存生成时间',
  `data` mediumblob NOT NULL COMMENT '缓存数据',
  PRIMARY KEY (`cname`)
);

-- ----------------------------
-- Table structure for lcl_portal_article
-- ----------------------------
DROP TABLE IF EXISTS `lcl_portal_article`;
CREATE TABLE `lcl_portal_article` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '添加用户',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `summary` varchar(255) NOT NULL DEFAULT '' COMMENT '摘要',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者',
  `from` varchar(255) NOT NULL DEFAULT '' COMMENT '来源',
  `fromurl` varchar(255) NOT NULL DEFAULT '' COMMENT '来源URL',
  `allowcomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许评论',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '访问URL',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `tag` smallint(16) unsigned NOT NULL DEFAULT '0' COMMENT '关键字',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`aid`)
);

-- ----------------------------
-- Table structure for lcl_portal_categry
-- ----------------------------
DROP TABLE IF EXISTS `lcl_portal_categry`;
CREATE TABLE `lcl_portal_categry` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目编号',
  `upid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级栏目编号',
  `catname` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `displayorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否关闭',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '添加用户',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`catid`)
);
