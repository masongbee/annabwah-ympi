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
	
	function JamKerja($bulangaji)
	{
		$array = array('parameter' => 'jam_kerja');
		$TimeWork = $this->db->select('value')->get_where('init',$array)->row_array();
		$bln = $bulangaji . "01";
		// Checking data
		//$rs = $this->db->query("SELECT BULAN from hitungpresensi WHERE BULAN = (SELECT BULAN from periodegaji)");
		//var_dump($rs);
		
		// 1. Proses Inisialisasi Insert Record
		$sql = "insert into HITUNGPRESENSI (NIK, BULAN, TANGGAL, JENISABSEN,HARIKERJA,JAMKERJA, USERNAME) select NIK, $bulangaji as BULAN, NOW() as TANGGAL, 'AL' as JENISABSEN,SUM(IF(TIMESTAMPDIFF(HOUR,TJMASUK,TJKELUAR)>=".$TimeWork["value"].",1,0)) as HARIKERJA,SUM(TIMESTAMPDIFF(MINUTE,TJMASUK,TJKELUAR))as JAMKERJA, USERNAME as USERNAME from PRESENSI where DATE_FORMAT(TJMASUK,'%Y%m')=DATE_FORMAT(DATE_SUB('$bln',INTERVAL 1 MONTH),'%Y%m') GROUP BY NIK";
		
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
	
	public function index()
	{
		//$this->load->view('v_test');
		//$this->JamKerja('201210');
		//$this->ImportPresensi();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */