<?php

/**
 * Class	: M_riwayatkerja
 * 
 * Table	: riwayatkerja
 *  
 * @author masongbee
 *
 */
class M_riwayatkerja extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->get('riwayatkerja')->result();
		$total  = $this->db->get('riwayatkerja')->num_rows();
	
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
		$key = array('NIK'=>$data->NIK, 'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('riwayatkerja', $key)->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.riwayatkerja.NIK = $data->NIK
			 */
			$this->db->where('NIK', $key)->update('riwayatkerja', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('riwayatkerja', $data);
			$last   = $this->db->get_where('riwayatkerja', $key)->row();
			
		}
		
		$total  = $this->db->get('riwayatkerja')->num_rows();
		
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
		$key = array('NIK'=>$data->NIK, 'NOURUT'=>$data->NOURUT);
		$this->db->where($key)->delete('riwayatkerja');
		
		$total  = $this->db->get('riwayatkerja')->num_rows();
		$last 	= $this->db->get('riwayatkerja')->result();
		
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