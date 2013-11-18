<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rinciancuti
 * 
 * Table	: rinciancuti
 *  
 * @author masongbee
 *
 */
class M_rinciancuti extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('rinciancuti')->result();
		$total  = $this->db->get('rinciancuti')->num_rows();
		
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
		
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('rinciancuti', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'LAMA'=>$data->LAMA,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'SISACUTI'=>$data->SISACUTI,'STATUSCUTI'=>$data->STATUSCUTI);
			 
			$this->db->where($pkey)->update('rinciancuti', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$data->NOURUT,'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'LAMA'=>$data->LAMA,'TGLMULAI'=>(strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL),'TGLSAMPAI'=>(strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL),'SISACUTI'=>$data->SISACUTI,'STATUSCUTI'=>$data->STATUSCUTI);
			 
			$this->db->insert('rinciancuti', $arrdatac);
			$last   = $this->db->where($pkey)->get('rinciancuti')->row();
			
		}
		
		$total  = $this->db->get('rinciancuti')->num_rows();
		
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
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('rinciancuti');
		
		$total  = $this->db->get('rinciancuti')->num_rows();
		$last = $this->db->get('rinciancuti')->result();
		
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