<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_jenistraining extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_jenistraining', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_jenistraining->getAll($start, $page, $limit);
		echo json_encode($result);
	}
}