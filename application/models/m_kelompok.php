<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_kelompok
 * 
 * Table	: kelompok
 *  
 * @author masongbee
 *
 */
class M_kelompok extends CI_Model{

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
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('KODEKEL', 'ASC')->get('kelompok')->result();
		$total  = $this->db->get('kelompok')->num_rows();
		
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
		
		$pkey = array('KODEKEL'=>$data->KODEKEL);
		
		if($this->db->get_where('kelompok', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAKEL'=>$data->NAMAKEL);
			 
			$this->db->where($pkey)->update('kelompok', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('KODEKEL'=>$data->KODEKEL,'NAMAKEL'=>$data->NAMAKEL);
			 
			$this->db->insert('kelompok', $arrdatac);
			$last   = $this->db->where($pkey)->get('kelompok')->row();
			
		}
		
		$total  = $this->db->get('kelompok')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
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
		$pkey = array('KODEKEL'=>$data->KODEKEL);
		
		$this->db->where($pkey)->delete('kelompok');
		
		$total  = $this->db->get('kelompok')->num_rows();
		$last = $this->db->get('kelompok')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
}
?>