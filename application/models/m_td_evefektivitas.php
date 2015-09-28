<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_td_evefektivitas
 * 
 * Table	: td_evefektivitas
 *  
 * @author masongbee
 *
 */
class M_td_evefektivitas extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TDEVE_ID', 'ASC')->get('td_evefektivitas')->result();
		$total  = $this->db->get('td_evefektivitas')->num_rows();
		
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
		
		$pkey = array('TDEVE_ID'=>$data->TDEVE_ID);
		
		if($this->db->get_where('td_evefektivitas', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('TDEVE_TDPELATIHAN_ID'=>$data->TDEVE_TDPELATIHAN_ID,'TDEVE_NIK'=>$data->TDEVE_NIK,'TDEVE_SARANEVALUATOR'=>$data->TDEVE_SARANEVALUATOR,'TDEVE001'=>$data->TDEVE001,'TDEVE002'=>$data->TDEVE002,'TDEVE003'=>$data->TDEVE003,'TDEVE004'=>$data->TDEVE004,'TDEVE005'=>$data->TDEVE005,'TDEVE006'=>$data->TDEVE006,'TDEVE007'=>$data->TDEVE007,'TDEVE008'=>$data->TDEVE008,'TDEVE009'=>$data->TDEVE009);
			 
			$this->db->where($pkey)->update('td_evefektivitas', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('TDEVE_ID'=>$data->TDEVE_ID,'TDEVE_TDPELATIHAN_ID'=>$data->TDEVE_TDPELATIHAN_ID,'TDEVE_NIK'=>$data->TDEVE_NIK,'TDEVE_SARANEVALUATOR'=>$data->TDEVE_SARANEVALUATOR,'TDEVE001'=>$data->TDEVE001,'TDEVE002'=>$data->TDEVE002,'TDEVE003'=>$data->TDEVE003,'TDEVE004'=>$data->TDEVE004,'TDEVE005'=>$data->TDEVE005,'TDEVE006'=>$data->TDEVE006,'TDEVE007'=>$data->TDEVE007,'TDEVE008'=>$data->TDEVE008,'TDEVE009'=>$data->TDEVE009);
			 
			$this->db->insert('td_evefektivitas', $arrdatac);
			$last   = $this->db->where($pkey)->get('td_evefektivitas')->row();
			
		}
		
		$total  = $this->db->get('td_evefektivitas')->num_rows();
		
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
		$pkey = array('TDEVE_ID'=>$data->TDEVE_ID);
		
		$this->db->where($pkey)->delete('td_evefektivitas');
		
		$total  = $this->db->get('td_evefektivitas')->num_rows();
		$last = $this->db->get('td_evefektivitas')->result();
		
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