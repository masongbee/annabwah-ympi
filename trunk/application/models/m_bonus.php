<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_bonus
 * 
 * Table	: bonus
 *  
 * @author masongbee
 *
 */
class M_bonus extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('bonus')->result();
		$total  = $this->db->get('bonus')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('bonus', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('PERIODE'=>$data->PERIODE,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'PERSENTASE'=>$data->PERSENTASE,'GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'RPBONUS'=>$data->RPBONUS,'FPENGALI'=>$data->FPENGALI,'PENGALI'=>$data->PENGALI,'UPENGALI'=>$data->UPENGALI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('bonus', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT,'PERIODE'=>$data->PERIODE,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'PERSENTASE'=>$data->PERSENTASE,'GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'RPBONUS'=>$data->RPBONUS,'FPENGALI'=>$data->FPENGALI,'PENGALI'=>$data->PENGALI,'UPENGALI'=>$data->UPENGALI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('bonus', $arrdatac);
			$last   = $this->db->where($pkey)->get('bonus')->row();
			
		}
		
		$total  = $this->db->get('bonus')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('bonus');
		
		$total  = $this->db->get('bonus')->num_rows();
		$last = $this->db->get('bonus')->result();
		
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