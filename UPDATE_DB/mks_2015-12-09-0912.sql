DELETE FROM s_permissions WHERE PERM_MENU = 523;

/*=======*/

SELECT @myLeft := MENU_LFT, @myRight := MENU_RGT, @myWidth := MENU_RGT - MENU_LFT + 1
FROM s_menus
WHERE MENU_ID = 523;

DELETE FROM s_menus WHERE MENU_LFT BETWEEN @myLeft AND @myRight;

UPDATE s_menus SET MENU_RGT = MENU_RGT - @myWidth WHERE MENU_RGT > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT - @myWidth WHERE MENU_LFT > @myRight;

/*=======*/

SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 247;

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
	MENU_RGT) VALUES(248,'LOWONGAN',2,48,'Lowongan','LOWONGAN','window','N','Y','Y', @myRight + 1, @myRight + 2);

INSERT INTO s_permissions(PERM_GROUP, PERM_MENU)
SELECT GROUP_ID, 248
FROM s_usergroups;

/*=======*/

DELETE FROM s_permissions WHERE PERM_MENU = 524;

/*=======*/

SELECT @myLeft := MENU_LFT, @myRight := MENU_RGT, @myWidth := MENU_RGT - MENU_LFT + 1
FROM s_menus
WHERE MENU_ID = 524;

DELETE FROM s_menus WHERE MENU_LFT BETWEEN @myLeft AND @myRight;

UPDATE s_menus SET MENU_RGT = MENU_RGT - @myWidth WHERE MENU_RGT > @myRight;
UPDATE s_menus SET MENU_LFT = MENU_LFT - @myWidth WHERE MENU_LFT > @myRight;

/*=======*/

SELECT @myRight := MENU_RGT FROM s_menus
WHERE MENU_ID = 248;

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
	MENU_RGT) VALUES(249,'POSISILOWONGAN',2,49,'Posisi Lowongan','POSISILOWONGAN','window','N','Y','Y', @myRight + 1, @myRight + 2);

INSERT INTO s_permissions(PERM_GROUP, PERM_MENU)
SELECT GROUP_ID, 249
FROM s_usergroups;

/*=======*/

