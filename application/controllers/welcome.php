<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		//$this->load->view('welcome_message');
		
		
		$path = './application'; //ini adalah path application CI
		$nfile = 'jenisabsen'; // ini adalah namafile bisa berdasar nama tabel
		$tbl = 'jenisabsen';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data('jenisabsen');
		$data['pathjs'] = 'MASTER';		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->CController($path,$nfile,$tbl,$data);  // ini adalah eksekusi Utama Generator
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */