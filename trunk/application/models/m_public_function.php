<?php

class M_public_function extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function getJabatan($start, $page, $limit){
		$query = "SELECT KODEJAB, NAMAJAB
			FROM jabatan
			LIMIT ".$start.",".$limit;
		
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('jabatan')->num_rows();
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
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