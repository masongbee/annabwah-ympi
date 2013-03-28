<?php

/**
 * Class	: M_user
 * 
 * Table	: s_users
 *  
 * @author masongbee
 *
 */
class M_user extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $group_id
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($group_id, $start, $page, $limit){
		$query  = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID')
				->where('GROUP_ID', $group_id)->limit($limit, $start)->get('vu_s_users')->result();
		$total  = $this->db->where('GROUP_ID', $group_id)->get('vu_s_users')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
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
		
		if($this->db->get_where('s_users', array('USER_NAME'=>$data->USER_NAME))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.s_users.USER_NAME = $data->USER_NAME
			 */
			if($data->USER_PASSWD != ''){
				$this->db->where('USER_NAME', $data->USER_NAME)->update('s_users', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('vu_s_users')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('s_users', array('USER_NAME'=>$data->USER_NAME, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('USER_NAME', 'ASC')->get('vu_s_users')->row();
			
		}
		$total  = $this->db->get('vu_s_users')->num_rows();
		
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
		$this->db->where('USER_NAME', $data->USER_NAME)->delete('s_users');
		
		$total  = $this->db->get('vu_s_users')->num_rows();
		$last = $this->db->get('vu_s_users')->result();
		
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