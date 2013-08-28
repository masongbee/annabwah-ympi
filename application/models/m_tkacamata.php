<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tkacamata
 * 
 * Table	: tkacamata
 *  
 * @author masongbee
 *
 */
class M_tkacamata extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('tkacamata')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NIK
				,TANGGAL
				,RPFRAME
				,RPLENSA
				,USERNAME
			FROM tkacamata
			ORDER BY BULAN, NIK
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('tkacamata')->num_rows();
		
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
		
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK);
		
		if($this->db->get_where('tkacamata', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$arrdatau = array(
					'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
					'RPFRAME'=>$data->RPFRAME,
					'RPLENSA'=>$data->RPLENSA,
					'USERNAME'=>$data->USERNAME
				);
				
				$this->db->where($pkey)->update('tkacamata', $arrdatau);
				$last   = $data;
				
				$total  = $this->db->get('tkacamata')->num_rows();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil disimpan',
								"total"     => $total,
								"data"      => $last
				);
			}else{
				$last   = $data;
				
				$total  = $this->db->get('tkacamata')->num_rows();
				
				$json   = array(
								"success"   => FALSE,
								"message"   => 'Data tidak dapat disimpan, karena data sudah ada.',
								"total"     => $total,
								"data"      => $last
				);
			}
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'NIK'=>$data->NIK,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'RPFRAME'=>(trim($data->RPFRAME) == '' ? 0 : $data->RPFRAME),
				'RPLENSA'=>(trim($data->RPLENSA) == '' ? 0 : $data->RPLENSA),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tkacamata', $arrdatac);
			$last   = $this->db->where($pkey)->get('tkacamata')->row();
			
			$total  = $this->db->get('tkacamata')->num_rows();
			
			$json   = array(
							"success"   => TRUE,
							"message"   => 'Data berhasil disimpan',
							"total"     => $total,
							"data"      => $last
			);
			
		}
		
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
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('tkacamata');
		
		$total  = $this->db->get('tkacamata')->num_rows();
		$last = $this->db->get('tkacamata')->result();
		
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