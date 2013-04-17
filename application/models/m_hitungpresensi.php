<?php

/**
 * Class	: M_hitungpresensi
 * 
 * Table	: hitungpresensi
 *  
 * @author masongbee
 *
 */
class M_hitungpresensi extends CI_Model{

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
		$query  = $this->db->get('hitungpresensi')->result();
		$total  = $this->db->get('hitungpresensi')->num_rows();
		
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
		
		if($this->db->get_where('hitungpresensi', array('NIK'=>$data->NIK))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.hitungpresensi.NIK = $data->NIK
			 */
			if($data->NIK != ''){
				$this->db->where('NIK', $data->NIK)->update('hitungpresensi', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, NIK, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('hitungpresensi')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('hitungpresensi', array('NIK'=>$data->NIK, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, NIK, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('NIK', 'ASC')->get('hitungpresensi')->row();
			
		}
		$total  = $this->db->get('hitungpresensi')->num_rows();
		
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
		$this->db->where('NIK', $data->NIK)->delete('hitungpresensi');
		
		$total  = $this->db->get('hitungpresensi')->num_rows();
		$last = $this->db->get('hitungpresensi')->result();
		
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