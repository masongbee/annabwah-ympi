/*
Navicat MySQL Data Transfer

Source Server         : MySQL_local
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : dbympi

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-12-23 08:02:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for s_userslog
-- ----------------------------
DROP TABLE IF EXISTS `s_userslog`;
CREATE TABLE `s_userslog` (
  `USERLOG_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERLOG_USER_ID` int(11) NOT NULL,
  `USERLOG_USER_NAME` varchar(50) NOT NULL,
  `USERLOG_TIME` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `USERLOG_STATUS` enum('out','in') DEFAULT 'in',
  PRIMARY KEY (`USERLOG_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
