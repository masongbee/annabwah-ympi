<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_jenistraining
 * 
 * Table	: jenistraining
 *  
 * @author masongbee
 *
 */
class M_jenistraining extends CI_Model{

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
		$sql = "SELECT KODETRAINING,NAMATRAINING
			FROM jenistraining";
		
		$result 	= $this->db->query($sql)->result();
		$result_total = $this->db->select('COUNT(*) AS total')->get('jenistraining')->row();
		$total  = $result_total->total;
		
		// $data   = array();
		// foreach($query as $result){
		// 	$data[] = $result;
		// }
		
		$json   = array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total,
				'data'      => $result
		);
		
		return $json;
	}
}
?>