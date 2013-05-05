<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index($pathjs="", $table="")
	{
		//$this->load->view('welcome_message');
		
		
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->CController($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
	
	function gen($pathjs="", $table="")
	{
		//$this->load->view('welcome_message');
		
		
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->CController($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */