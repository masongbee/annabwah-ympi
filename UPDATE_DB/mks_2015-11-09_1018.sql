ALTER TABLE `pesertatraining` DROP FOREIGN KEY `pesertatraining_ibfk_1`;
ALTER TABLE `pesertatraining` MODIFY COLUMN `NOREALISASI`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `TAHUN`;
ALTER TABLE `pesertatraining` DROP PRIMARY KEY, ADD PRIMARY KEY (`KODETRAINING`, `TAHUN`, `NIK`);
ALTER TABLE `pesertatraining` ADD CONSTRAINT `pesertatraining_ibfk_1` FOREIGN KEY (`KODETRAINING`, `TAHUN`) REFERENCES `realisasitraining` (`KODETRAINING`, `TAHUN`) ON DELETE RESTRICT ON UPDATE RESTRICT;



ALTER TABLE `realisasitraining` MODIFY COLUMN `NOREALISASI`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `TAHUN`;
ALTER TABLE `realisasitraining` DROP PRIMARY KEY, ADD PRIMARY KEY (`KODETRAINING`, `TAHUN`);



ALTER TABLE `riwayattraining` MODIFY COLUMN `NOURUT`  int(11) NULL DEFAULT NULL AFTER `NIK`;
ALTER TABLE `riwayattraining` MODIFY COLUMN `NAMATRAINING`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `NOURUT`;
drop procedure if exists schema_change;

delimiter ';;'
create procedure schema_change() begin

 	/* delete columns if they exist */
 	if exists (select * from information_schema.columns where table_schema = 'dbympi_test' and table_name = 'riwayattraining' and column_name = 'KODETRAINING') then
  		alter table riwayattraining drop column KODETRAINING;
 	end if;
  
end;;

delimiter ';'
call schema_change();

drop procedure if exists schema_change;
ALTER TABLE `riwayattraining` ADD COLUMN `KODETRAINING`  varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `KETERANGAN`;
ALTER TABLE `riwayattraining` DROP PRIMARY KEY, ADD PRIMARY KEY (`NIK`, `KODETRAINING`);



ALTER TABLE `jenistraining` MODIFY COLUMN `NAMATRAINING`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `KODETRAINING`;



SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 658;

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
	MENU_RGT) VALUES(659,'LAPTRAINING',6,59,'Daftar Training Karyawan','LAPTRAINING','window','N','Y','Y', @myRight + 1, @myRight + 2);

INSERT INTO s_permissions(PERM_GROUP, PERM_MENU)
SELECT GROUP_ID, 659
FROM s_usergroups;