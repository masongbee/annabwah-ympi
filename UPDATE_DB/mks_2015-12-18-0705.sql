ALTER TABLE `c_jabatan` DROP FOREIGN KEY `c_jabatan_ibfk_2`;

ALTER TABLE `c_jabatan` ADD COLUMN `AKTIF`  enum('T','Y') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Y' AFTER `KODEKEL`;

ALTER TABLE `jabatan` ADD COLUMN `AKTIF`  enum('T','Y') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Y' AFTER `KODEKEL`;

ALTER TABLE `unitkerja` ADD COLUMN `AKTIF`  enum('T','Y') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'Y' AFTER `RGT`;

ALTER TABLE `c_karyawan` DROP FOREIGN KEY `c_karyawan_ibfk_1`;

ALTER TABLE `c_unitkerja` DROP FOREIGN KEY `c_unitkerja_ibfk_1`;

ALTER 
ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost` 
SQL SECURITY DEFINER 
VIEW `vu_unitkerja` AS 
SELECT
	concat(

		REPEAT
			(
				'&nbsp;&nbsp;&nbsp;',
				(
					count(`parent`.`NAMAUNIT`) - 1
				)
			),
			`node`.`NAMAUNIT`
	) AS `NAMAUNIT_TREE`,
	`node`.`NAMAUNIT` AS `NAMAUNIT`,
	`node`.`KODEUNIT` AS `KODEUNIT`,
	`node`.`P_KODEUNIT` AS `P_KODEUNIT`,
	(
		count(`parent`.`NAMAUNIT`) - 1
	) AS `depth`
FROM
	(
		`unitkerja` `node`
		JOIN `unitkerja` `parent`
	)
WHERE
	(
		`node`.`AKTIF` = 'Y' AND `parent`.`AKTIF` = 'Y'
		AND `node`.`LFT` BETWEEN `parent`.`LFT`
		AND `parent`.`RGT`
	)
GROUP BY
	`node`.`KODEUNIT`
ORDER BY
	`node`.`LFT`,
	`node`.`KODEUNIT` ;