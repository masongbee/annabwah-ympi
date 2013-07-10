<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_insdisiplin
 * 
 * Table	: insdisiplin
 *  
 * @author masongbee
 *
 */
class M_insdisiplin extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('insdisiplin')->result();
		$total  = $this->db->get('insdisiplin')->num_rows();
		
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
		
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('insdisiplin', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'FABSEN'=>$data->FABSEN,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('insdisiplin', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),'NOURUT'=>$data->NOURUT,'GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'FABSEN'=>$data->FABSEN,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('insdisiplin', $arrdatac);
			$last   = $this->db->where($pkey)->get('insdisiplin')->row();
			
		}
		
		$total  = $this->db->get('insdisiplin')->num_rows();
		
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
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('insdisiplin');
		
		$total  = $this->db->get('insdisiplin')->num_rows();
		$last = $this->db->get('insdisiplin')->result();
		
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