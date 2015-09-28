<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_lowongan
 * 
 * Table	: lowongan
 *  
 * @author masongbee
 *
 */
class M_lowongan extends CI_Model{

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
		$query = "SELECT GELLOW
				,TANGGAL
				,KETERANGAN
			FROM lowongan
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('lowongan')->num_rows();
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
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
		
		$pkey = array('GELLOW'=>$data->GELLOW);
		
		if($this->db->get_where('lowongan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'KETERANGAN'=>$data->KETERANGAN
			);
			
			$this->db->where($pkey)->update('lowongan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'GELLOW'=>$data->GELLOW,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'KETERANGAN'=>$data->KETERANGAN
			);
			
			$this->db->insert('lowongan', $arrdatac);
			$last   = $this->db->where($pkey)->get('lowongan')->row();
			
		}
		
		$total  = $this->db->get('lowongan')->num_rows();
		
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
		$pkey = array('GELLOW'=>$data->GELLOW);
		
		$this->db->where($pkey)->delete('lowongan');
		
		$total  = $this->db->get('lowongan')->num_rows();
		$last = $this->db->get('lowongan')->result();
		
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