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
	function check($data){
		/*Cek (NIK dan RPFRAME) atau (NIK dan RPLENSA) <== sudah pernah diklaimkan?*/
		$query_cek_frame = "SELECT NIK
			FROM vu_countdate_frame
			WHERE NIK = '".$data->NIK."'
				AND (COUNTDATE >= 3 OR COUNTDATE IS NULL)";
		$query_cek_lensa = "SELECT NIK
			FROM vu_countdate_lensa
			WHERE NIK = '".$data->NIK."'
				AND (COUNTDATE >= 2 OR COUNTDATE IS NULL)";
		if((trim($data->RPFRAME) == '' ? 0 : $data->RPFRAME) > 0){
			if($this->db->query($query_cek_frame)->num_rows() > 0){
				//insert into db.tkacamata
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Data valid.',
					"result"     => 1
				);
				return $json;
			}else{
				//return msg = 'Karyawan yang dimaksud sudah pernah mengajukan tunjangan frame kacamata'
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Dalam 3 Tahun terakhir ini, Karyawan yang dimaksud sudah mendapat tunjangan "frame" kacamata.<br/>Apakah Anda tetap ingin melanjutkan?',
					"result"     => 0
				);
				return $json;
			}
		}else{
			if($this->db->query($query_cek_lensa)->num_rows() > 0){
				//insert into db.tkacamata
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Data valid.',
					"result"     => 1
				);
				return $json;
			}else{
				//return msg = 'Karyawan yang dimaksud sudah pernah mengajukan tunjangan frame kacamata'
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Dalam 2 Tahun terakhir ini, Karyawan yang dimaksud sudah mendapat tunjangan "lensa" kacamata.<br/>Apakah Anda tetap ingin melanjutkan?',
					"result"     => 0
				);
				return $json;
			}
		}
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
			$this->firephp->log('data exist');
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$this->firephp->log('data exist update');
				$arrdatau = array(
					'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
					'RPFRAME'=>(trim($data->RPFRAME) == '' ? NULL : $data->RPFRAME),
					'RPLENSA'=>(trim($data->RPLENSA) == '' ? NULL : $data->RPLENSA),
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
				$this->firephp->log('data exist tidak dapat disimpan');
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
			$this->firephp->log('data not exist');
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'NIK'=>$data->NIK,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'RPFRAME'=>(trim($data->RPFRAME) == '' ? NULL : $data->RPFRAME),
				'RPLENSA'=>(trim($data->RPLENSA) == '' ? NULL : $data->RPLENSA),
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