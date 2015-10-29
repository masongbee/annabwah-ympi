DROP VIEW IF EXISTS `vu_tree_menus`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost`  VIEW `vu_tree_menus` AS select concat(convert(convert(repeat('&nbsp;&nbsp;&nbsp;',(count(`parent`.`MENU_ID`) - 1)) using latin1) using utf8),`node`.`MENU_TITLE`) AS `TREE_MENU_TITLE`,`node`.`MENU_ID` AS `MENU_ID`,`node`.`MENU_KODE` AS `MENU_KODE`,`node`.`MENU_PARENT` AS `MENU_PARENT`,`node`.`MENU_POSITION` AS `MENU_POSITION`,`node`.`MENU_TITLE` AS `MENU_TITLE`,`node`.`MENU_FILENAME` AS `MENU_FILENAME`,`node`.`MENU_CAT` AS `MENU_CAT`,`node`.`MENU_CONFIRM` AS `MENU_CONFIRM`,`node`.`MENU_LEFTPANEL` AS `MENU_LEFTPANEL`,`node`.`MENU_ICONPANEL` AS `MENU_ICONPANEL`,`node`.`MENU_ICONMENU` AS `MENU_ICONMENU`,`node`.`MENU_AKTIF` AS `MENU_AKTIF`,`node`.`MENU_LFT` AS `MENU_LFT`,`node`.`MENU_RGT` AS `MENU_RGT`,(count(`parent`.`MENU_ID`) - 1) AS `DEPTH` from (`s_menus` `node` join `s_menus` `parent`) where (`node`.`MENU_AKTIF` = 'Y' and `node`.`MENU_LFT` between `parent`.`MENU_LFT` and `parent`.`MENU_RGT`) group by `node`.`MENU_ID` order by `node`.`MENU_LFT`,`node`.`MENU_POSITION` ;





UPDATE s_menus
SET MENU_AKTIF = 'T'
WHERE MENU_ID IN(224,225,226,227,228,229,
519,520,521,522,
646,647,648,
506,507,
635,636,637,638);