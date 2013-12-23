/*
Navicat MySQL Data Transfer

Source Server         : MySQL_local
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : dbympi

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-12-23 22:33:27
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rpresensi
-- ----------------------------
DROP TABLE IF EXISTS `rpresensi`;
CREATE TABLE `rpresensi` (
  `RPRESENSI_ID` int(11) NOT NULL AUTO_INCREMENT,
  `RPRESENSI_NIK` varchar(10) NOT NULL,
  `RPRESENSI_NAMA` varchar(50) NOT NULL,
  `RPRESENSI_BULAN` varchar(6) NOT NULL,
  `d1` varchar(3) DEFAULT NULL,
  `d2` varchar(3) DEFAULT NULL,
  `d3` varchar(3) DEFAULT NULL,
  `d4` varchar(3) DEFAULT NULL,
  `d5` varchar(3) DEFAULT NULL,
  `d6` varchar(3) DEFAULT NULL,
  `d7` varchar(3) DEFAULT NULL,
  `d8` varchar(3) DEFAULT NULL,
  `d9` varchar(3) DEFAULT NULL,
  `d10` varchar(3) DEFAULT NULL,
  `d11` varchar(3) DEFAULT NULL,
  `d12` varchar(3) DEFAULT NULL,
  `d13` varchar(3) DEFAULT NULL,
  `d14` varchar(3) DEFAULT NULL,
  `d15` varchar(3) DEFAULT NULL,
  `d16` varchar(3) DEFAULT NULL,
  `d17` varchar(3) DEFAULT NULL,
  `d18` varchar(3) DEFAULT NULL,
  `d19` varchar(3) DEFAULT NULL,
  `d20` varchar(3) DEFAULT NULL,
  `d21` varchar(3) DEFAULT NULL,
  `d22` varchar(3) DEFAULT NULL,
  `d23` varchar(3) DEFAULT NULL,
  `d24` varchar(3) DEFAULT NULL,
  `d25` varchar(3) DEFAULT NULL,
  `d26` varchar(3) DEFAULT NULL,
  `d27` varchar(3) DEFAULT NULL,
  `d28` varchar(3) DEFAULT NULL,
  `d29` varchar(3) DEFAULT NULL,
  `d30` varchar(3) DEFAULT NULL,
  `d31` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`RPRESENSI_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
