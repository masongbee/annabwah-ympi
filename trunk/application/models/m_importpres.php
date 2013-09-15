<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_importpres
 * 
 * Table	: presensi
 *  
 * @author masongbee
 *
 */
class M_importpres extends CI_Model{

	private $id1;
	private $id2;
	private $jam1;
	private $jam2;

	function __construct(){
		parent::__construct();
		
		//$DB1 = $this->load->database('default', TRUE);
		//$DB2 = $this->load->database('mybase', TRUE); 
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	 
	function ImportPresensi($tglmulai,$tglsampai){
		/*$sql = "INSERT INTO dbympi.presensi
		(NIK,TJMASUK,TJKELUAR,ASALDATA,USERNAME)
		SELECT t1.NIK AS NIK,
		t1.MASUK AS TJMASUK,
		t1.KELUAR AS TJKELUAR,
		'D' AS ASALDATA,
		'Super Admin' AS USERNAME
		FROM (
			SELECT k.NIK, j.trans_tgl, j.trans_jam, j.trans_keluar, j.trans_status, j.MASUK, j.KELUAR, j.jml as JRECORD
			FROM dbympi.karyawan k
			JOIN (
			select t.trans_pengenal,t.trans_tgl,t.trans_jam,MAX(t.trans_jam)as trans_keluar,t.trans_status, 
			TIMESTAMP(MIN(t.trans_tgl),MIN(t.trans_jam)) as MASUK, 
			TIMESTAMP(MAX(t.trans_tgl),MAX(t.trans_jam)) as KELUAR,count(t.trans_tgl) as jml
			from (
			SELECT DISTINCT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log
				FROM mybase.absensi ) as t
			WHERE t.trans_tgl >= DATE('$tglmulai') AND t.trans_tgl <= DATE('$tglsampai')
			group by t.trans_pengenal, t.trans_tgl) as j
			ON k.NIK=j.trans_pengenal) as t1
		WHERE t1.trans_tgl >= DATE('$tglmulai') AND t1.trans_tgl <= DATE('$tglsampai')";
		$query = $this->db->query($sql);
		$rs = $this->db->order_by('TJMASUK', 'ASC')->get('presensi')->result();
		//$firephp->info($query);
		$total = $this->db->get('presensi')->num_rows();
		$data   = array();
		foreach($rs as $result){
			$data[] = $result;
		}
		
		$json	= array(
					'success'   => TRUE,
					'message'   => "Loaded data",
					'total'     => $total,
					'data'      => $data
			);
			
		return $json;*/
		
		
		$DB1 = $this->load->database('default', TRUE);
		$DB2 = $this->load->database('mybase', TRUE); 
		
		//$sql = "SELECT DISTINCT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log from absensi WHERE trans_pengenal = 00030453 ORDER BY trans_pengenal,trans_log";
		
		//$cp = intval(read_file("./assets/checkpoint/cp.txt"));
		//$limit = 100;
		/*$query = $DB2->limit($limit, $cp)->select('distinct (IF((SUBSTR(trans_pengenal,1,2) >= 97)AND(SUBSTR(trans_pengenal,1,2)<=99),CONCAT(CHAR(SUBSTR(trans_pengenal,1,2)-32),trans_pengenal),CONCAT(CHAR(SUBSTR(trans_pengenal,1,2)+68),trans_pengenal))) AS trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log')->order_by('trans_pengenal','trans_log')->get('absensi');
		$total  = $query->num_rows();*/
		
		$sql = "INSERT INTO absensi (SELECT distinct (IF((SUBSTR(t1.trans_pengenal,1,2) >= 97)AND(SUBSTR(t1.trans_pengenal,1,2)<=99),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)-32),t1.trans_pengenal),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)+68),t1.trans_pengenal))) AS trans_pengenal,
		t1.trans_tgl,t1.trans_jam,t1.trans_status,t1.trans_log, '0' AS import
		FROM mybase.absensi AS t1
		WHERE t1.trans_tgl >= DATE('$tglmulai') AND t1.trans_tgl <= DATE('$tglsampai')
		ORDER BY t1.trans_pengenal,t1.trans_log)";
		$query = $this->db->query($sql);
		//$total  = $query->num_rows();
		
		$sql = "SELECT a.trans_pengenal,a.trans_tgl,a.trans_jam,a.trans_status,a.trans_log
		FROM absensi a
		INNER JOIN karyawan k ON k.NIK=a.trans_pengenal
		WHERE a.trans_tgl >= DATE('$tglmulai') AND a.trans_tgl <= DATE('$tglsampai') AND a.import='0'";
		$query = $this->db->query($sql);
		//$total  = $query->num_rows();
		
		//$TimeWork = 12; // misal jam kerja dalam 1hari adlah 9 jam
		
		/*Prosedur Import Presensi Page 8
		A      = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		
		foreach($query->result_array() as $val)
		{
		
			if(!$ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A simpan nik ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				//$this->firephp->info($this->jam1);
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuA && $val['trans_status'] == "B")
			{
				//Update Record A->B
				$this->id2 = $val['trans_pengenal'];
				
				if($this->id2 == $this->id1)
				{
					$data = array(
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam']
					);
					
					$array = array('NIK' => $this->id1, 'TJMASUK' => $this->jam1);

					$DB1->where($array);
					$DB1->update('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
					
					$ketemuA = false;
					$ketemuB = true;
				}
				else
				{
					//Insert Record A->B jika nik berbeda
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);				
					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TANGGAL' => $val['trans_tgl'],
						   'TJMASUK' => null,
						   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
						   'ASALDATA' => 'D' ,
						   'POSTING' => null ,
						   'USERNAME' => $this->session->userdata('user_name')
						);
						$DB1->insert('presensi', $data);
						
						
						$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				
						$ketemuA = false;
						$ketemuB = false;
					}
				}
			}
			elseif($ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A->A simpan nik $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				
				$ketemuA = true;
				$ketemuB = false;				
			}
			elseif(!$ketemuB && $val['trans_status'] == "B")
			{
				//Record Baru B				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = false;
				$ketemuB = true;
			}
			elseif($ketemuB && $val['trans_status'] == "A")
			{
				//Record Baru B->A simpan nik $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => null,
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuB && $val['trans_status'] == "B")
			{
				//Record Baru B->B
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'TJMASUK' => null,
					   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => $val['trans_tgl']." ".$val['trans_jam'],
					   'ASALDATA' => 'D' ,
					   'POSTING' => null ,
					   'USERNAME' => $this->session->userdata('user_name')
					);
					$DB1->insert('presensi', $data);
					
					
					$DB1->where(array('trans_pengenal' => $val['trans_pengenal'], 'trans_tgl' => $val['trans_tgl'],'trans_jam' => $val['trans_jam']))->update('absensi', array('import' => '1'));
				}
				$ketemuA = false;
				$ketemuB = true;
			}
		}
		
		
		/*foreach($query->result_array() as $val)
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
		}*/
		
		/*if (write_file("./assets/checkpoint/cp.txt", $cp + $total))
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
					'message'   => "Import Success",
					'total'     => $total,
					'data'      => $data
			);
			
			return $json;
		}*/
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
	
	function getAll($filt,$start, $page, $limit){
		if($filt == "Filter")
		{
			$this->db->where('TJKELUAR IS NULL', NULL);
			$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
			$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi');
			$total  = $query->num_rows();
			
			$data   = array();
			foreach($query->result() as $result){
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
		else
		{
			$sql = "SELECT p.NIK, k.NAMAKAR as NAMA, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			ORDER BY p.TJMASUK ASC
			LIMIT $start,$limit";
			$query = $this->db->query($sql)->result();
			//$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi')->result();
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
	}
	
	/**
	 * Fungsi	: save
	 * 
	 * Untuk menambah data baru atau mengubah data lama
	 * 
	 * @param array $data
	 * @return json
	 */
	function save($data){
		$last   = NULL;
		
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
	
		//$this->firephp->info($data->TJMASUK);
		//$this->firephp->info(date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		if($this->db->get_where('presensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('presensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'POSTING'=>$data->POSTING,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('presensi', $arrdatac);
			$last   = $this->db->where($pkey)->get('presensi')->row();
			
		}
		
		$total  = $this->db->get('presensi')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	/**
	 * Fungsi	: delete
	 * 
	 * Untuk menghapus satu data
	 * 
	 * @param array $data
	 * @return json
	 */
	function delete($data){
		$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$this->db->where($pkey)->delete('presensi');
		
		$total  = $this->db->get('presensi')->num_rows();
		$last = $this->db->get('presensi')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function getAllData($tglmulai, $tglsampai,$saring,$sorts,$filters,$start, $page, $limit)
	{
		if($saring == "Salah Cek Log")
		{
			$sql = "SELECT p.NIK, k.NAMAKAR,uk.NAMAUNIT,kk.NAMAKEL, p.TANGGAL,sjk.NAMASHIFT,sjk.SHIFTKE,
			sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN karyawanshift ks ON ks.NIK=p.NIK
			LEFT JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			LEFT JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			WHERE p.TJKELUAR IS NULL OR p.TJMASUK IS NULL";
			
			/*$sql = "SELECT p.NIK, k.NAMAKAR,uk.NAMAUNIT, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING,d.JUMLAH
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN (SELECT NIK,TANGGAL,TJMASUK,TJKELUAR,ASALDATA,POSTING,COUNT(*) AS JUMLAH
			FROM presensi
			GROUP BY NIK,TANGGAL) AS d ON d.NIK=p.NIK
			WHERE p.TJKELUAR IS NULL OR p.TJMASUK IS NULL AND d.JUMLAH > 1";*/
			
			$sql .= " ORDER BY p.NIK ASC";
			//$sql .= " LIMIT ".$start.",".$limit;		
			$query = $this->db->query($sql);
			
			//$this->db->where('TJKELUAR IS NULL', NULL);
			//$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
			//$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi');
			$total  = $query->num_rows();
			
			$data   = array();
			foreach($query->result() as $result){
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
		elseif($saring == "Range" && $filters == null)
		{
			$sql = "SELECT p.NIK, k.NAMAKAR,uk.NAMAUNIT, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			WHERE p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')";
			$sql .= " ORDER BY k.NAMAKAR ASC";
			//$sql .= " LIMIT ".$start.",".$limit;		
			$query = $this->db->query($sql);
			
			//$this->db->where('TJKELUAR IS NULL', NULL);
			//$this->db->or_where('TJMASUK = TJKELUAR', NULL); 
			//$query  = $this->db->limit($limit, $start)->order_by('TJMASUK', 'ASC')->get('presensi');
			$total  = $query->num_rows();
			
			$data   = array();
			foreach($query->result() as $result){
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
		else
		{
			// collect request parameters
			//$start  = isset($_REQUEST['start'])  ? $_REQUEST['start']  :  0;
			//$limit  = isset($_REQUEST['limit'])  ? $_REQUEST['limit']  : 50;
			//$sort   = isset($_REQUEST['sort'])   ? json_decode($_REQUEST['sort'])   : null;
			//$filters = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : null;

			//$sortProperty = $sorts[0]->property;
			//$sortDirection = $sorts[0]->direction;
			
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC';
			$ks = '';
			
			if (is_array($sorts)) {
				for ($i=0;$i<count($sorts);$i++){
					$sort = $sorts[$i];

					// assign Sort data (location depends if encoded or not)
					if ($encoded) {
						if($sort->property == 'NIK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'NAMAKAR')
							$prop = "k.".$sort->property;
						elseif($sort->property == 'NAMAUNIT')
							$prop = "uk.".$sort->property;
						elseif($sort->property == 'NAMAKEL')
							$prop = "kk.".$sort->property;
						else if($sort->property == 'TANGGAL')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJMASUK')
							$prop = "p.".$sort->property;
						elseif($sort->property == 'TJKELUAR')
							$prop = "p.".$sort->property;
						else
							$prop = $sort->property;
						
						$dir = $sort->direction;					
					} else {
						$prop = $sort['property'];
						$dir = $sort['direction'];
					}
					$ks .= ",".$prop." ".$dir;
				}
				$dsort .= $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' 0 = 0 ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						else
							$field = $filter->field;
							
						$value = $filter->value;
						$compare = isset($filter->comparison) ? $filter->comparison : null;
						$filterType = $filter->type;
					} else {
						$field = $filter['field'];
						$value = $filter['data']['value'];
						$compare = isset($filter['data']['comparison']) ? $filter['data']['comparison'] : null;
						$filterType = $filter['data']['type'];
					}

					switch($filterType){
						case 'string' : $qs .= " AND ".$field." LIKE '%".$value."%'"; Break;
						case 'list' :
							if (strstr($value,',')){
								$fi = explode(',',$value);
								for ($q=0;$q<count($fi);$q++){
									$fi[$q] = "'".$fi[$q]."'";
								}
								$value = implode(',',$fi);
								$qs .= " AND ".$field." IN (".$value.")";
							}else{
								$qs .= " AND ".$field." = '".$value."'";
							}
						Break;
						case 'boolean' : $qs .= " AND ".$field." = ".($value); Break;
						case 'numeric' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = ".$value; Break;
								case 'lt' : $qs .= " AND ".$field." < ".$value; Break;
								case 'gt' : $qs .= " AND ".$field." > ".$value; Break;
							}
						Break;
						case 'date' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d',strtotime($value))."'"; Break;
							}
						Break;
						case 'datetime' :
							switch ($compare) {
								case 'eq' : $qs .= " AND ".$field." = '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'lt' : $qs .= " AND ".$field." < '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
								case 'gt' : $qs .= " AND ".$field." > '".date('Y-m-d H:i:s',strtotime($value))."'"; Break;
							}
						Break;
					}
				}
				$where .= $qs;
			}
			
			/*$sql = "SELECT p.NIK, k.NAMAKAR,uk.NAMAUNIT,kk.NAMAKEL, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			WHERE ".$where;*/
			
			$sql = "SELECT p.NIK, k.NAMAKAR,uk.NAMAUNIT,kk.NAMAKEL, p.TANGGAL,sjk.NAMASHIFT,sjk.SHIFTKE,
			sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=uk.KODEKEL
			LEFT JOIN karyawanshift ks ON ks.NIK=p.NIK
			LEFT JOIN pembagianshift ps ON ps.KODESHIFT=ks.KODESHIFT
			LEFT JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=ps.NAMASHIFT AND sjk.SHIFTKE=ps.SHIFTKE
			WHERE ".$where;
			
			//$sql .= " ORDER BY k.NAMAKAR ASC,p.TANGGAL ASC";
			//$sql .= " ORDER BY ".$sortProperty." ".$sortDirection."";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;		
			$query = $this->db->query($sql)->result();
			//$total = $query->num_rows();
			
			$total  = $this->db->query("SELECT count(p.NIK) as total, k.NAMAKAR,uk.NAMAUNIT, p.TANGGAL, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT WHERE ".$where)->result();
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			//$this->firephp->info($sql);
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				//'total'     => $total,
				'data'      => $data
			);
			
			return $json;
		}
	}
}
?>