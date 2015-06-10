<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_td_kelompok
 * 
 * Table	: td_kelompok
 *  
 * @author masongbee
 *
 */
class M_td_kelompok extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TDKELOMPOK_ID', 'ASC')->get('td_kelompok')->result();
		$total  = $this->db->get('td_kelompok')->num_rows();
		
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
		
		$pkey = array('TDKELOMPOK_ID'=>$data->TDKELOMPOK_ID);
		
		if($this->db->get_where('td_kelompok', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('TDKELOMPOK_KODE'=>$data->TDKELOMPOK_KODE,'TDKELOMPOK_NAMA'=>$data->TDKELOMPOK_NAMA,'TDKELOMPOK_KETERANGAN'=>$data->TDKELOMPOK_KETERANGAN,'TDKELOMPOK_CREATED_BY'=>$data->TDKELOMPOK_CREATED_BY,'TDKELOMPOK_CREATED_DATE'=>$data->TDKELOMPOK_CREATED_DATE,'TDKELOMPOK_UPDATED_BY'=>$data->TDKELOMPOK_UPDATED_BY,'TDKELOMPOK_UPDATED_DATE'=>$data->TDKELOMPOK_UPDATED_DATE,'TDKELOMPOK_REVISED'=>$data->TDKELOMPOK_REVISED);
			 
			$this->db->where($pkey)->update('td_kelompok', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('TDKELOMPOK_ID'=>$data->TDKELOMPOK_ID,'TDKELOMPOK_KODE'=>$data->TDKELOMPOK_KODE,'TDKELOMPOK_NAMA'=>$data->TDKELOMPOK_NAMA,'TDKELOMPOK_KETERANGAN'=>$data->TDKELOMPOK_KETERANGAN,'TDKELOMPOK_CREATED_BY'=>$data->TDKELOMPOK_CREATED_BY,'TDKELOMPOK_CREATED_DATE'=>$data->TDKELOMPOK_CREATED_DATE,'TDKELOMPOK_UPDATED_BY'=>$data->TDKELOMPOK_UPDATED_BY,'TDKELOMPOK_UPDATED_DATE'=>$data->TDKELOMPOK_UPDATED_DATE,'TDKELOMPOK_REVISED'=>$data->TDKELOMPOK_REVISED);
			 
			$this->db->insert('td_kelompok', $arrdatac);
			$last   = $this->db->where($pkey)->get('td_kelompok')->row();
			
		}
		
		$total  = $this->db->get('td_kelompok')->num_rows();
		
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
		$pkey = array('TDKELOMPOK_ID'=>$data->TDKELOMPOK_ID);
		
		$this->db->where($pkey)->delete('td_kelompok');
		
		$total  = $this->db->get('td_kelompok')->num_rows();
		$last = $this->db->get('td_kelompok')->result();
		
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