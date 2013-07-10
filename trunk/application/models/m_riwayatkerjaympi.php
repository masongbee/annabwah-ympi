<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_riwayatkerjaympi
 * 
 * Table	: riwayatkerjaympi
 *  
 * @author masongbee
 *
 */
class M_riwayatkerjaympi extends CI_Model{

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
	function getAll($nik, $start, $page, $limit){
		$query  = $this->db->where('NIK', $nik)->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('riwayatkerjaympi')->result();
		$total  = $this->db->get('riwayatkerjaympi')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('riwayatkerjaympi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAUNIT'=>$data->NAMAUNIT,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL));
			 
			$this->db->where($pkey)->update('riwayatkerjaympi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'NAMAUNIT'=>$data->NAMAUNIT,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL));
			 
			$this->db->insert('riwayatkerjaympi', $arrdatac);
			$last   = $this->db->where($pkey)->get('riwayatkerjaympi')->row();
			
		}
		
		$total  = $this->db->get('riwayatkerjaympi')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('riwayatkerjaympi');
		
		$total  = $this->db->get('riwayatkerjaympi')->num_rows();
		$last = $this->db->get('riwayatkerjaympi')->result();
		
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