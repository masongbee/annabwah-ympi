<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jemputankar
 * 
 * Table	: jemputankar
 *  
 * @author masongbee
 *
 */
class M_jemputankar extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('jemputankar')->result();
		$total  = $this->db->get('jemputankar')->num_rows();
		
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
		
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('jemputankar', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('ZONA'=>$data->ZONA,'IKUTJEMPUTAN'=>$data->IKUTJEMPUTAN,'KETERANGAN'=>$data->KETERANGAN);
			 
			$this->db->where($pkey)->update('jemputankar', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'NIK'=>$data->NIK,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'ZONA'=>$data->ZONA,'IKUTJEMPUTAN'=>$data->IKUTJEMPUTAN,'KETERANGAN'=>$data->KETERANGAN);
			 
			$this->db->insert('jemputankar', $arrdatac);
			$last   = $this->db->where($pkey)->get('jemputankar')->row();
			
		}
		
		$total  = $this->db->get('jemputankar')->num_rows();
		
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
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('jemputankar');
		
		$total  = $this->db->get('jemputankar')->num_rows();
		$last = $this->db->get('jemputankar')->result();
		
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