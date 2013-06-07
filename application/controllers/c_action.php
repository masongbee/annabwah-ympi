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
	   	
		if( file_exists ($file ))
		{
			delete_files($dir);
			$username=$this->input->post('user',true);
			$password=md5($this->input->post('pass',true));
			$group=$this->input->post('group',true);
			
			$success = $this->auth->do_login($username,$password,$group);
			if($success)
			{
				$user_file = $this->auth->get($username);
				if($user_file == 0)
				{
					$json   = array(
							"success"   => true,
							"message"   => 'Normal User Without File...'
					);
					echo json_encode($json);
				}
				else
				{
					$json   = array(
							"success"   => false,
							"message"   => 'You are a special user, please provide a file...!!!'
					);
					echo json_encode($json);
				}
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"message"   => 'WRONG USERNAME OR PASSWORD'
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
							"message"   => 'Upload '.$file_name . ' success!'
					);
					echo json_encode($json);
					delete_files($dir);
				}
				elseif($user_file == 0)
				{
					$json   = array(
							"success"   => false,
							"message"   => 'You are a Normal User, do not provide any file...!!!'
					);
					echo json_encode($json);
					delete_files($dir);
				}
				else
				{
					$json   = array(
							"success"   => false,
							"message"   => 'File is invalid...!!!'
					);
					echo json_encode($json);
					delete_files($dir);
				}				
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"message"   => 'WRONG USERNAME OR PASSWORD'
				);
				echo json_encode($json);
				delete_files($dir);
			}
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */