<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tpekerjaan
 * 
 * Table	: tpekerjaan
 *  
 * @author masongbee
 *
 */
class M_tpekerjaan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('VALIDFROM DESC, NOURUT ASC')->get('tpekerjaan')->result();
		$total  = $this->db->get('tpekerjaan')->num_rows();
		
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
		
		$pkey = array(
			'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
			'NOURUT'=>$data->NOURUT
		);
		$non_pkey = array(
			'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
			'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
			'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
			'NIK'=>(strlen(trim($data->NIK)) > 0 ? $data->NIK : NULL),
			'GRADE'=>(strlen(trim($data->GRADE)) > 0 ? $data->GRADE : NULL),
			'KATPEKERJAAN'=>(strlen(trim($data->KATPEKERJAAN)) > 0 ? $data->KATPEKERJAAN : NULL),
			'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,
			'FPENGALI'=>(strlen(trim($data->FPENGALI)) > 0 ? $data->FPENGALI : NULL),
			'USERNAME'=>$data->USERNAME
		);
		$arrdatau = $non_pkey;
		$arrdatac = array_merge($pkey, $non_pkey);
		
		if($this->db->get_where('tpekerjaan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>(strlen(trim($data->NIK)) > 0 ? $data->NIK : NULL),
				'GRADE'=>(strlen(trim($data->GRADE)) > 0 ? $data->GRADE : NULL),
				'KATPEKERJAAN'=>(strlen(trim($data->KATPEKERJAAN)) > 0 ? $data->KATPEKERJAAN : NULL),
				'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,
				'FPENGALI'=>(strlen(trim($data->FPENGALI)) > 0 ? $data->FPENGALI : NULL),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('tpekerjaan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('tjabatan')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>(strlen(trim($data->NIK)) > 0 ? $data->NIK : NULL),
				'GRADE'=>(strlen(trim($data->GRADE)) > 0 ? $data->GRADE : NULL),
				'KATPEKERJAAN'=>(strlen(trim($data->KATPEKERJAAN)) > 0 ? $data->KATPEKERJAAN : NULL),
				'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,
				'FPENGALI'=>(strlen(trim($data->FPENGALI)) > 0 ? $data->FPENGALI : NULL),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tpekerjaan', $arrdatac);
			$last   = $this->db->where($pkey)->get('tpekerjaan')->row();
			
		}
		
		$total  = $this->db->get('tpekerjaan')->num_rows();
		
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
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('tpekerjaan');
		
		$total  = $this->db->get('tpekerjaan')->num_rows();
		$last = $this->db->get('tpekerjaan')->result();
		
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