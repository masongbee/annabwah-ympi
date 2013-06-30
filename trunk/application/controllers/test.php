<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	
	private $jam1;
	private $jam2;
	private $TimeLimit;
	
	private $id1;
	private $id2;
	
	function __construct(){
		parent::__construct();
		
		//$this->DB1 = $this->load->database('default', TRUE);
		//$this->DB2 = $this->load->database('mybase', TRUE); 
	}
	
	function ImportPresensi(){
		$DB1 = $this->load->database('default', TRUE);
		$DB2 = $this->load->database('mybase', TRUE); 
		
		//$sql = "SELECT DISTINCT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log from absensi WHERE trans_pengenal = 00030453 ORDER BY trans_pengenal,trans_log";
		
		$cp = intval(read_file("./assets/checkpoint/cp.txt"));
		$limit = 100;
		$query = $DB2->limit($limit, $cp)->distinct()->order_by('trans_pengenal','trans_log')->get('absensi');
		$total  = $query->num_rows();
		
		$TimeWork = 12; // misal jam kerja dalam 1hari adlah 9 jam
		
		/*Prosedur Import Presensi Page 8
		A    = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		foreach($query->result_array() as $val)
		{
			if ($ketemuA)
			{				
				$this->id2 = $val['trans_pengenal'];
				$this->jam2 = new DateTime($val['trans_tgl']." ".$val['trans_jam']);			
				$interval = date_diff($this->TimeLimit,$this->jam2);
				//echo "Jam 2 A : ".$this->jam1."<br />";
				
				if(($val['trans_status'] == "A") && ($interval->h > $TimeWork))
				{					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'] ,
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => null,
						   'ASALDATA' => 'D',
						   'POSTING' => null,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}					
					
					$ketemuA = false;
					$ketemuB = false;
				}
				elseif (($val['trans_status'] == "B") && ($interval->h <= $TimeWork) && ($this->id2 == $this->id1))
				{
					$data = array(
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam']
					);
					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					$DB1->where($array);
					$DB1->update('presensi', $data);
					
					$ketemuB = true;
					$ketemuA = false;
				}
				elseif(($val['trans_status'] == "A") && ($interval->h <= $TimeWork))
				{
					$array = array('NIK' => $this->id1, 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'] ,
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => null,
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}
				}
				else
				{
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
					}
				}
				
			}
			
			if (!$ketemuA && $val['trans_status'] == "A")
			{				
				$ketemuA = true;				
				$array = array('NIK' => $this->id1, 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'] ,
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
				}	
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
							
				$waktu = new DateTime($this->jam1);
				$waktu->add(new DateInterval("PT".$TimeWork."H"));
				$this->TimeLimit = $waktu;
				$this->id1 = $val['trans_pengenal'];
				//echo "Jam 1 A : ".$this->jam1."<br />";
			}
			elseif (!$ketemuB && $val['trans_status'] == "B")
			{
				if($ketemuA == true)
					$ketemuA = false;
					
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);

				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'] ,
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
						'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
				}
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$waktu = new DateTime($this->jam1);
				$waktu->add(new DateInterval("PT".$TimeWork."H"));
				$this->TimeLimit = $waktu;
				$this->id1 = $val['trans_pengenal'];
				//echo "Jam 1 B : ".$this->jam1."<br />";
			}
		}
		
		if (write_file("./assets/checkpoint/cp.txt", $cp + $total))
		{
			//echo "Checkpoint telah dibuat....<br /><br />";
			$query  = $DB1->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
			$total = $DB1->get('presensi')->num_rows();
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			$json	= array(
					'success'   => TRUE,
					'message'   => "Loaded data",
					'total'     => $total,
					'data'      => $data
			);
			
			return $json;
		}
	}
	 
	function FilterPresensi($start, $page, $limit){
		$this->db->where('TJKELUAR IS NULL', NULL);
		$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
		$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
		$total  = $this->db->get('presensi')->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'total'     => $total,
						'data'      => $data
		);
		
		return $json;
	}
	
	function Totalan($bulangaji){
		$array = array('parameter' => 'jam_kerja');
		$TimeWork = $this->db->select('value')->get_where('init',$array)->row_array();
		$bln = $bulangaji . "01";
		// Checking data
		//$rs = $this->db->query("SELECT BULAN from hitungpresensi WHERE BULAN = (SELECT BULAN from periodegaji)");
		//var_dump($rs);
		
		// 1. Proses Inisialisasi Insert Record
		$sql = "insert into HITUNGPRESENSI 
		(NIK, BULAN, TANGGAL, JENISABSEN,HARIKERJA,JAMKERJA, USERNAME) 
		select NIK, 
		$bulangaji as BULAN, 
		NOW() as TANGGAL, 
		'AL' as JENISABSEN,
		SUM(IF(TIMESTAMPDIFF(HOUR,TJMASUK,TJKELUAR)>=".$TimeWork["value"].",1,0)) as HARIKERJA,
		SUM(TIMESTAMPDIFF(MINUTE,TJMASUK,TJKELUAR))as JAMKERJA, 
		USERNAME as USERNAME 
		from PRESENSI 
		where DATE_FORMAT(TJMASUK,'%Y%m')=DATE_FORMAT(DATE_SUB('$bln',INTERVAL 1 MONTH),'%Y%m') 
		GROUP BY NIK";
		
		$query = $this->db->query($sql);
		
		// 2. Update perhitungan Presensi
		//$query = $this->db->query("SELECT NIK,SUM(IF(TIMESTAMPDIFF(HOUR,TJMASUK,TJKELUAR)>=8,1,0)) as harikerja,SUM(TIMESTAMPDIFF(MINUTE,TJmasuk,tjkeluar))as jamkerja from presensi WHERE DATE_FORMAT(tjmasuk,'%Y%m')='201208' GROUP BY NIK");
		
		$total  = $this->db->get('hitungpresensi')->num_rows();
		$last   = $this->db->select('NIK, BULAN,TANGGAL,JENISABSEN,HARIKERJA,JAMKERJA')->order_by('NIK', 'ASC')->get('hitungpresensi')->row();
		$json	= array(
						'success'   => TRUE,
						'message'   => "Data berhasil disimpan",
						'total'     => $total,
						'data'      => $last
		);
		
		return $json;
	}
	
	function InitRecord($bulangaji){
		$bln = $bulangaji . "01";
		$this->db->select('TGLMULAI,TGLSAMPAI');
		$sql = $this->db->get_where('periodegaji',array('BULAN' => $bulangaji))->result_array();
		
		$TMASUK = new DateTime($sql[0]['TGLMULAI']);
		$TSAMPAI = new DateTime($sql[0]['TGLSAMPAI']);
		$TM = intval($TMASUK->format('d'));
		$TS = intval($TSAMPAI->format('d'));
		
		for($i=$TM;$i<=$TS;$i++)
		{
			$sql = "insert into HITUNGPRESENSI 
					(NIK, BULAN, TANGGAL, JENISABSEN, USERNAME) 
					select NIK, $bulangaji as BULAN, '".$TMASUK->format('Y-m')."-".$i."' as TANGGAL, 'AL' as JENISABSEN,
					USERNAME as USERNAME 
					from PRESENSI 
					where DATE_FORMAT(TJMASUK,'%Y%m')=DATE_FORMAT(DATE_SUB('$bln',INTERVAL 1 MONTH),'%Y%m')
					GROUP BY NIK";
			$query = $this->db->query($sql);
			//echo $TMASUK->format('Y-m')."-".$i."<br />";
		}
		echo "Init Record Sukses";
	}
	
	function ListLembur(){
		$sql = "SELECT DATE(t1.TJMASUK) as TANGGAL, t1.NIK, t1.TJMASUK, t1.TJKELUAR,t4.TJMASUK as TJLEMBUR, sum(TIMESTAMPDIFF(MINUTE,t1.TJMASUK,t4.TJMASUK)) as JAMKERJA, SUM(TIMESTAMPDIFF(MINUTE,t4.TJMASUK,t1.TJKELUAR)) as JAMLEMBUR, SUM(TIMESTAMPDIFF(MINUTE,t1.TJMASUK,t1.TJKELUAR)) as TOTAL
		FROM presensi t1
		JOIN (
		SELECT t3.NIK, t2.NOLEMBUR, t2.TANGGAL, t3.TJMASUK
		FROM splembur t2
		RIGHT JOIN rencanalembur t3
		ON t2.NOLEMBUR = t3.NOLEMBUR ) as t4
		ON t1.nik=t4.NIK AND date(t1.TJMASUK)=DATE(t4.TJMASUK)
		GROUP BY t1.NIK";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			//var_dump($query->result_array());
			return $query->result_array();
		}
		else
			return 0;
		//echo "<br /><br />";
	}
	
	function JamKerja($tgl,$nik){
		$sql = "SELECT NIK, SUM(TIMESTAMPDIFF(MINUTE,tjmasuk,tjkeluar)) as JAMKERJA
				FROM presensi
				WHERE NIK=$nik AND DATE(TJMASUK)=DATE('$tgl')
				GROUP BY DATE(TJMASUK);";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$lembur = $this->ListLembur();
			$data=array();
			foreach($lembur as $v)
			{
				if($v['TANGGAL']==$tgl && $v['NIK']==$nik)
				{
					$data['JAMKERJA'] = intval($v['JAMKERJA']);
					$data['JAMLEMBUR'] = intval($v['JAMLEMBUR']);
					//return $data;
					break;
				}
				else
				{					
					$rs = $query->result_array();
					$data['JAMKERJA'] = intval($rs[0]['JAMKERJA']);
					$data['JAMLEMBUR'] = 0;
					//return $data;
				}
				//echo $v['TANGGAL']." ".$v['NIK']." ".$v['JAMKERJA']." ".$v['JAMLEMBUR']."<br />";
			}
			return $data;
		}
		else
		{
			$data['JAMKERJA'] = 0;
			$data['JAMLEMBUR'] = 0;
			return $data;
		}
	}
	
	function UpdatePresensi($tgl,$nik){
		$array = array('parameter' => 'jam_kerja');
		$TimeWork = $this->db->select('value')->get_where('init',$array)->row_array();
		
		$jk = $this->JamKerja($tgl,$nik);
		
		$sql = "UPDATE hitungpresensi
				SET JENISABSEN=(IF(".$jk['JAMKERJA']." >= ".(intval($TimeWork["value"])*60).",'HD','AL')), JAMKERJA='".$jk['JAMKERJA']."', 
				HARIKERJA=(IF(".$jk['JAMKERJA']." >= ".(intval($TimeWork["value"])*60).",1,0)), 
				JAMLEMBUR='".$jk['JAMLEMBUR']."'
				WHERE NIK=$nik AND TANGGAL='$tgl'";
		$query = $this->db->query($sql);
		//echo "Sukses";
	}
	
	function LoopUpdate($bulangaji){
		$bln = $bulangaji . "01";
		$this->db->select('TGLMULAI,TGLSAMPAI');
		$sql = $this->db->get_where('periodegaji',array('BULAN' => $bulangaji))->result_array();
		
		$TMASUK = new DateTime($sql[0]['TGLMULAI']);
		$TSAMPAI = new DateTime($sql[0]['TGLSAMPAI']);
		$TM = intval($TMASUK->format('d'));
		$TS = intval($TSAMPAI->format('d'));
		
		$sql = "SELECT NIK FROM presensi GROUP BY NIK";
		$query = $this->db->query($sql)->result_array();
		
		foreach($query as $lsnik)
		{
			for($i=$TM;$i<=$TS;$i++)
			{
				$tgl = new DateTime($TMASUK->format('Y-m')."-".$i);
				//echo $tgl->format('Y-m-d')." ".$lsnik['NIK']."<br />";
				//$jk = $this->JamKerja($tgl->format('Y-m-d'),$lsnik['NIK']);
				//var_dump($jk);
				//echo $tgl->format('Y-m-d')." ".$lsnik['NIK']."<br /><br />";
				$this->UpdatePresensi($tgl->format('Y-m-d'),$lsnik['NIK']);
			}
			//echo $lsnik['NIK']."<br />";
		}		
		
		echo "Loop Update Sukses";
	}
	
	function ListNIK(){
		$sql = "SELECT BULAN FROM hitungpresensi WHERE BULAN = '201209' GROUP BY BULAN";
		$query = $this->db->query($sql)->result_array();
		
		//echo sizeof($query);
		//foreach($query as $v)
		//{
			if(sizeof($query) > 0)
			{
				echo $query[0]['BULAN']."<br />";
			}
			else
			{
				echo "kosong";
			}
		//}
	}
	
	function JamKurangPerHari($norm,$tgl,$nik)
	{
		// Menghasilkan Data :
		// VALIDTO, KODESHIFT, NIK, NAMASHIFT,SHIFTKE, JAMDARI, JAMSAMPAI,
		// TOTALJAM(dlam menit), TGLMULAI, DAN TGLSAMPAI (dari shift yg digunakan)
		
		$sql = "SELECT t5.VALIDTO, t6.KODESHIFT, t6.NIK, t5.NAMASHIFT, t5.SHIFTKE, t5.JAMDARI, t5.JAMSAMPAI, ((HOUR(TIMEDIFF(t5.JAMDARI,t5.JAMSAMPAI))*60) + MINUTE(TIMEDIFF(t5.JAMDARI,t5.JAMSAMPAI))) as TOTALJAM, t5.TGLMULAI, t5.TGLSAMPAI
		FROM karyawanshift t6
		JOIN (
		SELECT t4.VALIDTO, t4.KODESHIFT, t1.NAMASHIFT, t1.SHIFTKE, t1.JAMDARI, t1.JAMSAMPAI, t4.TGLMULAI,t4.TGLSAMPAI
		FROM shiftjamkerja t1
		JOIN (
		SELECT t3.KODESHIFT, t2.NAMASHIFT, t3.SHIFTKE, t2.VALIDTO, t3.TGLMULAI, t3.TGLSAMPAI
		FROM shift t2
		RIGHT JOIN pembagianshift t3
		ON t2.NAMASHIFT=t3.NAMASHIFT) as t4
		ON t1.NAMASHIFT=t4.namashift AND t1.SHIFTKE=t4.SHIFTKE) as t5
		ON t5.KODESHIFT = t6.KODESHIFT AND DATE('$tgl') <= DATE(t5.TGLSAMPAI) AND DATE('$tgl') >= DATE(t5.TGLMULAI) AND T6.NIK=$nik";
		$query = $this->db->query($sql);
		$rs = $query->result_array();
			
		//var_dump($query);
		//echo "<br /><br />".$query->num_rows();
		if($query->num_rows() > 0)
		{
			$data = intval($rs[0]['TOTALJAM']) - intval($norm);
			if($data >= 0)
			{
				echo $data;
			}
			else
				echo 0;
		}
		else
		{
			echo 0;
		}
	}
	
	public function index()
	{
		//$this->load->view('v_test');
		//$this->JamKerja('201210');
		//$this->ImportPresensi();
		//$this->InitRecord('201209');
		//$l = $this->ListLembur();
		//var_dump($l);
		//echo "<br /><br />";
		//$jk = $this->JamKerja('2012-08-02','00010427');
		//var_dump($jk);
		//$this->UpdatePresensi('2012-08-08','00010427');
		//$this->LoopUpdate('201209');
		//$this->ListNIK();
		$this->JamKurangPerHari(162,'20120808','00010427');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */