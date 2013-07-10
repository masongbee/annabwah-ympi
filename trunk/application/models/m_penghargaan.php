<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_penghargaan
 * 
 * Table	: penghargaan
 *  
 * @author masongbee
 *
 */
class M_penghargaan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('penghargaan')->result();
		$total  = $this->db->get('penghargaan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('penghargaan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('PENGHARGAAN'=>$data->PENGHARGAAN,'BULAN'=>$data->BULAN,'TAHUN'=>$data->TAHUN);
			 
			$this->db->where($pkey)->update('penghargaan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT,'PENGHARGAAN'=>$data->PENGHARGAAN,'BULAN'=>$data->BULAN,'TAHUN'=>$data->TAHUN);
			 
			$this->db->insert('penghargaan', $arrdatac);
			$last   = $this->db->where($pkey)->get('penghargaan')->row();
			
		}
		
		$total  = $this->db->get('penghargaan')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('penghargaan');
		
		$total  = $this->db->get('penghargaan')->num_rows();
		$last = $this->db->get('penghargaan')->result();
		
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