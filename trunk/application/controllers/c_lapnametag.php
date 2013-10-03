<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_lapnametag extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$print_view=$this->load->view("p_lapnametag.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/lapnametag.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}
	
}