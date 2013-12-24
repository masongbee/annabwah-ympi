/*
Navicat MySQL Data Transfer

Source Server         : MySQL_local
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : dbympi

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2013-12-24 16:50:25
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
  `RPRESENSI_KODEKEL` varchar(3) NOT NULL,
  `RPRESENSI_KODEUNIT` varchar(5) NOT NULL,
  `d1` varchar(3) DEFAULT 'N/A',
  `d2` varchar(3) DEFAULT 'N/A',
  `d3` varchar(3) DEFAULT 'N/A',
  `d4` varchar(3) DEFAULT 'N/A',
  `d5` varchar(3) DEFAULT 'N/A',
  `d6` varchar(3) DEFAULT 'N/A',
  `d7` varchar(3) DEFAULT 'N/A',
  `d8` varchar(3) DEFAULT 'N/A',
  `d9` varchar(3) DEFAULT 'N/A',
  `d10` varchar(3) DEFAULT 'N/A',
  `d11` varchar(3) DEFAULT 'N/A',
  `d12` varchar(3) DEFAULT 'N/A',
  `d13` varchar(3) DEFAULT 'N/A',
  `d14` varchar(3) DEFAULT 'N/A',
  `d15` varchar(3) DEFAULT 'N/A',
  `d16` varchar(3) DEFAULT 'N/A',
  `d17` varchar(3) DEFAULT 'N/A',
  `d18` varchar(3) DEFAULT 'N/A',
  `d19` varchar(3) DEFAULT 'N/A',
  `d20` varchar(3) DEFAULT 'N/A',
  `d21` varchar(3) DEFAULT 'N/A',
  `d22` varchar(3) DEFAULT 'N/A',
  `d23` varchar(3) DEFAULT 'N/A',
  `d24` varchar(3) DEFAULT 'N/A',
  `d25` varchar(3) DEFAULT 'N/A',
  `d26` varchar(3) DEFAULT 'N/A',
  `d27` varchar(3) DEFAULT 'N/A',
  `d28` varchar(3) DEFAULT 'N/A',
  `d29` varchar(3) DEFAULT 'N/A',
  `d30` varchar(3) DEFAULT 'N/A',
  `d31` varchar(3) DEFAULT 'N/A',
  PRIMARY KEY (`RPRESENSI_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2048 DEFAULT CHARSET=utf8;
