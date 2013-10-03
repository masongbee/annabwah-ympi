<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_nametag
 * 
 * Table	: nametag
 *  
 * @author masongbee
 *
 */
class M_nametag extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('KODEJAB', 'ASC')->get('nametag')->result();
		$total  = $this->db->get('nametag')->num_rows();
		
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
		
		$pkey = array('IDTAG'=>$data->IDTAG,'KODEJAB'=>$data->KODEJAB);
		
		if($this->db->get_where('nametag', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMAJAB'=>$data->NAMAJAB,'WARNATAGR'=>$data->WARNATAGR,'WARNATAGG'=>$data->WARNATAGG,'WARNATAGB'=>$data->WARNATAGB);
			 
			$this->db->where($pkey)->update('nametag', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('IDTAG'=>$data->IDTAG,'KODEJAB'=>$data->KODEJAB,'NAMAJAB'=>$data->NAMAJAB,'WARNATAGR'=>$data->WARNATAGR,'WARNATAGG'=>$data->WARNATAGG,'WARNATAGB'=>$data->WARNATAGB);
			 
			$this->db->insert('nametag', $arrdatac);
			$last   = $this->db->where($pkey)->get('nametag')->row();
			
		}
		
		$total  = $this->db->get('nametag')->num_rows();
		
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
		$pkey = array('IDTAG'=>$data->IDTAG,'KODEJAB'=>$data->KODEJAB);
		
		$this->db->where($pkey)->delete('nametag');
		
		$total  = $this->db->get('nametag')->num_rows();
		$last = $this->db->get('nametag')->result();
		
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