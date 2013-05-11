<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_upahpokok
 * 
 * Table	: upahpokok
 *  
 * @author masongbee
 *
 */
class M_upahpokok extends CI_Model{

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
	function getAll($start, $page, $limit){$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('upahpokok')->result();
		$total  = $this->db->get('upahpokok')->num_rows();
		
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
		
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('upahpokok', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$arrdatau = array(
				'GRADE'			=>$data->GRADE,
				'KODEJAB'		=>$data->KODEJAB,
				'NIK'			=>$data->NIK,
				'RPUPAHPOKOK'	=>$data->RPUPAHPOKOK,
				'USERNAME'		=>$data->USERNAME
			);
			$this->db->where($pkey)->update('upahpokok', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$arrdatac = array(
				'VALIDFROM'		=>date('Y-m-d', strtotime($data->VALIDFROM)),
				'NOURUT'		=>$data->NOURUT,
				'GRADE'			=>$data->GRADE,
				'KODEJAB'		=>$data->KODEJAB,
				'NIK'			=>$data->NIK,
				'RPUPAHPOKOK'	=>$data->RPUPAHPOKOK,
				'USERNAME'		=>$data->USERNAME
			);
			$this->db->insert('upahpokok', $arrdatac);
			$last   = $this->db->order_by('NOURUT', 'ASC')->get('upahpokok')->row();
			
		}
		
		$total  = $this->db->get('upahpokok')->num_rows();
		
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
		$pkey = array('VALIDFROM'=>$data->VALIDFROM,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('upahpokok');
		
		$total  = $this->db->get('upahpokok')->num_rows();
		$last = $this->db->get('upahpokok')->result();
		
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