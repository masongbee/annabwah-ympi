<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jenistambahan
 * 
 * Table	: jenistambahan
 *  
 * @author masongbee
 *
 */
class M_jenistambahan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('KODEUPAH', 'ASC')->get('jenistambahan')->result();
		$total  = $this->db->get('jenistambahan')->num_rows();
		
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
		
		$pkey = array('KODEUPAH'=>$data->KODEUPAH);
		
		if($this->db->get_where('jenistambahan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAUPAH'=>$data->NAMAUPAH,'POSCETAK'=>$data->POSCETAK);
			 
			$this->db->where($pkey)->update('jenistambahan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('KODEUPAH'=>$data->KODEUPAH,'NAMAUPAH'=>$data->NAMAUPAH,'POSCETAK'=>$data->POSCETAK);
			 
			$this->db->insert('jenistambahan', $arrdatac);
			$last   = $this->db->where($pkey)->get('jenistambahan')->row();
			
		}
		
		$total  = $this->db->get('jenistambahan')->num_rows();
		
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
		$pkey = array('KODEUPAH'=>$data->KODEUPAH);
		
		$this->db->where($pkey)->delete('jenistambahan');
		
		$total  = $this->db->get('jenistambahan')->num_rows();
		$last = $this->db->get('jenistambahan')->result();
		
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