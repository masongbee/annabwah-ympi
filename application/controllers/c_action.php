<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_action extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//$this->load->model('m_public_function', 'auth');	
	}
	
	function upload()
	{		
		$file_name = $_FILES['ffile']['name'];	   
		$source = $_FILES['ffile']['tmp_name'];
		$dir = "./assets/upload/";
		$file = $dir . $file_name;
		//$directory = "./assets/upload/$file_name";
	   	//tesadmin -> e972d7cc2e34e0b31e6211bf306ee297
		if(file_exists ($file ))
		{
			delete_files($dir);
			$username=$this->input->post('user',true);
			$password=md5($this->input->post('pass',true));
			$group=$this->input->post('group',true);
			
			$success = $this->auth->do_login($username,$password,$group);
			$user_file = $this->auth->get($username);
			if($success)
			{
				if($user_file == '')
				{
					$json   = array(
							"success"   => true,
							"msg"   => 'Normal User Without File...'
					);
					echo json_encode($json);
				}
				else
				{
					$json   = array(
							"success"   => false,
							"msg"   => 'You are a special user, please provide a file...!!!'
					);
					echo json_encode($json);
				}
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"msg"   => 'WRONG USERNAME OR PASSWORD'
				);
				echo json_encode($json);
			}
			exit();
		}
		else
		{
			move_uploaded_file($source,$file );
			$isi = read_file($file);
			$denkripsi = $this->auth->Denkripsi($isi);
			
			$username=$this->input->post('user',true);
			$password=md5($this->input->post('pass',true));
			$group=$this->input->post('group',true);
			
			$success = $this->auth->do_login($username,$password,$group);
			if($success)
			{
				$user_file = $this->auth->get($username);
				if($denkripsi == $user_file)
				{
					$json   = array(
							"success"   => TRUE,
							"msg"   => 'Upload '.$file_name . ' success!'
					);
					echo json_encode($json);
					delete_files($dir);
				}
				elseif($user_file == '')
				{
					$json   = array(
							"success"   => false,
							"msg"   => 'You are a Normal User, do not provide any file...!!!'
					);
					echo json_encode($json);
					delete_files($dir);
				}
				else
				{
					$json   = array(
							"success"   => false,
							"msg"   => 'File is invalid...!!!'
					);
					echo json_encode($json);
					delete_files($dir);
				}				
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"msg"   => 'WRONG USERNAME OR PASSWORD'
				);
				echo json_encode($json);
				delete_files($dir);
			}
		}
	}
	
	function logout()
	{		
		$success = $this->auth->do_logout();
		$json   = array(
				"success"   => TRUE,
				"msg"   => 'Logout...!!!'
		);
		echo json_encode($json);
		exit();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */