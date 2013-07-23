<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_shiftjamkerja
 * 
 * Table	: shiftjamkerja
 *  
 * @author masongbee
 *
 */
class M_shiftjamkerja extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('JENISHARI', 'ASC')->get('shiftjamkerja')->result();
		$total  = $this->db->get('shiftjamkerja')->num_rows();
		
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
		
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'JENISHARI'=>$data->JENISHARI);
		
		if($this->db->get_where('shiftjamkerja', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'JAMREHAT1M'=>$data->JAMREHAT1M,'JAMREHAT1S'=>$data->JAMREHAT1S,'JAMREHAT2M'=>$data->JAMREHAT2M,'JAMREHAT2S'=>$data->JAMREHAT2S,'JAMREHAT3M'=>$data->JAMREHAT3M,'JAMREHAT3S'=>$data->JAMREHAT3S,'JAMREHAT4M'=>$data->JAMREHAT4M,'JAMREHAT4S'=>$data->JAMREHAT4S);
			 
			$this->db->where($pkey)->update('shiftjamkerja', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'JENISHARI'=>$data->JENISHARI,'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'JAMREHAT1M'=>$data->JAMREHAT1M,'JAMREHAT1S'=>$data->JAMREHAT1S,'JAMREHAT2M'=>$data->JAMREHAT2M,'JAMREHAT2S'=>$data->JAMREHAT2S,'JAMREHAT3M'=>$data->JAMREHAT3M,'JAMREHAT3S'=>$data->JAMREHAT3S,'JAMREHAT4M'=>$data->JAMREHAT4M,'JAMREHAT4S'=>$data->JAMREHAT4S);
			 
			$this->db->insert('shiftjamkerja', $arrdatac);
			$last   = $this->db->where($pkey)->get('shiftjamkerja')->row();
			
		}
		
		$total  = $this->db->get('shiftjamkerja')->num_rows();
		
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
		$pkey = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'JENISHARI'=>$data->JENISHARI);
		
		$this->db->where($pkey)->delete('shiftjamkerja');
		
		$total  = $this->db->get('shiftjamkerja')->num_rows();
		$last = $this->db->get('shiftjamkerja')->result();
		
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