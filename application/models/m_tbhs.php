<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tbhs
 * 
 * Table	: tbhs
 *  
 * @author masongbee
 *
 */
class M_tbhs extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tbhs')->result();
		$total  = $this->db->get('tbhs')->num_rows();
		
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
		
		if($this->db->get_where('tbhs', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'RPTBHS'=>$data->RPTBHS,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('tbhs', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('tbhs')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'RPTBHS'=>$data->RPTBHS,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tbhs', $arrdatac);
			$last   = $this->db->where($pkey)->get('tbhs')->row();
			
		}
		
		$total  = $this->db->get('tbhs')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('tbhs');
		
		$total  = $this->db->get('tbhs')->num_rows();
		$last = $this->db->get('tbhs')->result();
		
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
	 * Untuk mengubah seluruh data yang db.tbhs.VALIDTO = null
	 * 
	 * @param array $data
	 * @return json
	 */
	function validtoall_update($data){
		$last   = NULL;
		
		$where = array('VALIDTO'=>NULL);
		
		if($this->db->get_where('tbhs', $where)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL)
			);
			
			$this->db->where($where)->update('tbhs', $arrdatau);
			
			$result = $this->db->get('tbhs')->result();
			$total  = $this->db->get('tbhs')->num_rows();
			
			$data   = array();
			foreach($result as $row){
				$data[] = $row;
			}
			
			$total  = $this->db->get('tbhs')->num_rows();
			
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
			
			$last   = $this->db->get('tbhs')->row();
			$total  = $this->db->get('tbhs')->num_rows();
			
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