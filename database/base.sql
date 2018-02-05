/*
Navicat MySQL Data Transfer

Source Server         : MySql
Source Server Version : 50720
Source Host           : localhost:3306
Source Database       : base

Target Server Type    : MYSQL
Target Server Version : 50720
File Encoding         : 65001

Date: 2018-02-05 17:19:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tab_menu
-- ----------------------------
DROP TABLE IF EXISTS `tab_menu`;
CREATE TABLE `tab_menu` (
  `id` varchar(50) NOT NULL COMMENT 'ID',
  `pid` varchar(50) NOT NULL COMMENT '父ID',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `url` varchar(200) DEFAULT NULL COMMENT '链接',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `isparent` int(1) NOT NULL DEFAULT '0' COMMENT '是否有子集',
  `name_en` varchar(100) DEFAULT NULL,
  `order` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tab_menu
-- ----------------------------
INSERT INTO `tab_menu` VALUES ('e9e09ebe-e486-11e7-b9b6-507b9d4a3880', '-1', '系统管理', '/#', null, '1', null, null);

-- ----------------------------
-- Table structure for tab_permission
-- ----------------------------
DROP TABLE IF EXISTS `tab_permission`;
CREATE TABLE `tab_permission` (
  `id` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tab_permission
-- ----------------------------

-- ----------------------------
-- Table structure for tab_role
-- ----------------------------
DROP TABLE IF EXISTS `tab_role`;
CREATE TABLE `tab_role` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tab_role
-- ----------------------------
INSERT INTO `tab_role` VALUES ('0', '管理员');
INSERT INTO `tab_role` VALUES ('1', '普通用户');
INSERT INTO `tab_role` VALUES ('2', '会员用户');

-- ----------------------------
-- Table structure for tab_user
-- ----------------------------
DROP TABLE IF EXISTS `tab_user`;
CREATE TABLE `tab_user` (
  `id` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sex` int(1) DEFAULT '0' COMMENT '0：男，1：女',
  `phone` varchar(20) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `role` int(10) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tab_user
-- ----------------------------
