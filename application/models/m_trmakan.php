<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_trmakan
 * 
 * Table	: trmakan
 *  
 * @author masongbee
 *
 */
class M_trmakan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TANGGAL DESC, NIK ASC')->get('trmakan')->result();
		$total  = $this->db->get('trmakan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('trmakan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$arrdatau = array(
					'FMAKAN'=>$data->FMAKAN,
					'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
					'RPPMAKAN'=>(trim($data->RPPMAKAN) == '' ? 0 : $data->RPPMAKAN),
					'KETERANGAN'=>$data->KETERANGAN,
					'USERNAME'=>$data->USERNAME
				);
				
				$this->db->where($pkey)->update('trmakan', $arrdatau);
				$last   = $data;
			}else{
				$last   = $data;
				
				$total  = $this->db->get('trmakan')->num_rows();
				
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
				'NIK'=>$data->NIK,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'FMAKAN'=>$data->FMAKAN,
				'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
				'RPPMAKAN'=>(trim($data->RPPMAKAN) == '' ? 0 : $data->RPPMAKAN),
				'KETERANGAN'=>$data->KETERANGAN,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('trmakan', $arrdatac);
			$last   = $this->db->where($pkey)->get('trmakan')->row();
			
			$total  = $this->db->get('trmakan')->num_rows();
			
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
		$pkey = array('NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('trmakan');
		
		$total  = $this->db->get('trmakan')->num_rows();
		$last = $this->db->get('trmakan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function get_tmakan($filter){
		$sql = "SELECT * 
			FROM tmakan
			WHERE FMAKAN = '".$filter->fmakan."'
				AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
				AND NIK = '".$filter->nik."'
			LIMIT 1";
		$query = $this->db->query($sql)->result();
		if(sizeof($query) == 0){
			$sql = "SELECT * 
				FROM tmakan
				WHERE FMAKAN = '".$filter->fmakan."'
					AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
					AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
					AND GRADE = '".$filter->grade."' AND KODEJAB = '".$filter->kodejab."'
				LIMIT 1";
			$query = $this->db->query($sql)->result();
			if(sizeof($query) == 0){
				$sql = "SELECT * 
					FROM tmakan
					WHERE FMAKAN = '".$filter->fmakan."'
						AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
						AND KODEJAB = '".$filter->kodejab."'
					LIMIT 1";
				$query = $this->db->query($sql)->result();
				if(sizeof($query) == 0){
					$sql = "SELECT * 
						FROM tmakan
						WHERE FMAKAN = '".$filter->fmakan."'
							AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
							AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
							AND GRADE = '".$filter->grade."'
						LIMIT 1";
					$query = $this->db->query($sql)->result();
				}
			}
		}
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'data'      => $data,
						'total'		=> sizeof($data)
		);
		
		return $json;
	}
}
?>