<?php

/**
 * Class	: M_permohonancuti
 * 
 * Table	: permohonancuti
 *  
 * @author masongbee
 *
 */
class M_permohonancuti extends CI_Model{

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
		$query  = $this->db->get('permohonancuti')->result();
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
		
		if($this->db->get_where('permohonancuti', array('NOCUTI'=>$data->NOCUTI))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.permohonancuti.NOCUTI = $data->NOCUTI
			 */
			if($data->NOCUTI != ''){
				$this->db->where('NOCUTI', $data->NOCUTI)->update('permohonancuti', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, NOCUTI, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('permohonancuti')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('permohonancuti', array('NOCUTI'=>$data->NOCUTI, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, NOCUTI, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('NOCUTI', 'ASC')->get('permohonancuti')->row();
			
		}
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
		$this->db->where('NOCUTI', $data->NOCUTI)->delete('permohonancuti');
		
		$total  = $this->db->get('permohonancuti')->num_rows();
		$last = $this->db->get('permohonancuti')->result();
		
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