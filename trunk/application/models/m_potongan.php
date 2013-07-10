<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_potongan
 * 
 * Table	: potongan
 *  
 * @author masongbee
 *
 */
class M_potongan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('potongan')->result();
		$total  = $this->db->get('potongan')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('potongan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('GRADE'=>$data->GRADE,'JUMLAH'=>$data->JUMLAH,'KETERANGAN'=>$data->KETERANGAN,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('potongan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT,'GRADE'=>$data->GRADE,'JUMLAH'=>$data->JUMLAH,'KETERANGAN'=>$data->KETERANGAN,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('potongan', $arrdatac);
			$last   = $this->db->where($pkey)->get('potongan')->row();
			
		}
		
		$total  = $this->db->get('potongan')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('potongan');
		
		$total  = $this->db->get('potongan')->num_rows();
		$last = $this->db->get('potongan')->result();
		
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