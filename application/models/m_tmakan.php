<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tmakan
 * 
 * Table	: tmakan
 *  
 * @author masongbee
 *
 */
class M_tmakan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tmakan')->result();
		$total  = $this->db->get('tmakan')->num_rows();
		
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
		
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('tmakan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'FMAKAN'=>$data->FMAKAN,
				'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('tmakan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('tmakan')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'FMAKAN'=>$data->FMAKAN,
				'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tmakan', $arrdatac);
			$last   = $this->db->where($pkey)->get('tmakan')->row();
			
		}
		
		$total  = $this->db->get('tmakan')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('tmakan');
		
		$total  = $this->db->get('tmakan')->num_rows();
		$last = $this->db->get('tmakan')->result();
		
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