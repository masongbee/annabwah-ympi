<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->model('m_login', '', TRUE);
	}
	
	public function index()
	{
		$this->load->view('v_login');
	}
	
	function verify()
	{
		$user = trim($_POST['user']);
		$pass = trim($_POST['pass']);
		
		if( isset($_POST['user']) && isset($_POST['pass']))
		{
			$u	= preg_replace('[^a-zA-Z0-9_]','',$_POST['user']);
			//echo $u;
			$pw	= md5($_POST['pass']);
			$_SESSION["user_post"]=$u;
			$_SESSION["msg"]="";
			$auth = $this->m_login->verifyUser($u, $pw);
	
			if($auth){
				//echo "{success:true}";
				echo '1';
			} else{
				//echo "{success:false,msg:'Username or Password incorrect'}";
				echo '0';
			}
		} else {
			//echo "{success:false,msg:'Please fill the Requirement Field!'}";
			echo '0';
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */