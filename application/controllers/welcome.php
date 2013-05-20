<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		//$this->load->view('welcome_message');
		
		
		$path = './application'; //ini adalah path application CI
		$nfile = 'importpres'; // ini adalah namafile bisa berdasar nama tabel
		$tbl = 'presensi';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data('presensi');
		$data['pathjs'] = 'PROSES';		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGrid($path,$nfile,$tbl,$data);
		//$this->egen->SingleGridSF($path,$nfile,$table,$data);
		
		$key = array();
		foreach($data['fields'] as $val)
		{
			if($val->primary_key == "1")
			{
				$key[$val->name] = $val->name;
			}
			echo $val->name . "<br />";
		}
		var_dump($key);
	}
	
	function GenSG($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGrid($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
	
	function GenSGSF($pathjs="", $table="")
	{
		$path = './application'; //ini adalah path application CI
		$nfile = $table; // ini adalah namafile bisa berdasar nama tabel
		//$tbl = 'cutitahunan';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data($table);
		$data['pathjs'] = $pathjs;		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		$this->egen->SingleGridSF($path,$nfile,$table,$data);  // ini adalah eksekusi Utama Generator
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */