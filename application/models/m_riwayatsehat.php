<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_riwayatsehat
 * 
 * Table	: riwayatsehat
 *  
 * @author masongbee
 *
 */
class M_riwayatsehat extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('JENISSAKIT', 'ASC')->get('riwayatsehat')->result();
		$total  = $this->db->get('riwayatsehat')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'JENISSAKIT'=>$data->JENISSAKIT);
		
		if($this->db->get_where('riwayatsehat', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RINCIAN'=>$data->RINCIAN,'LAMA'=>$data->LAMA,'TGLRAWAT'=>$data->TGLRAWAT,'AKIBAT'=>$data->AKIBAT);
			 
			$this->db->where($pkey)->update('riwayatsehat', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'JENISSAKIT'=>$data->JENISSAKIT,'RINCIAN'=>$data->RINCIAN,'LAMA'=>$data->LAMA,'TGLRAWAT'=>$data->TGLRAWAT,'AKIBAT'=>$data->AKIBAT);
			 
			$this->db->insert('riwayatsehat', $arrdatac);
			$last   = $this->db->where($pkey)->get('riwayatsehat')->row();
			
		}
		
		$total  = $this->db->get('riwayatsehat')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'JENISSAKIT'=>$data->JENISSAKIT);
		
		$this->db->where($pkey)->delete('riwayatsehat');
		
		$total  = $this->db->get('riwayatsehat')->num_rows();
		$last = $this->db->get('riwayatsehat')->result();
		
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