<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jabatan
 * 
 * Table	: jabatan
 *  
 * @author masongbee
 *
 */
class M_jabatan extends CI_Model{

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
	function getAll($kodeunit, $start, $page, $limit){
		/*$query  = $this->db->where('KODEUNIT', $kodeunit)
				->limit($limit, $start)
				->get('vu_jabatan')
				->result();*/
		$sql = "SELECT KODEUNIT, KODEJAB, NAMAJAB, HITUNGLEMBUR, KOMPENCUTI, KODEAKUN
			FROM vu_jabatan";
		//if($kodeunit){
			$sql .=preg_match("/WHERE/i",$sql)? " AND ":" WHERE ";
			$sql .= " (KODEUNIT = '".$kodeunit."')";
		//}
		$sql .= " LIMIT ".$start.",".$limit;
		$query 	= $this->db->query($sql)->result();
		$query_total = $this->db->select('COUNT(*) AS total')->where('KODEUNIT', $kodeunit)->get('vu_jabatan')->row();
		$total  = $query_total->total;
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json   = array(
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
		
		$pkey = array('KODEUNIT'=>$data->KODEUNIT,'KODEJAB'=>$data->KODEJAB);
		
		if($this->db->get_where('jabatan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			  
			$arrdatau = array(
				'NAMAJAB'=>$data->NAMAJAB,
				'HITUNGLEMBUR'=>($data->HITUNGLEMBUR ? 'Y' : 'T'),
				'KOMPENCUTI'=>($data->KOMPENCUTI ? 'Y' : 'T'),
				'KODEAKUN'=>$data->KODEAKUN
			);
			 
			$this->db->where($pkey)->update('jabatan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array(
				'KODEUNIT'=>$data->KODEUNIT,
				'KODEJAB'=>$data->KODEJAB,
				'NAMAJAB'=>$data->NAMAJAB,
				'HITUNGLEMBUR'=>($data->HITUNGLEMBUR ? 'Y' : 'T'),
				'KOMPENCUTI'=>($data->KOMPENCUTI ? 'Y' : 'T'),
				'KODEAKUN'=>$data->KODEAKUN
			);
			
			$this->db->insert('jabatan', $arrdatac);
			$last   = $this->db->where($pkey)->get('jabatan')->row();
			
		}
		
		$total  = $this->db->get('jabatan')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
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
		$pkey = array('KODEUNIT'=>$data->KODEUNIT,'KODEJAB'=>$data->KODEJAB);
		
		$this->db->where($pkey)->delete('jabatan');
		
		$total  = $this->db->get('jabatan')->num_rows();
		$last = $this->db->get('jabatan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);				
		return $json;
	}
}
?>