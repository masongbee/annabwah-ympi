
-- ----------------------------
-- Function structure for substrCount
-- ----------------------------
DROP FUNCTION IF EXISTS `substrCount`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `substrCount`(s VARCHAR(255), ss VARCHAR(255)) RETURNS tinyint(3) unsigned
    READS SQL DATA
BEGIN
DECLARE count TINYINT(3) UNSIGNED;
DECLARE offset TINYINT(3) UNSIGNED;
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET s = NULL;
SET count = 0;
SET offset = 1;
REPEAT
IF NOT ISNULL(s) AND offset > 0 THEN
SET offset = LOCATE(ss, s, offset);
IF offset > 0 THEN
SET count = count + 1;
SET offset = offset + 1;
END IF;
END IF;
UNTIL ISNULL(s) OR offset = 0 END REPEAT;
RETURN count;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `auto_delete_karyawanmut`;
DELIMITER ;;
CREATE TRIGGER `auto_delete_karyawanmut` BEFORE DELETE ON `karyawan` FOR EACH ROW -- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
	DELETE FROM karyawanmut WHERE NIK=OLD.NIK;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `auto_delete_s_permissions_s_users`;
DELIMITER ;;
CREATE TRIGGER `auto_delete_s_permissions_s_users` BEFORE DELETE ON `s_usergroups` FOR EACH ROW -- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
	DELETE FROM s_permissions WHERE PERM_GROUP=OLD.GROUP_ID;
	DELETE FROM s_users WHERE USER_GROUP=OLD.GROUP_ID;
END
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `auto_delete_jabatan`;
DELIMITER ;;
CREATE TRIGGER `auto_delete_jabatan` BEFORE DELETE ON `unitkerja` FOR EACH ROW -- Edit trigger body code below this line. Do not edit lines above this one
BEGIN
	DELETE FROM jabatan WHERE KODEUNIT=OLD.KODEUNIT;
END
;;
DELIMITER ;




-- ----------------------------
-- Function structure for stringSplit
-- ----------------------------
DROP FUNCTION IF EXISTS `stringSplit`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `stringSplit`(x VARCHAR(255),
delim VARCHAR(12),
pos INT) RETURNS varchar(255) CHARSET utf8
RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos),
LENGTH(SUBSTRING_INDEX(x, delim, pos -1)) + 1), delim, '')
;;
DELIMITER ;




-- ----------------------------
-- Procedure structure for splitter
-- ----------------------------
DROP PROCEDURE IF EXISTS `splitter`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `splitter`(x varchar(255), delim varchar(12))
BEGIN
SET @Valcount = substrCount(x,delim)+1;
SET @v1=0;
drop table if exists splitResults;
create temporary
table splitResults (split_value varchar(255));
WHILE (@v1 < @Valcount) DO
set @val = stringSplit(x,delim,@v1+1);
INSERT INTO splitResults (split_value) VALUES (@val);
SET @v1 = @v1 + 1;
END WHILE;
END
;;
DELIMITER ;
