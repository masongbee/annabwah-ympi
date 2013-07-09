<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_kalenderlibur
 * 
 * Table	: kalenderlibur
 *  
 * @author masongbee
 *
 */
class M_kalenderlibur extends CI_Model{

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
	function getAll($tglmulai, $tglsampai, $start, $page, $limit){
		if($tglmulai != '' and $tglsampai != '')
		{
			$query  = $this->db->where(array('TANGGAL >='=>$tglmulai,'TANGGAL <='=>$tglsampai))->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('kalenderlibur')->result();
			//$total  = $this->db->get('kalenderlibur')->num_rows();
			
			$query_total = $this->db->select('COUNT(*) AS total')->where(array('TANGGAL >='=>$tglmulai,'TANGGAL <='=>$tglsampai))->get('kalenderlibur')->row();
			$total  = $query_total->total;
		}
		else
		{
			$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('kalenderlibur')->result();
			$total  = $this->db->get('kalenderlibur')->num_rows();
		}
		
		
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
		
		$pkey = array('TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('kalenderlibur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('JENISLIBUR'=>$data->JENISLIBUR,'AGAMA'=>$data->AGAMA,'KETERANGAN'=>$data->KETERANGAN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('kalenderlibur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JENISLIBUR'=>$data->JENISLIBUR,'AGAMA'=>$data->AGAMA,'KETERANGAN'=>$data->KETERANGAN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('kalenderlibur', $arrdatac);
			$last   = $this->db->where($pkey)->get('kalenderlibur')->row();
			
		}
		
		$total  = $this->db->get('kalenderlibur')->num_rows();
		
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
		$pkey = array('TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('kalenderlibur');
		
		$total  = $this->db->get('kalenderlibur')->num_rows();
		$last = $this->db->get('kalenderlibur')->result();
		
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