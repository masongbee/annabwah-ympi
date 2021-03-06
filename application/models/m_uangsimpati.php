<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_uangsimpati
 * 
 * Table	: uangsimpati
 *  
 * @author masongbee
 *
 */
class M_uangsimpati extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('JNSSIMPATI', 'ASC')->get('uangsimpati')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NIK
				,JNSSIMPATI
				,RPTSIMPATI
				,KETERANGAN
				,NIKATASAN1
				,NIKATASAN2
				,NIKATASAN3
				,NIKPERSONALIA
			FROM uangsimpati
			ORDER BY BULAN, NIK, JNSSIMPATI
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('uangsimpati')->num_rows();
		
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
		
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK,'JNSSIMPATI'=>$data->JNSSIMPATI);
		
		if($this->db->get_where('uangsimpati', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$arrdatau = array(
					'RPTSIMPATI'=>(trim($data->RPTSIMPATI) == '' ? 0 : $data->RPTSIMPATI),
					'KETERANGAN'=>$data->KETERANGAN,
					'NIKATASAN1'=>$data->NIKATASAN1,
					'NIKATASAN2'=>$data->NIKATASAN2,
					'NIKATASAN3'=>$data->NIKATASAN3,
					'NIKPERSONALIA'=>$data->NIKPERSONALIA
				);
				
				$this->db->where($pkey)->update('uangsimpati', $arrdatau);
				$last   = $data;
				
				$total  = $this->db->get('uangsimpati')->num_rows();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil disimpan',
								"total"     => $total,
								"data"      => $last
				);
			}else{
				$last   = $data;
				
				$total  = $this->db->get('uangsimpati')->num_rows();
				
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
				'JNSSIMPATI'=>$data->JNSSIMPATI,
				'RPTSIMPATI'=>(trim($data->RPTSIMPATI) == '' ? 0 : $data->RPTSIMPATI),
				'KETERANGAN'=>$data->KETERANGAN,
				'NIKATASAN1'=>$data->NIKATASAN1,
				'NIKATASAN2'=>$data->NIKATASAN2,
				'NIKATASAN3'=>$data->NIKATASAN3,
				'NIKPERSONALIA'=>$data->NIKPERSONALIA
			);
			
			$this->db->insert('uangsimpati', $arrdatac);
			$last   = $this->db->where($pkey)->get('uangsimpati')->row();
			
			$total  = $this->db->get('uangsimpati')->num_rows();
			
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
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK,'JNSSIMPATI'=>$data->JNSSIMPATI);
		
		$this->db->where($pkey)->delete('uangsimpati');
		
		$total  = $this->db->get('uangsimpati')->num_rows();
		$last = $this->db->get('uangsimpati')->result();
		
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