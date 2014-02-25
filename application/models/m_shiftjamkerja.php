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
	function getAll($namashift,$shiftke,$start, $page, $limit){
		if($shiftke != null){
			$query  = $this->db->limit($limit, $start)->order_by('SHIFTKE', 'ASC')->get_where('shiftjamkerja',array('NAMASHIFT'=>$namashift,'SHIFTKE'=>$shiftke))->result();
		}
		else
			$query  = $this->db->limit($limit, $start)->order_by('SHIFTKE', 'ASC')->get_where('shiftjamkerja',array('NAMASHIFT'=>$namashift))->result();
		
		$total  = $this->db->get('shiftjamkerja')->num_rows();
		
		//$query  = $this->db->limit($limit, $start)->order_by('JENISHARI', 'ASC')->get('shiftjamkerja')->result();
		//$total  = $this->db->get('shiftjamkerja')->num_rows();
		
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
				
			 
			$arrdatau = array(
				'JAMDARI_AWAL'=>(isset($data->JAMDARI_AWAL)?$data->JAMDARI_AWAL:NULL),
				'JAMDARI'=>(isset($data->JAMDARI)?$data->JAMDARI:NULL),
				'JAMDARI_AKHIR'=>(isset($data->JAMDARI_AKHIR)?$data->JAMDARI_AKHIR:NULL),
				'JAMSAMPAI_AWAL'=>(isset($data->JAMSAMPAI_AWAL)?$data->JAMSAMPAI_AWAL:NULL),
				'JAMSAMPAI'=>(isset($data->JAMSAMPAI)?$data->JAMSAMPAI:NULL),
				'JAMSAMPAI_AKHIR'=>(isset($data->JAMSAMPAI_AKHIR)?$data->JAMSAMPAI_AKHIR:NULL),
				'JAMREHAT1M'=>$data->JAMREHAT1M,
				'JAMREHAT1S'=>$data->JAMREHAT1S,
				'JAMREHAT2M'=>$data->JAMREHAT2M,
				'JAMREHAT2S'=>$data->JAMREHAT2S,
				'JAMREHAT3M'=>$data->JAMREHAT3M,
				'JAMREHAT3S'=>$data->JAMREHAT3S,
				'JAMREHAT4M'=>$data->JAMREHAT4M,
				'JAMREHAT4S'=>$data->JAMREHAT4S
			);
			
			$this->db->where($pkey)->update('shiftjamkerja', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array(
				'NAMASHIFT'=>$data->NAMASHIFT,
				'SHIFTKE'=>$data->SHIFTKE,
				'JENISHARI'=>$data->JENISHARI,
				'JAMDARI_AWAL'=>(isset($data->JAMDARI_AWAL)?$data->JAMDARI_AWAL:NULL),
				'JAMDARI'=>(isset($data->JAMDARI)?$data->JAMDARI:NULL),
				'JAMDARI_AKHIR'=>(isset($data->JAMDARI_AKHIR)?$data->JAMDARI_AKHIR:NULL),
				'JAMSAMPAI_AWAL'=>(isset($data->JAMSAMPAI_AWAL)?$data->JAMSAMPAI_AWAL:NULL),
				'JAMSAMPAI'=>(isset($data->JAMSAMPAI)?$data->JAMSAMPAI:NULL),
				'JAMSAMPAI_AKHIR'=>(isset($data->JAMSAMPAI_AKHIR)?$data->JAMSAMPAI_AKHIR:NULL),
				'JAMREHAT1M'=>$data->JAMREHAT1M,
				'JAMREHAT1S'=>$data->JAMREHAT1S,
				'JAMREHAT2M'=>$data->JAMREHAT2M,
				'JAMREHAT2S'=>$data->JAMREHAT2S,
				'JAMREHAT3M'=>$data->JAMREHAT3M,
				'JAMREHAT3S'=>$data->JAMREHAT3S,
				'JAMREHAT4M'=>$data->JAMREHAT4M,
				'JAMREHAT4S'=>$data->JAMREHAT4S
			);
			
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