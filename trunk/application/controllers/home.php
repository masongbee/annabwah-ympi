<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {	
	private $username;
	private $gid;
	
	public function __construct(){
		parent::__construct();
		if($this->auth->is_logged_in() == false){
			redirect(base_url(),'refresh');
		}
		$this->gid = $this->session->userdata('group_id');
		$this->username = $this->session->userdata('user_name');
	}
	public function index()
	{
		$this->load->view('welcome');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */