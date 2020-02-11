/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : medical

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-02-11 20:12:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `md_consumable`
-- ----------------------------
DROP TABLE IF EXISTS `md_consumable`;
CREATE TABLE `md_consumable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '耗材名称',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_log
-- ----------------------------

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_role
-- ----------------------------

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
  `token` varchar(20) DEFAULT NULL COMMENT 'token标识',
  `add_time` int(12) DEFAULT NULL COMMENT '添加时间',
  `user_ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_token
-- ----------------------------

-- ----------------------------
-- Table structure for `md_rbac_user`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_user`;
CREATE TABLE `md_rbac_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '项目id',
  `user_name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `pass_word` varchar(50) DEFAULT NULL COMMENT '用户名密码',
  `gender` int(11) DEFAULT NULL COMMENT '性别 0 男 1 女',
  `hospital_name` varchar(255) DEFAULT NULL COMMENT '医院名字',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话号码',
  `province` varchar(50) DEFAULT NULL COMMENT '省',
  `city` varchar(50) DEFAULT NULL COMMENT '市',
  `zone` varchar(50) DEFAULT NULL COMMENT '区',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `add_time` int(12) DEFAULT NULL COMMENT '注册时间',
  `status` int(2) DEFAULT '0' COMMENT '用户状态  0 正常 1 禁用 ',
  `login_times` int(2) DEFAULT '0' COMMENT '登录次数限定，超过5次锁定',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_user
-- ----------------------------

-- ----------------------------
-- Table structure for `md_rbac_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `md_rbac_user_role`;
CREATE TABLE `md_rbac_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '用户id',
  `role_id` varchar(100) DEFAULT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of md_rbac_user_role
-- ----------------------------
