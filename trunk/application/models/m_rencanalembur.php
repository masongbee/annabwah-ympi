<?php

/**
 * Class	: M_rencanalembur
 * 
 * Table	: rencanalembur
 *  
 * @author masongbee
 *
 */
class M_rencanalembur extends CI_Model{

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
		$query  = $this->db->get('rencanalembur')->result();
		$total  = $this->db->get('rencanalembur')->num_rows();
		
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
		
		if($this->db->get_where('rencanalembur', array('NOLEMBUR'=>$data->NOLEMBUR))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.rencanalembur.NOLEMBUR = $data->NOLEMBUR
			 */
			if($data->NOLEMBUR != ''){
				$this->db->where('NOLEMBUR', $data->NOLEMBUR)->update('rencanalembur', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, NOLEMBUR, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('rencanalembur')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('rencanalembur', array('NOLEMBUR'=>$data->NOLEMBUR, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, NOLEMBUR, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('NOLEMBUR', 'ASC')->get('rencanalembur')->row();
			
		}
		$total  = $this->db->get('rencanalembur')->num_rows();
		
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
		$this->db->where('NOLEMBUR', $data->NOLEMBUR)->delete('rencanalembur');
		
		$total  = $this->db->get('rencanalembur')->num_rows();
		$last = $this->db->get('rencanalembur')->result();
		
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