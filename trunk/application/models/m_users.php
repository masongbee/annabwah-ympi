<?php

/**
 * Class	: M_users
 * 
 * Table	: s_users
 *  
 * @author masongbee
 *
 */
class M_users extends CI_Model{

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
		// $query  = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN')
		// 		->where('GROUP_ID', $group_id)->limit($limit, $start)->get('vu_s_users')->result();
		$query  = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN,NAMAKAR')
				->limit($limit, $start)->get('vu_s_users')->result();
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
				$this->db->where('USER_NAME', $data->USER_NAME)->update('s_users', array('USER_PASSWD'=>md5($data->USER_PASSWD),'USER_KARYAWAN'=>$data->USER_KARYAWAN));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER,USER_KARYAWAN')->get('vu_s_users')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('s_users', array('USER_NAME'=>$data->USER_NAME, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_KARYAWAN'=>$data->USER_KARYAWAN));
			$insert_id = $this->db->insert_id();
			if ($data->VIP_USER){
				$user_file = $this->auth->Enkripsi($data->USER_PASSWD,$data->USER_NAME.'.txt');
				$this->db->where('USER_ID', $insert_id)->update('s_users', array('USER_FILE'=>$user_file));
			}
			$last   = $this->db->select('USER_ID, USER_NAME, "[hidden]" AS USER_PASSWD, GROUP_ID, IF(USER_FILE IS NULL, 0, 1) AS VIP_USER')
					->where('USER_ID', $insert_id)->get('vu_s_users')->row();
			
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