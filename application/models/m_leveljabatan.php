<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_leveljabatan
 * 
 * Table	: leveljabatan
 *  
 * @author masongbee
 *
 */
class M_leveljabatan extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('KODEJAB ASC, GRADE ASC')->get('leveljabatan')->result();
		$query = "SELECT KODEJAB, grade.GRADE, NAMALEVEL, grade.KETERANGAN
			FROM leveljabatan
			JOIN grade ON(grade.GRADE = leveljabatan.GRADE)
			ORDER BY KODEJAB, GRADE
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('leveljabatan')->num_rows();
		
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
		
		$pkey = array('KODEJAB'=>$data->KODEJAB, 'GRADE'=>$data->GRADE);
		
		if($this->db->get_where('leveljabatan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMALEVEL'=>$data->NAMALEVEL);
			 
			$this->db->where($pkey)->update('leveljabatan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('KODEJAB'=>$data->KODEJAB,'GRADE'=>$data->GRADE,'NAMALEVEL'=>$data->NAMALEVEL);
			 
			$this->db->insert('leveljabatan', $arrdatac);
			$last   = $this->db->where($pkey)->get('leveljabatan')->row();
			
		}
		
		$total  = $this->db->get('leveljabatan')->num_rows();
		
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
		$pkey = array('KODEJAB'=>$data->KODEJAB, 'GRADE'=>$data->GRADE);
		
		$this->db->where($pkey)->delete('leveljabatan');
		
		$total  = $this->db->get('leveljabatan')->num_rows();
		$last = $this->db->get('leveljabatan')->result();
		
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