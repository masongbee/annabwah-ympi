<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($start, $page, $limit){
		//$query  = $this->db->limit($limit, $start)->order_by('BULAN', 'ASC')->get('periodegaji')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,TGLMULAI
				,TGLSAMPAI
				,POSTING
				,TGLPOSTING
				,USERNAME
			FROM periodegaji
			ORDER BY BULAN
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('periodegaji')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN);
		
		if($this->db->get_where('periodegaji', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'POSTING'=>$data->POSTING,
				'TGLPOSTING'=>(strlen(trim($data->TGLPOSTING)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLPOSTING)) : NULL),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('periodegaji', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'POSTING'=>$data->POSTING,
				'TGLPOSTING'=>(strlen(trim($data->TGLPOSTING)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLPOSTING)) : NULL),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('periodegaji', $arrdatac);
			$last   = $this->db->where($pkey)->get('periodegaji')->row();
			
		}
		
		$total  = $this->db->get('periodegaji')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN);
		
		$this->db->where($pkey)->delete('periodegaji');
		
		$total  = $this->db->get('periodegaji')->num_rows();
		$last = $this->db->get('periodegaji')->result();
		
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