<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_mohonizin
 * 
 * Table	: PERMOHONANIJIN
 *  
 * @author masongbee
 *
 */
class M_mohonizin extends CI_Model{

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
	
	function get_jenisabsen(){
		
		$query  = $this->db->get('jenisabsen')->result();
		$total  = $this->db->get('jenisabsen')->num_rows();
		
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
	
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('NOIJIN', 'ASC')->get('PERMOHONANIJIN')->result();
		$total  = $this->db->get('PERMOHONANIJIN')->num_rows();
		
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
		
		$pkey = array('NOIJIN'=>$data->NOIJIN);
		
		if($this->db->get_where('PERMOHONANIJIN', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>$data->NIKATASAN1,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('PERMOHONANIJIN', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NOIJIN'=>$data->NOIJIN,'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>$data->NIKATASAN1,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('PERMOHONANIJIN', $arrdatac);
			$last   = $this->db->where($pkey)->get('PERMOHONANIJIN')->row();
			
		}
		
		$total  = $this->db->get('PERMOHONANIJIN')->num_rows();
		
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
		$pkey = array('NOIJIN'=>$data->NOIJIN);
		
		$this->db->where($pkey)->delete('PERMOHONANIJIN');
		
		$total  = $this->db->get('PERMOHONANIJIN')->num_rows();
		$last = $this->db->get('PERMOHONANIJIN')->result();
		
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