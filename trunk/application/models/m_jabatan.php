<?php

/**
 * Class	: M_jabatan
 * 
 * Table	: jabatan
 *  
 * @author masongbee
 *
 */
class M_jabatan extends CI_Model{

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
	function getAll($kodeunit, $start, $page, $limit){
		$query  = $this->db->where('KODEUNIT', $kodeunit)->limit($limit, $start)->get('vu_jabatan')->result();
		$total  = $this->db->where('KODEUNIT', $kodeunit)->get('vu_jabatan')->num_rows();
	
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
		
		if($this->db->get_where('jabatan', array('KODEUNIT'=>$data->KODEUNIT, 'KODEJAB'=>$data->KODEJAB))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.jabatan.KODEUNIT = $data->KODEUNIT and db.jabatan.KODEJAB = $data->KODEJAB
			 */
			$this->db->where(array('KODEUNIT'=>$data->KODEUNIT, 'KODEJAB'=>$data->KODEJAB))->update('jabatan', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('jabatan', $data);
			$last   = $this->db->where('KODEJAB', $data->KODEJAB)->get('jabatan')->row();
			
		}
		
		$total  = $this->db->get('jabatan')->num_rows();
		
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
		$this->db->where(array('KODEUNIT'=>$data->KODEUNIT, 'KODEJAB'=>$data->KODEJAB))->delete('jabatan');
		
		$total  = $this->db->get('jabatan')->num_rows();
		$last = $this->db->get('jabatan')->result();
		
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