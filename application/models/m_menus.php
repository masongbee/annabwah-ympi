<?php

/**
 * Class	: M_menus
 * 
 * Table	: grade
 *  
 * @author masongbee
 *
 */
class M_menus extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: build_menus
	 * 
	 * Untuk menghasilkan List Menu berbentu "tree"
	 * 
	 * @param stdClass $rows
	 * @param number $first_depth
	 * @param number $prev_depth
	 * @param number $depth
	 * @return string
	 */
	function build_menus($rows, $first_depth=0, $prev_depth=0, $depth=0){
		$count_rows = sizeof($rows);
	
		$result = '{
						"expanded":true,
						"children":[
						';
	
		$i = 0;
		foreach ($rows as $row){
			$depth = $row['DEPTH'];
				
			/*
			 * Jika ($prev_depth < $depth) ==> variable $result ditutup dengan "]}";
			*/
			if($prev_depth > $depth){
				$gap_depth = $prev_depth - $depth;
					
				for($g=1; $g<=$gap_depth; $g++){
					$result .= "]}";
				}
			}
				
			/*
			 * Jika (($i>0) && ($prev_depth == $depth)) ==> variable $result ditambah dengan ",";
			*/
			if(($i>0) && ($prev_depth == $depth)){
				$result .= ',';
			}elseif (($i>0) && ($prev_depth > $depth)){
				$result .= ',';
			}
				
			if($row['MENU_RGT'] == ($row['MENU_LFT'] + 1)){
				$result .= '{
								"id":"'.$row['MENU_FILENAME'].'",
								"text":"'.$row['MENU_TITLE'].'",
								"iconCls":"'.$row['MENU_ICONMENU'].'",
								"leaf":true
							}';
			}else{
				$result .= '{
								"id":"'.$row['MENU_FILENAME'].'",
								"text":"'.$row['MENU_TITLE'].'",
								"iconCls":"'.$row['MENU_ICONMENU'].'",
								"expanded":false,
								"children":[
							';
			}
				
			if(($i+1) == $count_rows){
				$gap_depth = $depth - $first_depth;
	
				for($g=1; $g<=$gap_depth; $g++){
					$result .= "]}";
				}
			}
	
			if($prev_depth != $depth){
				$prev_depth = $depth;
			}
			$i++;
		}
		$result.= ']}';
	
		return $result;
	}
	
	/**
	 * Fungsi	: getMenus
	 * 
	 * Untuk mengambil data Tree dari db.vu_s_menus
	 * 
	 * @param number $group_id
	 * @return string
	 */
	function getMenus($group_id){
		$sql = "SELECT node.MENU_ID, node.MENU_KODE, node.MENU_TITLE, node.MENU_FILENAME, node.MENU_ICONMENU, node.MENU_LFT, node.MENU_RGT,
				node.DEPTH AS DEPTH
			FROM vu_s_menus AS node,
				vu_s_menus AS parent,
				vu_s_menus AS sub_parent,
				vu_s_menus AS sub_tree
			WHERE node.MENU_LFT BETWEEN parent.MENU_LFT AND parent.MENU_RGT
				AND node.MENU_LFT BETWEEN sub_parent.MENU_LFT AND sub_parent.MENU_RGT
				AND sub_parent.MENU_ID = sub_tree.MENU_ID
			GROUP BY node.MENU_ID
			ORDER BY node.MENU_LFT, node.MENU_POSITION";
		
		$result = $this->db->query($sql);
		$rows = $result->result_array();
		
		$tree_menus = $this->build_menus($rows);
		
		return $tree_menus;
	}
	
}


?>