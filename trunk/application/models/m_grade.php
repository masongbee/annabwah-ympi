<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_grade
 * 
 * Table	: grade
 *  
 * @author masongbee
 *
 */
class M_grade extends CI_Model{

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
	function getAll($start, $page, $limit){$query  = $this->db->limit($limit, $start)->order_by('GRADE', 'ASC')->get('grade')->result();
		$total  = $this->db->get('grade')->num_rows();
		
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
		
		$pkey = array('GRADE'=>$data->GRADE);
		
		if($this->db->get_where('grade', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$this->db->where($pkey)->update('grade', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('grade', $data);
			$last   = $this->db->order_by('GRADE', 'ASC')->get('grade')->row();
			
		}
		
		$total  = $this->db->get('grade')->num_rows();
		
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
		$pkey = array('GRADE'=>$data->GRADE);
		
		$this->db->where($pkey)->delete('grade');
		
		$total  = $this->db->get('grade')->num_rows();
		$last = $this->db->get('grade')->result();
		
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