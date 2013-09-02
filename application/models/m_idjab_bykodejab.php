<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jabatan
 * 
 * Table	: jabatan
 *  
 * @author masongbee
 *
 */
class M_idjab_bykodejab extends CI_Model{

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
	function getAll($kodeunit, $kodejab, $start, $page, $limit){
		$sql = "SELECT IDJAB
			FROM jabatan
			WHERE KODEUNIT = '".$kodeunit."' AND KODEJAB = '".$kodejab."'
			LIMIT ".$start.",".$limit;
		$query 	= $this->db->query($sql)->result();
		$query_total = $this->db->select('COUNT(*) AS total')->where(array('KODEUNIT'=>$kodeunit, 'KODEJAB'=>$kodejab))->get('jabatan')->row();
		$total  = $query_total->total;
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json   = array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total,
				'data'      => $data
		);
		
		return $json;
	}
}
?>