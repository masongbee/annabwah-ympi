<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_td_trainer
 * 
 * Table	: td_trainer
 *  
 * @author masongbee
 *
 */
class M_td_trainer extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TDTRAINER_ID', 'ASC')->get('td_trainer')->result();
		$total  = $this->db->get('td_trainer')->num_rows();
		
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
		
		$pkey = array('TDTRAINER_ID'=>$data->TDTRAINER_ID);
		
		if($this->db->get_where('td_trainer', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('TDTRAINER_KODE'=>$data->TDTRAINER_KODE,'TDTRAINER_NAMA'=>$data->TDTRAINER_NAMA,'TDTRAINER_KETERANGAN'=>$data->TDTRAINER_KETERANGAN,'TDTRAINER_CREATED_BY'=>$data->TDTRAINER_CREATED_BY,'TDTRAINER_CREATED_DATE'=>$data->TDTRAINER_CREATED_DATE,'TDTRAINER_UPDATED_BY'=>$data->TDTRAINER_UPDATED_BY,'TDTRAINER_UPDATED_DATE'=>$data->TDTRAINER_UPDATED_DATE,'TDTRAINER_REVISED'=>$data->TDTRAINER_REVISED);
			 
			$this->db->where($pkey)->update('td_trainer', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('TDTRAINER_ID'=>$data->TDTRAINER_ID,'TDTRAINER_KODE'=>$data->TDTRAINER_KODE,'TDTRAINER_NAMA'=>$data->TDTRAINER_NAMA,'TDTRAINER_KETERANGAN'=>$data->TDTRAINER_KETERANGAN,'TDTRAINER_CREATED_BY'=>$data->TDTRAINER_CREATED_BY,'TDTRAINER_CREATED_DATE'=>$data->TDTRAINER_CREATED_DATE,'TDTRAINER_UPDATED_BY'=>$data->TDTRAINER_UPDATED_BY,'TDTRAINER_UPDATED_DATE'=>$data->TDTRAINER_UPDATED_DATE,'TDTRAINER_REVISED'=>$data->TDTRAINER_REVISED);
			 
			$this->db->insert('td_trainer', $arrdatac);
			$last   = $this->db->where($pkey)->get('td_trainer')->row();
			
		}
		
		$total  = $this->db->get('td_trainer')->num_rows();
		
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
		$pkey = array('TDTRAINER_ID'=>$data->TDTRAINER_ID);
		
		$this->db->where($pkey)->delete('td_trainer');
		
		$total  = $this->db->get('td_trainer')->num_rows();
		$last = $this->db->get('td_trainer')->result();
		
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