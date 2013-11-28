<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_detilshift
 * 
 * Table	: detilshift
 *  
 * @author masongbee
 *
 */
class M_detilshift extends CI_Model{

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
	function getAll($namashift,$start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('SHIFTKE', 'ASC')->get_where('detilshift',array('NAMASHIFT'=>$namashift))->result();
		$total  = $this->db->get('detilshift')->num_rows();
		
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
		
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE);
		
		if($this->db->get_where('detilshift', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('KETERANGAN'=>$data->KETERANGAN,'POLASHIFT'=>$data->POLASHIFT);
			 
			$this->db->where($pkey)->update('detilshift', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'KETERANGAN'=>$data->KETERANGAN,'POLASHIFT'=>$data->POLASHIFT);
			 
			$this->db->insert('detilshift', $arrdatac);
			$last   = $this->db->where($pkey)->get('detilshift')->row();
			
		}
		
		$total  = $this->db->get('detilshift')->num_rows();
		
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
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE);
		
		$this->db->where($pkey)->delete('detilshift');
		
		$total  = $this->db->get('detilshift')->num_rows();
		$last = $this->db->get('detilshift')->result();
		
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