<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_splembur
 * 
 * Table	: splembur
 *  
 * @author masongbee
 *
 */
class M_splembur extends CI_Model{

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
	function getAll($nik,$start, $page, $limit){

		$query  = $this->db->limit($limit, $start)->where('NIKUSUL', $nik)->or_where('NIKSETUJU', $nik)->order_by('NOLEMBUR', 'ASC')->get('splembur')->result();
		$total  = $this->db->where('NIKUSUL', $nik)->or_where('NIKSETUJU', $nik)->get('splembur')->num_rows();
		
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
		
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR);
		
		if($this->db->get_where('splembur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('KODEUNIT'=>$data->KODEUNIT,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'KEPERLUAN'=>$data->KEPERLUAN,'NIKUSUL'=>$data->NIKUSUL,'NIKSETUJU'=>$data->NIKSETUJU,'NIKDIKETAHUI'=>$data->NIKDIKETAHUI,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'TGLSETUJU'=>(strlen(trim($data->TGLSETUJU)) > 0 ? date('Y-m-d', strtotime($data->TGLSETUJU)) : NULL),'TGLPERSONALIA'=>(strlen(trim($data->TGLPERSONALIA)) > 0 ? date('Y-m-d', strtotime($data->TGLPERSONALIA)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('splembur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NOLEMBUR'=>$data->NOLEMBUR,'KODEUNIT'=>$data->KODEUNIT,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'KEPERLUAN'=>$data->KEPERLUAN,'NIKUSUL'=>$data->NIKUSUL,'NIKSETUJU'=>$data->NIKSETUJU,'NIKDIKETAHUI'=>$data->NIKDIKETAHUI,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'TGLSETUJU'=>(strlen(trim($data->TGLSETUJU)) > 0 ? date('Y-m-d', strtotime($data->TGLSETUJU)) : NULL),'TGLPERSONALIA'=>(strlen(trim($data->TGLPERSONALIA)) > 0 ? date('Y-m-d', strtotime($data->TGLPERSONALIA)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('splembur', $arrdatac);
			$last   = $this->db->where($pkey)->get('splembur')->row();
			
		}
		
		$total  = $this->db->get('splembur')->num_rows();
		
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
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR);
		
		$this->db->where($pkey)->delete('splembur');
		
		$total  = $this->db->get('splembur')->num_rows();
		$last = $this->db->get('splembur')->result();
		
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