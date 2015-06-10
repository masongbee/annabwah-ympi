<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_td_training
 * 
 * Table	: td_training
 *  
 * @author masongbee
 *
 */
class M_td_training extends CI_Model{

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
		$query  = $this->db->select("TDTRAINING_ID
			,TDTRAINING_KODE
			,TDTRAINING_NAMA
			,TDTRAINING_KETERANGAN
			,TDTRAINING_TDKELOMPOK_ID
			,TDTRAINING_TDKELOMPOK_NAMA
			,TDTRAINING_TUJUAN
			,IF((TDTRAINING_JENIS = 'ex'), 'External', IF((TDTRAINING_JENIS = 'id'), 'In-House Intra Dept', 'In-House Cross Dept')) AS TDTRAINING_JENIS
			,IF((TDTRAINING_SIFAT = 'wajib'), 'Wajib', 'Rekomendasi') AS TDTRAINING_SIFAT")
			->limit($limit, $start)->order_by('TDTRAINING_ID', 'ASC')->get('td_training')->result();
		$total  = $this->db->get('td_training')->num_rows();
		
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
		$this->firephp->log($data);
		$last   = NULL;
		
		$pkey = array('TDTRAINING_ID'=>$data->TDTRAINING_ID);
		$dataexists = $this->db->select('TDTRAINING_ID')->get_where('td_training', $pkey)->num_rows();
		
		if($dataexists > 0){
			/*
			 * Data Exist
			 */			 
			$arrdatau = array(
				'TDTRAINING_KODE'=>$data->TDTRAINING_KODE,
				'TDTRAINING_NAMA'=>$data->TDTRAINING_NAMA,
				'TDTRAINING_KETERANGAN'=>$data->TDTRAINING_KETERANGAN,
				'TDTRAINING_TDKELOMPOK_ID'=>$data->TDTRAINING_TDKELOMPOK_ID,
				'TDTRAINING_TDKELOMPOK_NAMA'=>$data->TDTRAINING_TDKELOMPOK_NAMA,
				'TDTRAINING_TUJUAN'=>$data->TDTRAINING_TUJUAN,
				'TDTRAINING_JENIS'=>$data->TDTRAINING_JENIS,
				'TDTRAINING_SIFAT'=>$data->TDTRAINING_SIFAT
			);
			 
			$this->db->where($pkey)->update('td_training', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array(
				'TDTRAINING_ID'=>$data->TDTRAINING_ID,
				'TDTRAINING_KODE'=>$data->TDTRAINING_KODE,
				'TDTRAINING_NAMA'=>$data->TDTRAINING_NAMA,
				'TDTRAINING_KETERANGAN'=>$data->TDTRAINING_KETERANGAN,
				'TDTRAINING_TDKELOMPOK_ID'=>$data->TDTRAINING_TDKELOMPOK_ID,
				'TDTRAINING_TDKELOMPOK_NAMA'=>$data->TDTRAINING_TDKELOMPOK_NAMA,
				'TDTRAINING_TUJUAN'=>$data->TDTRAINING_TUJUAN,
				'TDTRAINING_JENIS'=>$data->TDTRAINING_JENIS,
				'TDTRAINING_SIFAT'=>$data->TDTRAINING_SIFAT
			);
			 
			$this->db->insert('td_training', $arrdatac);
			$last   = $this->db->where($pkey)->get('td_training')->row();
			
		}
		
		$total  = $this->db->get('td_training')->num_rows();
		
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
		$pkey = array('TDTRAINING_ID'=>$data->TDTRAINING_ID);
		
		$this->db->where($pkey)->delete('td_training');
		
		$total  = $this->db->get('td_training')->num_rows();
		$last = $this->db->get('td_training')->result();
		
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