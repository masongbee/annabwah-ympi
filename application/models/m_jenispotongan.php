<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jenispotongan
 * 
 * Table	: jenispotongan
 *  
 * @author masongbee
 *
 */
class M_jenispotongan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('KODEPOTONGAN', 'ASC')->get('jenispotongan')->result();
		$total  = $this->db->get('jenispotongan')->num_rows();
		
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
		
		$pkey = array('KODEPOTONGAN'=>$data->KODEPOTONGAN);
		
		if($this->db->get_where('jenispotongan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAPOTONGAN'=>$data->NAMAPOTONGAN,'POSCETAK'=>$data->POSCETAK);
			 
			$this->db->where($pkey)->update('jenispotongan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('KODEPOTONGAN'=>$data->KODEPOTONGAN,'NAMAPOTONGAN'=>$data->NAMAPOTONGAN,'POSCETAK'=>$data->POSCETAK);
			 
			$this->db->insert('jenispotongan', $arrdatac);
			$last   = $this->db->where($pkey)->get('jenispotongan')->row();
			
		}
		
		$total  = $this->db->get('jenispotongan')->num_rows();
		
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
		$pkey = array('KODEPOTONGAN'=>$data->KODEPOTONGAN);
		
		$this->db->where($pkey)->delete('jenispotongan');
		
		$total  = $this->db->get('jenispotongan')->num_rows();
		$last = $this->db->get('jenispotongan')->result();
		
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