<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_mohoncuti
 * 
 * Table	: PERMOHONANCUTI
 *  
 * @author masongbee
 *
 */
class M_mohoncuti extends CI_Model{

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
	 
	function get_jenisabsen(){
		
		$query  = $this->db->get('jenisabsen')->result();
		$total  = $this->db->get('jenisabsen')->num_rows();
		
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
	
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('NOCUTI', 'ASC')->get('PERMOHONANCUTI')->result();
		$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		
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
		
		$pkey = array('NOCUTI'=>$data->NOCUTI);
		
		if($this->db->get_where('PERMOHONANCUTI', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('KODEUNIT'=>$data->KODEUNIT,'NIKATASAN1'=>$data->NIKATASAN1,'NIKATASAN2'=>$data->NIKATASAN2,'NIKATASAN3'=>$data->NIKATASAN3,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN2)) : NULL),'TGLATASAN3'=>(strlen(trim($data->TGLATASAN3)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN3)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('PERMOHONANCUTI', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NOCUTI'=>$data->NOCUTI,'KODEUNIT'=>$data->KODEUNIT,'NIKATASAN1'=>$data->NIKATASAN1,'NIKATASAN2'=>$data->NIKATASAN2,'NIKATASAN3'=>$data->NIKATASAN3,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN2)) : NULL),'TGLATASAN3'=>(strlen(trim($data->TGLATASAN3)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN3)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('PERMOHONANCUTI', $arrdatac);
			$last   = $this->db->where($pkey)->get('PERMOHONANCUTI')->row();
			
		}
		
		$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		
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
		$pkey = array('NOCUTI'=>$data->NOCUTI);
		
		$this->db->where($pkey)->delete('PERMOHONANCUTI');
		
		$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		$last = $this->db->get('PERMOHONANCUTI')->result();
		
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