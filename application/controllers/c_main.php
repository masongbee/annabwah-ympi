<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_main extends CI_Controller {

	public function index()
	{
		$this->load->view('v_home');		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */