<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_keluarga
 * 
 * Table	: keluarga
 *  
 * @author masongbee
 *
 */
class M_keluarga extends CI_Model{

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
	function getAll($nik, $start, $page, $limit){
		$query  = $this->db->where('NIK', $nik)->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('keluarga')->result();
		$total  = $this->db->get('keluarga')->num_rows();
		
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
		
		$pkey = array('NOURUT'=>$data->NOURUT,'STATUSKEL'=>$data->STATUSKEL,'NIK'=>$data->NIK);
		
		if($this->db->get_where('keluarga', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAKEL'=>$data->NAMAKEL,'JENISKEL'=>$data->JENISKEL,'ALAMAT'=>$data->ALAMAT,'TMPLAHIR'=>$data->TMPLAHIR,'TGLLAHIR'=>(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL),'PENDIDIKAN'=>$data->PENDIDIKAN,'PEKERJAAN'=>$data->PEKERJAAN,'TANGGUNGSPKK'=>$data->TANGGUNGSPKK,'TGLMENINGGAL'=>(strlen(trim($data->TGLMENINGGAL)) > 0 ? date('Y-m-d', strtotime($data->TGLMENINGGAL)) : NULL));
			 
			$this->db->where($pkey)->update('keluarga', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NOURUT'=>$data->NOURUT,'STATUSKEL'=>$data->STATUSKEL,'NIK'=>$data->NIK,'NAMAKEL'=>$data->NAMAKEL,'JENISKEL'=>$data->JENISKEL,'ALAMAT'=>$data->ALAMAT,'TMPLAHIR'=>$data->TMPLAHIR,'TGLLAHIR'=>(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL),'PENDIDIKAN'=>$data->PENDIDIKAN,'PEKERJAAN'=>$data->PEKERJAAN,'TANGGUNGSPKK'=>$data->TANGGUNGSPKK,'TGLMENINGGAL'=>(strlen(trim($data->TGLMENINGGAL)) > 0 ? date('Y-m-d', strtotime($data->TGLMENINGGAL)) : NULL));
			 
			$this->db->insert('keluarga', $arrdatac);
			$last   = $this->db->where($pkey)->get('keluarga')->row();
			
		}
		
		$total  = $this->db->get('keluarga')->num_rows();
		
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
		$pkey = array('NOURUT'=>$data->NOURUT,'STATUSKEL'=>$data->STATUSKEL,'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('keluarga');
		
		$total  = $this->db->get('keluarga')->num_rows();
		$last = $this->db->get('keluarga')->result();
		
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