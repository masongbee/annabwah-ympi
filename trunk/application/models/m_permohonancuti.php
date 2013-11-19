<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_permohonancuti
 * 
 * Table	: permohonancuti
 *  
 * @author masongbee
 *
 */
class M_permohonancuti extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOCUTI', 'ASC')->get('permohonancuti')->result();
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
		
		if($this->db->get_where('permohonancuti', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('KODEUNIT'=>$data->KODEUNIT,'CUTIMASAL'=>$data->CUTIMASAL,'NIKATASAN1'=>$data->NIKATASAN1,'NIKATASAN2'=>$data->NIKATASAN2,'NIKATASAN3'=>$data->NIKATASAN3,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN2)) : NULL),'TGLATASAN3'=>(strlen(trim($data->TGLATASAN3)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN3)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLHR)) : NULL),'STATUSCUTI'=>$data->STATUSCUTI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('permohonancuti', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NOCUTI'=>$data->NOCUTI,'KODEUNIT'=>$data->KODEUNIT,'CUTIMASAL'=>$data->CUTIMASAL,'NIKATASAN1'=>$data->NIKATASAN1,'NIKATASAN2'=>$data->NIKATASAN2,'NIKATASAN3'=>$data->NIKATASAN3,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN2)) : NULL),'TGLATASAN3'=>(strlen(trim($data->TGLATASAN3)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN3)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLHR)) : NULL),'STATUSCUTI'=>$data->STATUSCUTI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('permohonancuti', $arrdatac);
			$last   = $this->db->where($pkey)->get('permohonancuti')->row();
			
		}
		
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('permohonancuti');
		
		$total  = $this->db->get('permohonancuti')->num_rows();
		$last = $this->db->get('permohonancuti')->result();
		
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