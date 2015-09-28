/*
Navicat MySQL Data Transfer

Source Server         : MySQL_local
Source Server Version : 50621
Source Host           : 127.0.0.1:3306
Source Database       : dbympi

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2015-05-29 06:55:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for td_kelompok
-- ----------------------------
DROP TABLE IF EXISTS `td_kelompok`;
CREATE TABLE `td_kelompok` (
  `TDKELOMPOK_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDKELOMPOK_KODE` varchar(5) DEFAULT NULL,
  `TDKELOMPOK_NAMA` varchar(255) NOT NULL,
  `TDKELOMPOK_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDKELOMPOK_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDKELOMPOK_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDKELOMPOK_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDKELOMPOK_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDKELOMPOK_REVISED` tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`TDKELOMPOK_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for td_pelatihan
-- ----------------------------
DROP TABLE IF EXISTS `td_pelatihan`;
CREATE TABLE `td_pelatihan` (
  `TDPELATIHAN_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDPELATIHAN_NO` varchar(25) DEFAULT NULL,
  `TDPELATIHAN_TANGGAL` date DEFAULT NULL,
  `TDPELATIHAN_DIBUAT` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DIBUAT_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_DIPERIKSA` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DIPERIKSA_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_DIKETAHUI` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DIKETAHUI_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI01` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI01_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI02` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI02_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI03` varchar(10) DEFAULT NULL,
  `TDPELATIHAN_DISETUJUI03_NAMA` varchar(50) DEFAULT NULL,
  `TDPELATIHAN_TDTRAINING_ID` int(10) unsigned DEFAULT NULL,
  `TDPELATIHAN_TDTRAINING_NAMA` varchar(255) DEFAULT NULL,
  `TDPELATIHAN_TDKELOMPOK_ID` int(10) unsigned DEFAULT NULL,
  `TDPELATIHAN_TDKELOMPOK_NAMA` varchar(255) DEFAULT NULL,
  `TDPELATIHAN_TDTRAINING_TUJUAN` varchar(255) DEFAULT NULL,
  `TDPELATIHAN_TDTRAINING_JENIS` enum('cd','id','ex') DEFAULT NULL,
  `TDPELATIHAN_TDTRAINING_SIFAT` enum('rekomendasi','wajib') DEFAULT NULL,
  `TDPELATIHAN_PESERTA` varchar(255) DEFAULT NULL,
  `TDPELATIHAN_PESERTA_JUMLAH` int(11) DEFAULT NULL,
  `TDPELATIHAN_DURASI` int(11) DEFAULT NULL,
  `TDPELATIHAN_BIAYA_PLAN` decimal(10,0) DEFAULT '0',
  `TDPELATIHAN_BIAYA_AKTUAL` decimal(10,0) DEFAULT '0',
  `TDPELATIHAN_BIAYA_BALANCE` decimal(10,0) DEFAULT '0',
  `TDPELATIHAN_TDTRAINER_ID` int(10) unsigned DEFAULT NULL,
  `TDPELATIHAN_TDTRAINER_NAMA` varchar(255) DEFAULT NULL,
  `TDPELATIHAN_EVREAKSI` int(11) DEFAULT NULL,
  `TDPELATIHAN_EVEFFECTIVITAS` int(11) DEFAULT NULL,
  `TDPELATIHAN_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDPELATIHAN_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDPELATIHAN_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDPELATIHAN_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDPELATIHAN_REVISED` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDPELATIHAN_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for td_realisasi
-- ----------------------------
DROP TABLE IF EXISTS `td_realisasi`;
CREATE TABLE `td_realisasi` (
  `TDREALISASI_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDREALISASI_TDPELATIHAN_ID` int(10) unsigned DEFAULT NULL,
  `TDREALISASI_TANGGAL` date DEFAULT NULL,
  `TDREALISASI_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDREALISASI_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDREALISASI_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDREALISASI_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDREALISASI_REVISED` tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`TDREALISASI_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for td_rencana
-- ----------------------------
DROP TABLE IF EXISTS `td_rencana`;
CREATE TABLE `td_rencana` (
  `TDRENCANA_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDRENCANA_TDPELATIHAN_ID` int(10) unsigned DEFAULT NULL,
  `TDRENCANA_TANGGAL` date DEFAULT NULL,
  `TDRENCANA_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDRENCANA_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDRENCANA_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDRENCANA_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDRENCANA_REVISED` tinyint(2) unsigned DEFAULT '0',
  PRIMARY KEY (`TDRENCANA_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for td_trainer
-- ----------------------------
DROP TABLE IF EXISTS `td_trainer`;
CREATE TABLE `td_trainer` (
  `TDTRAINER_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDTRAINER_KODE` varchar(5) DEFAULT NULL,
  `TDTRAINER_NAMA` varchar(255) NOT NULL,
  `TDTRAINER_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDTRAINER_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDTRAINER_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDTRAINER_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDTRAINER_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDTRAINER_REVISED` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDTRAINER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for td_training
-- ----------------------------
DROP TABLE IF EXISTS `td_training`;
CREATE TABLE `td_training` (
  `TDTRAINING_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDTRAINING_KODE` varchar(5) DEFAULT NULL,
  `TDTRAINING_NAMA` varchar(255) NOT NULL,
  `TDTRAINING_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDTRAINING_TDKELOMPOK_ID` int(10) unsigned DEFAULT NULL,
  `TDTRAINING_TDKELOMPOK_NAMA` varchar(255) DEFAULT NULL,
  `TDTRAINING_TUJUAN` varchar(255) DEFAULT NULL,
  `TDTRAINING_JENIS` enum('cd','id','ex') DEFAULT NULL,
  `TDTRAINING_SIFAT` enum('rekomendasi','wajib') DEFAULT NULL,
  `TDTRAINING_CREATED_BY` varchar(25) DEFAULT NULL,
  `TDTRAINING_CREATED_DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `TDTRAINING_UPDATED_BY` varchar(25) DEFAULT NULL,
  `TDTRAINING_UPDATED_DATE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `TDTRAINING_REVISED` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDTRAINING_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;






SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 244;

UPDATE s_menus SET MENU_RGT = MENU_RGT + 2 WHERE MENU_RGT > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT + 2 WHERE MENU_LFT > @myRight;

INSERT INTO s_menus(MENU_ID,
	MENU_KODE,
	MENU_PARENT,
	MENU_POSITION,
	MENU_TITLE,
	MENU_FILENAME,
	MENU_CAT,
	MENU_CONFIRM,
	MENU_LEFTPANEL,
	MENU_AKTIF, 
	MENU_LFT, 
	MENU_RGT) VALUES(245,'TD_KELOMPOK',2,45,'Kelompok Training','TD_KELOMPOK','window','N','Y','Y', @myRight + 1, @myRight + 2);





SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 245;

UPDATE s_menus SET MENU_RGT = MENU_RGT + 2 WHERE MENU_RGT > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT + 2 WHERE MENU_LFT > @myRight;

INSERT INTO s_menus(MENU_ID,
	MENU_KODE,
	MENU_PARENT,
	MENU_POSITION,
	MENU_TITLE,
	MENU_FILENAME,
	MENU_CAT,
	MENU_CONFIRM,
	MENU_LEFTPANEL,
	MENU_AKTIF, 
	MENU_LFT, 
	MENU_RGT) VALUES(246,'TD_TRAINING',2,46,'Training','TD_TRAINING','window','N','Y','Y', @myRight + 1, @myRight + 2);





SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 246;

UPDATE s_menus SET MENU_RGT = MENU_RGT + 2 WHERE MENU_RGT > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT + 2 WHERE MENU_LFT > @myRight;

INSERT INTO s_menus(MENU_ID,
	MENU_KODE,
	MENU_PARENT,
	MENU_POSITION,
	MENU_TITLE,
	MENU_FILENAME,
	MENU_CAT,
	MENU_CONFIRM,
	MENU_LEFTPANEL,
	MENU_AKTIF, 
	MENU_LFT, 
	MENU_RGT) VALUES(247,'TD_TRAINER',2,47,'Trainer','TD_TRAINER','window','N','Y','Y', @myRight + 1, @myRight + 2);





UPDATE `s_menus` SET `MENU_FILENAME` = 'TD_PELATIHAN' WHERE `MENU_ID` = 530;






-- ----------------------------
-- Table structure for td_nilai01
-- ----------------------------
DROP TABLE IF EXISTS `td_nilai01`;
CREATE TABLE `td_nilai01` (
  `TDNILAI01_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDNILAI01_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDNILAI01_SKOR` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDNILAI01_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_nilai01
-- ----------------------------
INSERT INTO `td_nilai01` VALUES ('1', 'Sedikit Tahu', '25');
INSERT INTO `td_nilai01` VALUES ('2', 'Memahami', '50');
INSERT INTO `td_nilai01` VALUES ('3', 'Menguasai / bisa menerapkan', '75');
INSERT INTO `td_nilai01` VALUES ('4', 'Menerapkan dan mengajarkan', '100');

-- ----------------------------
-- Table structure for td_nilai02
-- ----------------------------
DROP TABLE IF EXISTS `td_nilai02`;
CREATE TABLE `td_nilai02` (
  `TDNILAI02_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDNILAI02_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDNILAI02_SKOR` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDNILAI02_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_nilai02
-- ----------------------------
INSERT INTO `td_nilai02` VALUES ('1', 'Tidak Sesuai', '20');
INSERT INTO `td_nilai02` VALUES ('2', 'Sebagian Kecil', '40');
INSERT INTO `td_nilai02` VALUES ('3', 'Sebagian Besar', '60');
INSERT INTO `td_nilai02` VALUES ('4', 'Sesuai', '80');
INSERT INTO `td_nilai02` VALUES ('5', 'Melebihi Harapan', '100');

-- ----------------------------
-- Table structure for td_nilai03
-- ----------------------------
DROP TABLE IF EXISTS `td_nilai03`;
CREATE TABLE `td_nilai03` (
  `TDNILAI03_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDNILAI03_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDNILAI03_SKOR` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDNILAI03_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_nilai03
-- ----------------------------
INSERT INTO `td_nilai03` VALUES ('1', 'Tidak Berhasil', '20');
INSERT INTO `td_nilai03` VALUES ('2', 'Kurang Berhasil', '40');
INSERT INTO `td_nilai03` VALUES ('3', 'Cukup', '60');
INSERT INTO `td_nilai03` VALUES ('4', 'Berhasil', '80');
INSERT INTO `td_nilai03` VALUES ('5', 'Sangat Berhasil', '100');

-- ----------------------------
-- Table structure for td_nilai04
-- ----------------------------
DROP TABLE IF EXISTS `td_nilai04`;
CREATE TABLE `td_nilai04` (
  `TDNILAI04_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDNILAI04_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDNILAI04_SKOR` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDNILAI04_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_nilai04
-- ----------------------------
INSERT INTO `td_nilai04` VALUES ('1', 'Ya', '100');
INSERT INTO `td_nilai04` VALUES ('2', 'Sebagian', '66');
INSERT INTO `td_nilai04` VALUES ('3', 'Tidak', '33');

-- ----------------------------
-- Table structure for td_nilai05
-- ----------------------------
DROP TABLE IF EXISTS `td_nilai05`;
CREATE TABLE `td_nilai05` (
  `TDNILAI05_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TDNILAI05_KETERANGAN` varchar(255) DEFAULT NULL,
  `TDNILAI05_SKOR` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`TDNILAI05_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of td_nilai05
-- ----------------------------
INSERT INTO `td_nilai05` VALUES ('1', 'Baik', '100');
INSERT INTO `td_nilai05` VALUES ('2', 'Cukup', '66');
INSERT INTO `td_nilai05` VALUES ('3', 'Kurang', '33');
