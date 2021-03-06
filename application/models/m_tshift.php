<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tshift
 * 
 * Table	: tshift
 *  
 * @author masongbee
 *
 */
class M_tshift extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tshift')->result();
		$total  = $this->db->get('tshift')->num_rows();
		
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
		
		if($this->db->get_where('tshift', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NIK'=>$data->NIK,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'SHIFTKE'=>$data->SHIFTKE,
				'RPTSHIFT'=>$data->RPTSHIFT,
				'FPENGALI'=>$data->FPENGALI,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('tshift', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('tshift')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'NIK'=>$data->NIK,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'SHIFTKE'=>$data->SHIFTKE,
				'RPTSHIFT'=>$data->RPTSHIFT,
				'FPENGALI'=>$data->FPENGALI,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tshift', $arrdatac);
			$last   = $this->db->where($pkey)->get('tshift')->row();
			
		}
		
		$total  = $this->db->get('tshift')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('tshift');
		
		$total  = $this->db->get('tshift')->num_rows();
		$last = $this->db->get('tshift')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: validtoall_update
	 * 
	 * Untuk mengubah seluruh data yang db.tshift.VALIDTO = null
	 * 
	 * @param array $data
	 * @return json
	 */
	function validtoall_update($data){
		$last   = NULL;
		
		$where = array('VALIDTO'=>NULL);
		
		if($this->db->get_where('tshift', $where)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL)
			);
			
			$this->db->where($where)->update('tshift', $arrdatau);
			
			$result = $this->db->get('tshift')->result();
			$total  = $this->db->get('tshift')->num_rows();
			
			$data   = array();
			foreach($result as $row){
				$data[] = $row;
			}
			
			$total  = $this->db->get('tshift')->num_rows();
			
			$json   = array(
							"success"   => TRUE,
							"message"   => 'Update All VALIDTO telah berhasil.',
							"total"     => $total,
							"data"      => $data
			);
			
			return $json;
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Return Info
			 */
			
			$last   = $this->db->get('tshift')->row();
			$total  = $this->db->get('tshift')->num_rows();
			
			$json   = array(
							"success"   => FALSE,
							"message"   => 'Tidak ada data yang diubah.',
							"total"     => $total,
							"data"      => $last
			);
			
			return $json;
			
		}
	}
}
?>