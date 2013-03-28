<?php

/**
 * Class	: M_permissiongroup
 * 
 * Table	: s_permissions
 *  
 * @author masongbee
 *
 */
class M_permissiongroup extends CI_Model{

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
		$sql = "SELECT vu_tree_menus.TREE_MENU_TITLE, vu_tree_menus.DEPTH, s_permissions.PERM_ID, ".$group_id." AS PERM_GROUP, vu_tree_menus.MENU_ID AS PERM_MENU,
				IF(s_permissions.PERM_PRIV IS NULL,0,1) AS PERM_PRIV
			FROM vu_tree_menus
			LEFT JOIN s_permissions ON(s_permissions.PERM_MENU = vu_tree_menus.MENU_ID AND s_permissions.PERM_GROUP = ".$group_id.")";
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
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array $data
	 * @return json
	 */
	function save($data){
		$this->firephp->log($data);
		$last   = NULL;
		$group_id = 0;
		
		if(sizeof($data) > 1){
			foreach ($data as $row){
				$group_id = $row->PERM_GROUP;
				$datau = array();
				if($row->PERM_PRIV){
					$datau['PERM_PRIV']	= 'RCUD';
				}else{
					$datau['PERM_PRIV']	= null;
				}
				//UPDATE db.s_permissions
				$datau['PERM_GROUP'] 	= $row->PERM_GROUP;
				$datac['PERM_MENU'] 	= $row->PERM_MENU;
				$this->db->where('PERM_ID', $row->PERM_ID)->update('s_permissions', $datau);
			}
		}else{
			$group_id = $data->PERM_GROUP;
			$datau = array();
			if($data->PERM_PRIV){
				$datau['PERM_PRIV']	= 'RCUD';
			}else{
				$datau['PERM_PRIV']	= null;
			}
			//UPDATE db.s_permissions
			$datau['PERM_GROUP'] 	= $data->PERM_GROUP;
			$datac['PERM_MENU'] 	= $data->PERM_MENU;
			$this->db->where('PERM_ID', $data->PERM_ID)->update('s_permissions', $datau);
		}
		$json = $this->getAll($group_id);
		
		return $json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		$this->db->where('PERM_ID', $data->PERM_ID)->delete('s_permissions');
		
		$total  = $this->db->get('s_permissions')->num_rows();
		$last = $this->db->get('s_permissions')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}

}


?>