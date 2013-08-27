<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_thr
 * 
 * Table	: thr
 *  
 * @author masongbee
 *
 */
class M_thr extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('thr')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NOURUT
				,MSKERJADARI
				,MSKERJASAMPAI
				,NIK
				,PEMBAGI
				,PENGALI
				,UPENGALI
				,RPTHR
				,USERNAME
			FROM thr
			ORDER BY BULAN, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('thr')->num_rows();
		
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
		
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('thr', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'MSKERJADARI'=>(trim($data->MSKERJADARI) == '' ? NULL : $data->MSKERJADARI),
				'MSKERJASAMPAI'=>(trim($data->MSKERJASAMPAI) == '' ? NULL : $data->MSKERJASAMPAI),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'PEMBAGI'=>(strlen(trim($data->PEMBAGI)) > 0 ? $data->PEMBAGI : NULL),
				'PENGALI'=>(strlen(trim($data->PENGALI)) > 0 ? $data->PENGALI : NULL),
				'UPENGALI'=>$data->UPENGALI,
				'RPTHR'=>$data->RPTHR,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('thr', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('BULAN', date('Ym', strtotime($data->BULAN)))->get('thr')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'NOURUT'=>$nourut,
				'MSKERJADARI'=>(trim($data->MSKERJADARI) == '' ? NULL : $data->MSKERJADARI),
				'MSKERJASAMPAI'=>(trim($data->MSKERJASAMPAI) == '' ? NULL : $data->MSKERJASAMPAI),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'PEMBAGI'=>(strlen(trim($data->PEMBAGI)) > 0 ? $data->PEMBAGI : NULL),
				'PENGALI'=>(strlen(trim($data->PENGALI)) > 0 ? $data->PENGALI : NULL),
				'UPENGALI'=>$data->UPENGALI,
				'RPTHR'=>$data->RPTHR,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('thr', $arrdatac);
			$last   = $this->db->where($pkey)->get('thr')->row();
			
		}
		
		$total  = $this->db->get('thr')->num_rows();
		
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
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('thr');
		
		$total  = $this->db->get('thr')->num_rows();
		$last = $this->db->get('thr')->result();
		
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