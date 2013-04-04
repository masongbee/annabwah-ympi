<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_utama extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		//$this->load->model('m_login', '', TRUE);
	}
	
	public function index()
	{
		$this->load->view('v_utama');
	}
	
	function Send()
	{
		$pos = $this->input->post();
		
		foreach($pos as $val)
		{
			if($val == "Home")
				redirect(base_url('login'),'refresh');
			elseif($val == "Absensi")
				echo "<h1>Ini halaman Absensi</h1>";
			elseif($val == "Presensi")
				echo "<h1>Ini Halaman Presensi</h1>";
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */