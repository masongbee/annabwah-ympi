<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class	: C_permissiongroup
 * 
 * @author masongbee
 *
 */
class C_permissiongroup extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('m_permissiongroup', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$group_id = ($this->input->post('GROUP_ID', TRUE) ? $this->input->post('GROUP_ID', TRUE) : 0);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_permissiongroup->getAll($group_id);
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
		$result = $this->m_permissiongroup->save($data);
		echo json_encode($result);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */