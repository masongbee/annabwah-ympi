<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tkehadiran
 * 
 * Table	: tkehadiran
 *  
 * @author masongbee
 *
 */
class M_tkehadiran extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('tkehadiran')->result();
		$total  = $this->db->get('tkehadiran')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NIK
				,RPTHADIR
				,KETERANGAN
				,USERNAME
			FROM tkehadiran
			ORDER BY BULAN
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('tkehadiran')->num_rows();
		
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
		
		if($this->db->get_where('tkehadiran', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$arrdatau = array(
					'RPTHADIR'=>(trim($data->RPTHADIR) == '' ? 0 : $data->RPTHADIR),
					'KETERANGAN'=>$data->KETERANGAN,
					'USERNAME'=>$data->USERNAME
				);
				
				$this->db->where($pkey)->update('tkehadiran', $arrdatau);
				$last   = $data;
				
				$total  = $this->db->get('tkehadiran')->num_rows();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil disimpan',
								"total"     => $total,
								"data"      => $last
				);
			}else{
				$last   = $data;
				
				$total  = $this->db->get('tkehadiran')->num_rows();
				
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
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'RPTHADIR'=>(trim($data->RPTHADIR) == '' ? 0 : $data->RPTHADIR),
				'KETERANGAN'=>$data->KETERANGAN,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tkehadiran', $arrdatac);
			$last   = $this->db->where($pkey)->get('tkehadiran')->row();
			
			$total  = $this->db->get('tkehadiran')->num_rows();
			
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
		
		$this->db->where($pkey)->delete('tkehadiran');
		
		$total  = $this->db->get('tkehadiran')->num_rows();
		$last = $this->db->get('tkehadiran')->result();
		
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