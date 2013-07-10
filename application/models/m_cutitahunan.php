<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_cutitahunan
 * 
 * Table	: cutitahunan
 *  
 * @author masongbee
 *
 */
class M_cutitahunan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('cutitahunan')->result();
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('cutitahunan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('JENISCUTI'=>$data->JENISCUTI,'JMLCUTI'=>$data->JMLCUTI,'SISACUTI'=>$data->SISACUTI,'DIKOMPENSASI'=>$data->DIKOMPENSASI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('cutitahunan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JENISCUTI'=>$data->JENISCUTI,'JMLCUTI'=>$data->JMLCUTI,'SISACUTI'=>$data->SISACUTI,'DIKOMPENSASI'=>$data->DIKOMPENSASI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('cutitahunan', $arrdatac);
			$last   = $this->db->where($pkey)->get('cutitahunan')->row();
			
		}
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'TAHUN'=>$data->TAHUN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('cutitahunan');
		
		$total  = $this->db->get('cutitahunan')->num_rows();
		$last = $this->db->get('cutitahunan')->result();
		
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