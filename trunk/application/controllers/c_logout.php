<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_logout extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//$this->load->model('m_public_function', 'auth');	
	}
	
	function logout()
	{		
		$success = $this->auth->do_logout();
		$json   = array(
				"success"   => TRUE,
				"message"   => 'Logout...!!!'
		);
		echo json_encode($json);
		exit();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */