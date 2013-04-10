<?php

/**
 * Class	: M_keluarga
 * 
 * Table	: keluarga
 *  
 * @author masongbee
 *
 */
class M_keluarga extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->get('keluarga')->result();
		$total  = $this->db->get('keluarga')->num_rows();
	
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
		
		if($this->db->get_where('keluarga', array('NIK'=>$data->NIK))->num_rows() > 0){
			/*
			 * Data Exist
			 * 
			 * Process Update	==> update berdasarkan db.keluarga.NIK = $data->NIK
			 */
			$this->db->where('NIK', $data->NIK)->update('keluarga', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('keluarga', $data);
			$last   = $this->db->order_by('NIK', 'ASC')->get('keluarga')->row();
			
		}
		
		$total  = $this->db->get('keluarga')->num_rows();
		
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
		$this->db->where('NIK', $data->NIK)->delete('keluarga');
		
		$total  = $this->db->get('keluarga')->num_rows();
		$last 	= $this->db->get('keluarga')->result();
		
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