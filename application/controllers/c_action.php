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
			//$group=$this->input->post('group',true);
			$group=$this->session->userdata('group_icon');
			
			$success = $this->auth->do_login($username,$password,$group);
			$user_file = $this->auth->get($username);
			if($success)
			{
				$gname = $this->session->userdata('group_name');
				if($group == $gname)
				{
					if($user_file == '')
					{
						$json   = array(
								"success"   => true,
								//"msg"   => 'Normal User Without File...',
								"msg"   => 'Login ke '. $group . ' Berhasil'
						);
						echo json_encode($json);
					}
					else
					{
						$json   = array(
								"success"   => false,
								//"msg"   => 'You are a special user, please provide a file...!!!'
								"msg"   => 'Masukkan VIP Key !!!'
						);
						echo json_encode($json);
					}
				}
				else
				{
					$json   = array(
						"success"   => false,
						"msg"   => 'Anda Tidak Memiliki Hak Akses'
					);
					echo json_encode($json);
				}
			}
			else
			{
				$json   = array(
						"success"   => false,
						"msg"   => 'Username atau Password Salah'
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
			//$group=$this->input->post('group',true);
			$group=$this->session->userdata('group_icon');
			
			$success = $this->auth->do_login($username,$password,$group);
			if($success)
			{
				$gname = $this->session->userdata('group_name');
				if($group == $gname)
				{
					$user_file = $this->auth->get($username);
					if($denkripsi == $user_file)
					{
						$json   = array(
								"success"   => true,
								//"msg"   => 'Upload '.$file_name . ' success!',
								"msg"   => 'Login ke '. $group . ' Berhasil'
						);
						echo json_encode($json);
						delete_files($dir);
					}
					elseif($user_file == '')
					{
						$json   = array(
								"success"   => false,
								//"msg"   => 'You are a Normal User, do not provide any file...!!!',
								"msg"   => 'Masukkan VIP Key !!!'
						);
						echo json_encode($json);
						delete_files($dir);
					}
					else
					{
						$json   = array(
								"success"   => false,
								//"msg"   => 'File is invalid...!!!',
								"msg"   => 'VIP Key Tidak Cocok'
						);
						echo json_encode($json);
						delete_files($dir);
					}
				}
				else
				{
					$json   = array(
						"success"   => false,
						//"msg"   => 'You Don\'t Have Permission To Access',
						"msg"   => 'Anda Tidak Memiliki Hak Akses'
					);
					echo json_encode($json);
					delete_files($dir);
				}
			}
			else
			{
				$json   = array(
						"success"   => false,
						"msg"   => 'Username atau Password Salah'
				);
				echo json_encode($json);
				delete_files($dir);
			}
		}
	}
	
	function logout()
	{
		$success = $this->auth->do_logout();
		/*$json   = array(
				"success"   => TRUE,
				"msg"   => 'Logout...!!!'
		);
		echo json_encode($json);
		exit();*/
		redirect(base_url().'home','refresh');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */