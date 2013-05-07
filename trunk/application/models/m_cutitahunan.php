<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_cutitahunan
 * 
 * Table	: cutitahunan
 *  
 * @author masongbee
 *
 */
class M_cutitahunan extends CI_Model{

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
	function getAll($start, $page, $limit){$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('cutitahunan')->result();
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>$data->TANGGAL);
		
		if($this->db->get_where('cutitahunan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$this->db->where($pkey)->update('cutitahunan', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('cutitahunan', $data);
			$last   = $this->db->order_by('TANGGAL', 'ASC')->get('cutitahunan')->row();
			
		}
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>$data->TANGGAL);
		
		$this->db->where($pkey)->delete('cutitahunan');
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		$last = $this->db->get('cutitahunan')->result();
		
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