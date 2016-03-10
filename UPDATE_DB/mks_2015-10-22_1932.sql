/*ADD menu GROUP MANAGEMENT*/

SELECT @myLeft := MENU_LFT, @myRight := MENU_RGT, @myWidth := MENU_RGT - MENU_LFT + 1
FROM s_menus
WHERE MENU_ID = 102;

DELETE FROM s_menus WHERE MENU_LFT BETWEEN @myLeft AND @myRight;

UPDATE s_menus SET MENU_RGT = MENU_RGT - @myWidth WHERE rgt > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT - @myWidth WHERE lft > @myRight;



SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 101;

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
	MENU_RGT) VALUES(102,'GROUPMANAGE',1,2,'Group Management','GROUPMANAGE','window','N','Y','Y', @myRight + 1, @myRight + 2);


SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 102;

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
	MENU_RGT) VALUES(103,'USERMANAGE',1,3,'User Management','USERMANAGE','window','N','Y','Y', @myRight + 1, @myRight + 2);


UPDATE s_permissions
SET PERM_PRIV = NULL
WHERE PERM_MENU = 102;


INSERT INTO s_permissions(PERM_GROUP, PERM_MENU)
SELECT GROUP_ID, 103
FROM s_usergroups;

UPDATE s_permissions
SET PERM_PRIV = 'RCUD'
WHERE PERM_GROUP = 1 AND PERM_MENU = 103;