<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_s_gpass extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_s_gpass', '', TRUE);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.s_gpass]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_s_gpass->save($data);
		echo json_encode($result);
	}
}