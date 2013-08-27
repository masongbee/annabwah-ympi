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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('bonus')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NOURUT
				,TGLMULAI
				,TGLSAMPAI
				,NIK
				,GRADE
				,KODEJAB
				,RPBONUS
				,FPENGALI
				,PENGALI
				,UPENGALI
				,PERSENTASE
				,USERNAME
			FROM bonus
			ORDER BY BULAN, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('bonus')->num_rows();
		
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
		
		if($this->db->get_where('bonus', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>$data->NIK,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'RPBONUS'=>$data->RPBONUS,
				'FPENGALI'=>$data->FPENGALI,
				'PENGALI'=>$data->PENGALI,
				'UPENGALI'=>$data->UPENGALI,
				'PERSENTASE'=>$data->PERSENTASE,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('bonus', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('BULAN', date('Ym', strtotime($data->BULAN)))->get('bonus')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'NOURUT'=>$nourut,
				'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),
				'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),
				'NIK'=>$data->NIK,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'RPBONUS'=>$data->RPBONUS,
				'FPENGALI'=>$data->FPENGALI,
				'PENGALI'=>$data->PENGALI,
				'UPENGALI'=>$data->UPENGALI,
				'PERSENTASE'=>$data->PERSENTASE,
				'USERNAME'=>$data->USERNAME
			);
			
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
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NOURUT'=>$data->NOURUT);
		
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