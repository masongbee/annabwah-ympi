<?php

/**
 * Class	: M_permissions2
 * 
 * Table	: s_permissions
 *  
 * @author masongbee
 *
 */
class M_permissions2 extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($group_id){
		$sql = "SELECT vu_tree_menus.TREE_MENU_TITLE,
				s_permissions.PERM_ID, 
				".$group_id." AS PERM_GROUP, 
				vu_tree_menus.MENU_ID AS PERM_MENU,
				IF(s_permissions.PERM_PRIV IS NULL,0,1) AS PERM_PRIV,
				vu_tree_menus.MENU_PARENT,
				vu_tree_menus.DEPTH
			FROM vu_tree_menus
			LEFT JOIN s_permissions ON(s_permissions.PERM_MENU = vu_tree_menus.MENU_ID AND s_permissions.PERM_GROUP = ".$group_id.")
			WHERE s_permissions.PERM_PRIV IS NOT NULL
			ORDER BY vu_tree_menus.MENU_LFT, vu_tree_menus.MENU_POSITION";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->query($sql)->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json   = array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'total'     => $total,
						'data'      => $data
		);
		
		return $json;
	}

}


?>