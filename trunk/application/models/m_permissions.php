<?php

/**
 * Class	: M_permissions
 * 
 * Table	: s_permissions
 *  
 * @author masongbee
 *
 */
class M_permissions extends CI_Model{

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
		$last   = NULL;
		
		if(sizeof($data) > 1){
			$group_id = $data[0]->PERM_GROUP;
			$menu_parent = array();
			foreach ($data as $row){
				//$group_id = $row->PERM_GROUP;
				$datau = array();
				if($row->PERM_PRIV){
					$datau['PERM_PRIV']	= 'RCUD';
				}else{
					$datau['PERM_PRIV']	= null;
				}
				//UPDATE db.s_permissions
				//$datau['PERM_GROUP'] 	= $row->PERM_GROUP;
				//$datau['PERM_MENU'] 	= $row->PERM_MENU;
				$this->db->where('PERM_ID', $row->PERM_ID)->update('s_permissions', $datau);
				
				if(($row->MENU_PARENT != 0) && (!in_array($row->MENU_PARENT, $menu_parent))){
					array_push($menu_parent, $row->MENU_PARENT);
				}
			}
			//UPDATE db.s_permissions.PERM_PRIV ==> Untuk Parent dari menu yang telah dicentang Hak Aksesnya
			if(sizeof($menu_parent) > 0){
				for ($i=0; $i < sizeof($menu_parent); $i++){
					$this->parent_priv_update($menu_parent[$i], $group_id);
				}
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
			//$datau['PERM_GROUP'] 	= $data->PERM_GROUP;
			//$datau['PERM_MENU'] 	= $data->PERM_MENU;
			$this->db->where('PERM_ID', $data->PERM_ID)->update('s_permissions', $datau);
			
			if($data->MENU_PARENT != 0){
				$this->parent_priv_update($data->MENU_PARENT, $data->PERM_GROUP);
			}
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
	
	function parent_priv_update($menu_parent, $group_id){
		$datau = array();
		
		$this->db->query('CALL proc_submenus_by('.$menu_parent.')');
		$sql = "SELECT PERM_ID
			FROM submenus_by
			JOIN s_permissions ON(s_permissions.PERM_MENU = submenus_by.MENU_ID
				AND s_permissions.PERM_GROUP = ".$group_id." AND s_permissions.PERM_PRIV IS NOT NULL)";
		$rs = $this->db->query($sql);
		$nbrows = $rs->num_rows();
		
		if($nbrows > 0){
			//UPDATE db.s_permissions.PERM_PRIV ==> Untuk Parent dari menu yang telah dicentang Hak Aksesnya
			$datau['PERM_PRIV']	= 'RCUD';
			$this->db->where(array('PERM_GROUP'=>$group_id, 'PERM_MENU'=>$menu_parent))->update('s_permissions', $datau);
		}else{
			//UPDATE db.s_permissions.PERM_PRIV ==> Untuk Parent dari menu yang telah dicentang Hak Aksesnya
			$datau['PERM_PRIV']	= null;
			$this->db->where(array('PERM_GROUP'=>$group_id, 'PERM_MENU'=>$menu_parent))->update('s_permissions', $datau);
		}
	}

}


?>