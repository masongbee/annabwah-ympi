<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		//$this->load->view('welcome_message');
		/*
		
		$path = './application'; //ini adalah path application CI
		$nfile = 'mohoncuti'; // ini adalah namafile bisa berdasar nama tabel
		$tbl = 'PERMOHONANCUTI';   // ini adalah nama tabel
		$data['fields'] = $this->db->field_data('PERMOHONANCUTI');
		$data['pathjs'] = 'TRANSAKSI';		//ini adalah nama path View misal : Master,Proses,Aksess,dll
		//$this->egen->SingleGrid($path,$nfile,$tbl,$data);
		//$this->egen->SingleGridSF($path,$nfile,$tbl,$data);
		
		$key = array();
		foreach($data['fields'] as $val)
		{
			if($val->primary_key == "1")
			{
				$key[$val->name] = $val->name;
			}
			echo $val->name . "<br />";
		}
		var_dump($key);*/
	}
	
	function test()
	{
		/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
		sj.JAMDARI,sj.JAMSAMPAI
		FROM shift s
		RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
		WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
		$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
		sj.JAMDARI,sj.JAMSAMPAI,
		TIME((DATE_SUB(TIMESTAMP('2013-07-01',sj.JAMDARI),INTERVAL 4 HOUR))) AS RANGE_AWAL,
		TIME((DATE_ADD(TIMESTAMP('2013-07-01',sj.JAMDARI),INTERVAL 4 HOUR))) AS RANGE_AKHIR
		FROM shift s
		RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
		WHERE (s.VALIDFROM <= DATE('2013-07-01') AND s.VALIDTO >= DATE('2013-07-01')) AND (TIME('15:00:00') >= TIME((DATE_SUB(TIMESTAMP('2013-07-01',sj.JAMDARI),INTERVAL 4 HOUR))) AND TIME('15:00:00') <= TIME((DATE_ADD(TIMESTAMP('2013-07-01',sj.JAMDARI),INTERVAL 4 HOUR)))) AND sj.JENISHARI=IF(DAYNAME('2013-07-03')='Friday','J','N')";
		$hasil = $this->db->query($sqlshift)->result();
		
		$this->firephp->info($hasil[0]->SHIFTKE);
		
		$tgl = date('Y-m-d');
		$this->firephp->info($tgl);
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