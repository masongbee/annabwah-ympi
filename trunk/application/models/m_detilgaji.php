<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_detilgaji
 * 
 * Table	: detilgaji
 *  
 * @author masongbee
 *
 */
class M_detilgaji extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOREVISI', 'ASC')->get('detilgaji')->result();
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		if($this->db->get_where('detilgaji', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->where($pkey)->update('detilgaji', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI,'RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->insert('detilgaji', $arrdatac);
			$last   = $this->db->where($pkey)->get('detilgaji')->row();
			
		}
		
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		$this->db->where($pkey)->delete('detilgaji');
		
		$total  = $this->db->get('detilgaji')->num_rows();
		$last = $this->db->get('detilgaji')->result();
		
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