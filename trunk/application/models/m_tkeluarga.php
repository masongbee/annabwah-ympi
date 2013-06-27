<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tkeluarga
 * 
 * Table	: tkeluarga
 *  
 * @author masongbee
 *
 */
class M_tkeluarga extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tkeluarga')->result();
		$total  = $this->db->get('tkeluarga')->num_rows();
		
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
		
		$q_jmlvalidfrom = $this->db->select('COUNT(*) AS total')->where('VALIDFROM', date('Y-m-d', strtotime($data->VALIDFROM)))->get('tkeluarga')->row();
		$nourut = $q_jmlvalidfrom->total + 1;
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$nourut);
		
		if($this->db->get_where('tkeluarga', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'STATUSKEL2'=>$data->STATUSKEL2,'UMURTO'=>$data->UMURTO,'PELAJAR'=>$data->PELAJAR,'RPTKELUARGA'=>$data->RPTKELUARGA,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('tkeluarga', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('VALIDFROM'=>(strlen(trim($data->VALIDFROM)) > 0 ? date('Y-m-d', strtotime($data->VALIDFROM)) : NULL),'NOURUT'=>$nourut,'GRADE'=>$data->GRADE,'KODEJAB'=>$data->KODEJAB,'NIK'=>$data->NIK,'STATUSKEL2'=>$data->STATUSKEL2,'UMURTO'=>$data->UMURTO,'PELAJAR'=>$data->PELAJAR,'RPTKELUARGA'=>$data->RPTKELUARGA,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('tkeluarga', $arrdatac);
			$last   = $this->db->where($pkey)->get('tkeluarga')->row();
			
		}
		
		$total  = $this->db->get('tkeluarga')->num_rows();
		
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
		$pkey = array('VALIDFROM'=>date('Y-m-d', strtotime($data->VALIDFROM)),'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('tkeluarga');
		
		$total  = $this->db->get('tkeluarga')->num_rows();
		$last = $this->db->get('tkeluarga')->result();
		
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