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
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('GROUP_ID', 'DESC')->get('s_usergroups')->result();
		$total  = $this->db->get('s_usergroups')->num_rows();
		
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
			$this->db->where('GROUP_ID', $data->GROUP_ID)->update('s_usergroups', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('s_usergroups', $data);
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

}


?>