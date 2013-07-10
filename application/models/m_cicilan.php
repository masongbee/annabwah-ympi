<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_cicilan
 * 
 * Table	: cicilan
 *  
 * @author masongbee
 *
 */
class M_cicilan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOCICILAN', 'ASC')->get('cicilan')->result();
		$total  = $this->db->get('cicilan')->num_rows();
		
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
		
		$pkey = array('NOCICILAN'=>$data->NOCICILAN);
		
		if($this->db->get_where('cicilan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NIK'=>$data->NIK,'TGLAMBIL'=>(strlen(trim($data->TGLAMBIL)) > 0 ? date('Y-m-d', strtotime($data->TGLAMBIL)) : NULL),'RPPOKOK'=>$data->RPPOKOK,'LAMACICILAN'=>$data->LAMACICILAN,'RPCICILAN'=>$data->RPCICILAN,'RPCICILANAKHIR'=>$data->RPCICILANAKHIR,'KEPERLUAN'=>$data->KEPERLUAN,'BULANMULAI'=>$data->BULANMULAI,'LUNAS'=>$data->LUNAS,'TGLLUNAS'=>(strlen(trim($data->TGLLUNAS)) > 0 ? date('Y-m-d', strtotime($data->TGLLUNAS)) : NULL));
			 
			$this->db->where($pkey)->update('cicilan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NOCICILAN'=>$data->NOCICILAN,'NIK'=>$data->NIK,'TGLAMBIL'=>(strlen(trim($data->TGLAMBIL)) > 0 ? date('Y-m-d', strtotime($data->TGLAMBIL)) : NULL),'RPPOKOK'=>$data->RPPOKOK,'LAMACICILAN'=>$data->LAMACICILAN,'RPCICILAN'=>$data->RPCICILAN,'RPCICILANAKHIR'=>$data->RPCICILANAKHIR,'KEPERLUAN'=>$data->KEPERLUAN,'BULANMULAI'=>$data->BULANMULAI,'LUNAS'=>$data->LUNAS,'TGLLUNAS'=>(strlen(trim($data->TGLLUNAS)) > 0 ? date('Y-m-d', strtotime($data->TGLLUNAS)) : NULL));
			 
			$this->db->insert('cicilan', $arrdatac);
			$last   = $this->db->where($pkey)->get('cicilan')->row();
			
		}
		
		$total  = $this->db->get('cicilan')->num_rows();
		
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
		$pkey = array('NOCICILAN'=>$data->NOCICILAN);
		
		$this->db->where($pkey)->delete('cicilan');
		
		$total  = $this->db->get('cicilan')->num_rows();
		$last = $this->db->get('cicilan')->result();
		
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