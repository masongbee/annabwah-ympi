<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class	: C_usergroups
 * 
 * @author masongbee
 *
 */
class C_usergroups extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_usergroups', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 10);
		$user_id  =   ($this->input->post('userid', TRUE) ? $this->input->post('userid', TRUE) : 0);
	
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroups->getAll($start, $page, $limit, $user_id);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.UserGroups]
	 	 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroups->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.UserGroups]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroups->delete($data);
		echo json_encode($result);
	}

	function hakuser_save(){
		/*
		 * Collect Data ==> diambil dari [model.Permissions]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		$user_id  =   ($this->input->post('userid', TRUE) ? $this->input->post('userid', TRUE) : 0);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_usergroups->hakuser_save($data, $user_id);
		echo json_encode($result);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */