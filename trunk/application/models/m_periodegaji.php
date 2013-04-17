<?php

/**
 * Class	: M_periodegaji
 * 
 * Table	: periodegaji
 *  
 * @author masongbee
 *
 */
class M_periodegaji extends CI_Model{

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
		$query  = $this->db->get('periodegaji')->result();
		$total  = $this->db->get('periodegaji')->num_rows();
		
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
		
		if($this->db->get_where('periodegaji', array('BULAN'=>$data->BULAN))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.periodegaji.BULAN = $data->BULAN
			 */
			if($data->BULAN != ''){
				$this->db->where('BULAN', $data->BULAN)->update('periodegaji', array('USER_PASSWD'=>md5($data->USER_PASSWD)));
				if($this->db->affected_rows()){
					$last   = $this->db->select('USER_ID, BULAN, "[hidden]" AS USER_PASSWD, GROUP_ID')->get('periodegaji')->row();
				}
			}
			
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('periodegaji', array('BULAN'=>$data->BULAN, 'USER_PASSWD'=>md5($data->USER_PASSWD), 'USER_GROUP'=>$data->GROUP_ID));
			$last   = $this->db->select('USER_ID, BULAN, "[hidden]" AS USER_PASSWD, GROUP_ID')
					->order_by('BULAN', 'ASC')->get('periodegaji')->row();
			
		}
		$total  = $this->db->get('periodegaji')->num_rows();
		
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
		$this->db->where('BULAN', $data->BULAN)->delete('periodegaji');
		
		$total  = $this->db->get('periodegaji')->num_rows();
		$last = $this->db->get('periodegaji')->result();
		
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