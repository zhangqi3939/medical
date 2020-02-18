/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : medical

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-02-18 21:43:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `md_consumable`
-- ----------------------------
DROP TABLE IF EXISTS `md_consumable`;
CREATE TABLE `md_consumable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `consumable_name` varchar(100) DEFAULT NULL COMMENT '耗材名称',
  `rfid` varchar(100) DEFAULT NULL COMMENT '唯一idrfid',
  `is_use` int(2) DEFAULT '0' COMMENT '是否使用 0 未使用  1 已使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_consumable
-- ----------------------------

-- ----------------------------
-- Table structure for `md_current_alarm`
-- ----------------------------
DROP TABLE IF EXISTS `md_current_alarm`;
CREATE TABLE `md_current_alarm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL COMMENT '报警信息详情',
  `time` int(12) DEFAULT NULL COMMENT '报警信息插入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_current_alarm
-- ----------------------------

-- ----------------------------
-- Table structure for `md_data`
-- ----------------------------
DROP TABLE IF EXISTS `md_data`;
CREATE TABLE `md_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL COMMENT '数据内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_data
-- ----------------------------

-- ----------------------------
-- Table structure for `md_equipmemnt`
-- ----------------------------
DROP TABLE IF EXISTS `md_equipmemnt`;
CREATE TABLE `md_equipmemnt` (
  `id` int(10) NOT NULL DEFAULT '0' COMMENT '项目id',
  `pid` int(10) DEFAULT NULL,
  `equipment_name` varchar(100) DEFAULT NULL COMMENT '设备名',
  `number` int(10) DEFAULT '0' COMMENT '设备数量',
  `type` int(10) DEFAULT '0' COMMENT '设备类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_equipmemnt
-- ----------------------------

-- ----------------------------
-- Table structure for `md_equipment`
-- ----------------------------
DROP TABLE IF EXISTS `md_equipment`;
CREATE TABLE `md_equipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '设备名称',
  `is_open` int(2) DEFAULT NULL COMMENT '是否开启 0 关闭 1 开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_equipment
-- ----------------------------

-- ----------------------------
-- Table structure for `md_log`
-- ----------------------------
DROP TABLE IF EXISTS `md_log`;
CREATE TABLE `md_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '操作用户id',
  `user_ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  `remarks` varchar(255) DEFAULT NULL COMMENT '操作内容',
  `insert_time` int(12) DEFAULT NULL COMMENT '数据插入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_log
-- ----------------------------
INSERT INTO `md_log` VALUES ('1', '1', '127.0.0.1', '用户登录', '1581648174');

-- ----------------------------
-- Table structure for `md_message`
-- ----------------------------
DROP TABLE IF EXISTS `md_message`;
CREATE TABLE `md_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL COMMENT '消息详情',
  `type` int(2) DEFAULT NULL COMMENT '消息类型 1 运行消息 2 报警消息',
  `create_time` int(12) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_message
-- ----------------------------

-- ----------------------------
-- Table structure for `md_project`
-- ----------------------------
DROP TABLE IF EXISTS `md_project`;
CREATE TABLE `md_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `describe` varchar(255) DEFAULT NULL COMMENT '项目描述',
  `implementer` varchar(100) DEFAULT NULL COMMENT '项目负责人',
  `place` varchar(255) DEFAULT NULL COMMENT '实施地点 （医院，科研工作点..待定）',
  `gender` int(2) DEFAULT '0' COMMENT '负责人性别 0：男 1：女',
  `tel` int(15) DEFAULT NULL COMMENT '负责人手机号',
  `email` varchar(100) DEFAULT NULL COMMENT '负责人邮箱',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_project
-- ----------------------------

-- ----------------------------
-- Table structure for `md_project_user`
-- ----------------------------
DROP TABLE IF EXISTS `md_project_user`;
CREATE TABLE `md_project_user` (
  `id` int(10) NOT NULL DEFAULT '0',
  `pid` int(10) DEFAULT NULL COMMENT '项目id',
  `username` varchar(255) DEFAULT NULL COMMENT '项目人员姓名',
  `hospital` varchar(255) DEFAULT NULL COMMENT '医院名',
  `address` varchar(255) DEFAULT NULL COMMENT '具体地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_project_user
-- ----------------------------

-- ----------------------------
-- Table structure for `md_rbac_right`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_right`;
CREATE TABLE `md_rbac_right` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(2) DEFAULT NULL COMMENT '父级id 0 主菜单  1 子菜单',
  `right_name` varchar(50) DEFAULT NULL COMMENT '权限名',
  `url` varchar(255) DEFAULT NULL COMMENT 'url',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_right
-- ----------------------------

-- ----------------------------
-- Table structure for `md_rbac_role`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_role`;
CREATE TABLE `md_rbac_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) DEFAULT NULL COMMENT '角色名',
  `describe` varchar(100) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_role
-- ----------------------------
INSERT INTO `md_rbac_role` VALUES ('1', '超级管理员', '管理一切');

-- ----------------------------
-- Table structure for `md_rbac_role_right`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_role_right`;
CREATE TABLE `md_rbac_role_right` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) DEFAULT NULL COMMENT '角色id',
  `right_id` varchar(255) DEFAULT NULL COMMENT '权限id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_role_right
-- ----------------------------

-- ----------------------------
-- Table structure for `md_rbac_token`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_token`;
CREATE TABLE `md_rbac_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(25) DEFAULT NULL COMMENT '标记',
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `token` varchar(100) DEFAULT NULL COMMENT 'token标识',
  `add_time` int(12) DEFAULT NULL COMMENT '添加时间',
  `user_ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_token
-- ----------------------------
INSERT INTO `md_rbac_token` VALUES ('2', 'web', '1', '3a0a7370b5c851a9cb60b29080035205', '1581658620', '127.0.0.1');

-- ----------------------------
-- Table structure for `md_rbac_user`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_user`;
CREATE TABLE `md_rbac_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '项目id',
  `user_name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `real_name` varchar(100) DEFAULT NULL COMMENT '真实姓名',
  `pass_word` varchar(50) DEFAULT NULL COMMENT '用户名密码',
  `gender` int(11) DEFAULT NULL COMMENT '性别 0 男 1 女',
  `hospital_name` varchar(255) DEFAULT NULL COMMENT '医院名字',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话号码',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `province` varchar(50) DEFAULT NULL COMMENT '省',
  `city` varchar(50) DEFAULT NULL COMMENT '市',
  `zone` varchar(50) DEFAULT NULL COMMENT '区',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `add_time` int(12) DEFAULT NULL COMMENT '注册时间',
  `status` int(2) DEFAULT '0' COMMENT '用户状态  0 正常 1 禁用 ',
  `login_times` int(2) DEFAULT '0' COMMENT '登录次数限定，超过5次锁定',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_user
-- ----------------------------
INSERT INTO `md_rbac_user` VALUES ('1', null, 'phoebus', null, '549ce24fb62238d013a6e222cb4d41d8', '1', '青医附院', '15005323939', null, '山东', '青岛', '市南区', null, null, '0', '0');

-- ----------------------------
-- Table structure for `md_rbac_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_user_role`;
CREATE TABLE `md_rbac_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `role_id` varchar(100) DEFAULT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_user_role
-- ----------------------------
INSERT INTO `md_rbac_user_role` VALUES ('1', '1', '1');
