/* ADD COLUMN table ttransport */
ALTER TABLE `ttransport` ADD COLUMN `FPENGALI`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '#H (Hari Kerja) = dikali jumlah hari kerja\r\n#L (Lumpsum) = tanpa hari kerja' AFTER `KODEJAB`;


/* ADD COLUMN table tkehadiran */
ALTER TABLE `tkehadiran` ADD COLUMN `FPENGALI`  char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '#H (Hari Kerja) = dikali jumlah hari kerja\r\n#L (Lumpsum) = tanpa hari kerja' AFTER `NIK`;


/* ADD COLUMN table hitungpresensi */
ALTER TABLE `hitungpresensi` ADD COLUMN `XPOTONG`  int(1) NULL DEFAULT 0 AFTER `JAMKURANG`;


/* ADD COLUMN table pjamsostek */
ALTER TABLE `pjamsostek` ADD COLUMN `VALIDTO`  date NULL DEFAULT NULL AFTER `VALIDFROM`;