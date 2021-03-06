<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_skill
 * 
 * Table	: skill
 *  
 * @author masongbee
 *
 */
class M_skill extends CI_Model{

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
		$query  = $this->db->where('NIK', $nik)->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('skill')->result();
		$total  = $this->db->get('skill')->num_rows();
		
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
		
		if($this->db->get_where('skill', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMASKILL'=>$data->NAMASKILL,'KETERANGAN'=>$data->KETERANGAN);
			 
			$this->db->where($pkey)->update('skill', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'NAMASKILL'=>$data->NAMASKILL,'KETERANGAN'=>$data->KETERANGAN);
			 
			$this->db->insert('skill', $arrdatac);
			$last   = $this->db->where($pkey)->get('skill')->row();
			
		}
		
		$total  = $this->db->get('skill')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('skill');
		
		$total  = $this->db->get('skill')->num_rows();
		$last = $this->db->get('skill')->result();
		
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