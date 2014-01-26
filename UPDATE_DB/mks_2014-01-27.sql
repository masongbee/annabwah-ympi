UPDATE s_menus
SET MENU_ID = MENU_ID + 1, MENU_POSITION = MENU_POSITION + 1
WHERE MENU_PARENT = 4 AND MENU_ID > 401
ORDER BY MENU_ID DESC;

/*ADD CHILD at UP of node*/
LOCK TABLE s_menus WRITE;
SELECT @myLeft := MENU_LFT, @myRight := MENU_RGT FROM s_menus WHERE MENU_ID = 403;

UPDATE s_menus SET MENU_RGT = MENU_RGT + 2 WHERE MENU_RGT >= @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT + 2 WHERE MENU_LFT >= @myLeft;

INSERT INTO s_menus(MENU_ID, MENU_KODE, MENU_PARENT, MENU_POSITION, MENU_TITLE, MENU_FILENAME, MENU_LFT, MENU_RGT) 
VALUES(402, 'PRESENSIKHUSUS', 4, 2, 'Import Presensi Khusus', 'PRESENSIKHUSUS', @myLeft, @myRight);
UNLOCK TABLES;



DROP TABLE IF EXISTS `presensikhusus`;
CREATE TABLE `presensikhusus` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NIK` varchar(10) NOT NULL,
  `NAMASHIFT` varchar(20) NOT NULL,
  `SHIFTKE` char(1) NOT NULL,
  `TANGGAL` date NOT NULL,
  `TJMASUK` datetime DEFAULT NULL,
  `TJKELUAR` datetime DEFAULT NULL,
  `ASALDATA` char(1) DEFAULT 'D' COMMENT 'ASALDATA diisi (otomatis oleh sistem) dengan ''D'' (database mesin presensi) jika hasil migrasi / import dari hasil bacaan mesin absensi (dalam bentuk .mdb) dan diisi dengan ''M'' (manual) jika diketik / dientri manual.',
  `JENISABSEN` char(2) NOT NULL COMMENT 'JENISABSEN diisi dengan:\r\n            HD=Hadir\r\n            IJ=Ijin (dengan keterangan yang sah)\r\n            IN=Ijin Melaksanakan Kewajiban Negara\r\n            IH=Ijin Haid\r\n            II=Ijin Melaksanakan Ibadah\r\n            IK=Ijin Menunggui Keluarga ',
  `JENISLEMBUR` char(1) DEFAULT NULL,
  `EXTRADAY` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`,`NIK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;