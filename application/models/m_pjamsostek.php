<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_pjamsostek
 * 
 * Table	: pjamsostek
 *  
 * @author masongbee
 *
 */
class M_pjamsostek extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('pjamsostek')->result();
		$query = "SELECT VALIDFROM
				,VALIDTO
				,NOURUT
				,STR_TO_DATE(CONCAT(BULANMULAI,'01'),'%Y%m%d') AS BULANMULAI
				,STR_TO_DATE(CONCAT(BULANSAMPAI,'01'),'%Y%m%d') AS BULANSAMPAI
				,NIK
				,GRADE
				,KODEJAB
				,PERSENTASE
				,USERNAME
			FROM pjamsostek
			ORDER BY VALIDFROM, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = sizeof($result);
		
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
		
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('pjamsostek', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'PERSENTASE'=>(trim($data->PERSENTASE) == '' ? 0 : $data->PERSENTASE),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('pjamsostek', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('pjamsostek')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'PERSENTASE'=>(trim($data->PERSENTASE) == '' ? 0 : $data->PERSENTASE),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('pjamsostek', $arrdatac);
			$last   = $this->db->where($pkey)->get('pjamsostek')->row();
			
		}
		
		$total  = $this->db->get('pjamsostek')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('pjamsostek');
		
		$total  = $this->db->get('pjamsostek')->num_rows();
		$last = $this->db->get('pjamsostek')->result();
		
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