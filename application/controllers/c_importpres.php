<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_importpres extends CI_Controller {

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
	   	
		if(file_exists($file))
		{
			//delete_files($dir);
			move_uploaded_file($source,$file );
			$success = $this->elibdb->StoreSQL($file);
			if($success == 1)
			{
				$json   = array(
						"success"   => TRUE,
						"message"   => 'Upload '.$file_name . ' success!'
				);
				echo json_encode($json);
				delete_files($dir);				
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"message"   => 'Importing Data SQL Failed...'
				);
				echo json_encode($json);
				delete_files($dir);
			}
			exit();
		}
		else
		{
			move_uploaded_file($source,$file );
			$success = $this->elibdb->StoreSQL($file);
			if($success == 1)
			{
				$json   = array(
						"success"   => TRUE,
						"message"   => 'Upload '.$file_name . ' success!'
				);
				echo json_encode($json);
				delete_files($dir);				
			}
			else
			{
				$json   = array(
						"success"   => FALSE,
						"message"   => 'Importing Data SQL Failed'
				);
				echo json_encode($json);
				delete_files($dir);
			}
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */