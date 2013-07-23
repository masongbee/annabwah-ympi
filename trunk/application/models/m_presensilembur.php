<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_presensilembur
 * 
 * Table	: presensilembur
 *  
 * @author masongbee
 *
 */
class M_presensilembur extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensilembur')->result();
		$total  = $this->db->get('presensilembur')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		if($this->db->get_where('presensilembur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
			 
			$this->db->where($pkey)->update('presensilembur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NIK'=>$data->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT,'JENISLEMBUR'=>$data->JENISLEMBUR);
			 
			$this->db->insert('presensilembur', $arrdatac);
			$last   = $this->db->where($pkey)->get('presensilembur')->row();
			
		}
		
		$total  = $this->db->get('presensilembur')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$this->db->where($pkey)->delete('presensilembur');
		
		$total  = $this->db->get('presensilembur')->num_rows();
		$last = $this->db->get('presensilembur')->result();
		
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