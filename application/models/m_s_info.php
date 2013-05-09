<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_s_info
 * 
 * Table	: s_info
 *  
 * @author masongbee
 *
 */
class M_s_info extends CI_Model{

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
	function getAll($start, $page, $limit){$query  = $this->db->limit($limit, $start)->order_by('INFO_ID', 'ASC')->get('s_info')->result();
		$total  = $this->db->get('s_info')->num_rows();
		
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
		
		$pkey = array('INFO_ID'=>$data->INFO_ID);
		
		if($this->db->get_where('s_info', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$this->db->where($pkey)->update('s_info', $data);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$this->db->insert('s_info', $data);
			$last   = $this->db->order_by('INFO_ID', 'ASC')->get('s_info')->row();
			
		}
		
		$total  = $this->db->get('s_info')->num_rows();
		
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
		$pkey = array('INFO_ID'=>$data->INFO_ID);
		
		$this->db->where($pkey)->delete('s_info');
		
		$total  = $this->db->get('s_info')->num_rows();
		$last = $this->db->get('s_info')->result();
		
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