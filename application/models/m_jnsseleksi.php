<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jnsseleksi
 * 
 * Table	: jenisseleksi
 *  
 * @author masongbee
 *
 */
class M_jnsseleksi extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('KODESELEKSI', 'ASC')->get('jenisseleksi')->result();
		$total  = $this->db->get('jenisseleksi')->num_rows();
		
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
		
		$pkey = array('KODESELEKSI'=>$data->KODESELEKSI);
		
		if($this->db->get_where('jenisseleksi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'NAMASELEKSI'=>(strlen(trim($data->NAMASELEKSI)) > 0 ? $data->NAMASELEKSI : null)
			);
			
			$this->db->where($pkey)->update('jenisseleksi', $arrdatau);
			
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'KODESELEKSI'=>$data->KODESELEKSI,
				'NAMASELEKSI'=>(strlen(trim($data->NAMASELEKSI)) > 0 ? $data->NAMASELEKSI : null)
			);
			
			$this->db->insert('jenisseleksi', $arrdatac);
			
			$last   = $this->db->where($pkey)->get('jenisseleksi')->row();
			
		}
		
		$total  = $this->db->get('jenisseleksi')->num_rows();
		
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
		$pkey = array('KODESELEKSI'=>$data->KODESELEKSI);
		
		$this->db->where($pkey)->delete('jenisseleksi');
		
		$total  = $this->db->get('jenisseleksi')->num_rows();
		$last = $this->db->get('jenisseleksi')->result();
		
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