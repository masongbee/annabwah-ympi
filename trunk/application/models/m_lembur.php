<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_lembur
 * 
 * Table	: lembur
 *  
 * @author masongbee
 *
 */
class M_lembur extends CI_Model{

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
		$query = "SELECT VALIDFROM
				,VALIDTO
				,NOURUT
				,STR_TO_DATE(CONCAT(BULANMULAI,'01'),'%Y%m%d') AS BULANMULAI
				,STR_TO_DATE(CONCAT(BULANSAMPAI,'01'),'%Y%m%d') AS BULANSAMPAI
				,JENISLEMBUR
				,GRADE
				,KODEJAB
				,BATAS1
				,BATAS2
				,BATAS3
				,PENGALI1
				,PENGALI2
				,PENGALI3
				,UPENGALI
				,USERNAME
			FROM lembur
			ORDER BY VALIDFROM, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('lembur')->num_rows();
		
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
		
		if($this->db->get_where('lembur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'JENISLEMBUR'=>$data->JENISLEMBUR,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'BATAS1'=>$data->BATAS1,
				'BATAS2'=>$data->BATAS2,
				'BATAS3'=>$data->BATAS3,
				'PENGALI1'=>$data->PENGALI1,
				'PENGALI2'=>$data->PENGALI2,
				'PENGALI3'=>$data->PENGALI3,
				'UPENGALI'=>$data->UPENGALI,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('lembur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('lembur')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL),
				'NOURUT'=>$nourut,
				'BULANMULAI'=>date('Ym', strtotime($data->BULANMULAI)),
				'BULANSAMPAI'=>date('Ym', strtotime($data->BULANSAMPAI)),
				'JENISLEMBUR'=>$data->JENISLEMBUR,
				'GRADE'=>$data->GRADE,
				'KODEJAB'=>$data->KODEJAB,
				'BATAS1'=>$data->BATAS1,
				'BATAS2'=>$data->BATAS2,
				'BATAS3'=>$data->BATAS3,
				'PENGALI1'=>$data->PENGALI1,
				'PENGALI2'=>$data->PENGALI2,
				'PENGALI3'=>$data->PENGALI3,
				'UPENGALI'=>$data->UPENGALI,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('lembur', $arrdatac);
			$last   = $this->db->where($pkey)->get('lembur')->row();
			
		}
		
		$total  = $this->db->get('lembur')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('lembur');
		
		$total  = $this->db->get('lembur')->num_rows();
		$last = $this->db->get('lembur')->result();
		
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
	 * Untuk mengubah seluruh data yang db.lembur.VALIDTO = null
	 * 
	 * @param array $data
	 * @return json
	 */
	function validtoall_update($data){
		$last   = NULL;
		
		$where = array('VALIDTO'=>NULL);
		
		if($this->db->get_where('lembur', $where)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'VALIDTO'=>(strlen(trim($data->VALIDTO)) > 0 ? date('Y-m-d', strtotime($data->VALIDTO)) : NULL)
			);
			
			$this->db->where($where)->update('lembur', $arrdatau);
			
			$result = $this->db->get('lembur')->result();
			$total  = $this->db->get('lembur')->num_rows();
			
			$data   = array();
			foreach($result as $row){
				$data[] = $row;
			}
			
			$total  = $this->db->get('lembur')->num_rows();
			
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
			
			$last   = $this->db->get('lembur')->row();
			$total  = $this->db->get('lembur')->num_rows();
			
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