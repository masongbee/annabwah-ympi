<?php

/**
 * Class	: M_usergroups
 * 
 * Table	: s_usergroups
 *  
 * @author masongbee
 *
 */
class M_usergroups extends CI_Model{

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
	function getAll($start, $page, $limit, $user_id){
		// $query  = $this->db->limit($limit, $start)->order_by('GROUP_ID', 'DESC')->get('s_usergroups')->result();
		// $query  = $this->db->order_by('GROUP_ID', 'DESC')->get('s_usergroups')->result();
		$select = "SELECT GROUP_ID
			,GROUP_NAME
			,GROUP_DESC
			,GROUP_ACTIVE";
		$from = " FROM s_usergroups";
		$orderby = " ORDER BY GROUP_ID DESC";

		if ($user_id > 0) {
			//get USER_GROUP
			$user_group = $this->db->select('USER_GROUP')->where('USER_ID', $user_id)->get('s_users')->row()->USER_GROUP;
			$this->db->query("CALL splitter('".$user_group."', ',')");
			$from .= " LEFT JOIN splitResults ON(splitResults.split_value = s_usergroups.GROUP_ID)";
			$select .= ",IF(splitResults.split_value IS NULL, 0, 1) AS GROUP_USER";
		}else{
			$select .= ",0 AS GROUP_USER";
		}

		$sql = $select.$from.$orderby;
		$query = $this->db->query($sql)->result();
		$total  = sizeof($query);
		
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
		
		if($this->db->get_where('s_usergroups', array('GROUP_ID'=>$data->GROUP_ID))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.s_usergroups.GROUP_ID = $data->GROUP_ID
			 */
			$datau = array(
				'GROUP_NAME'	=> $data->GROUP_NAME,
				'GROUP_DESC'	=> $data->GROUP_DESC
			);
			$this->db->where('GROUP_ID', $data->GROUP_ID)->update('s_usergroups', $datau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$datai = array(
				'GROUP_NAME'	=> $data->GROUP_NAME,
				'GROUP_DESC'	=> $data->GROUP_DESC
			);
			$this->db->insert('s_usergroups', $datai);
			$group_id = $this->db->insert_id();
			$last   = $this->db->order_by('GROUP_ID', 'DESC')->get('s_usergroups')->row();
			
			/*
			 * Call Procedure	: proc_s_permissions_default
			 * 
			 * Untuk permission default dari Group yang telah di-create
			 */
			$this->db->query('CALL proc_s_permissions_default('.$group_id.')');
			
		}
		
		$total  = $this->db->get('s_usergroups')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
						"data"      => $last
		);
		
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
		$this->db->where('GROUP_ID', $data->GROUP_ID)->delete('s_usergroups');
		
		$total  = $this->db->get('s_usergroups')->num_rows();
		$last = $this->db->get('s_usergroups')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);
		
		return $json;
	}

	function hakuser_save($data, $user_id){
		$last   = NULL;

		if(sizeof($data) > 1){
			$datau = array();

			$USER_GROUP = "";
			$i = 0;
			foreach ($data as $row){
				if (($i == 0) && $row->GROUP_USER) {
					$USER_GROUP .= $row->GROUP_ID;

					$i++;
				}else if (($i > 0) && $row->GROUP_USER){
					$USER_GROUP .= ",".$row->GROUP_ID;
				}

			}
			$datau['USER_GROUP'] = $USER_GROUP;
			$this->db->where('USER_ID', $user_id)->update('s_users', $datau);
			
		}
		
		return 1;
	}

}


?>