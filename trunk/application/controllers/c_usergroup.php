<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class	: C_usergroup
 * 
 * @author masongbee
 *
 */
class C_usergroup extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_usergroup', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
	
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroup->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.Grade]
	 	 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroup->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.Grade]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroup->delete($data);
		echo json_encode($result);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */