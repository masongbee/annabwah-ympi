<?php

/**
 * Class	: M_splembur
 * 
 * Table	: splembur
 *  
 * @author masongbee
 *
 */
class M_splembur extends CI_Model{

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
		$query  = $this->db->get('splembur')->result();
		$total  = $this->db->get('splembur')->num_rows();
		
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
		
		if($this->db->get_where('splembur', array('NOLEMBUR'=>$data->NOLEMBUR))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.splembur.NOLEMBUR = $data->NOLEMBUR
			 */
			if($data->NOLEMBUR != ''){
				$this->db->where('NOLEMBUR', $data->NOLEMBUR)->update('splembur', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, NOLEMBUR, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('splembur')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('splembur', array('NOLEMBUR'=>$data->NOLEMBUR, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, NOLEMBUR, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('NOLEMBUR', 'ASC')->get('splembur')->row();
			
		}
		$total  = $this->db->get('splembur')->num_rows();
		
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
		$this->db->where('NOLEMBUR', $data->NOLEMBUR)->delete('splembur');
		
		$total  = $this->db->get('splembur')->num_rows();
		$last = $this->db->get('splembur')->result();
		
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