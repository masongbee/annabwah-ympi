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
	
	function hoursToSecods ($hour) {
		// $hour must be a string type: "HH:mm:ss"
		$parse = array();
		if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2}):(?<secs>[\d]{2})$#',$hour,$parse)) {
			// Throw error, exception, etc
			throw new RuntimeException ("Hour Format not valid");
		}
		return (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60 + (int) $parse['secs'];
	
	}
	
	function getShiftke($jam,$jenishari,$tjmasuk,$tjkeluar,$shiftN,$shiftJ){
		for($i=0;$i<3;$i++){			
			if($jenishari == 'N'){
				if($tjmasuk){
					if($shiftN[$i]->SHIFTKE == $shiftN[$i]->JAMDARI_SHIFTN_JUMP0){
						if(($this->hoursToSecods($shiftN[$i]->JAMDARI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods('23:59:59') >= $this->hoursToSecods($jam)) || ($this->hoursToSecods($shiftN[$i]->JAMDARI_AKHIR) >= $this->hoursToSecods($jam)) && ($this->hoursToSecods('00:00:00') <= $this->hoursToSecods($jam))){
							return $shiftN[$i];
						}
					}
					elseif(($this->hoursToSecods($shiftN[$i]->JAMDARI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods($shiftN[$i]->JAMDARI_AKHIR) >= $this->hoursToSecods($jam))){
						return $shiftN[$i];
					}
				}
				elseif($tjkeluar){
					if($shiftN[$i]->SHIFTKE == $shiftN[$i]->JAMSAMPAI_SHIFTN_JUMP0){
						if(($this->hoursToSecods($shiftN[$i]->JAMSAMPAI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods('23:59:59') >= $this->hoursToSecods($jam)) || ($this->hoursToSecods($shiftN[$i]->JAMSAMPAI_AKHIR) >= $this->hoursToSecods($jam)) && ($this->hoursToSecods('00:00:00') <= $this->hoursToSecods($jam))){
							return $shiftN[$i];
						}
					}
					elseif(($this->hoursToSecods($shiftN[$i]->JAMSAMPAI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods($shiftN[$i]->JAMSAMPAI_AKHIR) >= $this->hoursToSecods($jam))){
						return $shiftN[$i];
					}
				}
			}
			else{
				if($tjmasuk){
					if($shiftJ[$i]->SHIFTKE == $shiftJ[$i]->JAMDARI_SHIFTJ_JUMP0){
						if(($this->hoursToSecods($shiftJ[$i]->JAMDARI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods('23:59:59') >= $this->hoursToSecods($jam)) || ($this->hoursToSecods($shiftJ[$i]->JAMDARI_AKHIR) >= $this->hoursToSecods($jam)) && ($this->hoursToSecods('00:00:00') <= $this->hoursToSecods($jam))){
							return $shiftJ[$i];
						}
					}
					elseif(($this->hoursToSecods($shiftJ[$i]->JAMDARI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods($shiftJ[$i]->JAMDARI_AKHIR) >= $this->hoursToSecods($jam))){
						return $shiftJ[$i];
					}
				}
				elseif($tjkeluar){
					if($shiftJ[$i]->SHIFTKE == $shiftJ[$i]->JAMSAMPAI_SHIFTJ_JUMP0){
						if(($this->hoursToSecods($shiftJ[$i]->JAMSAMPAI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods('23:59:59') >= $this->hoursToSecods($jam)) || ($this->hoursToSecods($shiftJ[$i]->JAMSAMPAI_AKHIR) >= $this->hoursToSecods($jam)) && ($this->hoursToSecods('00:00:00') <= $this->hoursToSecods($jam))){
							return $shiftJ[$i];
						}
					}
					elseif(($this->hoursToSecods($shiftJ[$i]->JAMSAMPAI_AWAL) <= $this->hoursToSecods($jam)) && ($this->hoursToSecods($shiftJ[$i]->JAMSAMPAI_AKHIR) >= $this->hoursToSecods($jam))){
						return $shiftJ[$i];
					}
				}
			}
		}
	}
	
	function cekAbsensi($tglmulai,$tglsampai){
		//Cek tabel absensi
		// object : $tgl->tglm & $tgl->tgls
		$cek = $this->db->get_where('absensi',array('trans_tgl >=' => $tglmulai,'trans_tgl <=' => $tglsampai))->num_rows();
		if($cek == 0){
			$this->db->query("INSERT INTO absensi(trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log,import) (SELECT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log,import FROM absensi_tmp);");
			$json	= array(
				'success'   => TRUE,
				'message'   => 'Data tersimpan sebagian, silakan proses kembali...'
			);			
			return $json;
		}
		$json	= array(
			'success'   => FALSE
		);			
		return $json;
	}
	
	function ImportPresensi($tglmulai,$tglsampai){
		$mybasedb = $this->load->database('mybase', TRUE);
		
		$cnt = 0;
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$cnt));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		
		$this->db->query("DROP TABLE IF EXISTS absensi_tmp;");
		$this->db->query("CREATE TABLE absensi_tmp (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`trans_pengenal` char(10) NOT NULL,
		`trans_tgl` date NOT NULL,
		`trans_jam` time NOT NULL,
		`trans_status` char(2) NOT NULL,
		`trans_log` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`import` enum('1','0') DEFAULT NULL,
		PRIMARY KEY (`id`)
	  ) ENGINE=InnoDB AUTO_INCREMENT=63281 DEFAULT CHARSET=utf8;");
		
		/*$sql = "INSERT INTO absensi_tmp (trans_pengenal
			,trans_tgl
			,trans_jam
			,trans_status
			,trans_log
			,`import`)
			SELECT IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
				AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal)) AS trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status, t2.trans_log, '0'
			FROM mybase.absensi AS t2 
			LEFT JOIN absensi_tmp AS t1 ON(t1.trans_pengenal = (IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
					AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal))) 
				AND t1.trans_tgl = t2.trans_tgl
				AND t1.trans_jam = t2.trans_jam AND t1.trans_status = t2.trans_status)
			WHERE t1.trans_pengenal IS NULL 
				AND t1.trans_tgl IS NULL 
				AND t1.trans_jam IS NULL
				AND t1.trans_status IS NULL
				AND TO_DAYS(t2.trans_tgl) >= TO_DAYS('".$tglmulai."') AND TO_DAYS(t2.trans_tgl) <= TO_DAYS('".$tglsampai."')
			GROUP BY t2.trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status";
		$this->db->query($sql);*/
		$sql_mybase = "SELECT IF((SUBSTR(trans_pengenal,1,2) >= 97)
				AND (SUBSTR(trans_pengenal,1,2)<=99),
				CONCAT(CHAR(SUBSTR(trans_pengenal,1,2)-32),trans_pengenal),
				CONCAT(CHAR(SUBSTR(trans_pengenal,1,2)+68),trans_pengenal)) AS trans_pengenal,
				trans_tgl, trans_jam, trans_status, trans_log, '0' AS import
			FROM absensi
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."') AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')";
		$data_mybasedb = $mybasedb->query($sql_mybase)->result();
		
		$arrdata_mybasedb = array();
		foreach($data_mybasedb as $row){
			array_push($arrdata_mybasedb, (array) $row);
		}
		$this->db->insert_batch('absensi_tmp', $arrdata_mybasedb);
		
		/*$sqld = "DELETE FROM absensi_tmp
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan WHERE (STATUS='T' OR STATUS='K' OR STATUS='C'))";
		$this->db->query($sqld);*/		
		
		/*Prosedur Import Presensi Page 8
		A      = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		$range = $this->db->query("SELECT VALUE FROM INIT WHERE PARAMETER = 'Range'")->result();
		
		$sql = "SELECT a.id,a.trans_pengenal,a.trans_tgl,a.trans_jam,a.trans_status,a.trans_log
		FROM absensi_tmp a
		INNER JOIN karyawan k ON k.NIK=a.trans_pengenal
		WHERE (a.trans_tgl >= DATE('$tglmulai') AND a.trans_tgl <= DATE('$tglsampai')) AND (k.STATUS='T' OR k.STATUS='K' OR k.STATUS='C') AND a.import='0'
		order by a.trans_pengenal, a.trans_tgl, a.trans_jam";
		$query_abs = $this->db->query($sql);
		
		//Total data yg akan diproses
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$query_abs->num_rows()));
		
		//Inisialisasi parameter
		$total_data = $query_abs->num_rows();
		//$Rec_Proc = $this->db->query("SELECT VALUE FROM INIT WHERE PARAMETER = 'Rec_Proc'")->result();
		//$n = $Rec_Proc[0]->VALUE;
		//$p = intval($total_data/$n);
		//$proc = $n;
		
		// Data Presensi --> NIK, TJMASUK, TANGGAL, TJKELUAR, ASALDATA, ABSENSI_ID, NAMASHIFT, SHIFTKE
		$namashift = $this->db->query("SELECT *
		FROM shift
		WHERE (VALIDFROM <= DATE('$tglmulai') AND VALIDTO >= DATE('$tglsampai'))")->result();
		
		// -------------------------------- Proses Cek NAMASHIFT DAN SHIFTKE ------------------------
		$rs = $this->db->query("SELECT NAMASHIFT,SHIFTKE,JENISHARI,JAMDARI_AWAL,JAMDARI,JAMDARI_AKHIR,
		JAMSAMPAI_AWAL,JAMSAMPAI,JAMSAMPAI_AKHIR
		FROM shiftjamkerja
		WHERE NAMASHIFT='".$namashift[0]->NAMASHIFT."'")->result();
		
		// get shiftke jamdari jenishari = 'N' yang jump 00:00:00
		$shiftn_jamdari_jump0 = $this->db->query("SELECT SHIFTKE
			FROM shiftjamkerja
			WHERE JENISHARI = 'N' AND JAMDARI_AKHIR < JAMDARI_AWAL")
			->row()->SHIFTKE;
		// get shiftke jamdari jenishari = 'J' yang jump 00:00:00
		$shiftj_jamdari_jump0 = $this->db->query("SELECT SHIFTKE
			FROM shiftjamkerja
			WHERE JENISHARI = 'J' AND JAMDARI_AKHIR < JAMDARI_AWAL")
			->row()->SHIFTKE;
		
		// get shiftke jamsampai jenishari = 'N' yang jump 00:00:00
		$shiftn_jamsampai_jump0 = $this->db->query("SELECT SHIFTKE
			FROM shiftjamkerja
			WHERE JENISHARI = 'N' AND JAMSAMPAI_AKHIR < JAMSAMPAI_AWAL")
			->row()->SHIFTKE;
		// get shiftke jamsampai jenishari = 'J' yang jump 00:00:00
		$shiftj_jamsampai_jump0 = $this->db->query("SELECT SHIFTKE
			FROM shiftjamkerja
			WHERE JENISHARI = 'J' AND JAMSAMPAI_AKHIR < JAMSAMPAI_AWAL")
			->row()->SHIFTKE;
		
		$shiftN = array();$shiftJ = array();
		
		$hari = new stdClass();
		$hari->NAMASHIFT = NULL;
		$hari->SHIFTKE = NULL;
		$hari->JENISHARI = NULL;
		$hari->JAMDARI_AWAL = NULL;
		$hari->JAMDARI = NULL;
		$hari->JAMDARI_AKHIR = NULL;
		$hari->JAMDARI_SHIFTN_JUMP0 = $shiftn_jamdari_jump0;
		$hari->JAMDARI_SHIFTJ_JUMP0 = $shiftj_jamdari_jump0;
		$hari->JAMSAMPAI_AWAL = NULL;
		$hari->JAMSAMPAI = NULL;
		$hari->JAMSAMPAI_AKHIR = NULL;
		$hari->JAMSAMPAI_SHIFTN_JUMP0 = $shiftn_jamsampai_jump0;
		$hari->JAMSAMPAI_SHIFTJ_JUMP0 = $shiftj_jamsampai_jump0;
		
		foreach($rs as $val){
			if($val->JENISHARI == 'N'){
				$hari->NAMASHIFT = $val->NAMASHIFT;
				$hari->SHIFTKE = $val->SHIFTKE;
				$hari->JENISHARI = $val->JENISHARI;
				$hari->JAMDARI_AWAL = $val->JAMDARI_AWAL;
				$hari->JAMDARI = $val->JAMDARI;
				$hari->JAMDARI_AKHIR = $val->JAMDARI_AKHIR;
				$hari->JAMDARI_SHIFTN_JUMP0 = $shiftn_jamdari_jump0;
				$hari->JAMDARI_SHIFTJ_JUMP0 = $shiftj_jamdari_jump0;
				$hari->JAMSAMPAI_AWAL = $val->JAMSAMPAI_AWAL;
				$hari->JAMSAMPAI = $val->JAMSAMPAI;
				$hari->JAMSAMPAI_AKHIR = $val->JAMSAMPAI_AKHIR;
				$hari->JAMSAMPAI_SHIFTN_JUMP0 = $shiftn_jamsampai_jump0;
				$hari->JAMSAMPAI_SHIFTJ_JUMP0 = $shiftj_jamsampai_jump0;
				
				array_push($shiftN,$hari);
				$hari = new stdClass();				
			}
			else {
				$hari->NAMASHIFT = $val->NAMASHIFT;
				$hari->SHIFTKE = $val->SHIFTKE;
				$hari->JENISHARI = $val->JENISHARI;
				$hari->JAMDARI_AWAL = $val->JAMDARI_AWAL;
				$hari->JAMDARI = $val->JAMDARI;
				$hari->JAMDARI_AKHIR = $val->JAMDARI_AKHIR;
				$hari->JAMDARI_SHIFTN_JUMP0 = $shiftn_jamdari_jump0;
				$hari->JAMDARI_SHIFTJ_JUMP0 = $shiftj_jamdari_jump0;
				$hari->JAMSAMPAI_AWAL = $val->JAMSAMPAI_AWAL;
				$hari->JAMSAMPAI = $val->JAMSAMPAI;
				$hari->JAMSAMPAI_AKHIR = $val->JAMSAMPAI_AKHIR;
				$hari->JAMSAMPAI_SHIFTN_JUMP0 = $shiftn_jamsampai_jump0;
				$hari->JAMSAMPAI_SHIFTJ_JUMP0 = $shiftj_jamsampai_jump0;
				
				array_push($shiftJ,$hari);
				$hari = new stdClass();
			}
		}
		// ------------------------------------------------------------------------------------------
		
		// ------------------------ Parameter Record Sebelum dan Record Sesudah ---------------------
		$data = array();
		$absensi = array();
		
		$data_prev = new StdClass();
		$data_prev->NIK = NULL;
		$data_prev->TANGGAL = NULL;
		$data_prev->NAMASHIFT = $namashift[0]->NAMASHIFT;
		$data_prev->SHIFTKE = NULL;
		$data_prev->TJMASUK = NULL;
		$data_prev->TJKELUAR = NULL;
		$data_prev->ASALDATA = 'D';
		$data_prev->USERNAME = 'Admin';
		$data_prev->ABSENSI_ID = NULL;
		
		$data_next = new StdClass();
		$data_next->NIK = NULL;
		$data_next->TANGGAL = NULL;
		$data_next->NAMASHIFT = $namashift[0]->NAMASHIFT;
		$data_next->SHIFTKE = NULL;
		$data_next->TJMASUK = NULL;
		$data_next->TJKELUAR = NULL;
		$data_next->ASALDATA = 'D';
		$data_next->USERNAME = 'Admin';
		$data_next->ABSENSI_ID = NULL;
		
		// ------------------------------------------------------------------------------------------
		
		foreach($query_abs->result() as $val)
		{
			/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
			sj.JAMDARI,sj.JAMSAMPAI,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
			FROM shift s
			RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			WHERE (s.VALIDFROM <= DATE('".$val->trans_tgl."') AND s.VALIDTO >= DATE('".$val->trans_tgl."')) AND
			(TIMESTAMP('".$val->trans_tgl."','".$val->trans_jam."') >= ((DATE_SUB(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',".($val->trans_status == 'A' ? "sj.JAMDARI" : "sj.JAMSAMPAI")."),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AND
			TIMESTAMP('".$val->trans_tgl."','".$val->trans_jam."') <= ((DATE_ADD(STR_TO_DATE(CONCAT('".$val->trans_tgl."',' ',".($val->trans_status == 'A' ? "sj.JAMDARI" : "sj.JAMSAMPAI")."),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val->trans_tgl."')='Friday','J','N')";
			$hasil = $this->db->query($sqlshift)->result();
			
			$jda = (isset($hasil[0]->JAMDARI_AWAL) ? $hasil[0]->JAMDARI_AWAL: NULL);
			$jdk = (isset($hasil[0]->JAMDARI_AKHIR) ? $hasil[0]->JAMDARI_AKHIR: NULL);
			$jsa = (isset($hasil[0]->JAMSAMPAI_AWAL) ? $hasil[0]->JAMSAMPAI_AWAL: NULL);
			$jsk = (isset($hasil[0]->JAMSAMPAI_AKHIR) ? $hasil[0]->JAMSAMPAI_AKHIR: NULL);
			$ns = (isset($hasil[0]->NAMASHIFT) ? $hasil[0]->NAMASHIFT: NULL);
			$s = (isset($hasil[0]->SHIFTKE) ? $hasil[0]->SHIFTKE: 3);
			
			$tjmasuk = ($val->trans_status == 'A' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			$tjkeluar = ($val->trans_status == 'B' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			$shiftke = (($tjmasuk >= $jda && $tjmasuk <= $jdk) || ($tjkeluar >= $jsa && $tjkeluar <= $jsk)? $s: '3');
			
			$data_next->NIK = $val->trans_pengenal;
			$data_next->TANGGAL = $val->trans_tgl;
			$data_next->SHIFTKE = $shiftke;
			$data_next->TJMASUK = $tjmasuk;
			$data_next->TJKELUAR = $tjkeluar;*/
			
			$jenishari = (date('D', strtotime($val->trans_tgl)) == 'Fri' ? 'J' :'N');
			$masuk = ($val->trans_status == 'A' ? true : false);
			$keluar = (!$masuk && $val->trans_status == 'B' ? true : false);
			$hasil = $this->getShiftke($val->trans_jam,$jenishari,$masuk,$keluar,$shiftN,$shiftJ);
			
			$shiftke = (isset($hasil->SHIFTKE)?$hasil->SHIFTKE:3);
			$tjmasuk = ($val->trans_status == 'A' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			$tjkeluar = ($val->trans_status == 'B' ? date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam)) : NULL);
			
			$data_next->NIK = $val->trans_pengenal;
			$data_next->TANGGAL = $val->trans_tgl;
			$data_next->SHIFTKE = $shiftke;
			$data_next->TJMASUK = $tjmasuk;
			$data_next->TJKELUAR = $tjkeluar;
			
			/**
			 * A (pertama ketemu) ==> tampung A
			 * B (pertama ketemu) ==> tampung B
			 * A --> A ==> create A1, tampung A2
			 * A --> B ==>
			 * >> jika NIK sama maka create A dan B dalam 1 record (match)
			 * >> jika NIK beda maka create A dan create B
			 * B --> A ==> create B, tampung A
			 * B --> B ==> create B1, create B2
			 */
			
			if(!$ketemuA && !$ketemuB && $val->trans_status == 'A'){
				// A ==> tampung A
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				$data_prev->TJKELUAR = NULL;
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = true;
				$ketemuB = false;
			}elseif(!$ketemuA && !$ketemuB && $val->trans_status == 'B'){
				// B ==> tampung B
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = NULL;
				$data_prev->TJKELUAR = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = false;
				$ketemuB = true;
			}elseif($ketemuA && !$ketemuB && $val->trans_status == 'A'){
				// A --> A ==> create A1, tampung A2; $data_prev <== milik A1
				array_push($data,(array) $data_prev);
				
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				$data_prev->TJKELUAR = NULL;
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = true;
				$ketemuB = false;
			}elseif($ketemuA && !$ketemuB && $val->trans_status == 'B'){
				// A --> B
				if($data_prev->NIK == $data_next->NIK){
					// NIK sama ==> create A dan B dalam 1 record (match)
					$data_prev->TJMASUK = $data_prev->TJMASUK;
					$data_prev->TJKELUAR = $data_next->TJKELUAR;
					
					array_push($data,(array) $data_prev);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}else{
					// NIK beda ==> create A ($data_prev, TJKELUAR = NULL) dan create B ($data_next, TJMASUK = NULL)
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = true;
				}
			}elseif(!$ketemuA && $ketemuB && $val->trans_status == 'A'){
				// B --> A ==> create B ($data_prev), tampung A
				array_push($data,(array) $data_prev);
				
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				$data_prev->TJKELUAR = NULL;
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = true;
				$ketemuB = false;
			}elseif(!$ketemuA && $ketemuB && $val->trans_status == 'B'){
				// B --> B ==> create B1 ($data_prev), create B2 ($data_next)
				array_push($data,(array) $data_prev,(array) $data_next);
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = false;
				$ketemuB = true;
			}
			
			/*
			
			if(!$ketemuA && !$ketemuB && $val->trans_status == "A")
			{
				//Record Baru A
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				$data_prev->TJKELUAR = NULL;
				
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = true;
				$ketemuB = false;
			}
			elseif($ketemuA && $val->trans_status == "B")
			{
				//Update Record A->B jika NIK sama
				if($data_prev->NIK == $data_next->NIK)
				{
					$data_prev->TJMASUK = $data_prev->TJMASUK;
					$data_prev->TJKELUAR = $data_next->TJKELUAR;
					
					array_push($data,(array) $data_prev);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
				else
				{
					//Insert Record A->B jika NIK berbeda					
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			elseif($ketemuA && $val->trans_status == "A")
			{
				//Record Baru A->A
				if($data_prev->NIK != $data_next->NIK)
				{
					array_push($data,(array) $data_prev);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
				else
				{
					array_push($data,(array) $data_prev);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
			}
			elseif(!$ketemuA && !$ketemuB && $val->trans_status == "B")
			{
				//Record Baru B
				$data_prev->NIK = $val->trans_pengenal;
				$data_prev->TANGGAL = $val->trans_tgl;
				$data_prev->SHIFTKE = $shiftke;
				$data_prev->TJMASUK = NULL;
				$data_prev->TJKELUAR = date('Y-m-d H:i:s', strtotime($val->trans_tgl." ".$val->trans_jam));
				
				array_push($absensi,array('id'=>$val->id,'import'=>1));
				
				$ketemuA = false;
				$ketemuB = true;
			}
			elseif($ketemuB && $val->trans_status == "A")
			{
				//Record Baru B->A
				if($data_prev->NIK != $data_next->NIK)
				{
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$data_prev->NIK = $data_next->NIK;
					$data_prev->TANGGAL = $data_next->TANGGAL;
					$data_prev->SHIFTKE = $data_next->SHIFTKE;
					$data_prev->TJMASUK = $data_next->TJMASUK;
					$data_prev->TJKELUAR = NULL;
					
					$ketemuA = true;
					$ketemuB = false;
				}
				else
				{
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			elseif($ketemuB && $val->trans_status == "B")
			{
				//Record Baru B->B
				if($data_prev->NIK != $data_next->NIK)
				{
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
				else
				{
					array_push($data,(array) $data_prev,(array) $data_next);
					array_push($absensi,array('id'=>$val->id,'import'=>1));
					
					$ketemuA = false;
					$ketemuB = false;
				}
			}
			
			*/
			
			
			
			/*$j=1;
			if($cnt == $proc){
				if($j<=$p){
					$this->db->update_batch('absensi_tmp', $absensi, 'id');
					$absensi = array();
					$this->db->insert_batch('presensi', $data);
					$absensi = array();
					$proc = $proc + $n;
					$j++;
				}
			}
			
			if($cnt == intval($total_data - 255)){
				$this->db->update_batch('absensi_tmp', $absensi, 'id');
				$absensi = array();
				$this->db->insert_batch('presensi', $data);
				$absensi = array();
			}
			elseif($cnt == intval($total_data / 3)){
				$this->db->update_batch('absensi_tmp', $absensi, 'id');
				$absensi = array();
				$this->db->insert_batch('presensi', $data);
				$absensi = array();
			}
			elseif($cnt == intval($total_data / 2)){
				$this->db->update_batch('absensi_tmp', $absensi, 'id');
				$absensi = array();
				$this->db->insert_batch('presensi', $data);
				$absensi = array();
			}
			elseif($cnt == intval($total_data / 1.2)){
				$this->db->update_batch('absensi_tmp', $absensi, 'id');
				$absensi = array();
				$this->db->insert_batch('presensi', $data);
				$data = array();
			}*/
			$cnt ++;
			$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		}
		$this->db->insert_batch('presensi', $data);
		$this->db->update_batch('absensi_tmp', $absensi, 'id');
		//$this->db->query("INSERT INTO absensi(trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log,import) (SELECT trans_pengenal,trans_tgl,trans_jam,trans_status,trans_log,import FROM absensi_tmp);");
		//$this->db->query("DELETE d1 FROM presensi d1, presensi d2 WHERE d1.TANGGAL=d2.TANGGAL AND d1.NIK=d2.NIK AND d1.SHIFTKE=d2.SHIFTKE AND (d1.TJMASUK=d2.TJMASUK OR d1.TJKELUAR=d2.TJKELUAR) AND d1.ID > d2.ID");
		
		//1. DELETE yang match (TJMASUK+TJKELUAR NOT NULL) lebih dari 1 record
		$sqld1 = "DELETE t1
			FROM presensi AS t1
			JOIN presensi AS t2 ON(t2.NIK = t1.NIK
				AND t2.TJMASUK = t1.TJMASUK AND t2.TJKELUAR = t1.TJKELUAR
				AND t2.SHIFTKE = t1.SHIFTKE)
			WHERE t1.ID > t2.ID";
		$this->db->query($sqld1);
		
		//2. DELETE yang not match (TJMASUK NOT NULL, TJKELUAR NULL) lebih dari 1 record
		$sqld2 = "DELETE t1
			FROM presensi AS t1
			JOIN presensi AS t2 ON(t2.NIK = t1.NIK
				AND t1.TJMASUK IS NOT NULL AND t2.TJMASUK = t1.TJMASUK 
				AND t2.TJKELUAR IS NULL AND t1.TJKELUAR IS NULL
				AND t2.SHIFTKE = t1.SHIFTKE)
			WHERE t1.ID > t2.ID";
		$this->db->query($sqld2);
		
		//3. DELETE yang not match (TJMASUK NULL, TJKELUAR NOT NULL) lebih dari 1 record
		$sqld3 = "DELETE t1
			FROM presensi AS t1
			JOIN presensi AS t2 ON(t2.NIK = t1.NIK
				AND t1.TJKELUAR IS NOT NULL AND t2.TJKELUAR = t1.TJKELUAR 
				AND t2.TJMASUK IS NULL AND t1.TJMASUK IS NULL
				AND t2.SHIFTKE = t1.SHIFTKE)
			WHERE t1.ID > t2.ID";
		$this->db->query($sqld3);
		
		//4. DELETE yang t1 not match (TJKELUAR NULL) dicompare dengan t2 match
		$sqld4 = "DELETE t1
			FROM presensi AS t1
			JOIN presensi AS t2 ON(((t1.TJMASUK IS NOT NULL AND t1.TJKELUAR IS NULL) OR (t1.TJMASUK IS NULL AND t1.TJKELUAR IS NOT NULL))
				AND t2.TJMASUK IS NOT NULL AND t2.TJKELUAR IS NOT NULL
				AND t2.NIK = t1.NIK
				AND t2.TANGGAL = t1.TANGGAL)";
		$this->db->query($sqld4);
		
		//5. DELETE yang t1 not match (TJMASUK NULL) dicompare dengan t2 match
		/*$sqld5 = "DELETE t1
			FROM presensi AS t1
			JOIN presensi AS t2 ON(t1.TJMASUK IS NULL AND t1.TJKELUAR IS NOT NULL
				AND t2.TJMASUK IS NOT NULL AND t2.TJKELUAR IS NOT NULL
				AND t2.NIK = t1.NIK
				AND t2.TANGGAL = t1.TANGGAL)";
		$this->db->query($sqld5);*/
		
		/*$sqld = "DELETE t1
			FROM (
				SELECT ID, NIK, TJMASUK, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NOT NULL AND presensi.TJKELUAR IS NULL
			) AS t1
			JOIN (
				SELECT ID, NIK, TJMASUK, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NOT NULL AND presensi.TJKELUAR IS NOT NULL
			) AS t2 ON(t2.NIK = t1.NIK
				AND t2.TJMASUK = t1.TJMASUK
				AND t2.SHIFTKE = t1.SHIFTKE)";
		$this->db->query($sqld);
		
		$sqld = "DELETE t1
			FROM (
				SELECT ID, NIK, TJKELUAR, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NULL AND presensi.TJKELUAR IS NOT NULL
			) AS t1
			JOIN (
				SELECT ID, NIK, TJKELUAR, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NOT NULL AND presensi.TJKELUAR IS NOT NULL
			) AS t2 ON(t2.NIK = t1.NIK
				AND t2.TJKELUAR = t1.TJKELUAR
				AND t2.SHIFTKE = t1.SHIFTKE)";
		$this->db->query($sqld);*/
		
		//3.
		/*$sqld = "DELETE t1
			FROM (
				SELECT ID, NIK, TJMASUK, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NOT NULL AND presensi.TJKELUAR IS NULL
			) AS t1
			JOIN (
				SELECT ID, NIK, TJMASUK, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NOT NULL AND presensi.TJKELUAR IS NULL
			) AS t2 ON(t2.NIK = t1.NIK
				AND t2.TJMASUK = t1.TJMASUK
				AND t2.SHIFTKE = t1.SHIFTKE)
			WHERE t1.ID > t2.ID";
		$this->db->query($sqld);
		
		$sqld = "DELETE t1
			FROM (
				SELECT ID, NIK, TJKELUAR, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NULL AND presensi.TJKELUAR IS NOT NULL
			) AS t1
			JOIN (
				SELECT ID, NIK, TJKELUAR, SHIFTKE
				FROM presensi
				WHERE presensi.TJMASUK IS NULL AND presensi.TJKELUAR IS NOT NULL
			) AS t2 ON(t2.NIK = t1.NIK
				AND t2.TJKELUAR = t1.TJKELUAR
				AND t2.SHIFTKE = t1.SHIFTKE)
			WHERE t1.ID > t2.ID";
		$this->db->query($sqld);*/
		
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>'0'));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>'0'));
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Import Successfully...'
		);
		
		return $json;
	}
	
	function ImportPresensiKhusus($tglmulai,$tglsampai){
		$mybasedb = $this->load->database('mybase', TRUE);
		
		$cnt = 0;
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$cnt));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		
		$sql = "SELECT *
		FROM presensikhusus
		WHERE DATE(TANGGAL) BETWEEN DATE('".$tglmulai."') AND DATE('".$tglsampai."')
		ORDER BY NIK, TJMASUK";
		$query_abs = $this->db->query($sql);
		
		//Total data yg akan diproses
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$query_abs->num_rows()));
		
		$data = array();
		$presensikhusus2u = array();
		
		foreach($query_abs->result() as $val)
		{
			$this->db->delete('presensi', array(
				'NIK'=>$val->NIK, 'NAMASHIFT'=>$val->NAMASHIFT,
				'SHIFTKE'=>$val->SHIFTKE, 'TANGGAL'=>$val->TANGGAL));
			
			$datai = new stdClass();
			$datai->NIK			= $val->NIK;
			$datai->NAMASHIFT	= $val->NAMASHIFT;
			$datai->SHIFTKE		= $val->SHIFTKE;
			$datai->TJMASUK		= $val->TJMASUK;
			$datai->TANGGAL		= $val->TANGGAL;
			$datai->TJKELUAR	= $val->TJKELUAR;
			$datai->ASALDATA	= 'D';
			$datai->USERNAME	= 'Admin';
			
			array_push($data, (array) $datai);
			array_push($presensikhusus2u, array('ID'=>$val->ID,'IMPORT'=>'1'));
			
			$cnt ++;
			$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		}
		$this->db->insert_batch('presensi', $data);
		$this->db->update_batch('presensikhusus', $presensikhusus2u, 'ID');
		
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>'0'));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>'0'));
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Import Successfully...'
		);
		
		return $json;
	}
	
	function ImportPresensi_muk($tglmulai,$tglsampai){
		$cnt = 0;
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$cnt));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		/**
		 * Proses INSERT dari database mybase.absensi ke dbympi.absensi,
		 * dimana data mybase.absensi belum diimport ke dbympi.absensi
		 */
		$sql = "INSERT INTO absensi (trans_pengenal
			,trans_tgl
			,trans_jam
			,trans_status
			,trans_log
			,`import`)
			SELECT IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
				AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal)) AS trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status, t2.trans_log, '0'
			FROM mybase.absensi AS t2 
			LEFT JOIN absensi AS t1 ON(t1.trans_pengenal = (IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
					AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal))) 
				AND t1.trans_tgl = t2.trans_tgl
				AND t1.trans_jam = t2.trans_jam AND t1.trans_status = t2.trans_status)
			WHERE t1.trans_pengenal IS NULL 
				AND t1.trans_tgl IS NULL 
				AND t1.trans_jam IS NULL
				AND t1.trans_status IS NULL
				AND TO_DAYS(t2.trans_tgl) >= TO_DAYS('".$tglmulai."') AND TO_DAYS(t2.trans_tgl) <= TO_DAYS('".$tglsampai."')
			GROUP BY t2.trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status";
		$this->db->query($sql);
		
		/**
		 * DELETE absensi WHERE dbympi.absensi.trans_pengenal tidak ada di karyawan.NIK
		 */
		/*$sqld = "DELETE FROM absensi
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan)";
		$this->db->query($sqld);*/
		
		/**
		 * DELETE db.presensi WHERE TANGGAL diantara $tglmulai dan $tglsampai
		 */
		/*$sqld = "DELETE FROM presensi
			WHERE TO_DAYS(TANGGAL) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(TANGGAL) <= TO_DAYS('".$tglsampai."')";
		$this->db->query($sqld);*/
		
		/**
		 * INSERT into db.presensi dari dbympi.absensi yang kolom import = '0' dan trans_status = A
		 */
		$sql = "INSERT INTO presensi (NIK, TJMASUK, TANGGAL, TJKELUAR, ASALDATA, ABSENSI_ID, NAMASHIFT, SHIFTKE)
			SELECT trans_pengenal, STR_TO_DATE(CONCAT(trans_tgl,' ',trans_jam),'%Y-%m-%d %H:%i:%s'),
				trans_tgl, null, 'D', absensi.id, (
					SELECT NAMASHIFT
					FROM shift
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
						AND (CAST(DATE_FORMAT(VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
							OR VALIDTO IS NULL
						)
					LIMIT 1
				), (
					SELECT shiftjamkerja.SHIFTKE
					FROM shift
					JOIN shiftjamkerja ON(shiftjamkerja.NAMASHIFT = shift.NAMASHIFT)
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
						AND (CAST(DATE_FORMAT(VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
							OR VALIDTO IS NULL
						)
						AND (
							(
								TIME_TO_SEC(shiftjamkerja.JAMDARI_AWAL) <= TIME_TO_SEC(trans_jam)
								AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AKHIR) >= TIME_TO_SEC(trans_jam)
							)
							OR (
								shiftjamkerja.SHIFTKE = '3'
								AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AWAL) <= TIME_TO_SEC(trans_jam)
								AND TIME_TO_SEC('23:59:59') >= TIME_TO_SEC(trans_jam)
							)
							OR (
								shiftjamkerja.SHIFTKE = '3'
								AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AKHIR) >= TIME_TO_SEC(trans_jam)
								AND TIME_TO_SEC('00:00:00') <= TIME_TO_SEC(trans_jam)
							)
						)
					ORDER BY shiftjamkerja.SHIFTKE DESC
					LIMIT 1
				)
			FROM absensi
			JOIN karyawan ON(karyawan.NIK = absensi.trans_pengenal
				AND (karyawan.STATUS='T' OR karyawan.STATUS='K' OR karyawan.STATUS='C'))
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'A'
				AND NOT EXISTS (
					SELECT ABSENSI_ID FROM presensi WHERE ABSENSI_ID = absensi.id
				)
			ORDER BY trans_pengenal, trans_tgl, trans_jam";
		$this->db->query($sql);
		$rowsa_2presensi = $this->db->affected_rows();
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$rowsa_2presensi));
		
		
		/**
		 * UPDATE kolom absensi.import = '1' yang telah diimport ke db.presensi
		 */
		$sqlu = "UPDATE absensi
			JOIN presensi ON(presensi.ABSENSI_ID = absensi.id)
			SET import = '1'
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'A'";
		$this->db->query($sqlu);
		
		/**
		 * GET Data dari db.absensi yang import = '0' dan trans_status = 'B'
		 * Untuk di LOOPing dan meng-Update db.presensi atau create baru
		 */
		$sql = "SELECT id, trans_pengenal, trans_tgl, trans_jam, trans_status, trans_log
			FROM absensi
			JOIN karyawan ON(karyawan.NIK = absensi.trans_pengenal
				AND (karyawan.STATUS='T' OR karyawan.STATUS='K' OR karyawan.STATUS='C'))
			WHERE TO_DAYS(trans_tgl) >= TO_DAYS('".$tglmulai."')
				AND TO_DAYS(trans_tgl) <= TO_DAYS('".$tglsampai."')
				AND import = '0'
				AND trans_status = 'B'
			ORDER BY trans_pengenal, trans_tgl, trans_jam";
		$query = $this->db->query($sql);
		$rowsb_2presensi = $query->num_rows();
		
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>($rowsa_2presensi + $rowsb_2presensi)));
		
		$cnt = $rowsa_2presensi;
		foreach($query->result() as $row){
			/**
			 * GET Data paling akhir sebelom NIK dan trans_tgl+trans_jam
			 */
			$sql = "SELECT *
				FROM presensi
				WHERE NIK = '".$row->trans_pengenal."'
					AND UNIX_TIMESTAMP(TJMASUK) < UNIX_TIMESTAMP('".$row->trans_tgl." ".$row->trans_jam."')
				ORDER BY TJMASUK DESC
				LIMIT 1";
			$rs = $this->db->query($sql)->result();
			if(sizeof($rs) > 0){
				foreach($rs as $rowb){
					if(is_null($rowb->TJKELUAR) || empty($rowb->TJKELUAR)){
						//db.presensi.TJKELUAR IS NULL ==> UPDATE record di db.presensi dengan db.presensi.TJKELUAR = trans_tgl + trans_jam
						$datau = array(
							'TJKELUAR' => date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam))
						);
						$this->db->where('ID', $rowb->ID);
						$this->db->update('presensi', $datau);
						
						//update db.absensi.import = 1
						$this->db->where('id', $row->id);
						$this->db->update('absensi', array('import'=>'1'));
					}else{
						//get NAMASHIFT dan SHIFTKE
						$sql = "SELECT shiftjamkerja.NAMASHIFT, shiftjamkerja.SHIFTKE,
								shiftjamkerja.JAMSAMPAI_AWAL, shiftjamkerja.JAMSAMPAI_AKHIR
							FROM shift
							JOIN shiftjamkerja ON(shiftjamkerja.NAMASHIFT = shift.NAMASHIFT)
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
								AND (CAST(DATE_FORMAT(VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
									OR VALIDTO IS NULL
								)
								AND (
									(
										TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AWAL) <= TIME_TO_SEC('".$row->trans_jam."')
										AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AKHIR) >= TIME_TO_SEC('".$row->trans_jam."')
									)
									OR (
										shiftjamkerja.SHIFTKE = '2'
										AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AWAL) <= TIME_TO_SEC('".$row->trans_jam."')
										AND TIME_TO_SEC('23:59:59') >= TIME_TO_SEC('".$row->trans_jam."')
									)
									OR (
										shiftjamkerja.SHIFTKE = '2'
										AND TIME_TO_SEC('00:00:00') <= TIME_TO_SEC('".$row->trans_jam."')
										AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AKHIR) >= TIME_TO_SEC('".$row->trans_jam."')
									)
								)";
						$rs = $this->db->query($sql)->row();
						$namashift = $rs->NAMASHIFT;
						$shiftke = $rs->SHIFTKE;
						
						//db.presensi.TJKELUAR IS NOT NULL ==> CREATE record ke db.presensi dengan db.presensi.TJMASUK = null, db.presensi.TJKELUAR = trans_tgl + trans_jam, db.presensi.TANGGAL = trans_tgl
						$data = array(
							'NIK'		=> $row->trans_pengenal,
							'TJMASUK'	=> null,
							'TANGGAL'	=> $row->trans_tgl,
							'TJKELUAR'	=> date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam)),
							'ASALDATA'	=> 'D',
							'POSTING'	=> null,
							'NAMASHIFT'	=> $namashift,
							'SHIFTKE'	=> $shiftke,
							'ABSENSI_ID'=> $row->id
						);
						$this->db->insert('presensi', $data);
						
						//update db.absensi.import = 1
						$this->db->where('id', $row->id);
						$this->db->update('absensi', array('import'=>'1'));
					}
					
					break;
				}
			}else{
				//get NAMASHIFT dan SHIFTKE
				$sql = "SELECT shiftjamkerja.NAMASHIFT, shiftjamkerja.SHIFTKE,
						shiftjamkerja.JAMSAMPAI_AWAL, shiftjamkerja.JAMSAMPAI_AKHIR
					FROM shift
					JOIN shiftjamkerja ON(shiftjamkerja.NAMASHIFT = shift.NAMASHIFT)
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
						AND (CAST(DATE_FORMAT(VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
							OR VALIDTO IS NULL
						)
						AND (
							(
								TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AWAL) <= TIME_TO_SEC('".$row->trans_jam."')
								AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AKHIR) >= TIME_TO_SEC('".$row->trans_jam."')
							)
							OR (
								shiftjamkerja.SHIFTKE = '2'
								AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AWAL) <= TIME_TO_SEC('".$row->trans_jam."')
								AND TIME_TO_SEC('23:59:59') >= TIME_TO_SEC('".$row->trans_jam."')
							)
							OR (
								shiftjamkerja.SHIFTKE = '2'
								AND TIME_TO_SEC('00:00:00') <= TIME_TO_SEC('".$row->trans_jam."')
								AND TIME_TO_SEC(shiftjamkerja.JAMSAMPAI_AKHIR) >= TIME_TO_SEC('".$row->trans_jam."')
							)
						)";
				$rs = $this->db->query($sql)->row();
				$namashift = $rs->NAMASHIFT;
				$shiftke = $rs->SHIFTKE;
				
				//CREATE record ke db.presensi dengan db.presensi.TJMASUK = null, db.presensi.TJKELUAR = trans_tgl + trans_jam, db.presensi.TANGGAL =  trans_tgl
				$data = array(
					'NIK'		=> $row->trans_pengenal,
					'TJMASUK'	=> null,
					'TANGGAL'	=> $row->trans_tgl,
					'TJKELUAR'	=> date('Y-m-d H:i:s', strtotime($row->trans_tgl.' '.$row->trans_jam)),
					'ASALDATA'	=> 'D',
					'POSTING'	=> null,
					'NAMASHIFT'	=> $namashift,
					'SHIFTKE'	=> $shiftke,
					'ABSENSI_ID'=> $row->id
				);
				$this->db->insert('presensi', $data);
				
				//update db.absensi.import = 1
				$this->db->where('id', $row->id);
				$this->db->update('absensi', array('import'=>'1'));
			}
			
			$cnt++;
			$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Import Successfully...'
		);
		
		return $json;
	}
	 
	public function ImportPresensi_Eko($tglmulai,$tglsampai){
		$DB1 = $this->load->database('default', TRUE);
		$DB2 = $this->load->database('mybase', TRUE);
		$cnt = 0;
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$cnt));
		$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		
		/*$sql = "INSERT INTO absensi (trans_pengenal
			,trans_tgl
			,trans_jam
			,trans_status
			,trans_log
			,`import`)
			SELECT IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
				AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
				CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal)) AS trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status, t2.trans_log, '0'
			FROM mybase.absensi AS t2 
			LEFT JOIN absensi AS t1 ON(t1.trans_pengenal = (IF((SUBSTR(t2.trans_pengenal,1,2) >= 97)
					AND (SUBSTR(t2.trans_pengenal,1,2)<=99),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)-32),t2.trans_pengenal),
					CONCAT(CHAR(SUBSTR(t2.trans_pengenal,1,2)+68),t2.trans_pengenal))) 
				AND t1.trans_tgl = t2.trans_tgl
				AND t1.trans_jam = t2.trans_jam AND t1.trans_status = t2.trans_status)
			WHERE t1.trans_pengenal IS NULL 
				AND t1.trans_tgl IS NULL 
				AND t1.trans_jam IS NULL
				AND t1.trans_status IS NULL
				AND TO_DAYS(t2.trans_tgl) >= TO_DAYS('".$tglmulai."') AND TO_DAYS(t2.trans_tgl) <= TO_DAYS('".$tglsampai."')
			GROUP BY t2.trans_pengenal, t2.trans_tgl, t2.trans_jam, t2.trans_status";*/
		$sql = "INSERT INTO absensi (trans_pengenal, trans_tgl, trans_jam, trans_status, trans_log, import)
		(SELECT distinct (IF((SUBSTR(t1.trans_pengenal,1,2) >= 97)AND(SUBSTR(t1.trans_pengenal,1,2)<=99),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)-32),t1.trans_pengenal),CONCAT(CHAR(SUBSTR(t1.trans_pengenal,1,2)+68),t1.trans_pengenal))) AS trans_pengenal,
		t1.trans_tgl,t1.trans_jam,t1.trans_status,t1.trans_log, '0' AS import
		FROM mybase.absensi AS t1
		WHERE t1.trans_tgl >= DATE('$tglmulai') AND t1.trans_tgl <= DATE('$tglsampai')
		ORDER BY t1.trans_pengenal,t1.trans_tgl, t1.trans_jam)";
		$query = $this->db->query($sql);
		//$total  = $query->num_rows();
		
		/**
		 * DELETE absensi WHERE dbympi.absensi.trans_pengenal tidak ada di karyawan.NIK
		 */
		$sqld = "DELETE FROM absensi
			WHERE trans_pengenal NOT IN (SELECT NIK FROM karyawan WHERE (STATUS='T' OR STATUS='K' OR STATUS='C'))";
		$this->db->query($sqld);
		
		
		$sql = "SELECT a.trans_pengenal,a.trans_tgl,a.trans_jam,a.trans_status,a.trans_log
		FROM absensi a
		INNER JOIN karyawan k ON k.NIK=a.trans_pengenal
		WHERE (a.trans_tgl >= DATE('$tglmulai') AND a.trans_tgl <= DATE('$tglsampai')) AND (k.STATUS='T' OR k.STATUS='K' OR k.STATUS='C') AND a.import='0'
		order by a.trans_pengenal, a.trans_tgl, a.trans_jam, a.trans_status";
		$query_abs = $this->db->query($sql);
		
		$this->db->where(array('PARAMETER' => 'Total Data Import'))->update('init', array('VALUE'=>$query_abs->num_rows()));
		
		/*Prosedur Import Presensi Page 8
		A      = 1 REC (MASUK, TANPA KELUAR) (tergantung data berikutnya)
		A -> B = 1 REC (KELUAR TERISI -> NORMAL) (proses sempurna)
		A -> A = 2 REC (TANPA KELUAR) (rec ke-2 tergantung data berikutnya)
		B      = 1 REC (KELUAR, TANPA MASUK) (tak tergantung data berikutnya)*/
		
		$ketemuA = false;
		$ketemuB = false;
		/*
		$now = date('Y-m-d');
		$nshift = '';
		$shiftke = '';
		
		$range = $this->db->query("SELECT VALUE FROM INIT WHERE PARAMETER = 'Range'")->result();
		
		$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
		sj.JAMDARI,sj.JAMSAMPAI,
		((DATE_SUB(TIMESTAMP(s.VALIDFROM,sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
		((DATE_ADD(TIMESTAMP(s.VALIDFROM,sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
		((DATE_SUB(TIMESTAMP(s.VALIDFROM,sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
		((DATE_ADD(TIMESTAMP(s.VALIDFROM,sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
		FROM shift s
		RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
		WHERE (s.VALIDFROM <= DATE('".$now."') AND s.VALIDTO >= DATE('".$now."')) AND (sj.JENISHARI='N')";
		$hasil = $this->db->query($sqlshift)->result();
		//$this->firephp->info($sqlshift);
		
		foreach($query->result_array() as $val)
		{		
			if(!$ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A simpan NIK ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				//$this->firephp->info($this->jam1);
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					foreach($hasil as $v)
					{
						if(date('Y-m-d H:i:s',strtotime($val['trans_tgl']." ".$val['trans_jam']) >= $v->JAMDARI_AWAL && date('Y-m-d H:i:s',strtotime($val['trans_tgl']." ".$val['trans_jam']) <= $v->JAMDARI_AKHIR)
						{
							$nshift = $v->NAMASHIFT;
							$shiftke = $v->SHIFTKE;
							break;
						}
					}
					
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => $nshift,
					   'SHIFTKE' => $shiftke,
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
					//Insert Record A->B jika NIK berbeda
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);				
					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						foreach($hasil as $v)
						{
							if($val['trans_jam'] >= $v->JAMSAMPAI_AWAL && $val['trans_jam'] <= $v->JAMSAMPAI_AKHIR)
							{
								$nshift = $v->NAMASHIFT;
								$shiftke = $v->SHIFTKE;
								break;
							}
						}
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TANGGAL' => $val['trans_tgl'],
						   'NAMASHIFT' => $nshift,
						   'SHIFTKE' => $shiftke,
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
				//Record Baru A->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					foreach($hasil as $v)
					{
						if($val['trans_jam'] >= $v->JAMDARI_AWAL && $val['trans_jam'] <= $v->JAMDARI_AKHIR)
						{
							$nshift = $v->NAMASHIFT;
							$shiftke = $v->SHIFTKE;
							break;
						}
					}
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => $nshift,
					   'SHIFTKE' => $shiftke,
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
					foreach($hasil as $v)
					{
						if($val['trans_jam'] >= $v->JAMDARI_AWAL && $val['trans_jam'] <= $v->JAMDARI_AKHIR)
						{
							$nshift = $v->NAMASHIFT;
							$shiftke = $v->SHIFTKE;
							break;
						}
					}
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
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
				//Record Baru B->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = $val['trans_tgl']." ".$val['trans_jam'];
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					foreach($hasil as $v)
					{
						if($val['trans_jam'] >= $v->JAMDARI_AWAL && $val['trans_jam'] <= $v->JAMDARI_AKHIR)
						{
							$nshift = $v->NAMASHIFT;
							$shiftke = $v->SHIFTKE;
							break;
						}
					}
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => $nshift,
					   'SHIFTKE' => $shiftke,
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
					foreach($hasil as $v)
					{
						if($val['trans_jam'] >= $v->JAMSAMPAI_AWAL && $val['trans_jam'] <= $v->JAMSAMPAI_AKHIR)
						{
							$nshift = $v->NAMASHIFT;
							$shiftke = $v->SHIFTKE;
							break;
						}
					}
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
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
			
		}*/
		
		$range = $this->db->query("SELECT VALUE FROM INIT WHERE PARAMETER = 'Range'")->result();
		
		foreach($query_abs->result_array() as $val)
		{
			/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
			sj.JAMDARI,sj.JAMSAMPAI,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
			((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
			((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
			FROM shift s
			RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
			$hasil = $this->db->query($sqlshift)->result();*/
			//$this->firephp->info($sqlshift);
		
			if(!$ketemuA && $val['trans_status'] == "A")
			{
				//Record Baru A simpan NIK ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam']));
				//$this->firephp->info($this->jam1);
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])));
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,
						((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
						((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
						((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
						((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
						AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
						)
						AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
					$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
					$hasil = $this->db->query($sqlshift)->result();
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
					   'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
					   'TJKELUAR' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam']))
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
					//Insert Record A->B jika NIK berbeda
					$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam']);				
					if($DB1->get_where('presensi', $array)->num_rows() <= 0)
					{
						/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
						sj.JAMDARI,sj.JAMSAMPAI,
						((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
						((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR,
						((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
						((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
						FROM shift s
						RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
						WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
							AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
							AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
								>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
								AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
								<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							)
							AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
						$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
						sj.JAMDARI,sj.JAMSAMPAI,
						((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
						((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR,
						((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
						((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
						FROM shift s
						RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
						WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
						$hasil = $this->db->query($sqlshift)->result();
						$data = array(
						   'NIK' => $val['trans_pengenal'],
						   'TANGGAL' => $val['trans_tgl'],
						   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
						   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
						   'TJMASUK' => null,
						   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
						   'TJKELUAR' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
				//Record Baru A->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam']));
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])));
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
						AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
						)
						AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
					$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
					$hasil = $this->db->query($sqlshift)->result();
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
					   'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])));
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
						AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
						)
						AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
						
					$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
					$hasil = $this->db->query($sqlshift)->result();
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
					   'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
					   'TJKELUAR' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
				//Record Baru B->A simpan NIK $id2 ke $id1
				$this->id1 = $val['trans_pengenal'];
				$this->jam1 = date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam']));
				
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])));
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
						AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
						)
						AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
					$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
					$hasil = $this->db->query($sqlshift)->result();
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
					   'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
				$array = array('NIK' => $val['trans_pengenal'], 'TJMASUK' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])));
				
				if($DB1->get_where('presensi', $array)->num_rows() <= 0)
				{
					/*$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMDARI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (CAST(DATE_FORMAT(s.VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT(s.VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$val['trans_tgl']."','%Y%m%d') AS UNSIGNED))
						AND (UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							>= UNIX_TIMESTAMP(DATE_SUB(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
							AND UNIX_TIMESTAMP(STR_TO_DATE('".$val['trans_tgl']." ".$val['trans_jam']."','%Y-%m-%d %H:%i:%s'))
							<= UNIX_TIMESTAMP(DATE_ADD(STR_TO_DATE(CONCAT('".$val['trans_tgl']."',' ',sj.JAMSAMPAI),'%Y-%m-%d %H:%i:%s'),INTERVAL ".$range[0]->VALUE." HOUR))
						)
						AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";*/
					$sqlshift = "SELECT s.NAMASHIFT,s.VALIDFROM,s.VALIDTO,sj.SHIFTKE,sj.JENISHARI,
					sj.JAMDARI,sj.JAMSAMPAI,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMDARI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMDARI_AKHIR,
					((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AWAL,
					((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AS JAMSAMPAI_AKHIR
					FROM shift s
					RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
					WHERE (s.VALIDFROM <= DATE('".$val['trans_tgl']."') AND s.VALIDTO >= DATE('".$val['trans_tgl']."')) AND (TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') >= ((DATE_SUB(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR))) AND TIMESTAMP('".$val['trans_tgl']."','".$val['trans_jam']."') <= ((DATE_ADD(TIMESTAMP('".$val['trans_tgl']."',sj.JAMSAMPAI),INTERVAL ".$range[0]->VALUE." HOUR)))) AND sJ.JENISHARI=IF(DAYNAME('".$val['trans_tgl']."')='Friday','J','N')";
					$hasil = $this->db->query($sqlshift)->result();
					$data = array(
					   'NIK' => $val['trans_pengenal'],
					   'TANGGAL' => $val['trans_tgl'],
					   'NAMASHIFT' => (sizeof($hasil) > 0 ? $hasil[0]->NAMASHIFT : null),
					   'SHIFTKE' => (sizeof($hasil) > 0 ? $hasil[0]->SHIFTKE : null),
					   'TJMASUK' => null,
					   //'TJMASUK' => $val['trans_tgl']." ".$val['trans_jam'],
					   'TJKELUAR' => date('Y-m-d H:i:s', strtotime($val['trans_tgl']." ".$val['trans_jam'])),
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
			$cnt ++;
			$this->db->where(array('PARAMETER' => 'Counter'))->update('init', array('VALUE'=>$cnt));
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Import Successfully...'
		);
		
		return $json;
	}
	
	public function getProsesImport(){
		$totalData = $this->db->get_where('init',array('PARAMETER' => 'Total Data Import'))->result();
		$totalProses = $this->db->get_where('init',array('PARAMETER' => 'Counter'))->result();
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Total Data...',
			'totalData' => $totalData[0]->VALUE,
			'totalProses' => $totalProses[0]->VALUE
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
		
		//$pkey = array('ID'=>$data->ID,'NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)),'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		$pkey = array('ID'=>$data->ID);
	
		//$this->firephp->info($data->TJMASUK);
		//$this->firephp->info(date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		
		if($this->db->get_where('presensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'TANGGAL'=>trim($data->TANGGAL),
				'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),
				'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),
				'NAMASHIFT'=>$data->NAMASHIFT,
				'SHIFTKE'=>$data->SHIFTKE,
				'ASALDATA'=>$data->ASALDATA,
				'POSTING'=>$data->POSTING,
				'USERNAME'=>$this->session->userdata('user_name')
			);
			$this->db->where($pkey)->update('presensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'NIK'=>$data->NIK,
				'TANGGAL'=>trim($data->TANGGAL),
				'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),
				'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),
				'NAMASHIFT'=>$data->NAMASHIFT,
				'SHIFTKE'=>$data->SHIFTKE,
				'ASALDATA'=>$data->ASALDATA,
				'POSTING'=>$data->POSTING,
				'USERNAME'=>$this->session->userdata('user_name')
			);
			$this->db->insert('presensi', $arrdatac);
			$masterid = $this->db->select_max('ID')
				->where('USERNAME', $this->session->userdata('user_name'))
				->get('presensi')->row()->ID;
			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE,
				sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME, (IF(ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300,'Y','N')) AS STATUS
				FROM presensi p
				INNER JOIN karyawan k ON k.NIK=p.NIK
				INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
				INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
				INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
				WHERE ID = ".$masterid;
			$last = $this->db->query($sql)->row();
			
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
		//$pkey = array('ID'=>$data->ID);
		//$pkey = array('NIK'=>$data->NIK,'TJMASUK'=>date('Y-m-d H:i:s', strtotime($data->TJMASUK)));
		//$this->firephp->log(sizeof($data));
		//$this->db->where($pkey)->delete('presensi');
		$total = 0;
		if(sizeof($data) > 1){
			foreach($data as $row){
				$this->db->where('ID', $row->ID)->delete('presensi');
				$total++;
			}
		}else{
			$this->db->where('ID', $data->ID)->delete('presensi');
			$total++;
		}
		
		//$total  = $this->db->get('presensi')->num_rows();
		//$last = $this->db->get('presensi')->result();
		$last = $data;
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function setTukarShift($data){
		$last   = NULL;
		$pkey = array('NIK'=>$data->NIK,'TGLMULAI'=>$data->TANGGAL);
			
		/*
		 * Process Insert
		 */
		
		$arrdatac = array('NIK'=>$data->NIK,'TGLMULAI'=>trim($data->TANGGAL),'TGLSAMPAI'=>trim($data->TANGGAL),'NAMASHIFT'=>trim($data->NAMASHIFT),'NAMASHIFT2'=>trim($data->NAMASHIFT2),'SHIFTKE'=>trim($data->SHIFTKE),'SHIFTKE2'=>trim($data->SHIFTKE2));
		 
		$this->db->insert('tukarshift', $arrdatac);
		$last   = $this->db->where($pkey)->get('tukarshift')->row();
			
		$total  = $this->db->get('tukarshift')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
		);
		
		return $json;
	}
	
	function setMasuk($tglmulai, $tglsampai){
		$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
			FROM presensi p 
			JOIN (
				SELECT
					s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
					sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
				FROM
					shift s
				RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
			WHERE (p.TJMASUK IS NULL) AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
				AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai'))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJKELUAR=p.TJKELUAR
		SET 
			p.TJMASUK = TIMESTAMP(p.TANGGAL,t3.JAMDARI)";
		$query  = $this->db->query($sql);
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data has been updated"
		);
		
		return $json;
	}
	
	function set_tjmasuk($data){
		$result = 0;
		foreach($data as $row){
			if(strlen(trim($row->TJMASUK)) == 0){
				$sql = "UPDATE presensi p
					INNER JOIN (
						SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
						FROM presensi p 
						JOIN (
							SELECT
								s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
								sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
							FROM
								shift s
							RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
						) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
						WHERE p.ID = ".$row->ID."
							AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
					) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJKELUAR=p.TJKELUAR
					SET p.TJMASUK = IF(t3.JAMSAMPAI > t3.JAMDARI, CONCAT(p.TANGGAL, ' ', t3.JAMDARI), CONCAT(DATE_ADD(p.TANGGAL, INTERVAL -1 DAY), ' ', t3.JAMDARI)),
						p.TANGGAL = IF(t3.JAMSAMPAI > t3.JAMDARI, p.TANGGAL, DATE_ADD(p.TANGGAL, INTERVAL -1 DAY))";
				$query  = $this->db->query($sql);
				
				$result++;
			}
		}
		/*$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
			FROM presensi p 
			JOIN (
				SELECT
					s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
					sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
				FROM
					shift s
				RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
			WHERE (p.TJMASUK IS NULL) AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
				AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai'))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJKELUAR=p.TJKELUAR
		SET 
			p.TJMASUK = TIMESTAMP(p.TANGGAL,t3.JAMDARI)";
		$query  = $this->db->query($sql);*/
		
		if($result == 0){
			$json	= array(
				'success'   => TRUE,
				'message'   => "Data yang terpilih tidak ada yang diubah."
			);
		}else{
			$json	= array(
				'success'   => TRUE,
				'message'   => "Data yang terpilih sudah diubah."
			);
		}
		
		return $json;
	}
	
	function setKeluar($tglmulai, $tglsampai){
		$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
			FROM presensi p 
			JOIN (
				SELECT
					s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
					sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
				FROM
					shift s
				RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
			WHERE (p.TJKELUAR IS NULL) AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
				AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai'))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJMASUK=p.TJMASUK
		SET 
			p.TJKELUAR = TIMESTAMP(p.TANGGAL,t3.JAMSAMPAI)";
		$query  = $this->db->query($sql);
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data has been updated"
		);
		
		return $json;
	}
	
	function set_tjkeluar($data){
		$result = 0;
		foreach($data as $row){
			if(strlen(trim($row->TJKELUAR)) == 0){
				$sql = "UPDATE presensi p
					INNER JOIN (
						SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
						FROM presensi p 
						JOIN (
							SELECT
								s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
								sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
							FROM
								shift s
							RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
						) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
						WHERE p.ID = ".$row->ID."
							AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
					) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJMASUK=p.TJMASUK
					SET p.TJKELUAR = IF(t3.JAMSAMPAI > t3.JAMDARI, CONCAT(p.TANGGAL,' ',t3.JAMSAMPAI), CONCAT(DATE_ADD(p.TANGGAL, INTERVAL 1 DAY), ' ', t3.JAMSAMPAI))";
				$query  = $this->db->query($sql);
				
				$result++;
			}
		}
		
		/*$sql = "UPDATE presensi p
		INNER JOIN (
			SELECT p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,t1.JENISHARI,t1.JAMDARI,t1.JAMSAMPAI
			FROM presensi p 
			JOIN (
				SELECT
					s.VALIDFROM,s.VALIDTO,sj.NAMASHIFT,sj.SHIFTKE,sj.JENISHARI,sj.JAMDARI,sj.JAMSAMPAI,sj.JAMREHAT0M,sj.JAMREHAT0S,
					sj.JAMREHAT1M,sj.JAMREHAT1S,sj.JAMREHAT2M,sj.JAMREHAT2S,sj.JAMREHAT3M,sj.JAMREHAT3S,sj.JAMREHAT4M,sj.JAMREHAT4S
				FROM
					shift s
				RIGHT JOIN shiftjamkerja sj ON sj.NAMASHIFT=s.NAMASHIFT
			) AS t1 ON t1.NAMASHIFT=p.NAMASHIFT AND t1.SHIFTKE=p.SHIFTKE AND t1.JENISHARI=IF(DAYNAME(p.TANGGAL) = 'Friday','J','N')
			WHERE (p.TJKELUAR IS NULL) AND (p.TANGGAL >= t1.VALIDFROM AND p.TANGGAL <= t1.VALIDTO)
				AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai'))
		) AS t3 ON t3.NIK=p.NIK AND t3.TANGGAL=p.TANGGAL AND t3.NAMASHIFT=p.NAMASHIFT AND t3.SHIFTKE=p.SHIFTKE AND t3.TJMASUK=p.TJMASUK
		SET 
			p.TJKELUAR = TIMESTAMP(p.TANGGAL,t3.JAMSAMPAI)";
		$query  = $this->db->query($sql);*/
		
		if($result == 0){
			$json	= array(
				'success'   => TRUE,
				'message'   => "Data yang terpilih tidak ada yang diubah."
			);
		}else{
			$json	= array(
				'success'   => TRUE,
				'message'   => "Data yang terpilih sudah diubah."
			);
		}
		
		return $json;
	}
	
	function getShift($nshift,$tgls){
		$sql = "SELECT NAMASHIFT,SHIFTKE,JENISHARI,JAMDARI,JAMSAMPAI
		FROM shiftjamkerja
		WHERE NAMASHIFT='".$nshift."' AND JENISHARI=(IF(DAYNAME('".$tgls."')= 'Friday','J','N'))
		ORDER BY NAMASHIFT,SHIFTKE";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->query($sql)->num_rows();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Data Shift Loaded",
			'total'     => $total,
			'data'      => $data
		);
		
		return $json;
	}
	
	function getAllData($tglmulai, $tglsampai,$saring,$sorts,$filters,$start, $page, $limit){
		if($saring == "Log Kosong")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC, p.TANGGAL ASC';
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
					$ks .= $prop." ".$dir.",";
				}
				$ks = substr($ks,0,strlen($ks) -1);
				//$dsort = $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " (p.TJKELUAR IS NULL OR p.TJMASUK IS NULL OR p.TJKELUAR=p.TJMASUK) AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')) ";
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
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
			

			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,
				p.NAMASHIFT, p.SHIFTKE, sjk.JAMDARI, sjk.JAMSAMPAI, p.TJMASUK, p.TJKELUAR,
				p.ASALDATA, p.POSTING, p.USERNAME,
				(IF(ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300,'Y','N')) AS STATUS,
				LOWER(DAYNAME(p.TANGGAL)) AS NAMAHARI, kalib.JENISLIBUR
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN kalenderlibur kalib ON(kalib.TANGGAL = p.TANGGAL)
			WHERE ".$where;
			
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;	
			$query = $this->db->query($sql);
			//$this->firephp->log($sql);
			
			$total  = $this->db->query("SELECT p.ID,COUNT(p.NIK) AS total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE,
			sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Log Dobel")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK,p.TANGGAL ASC';
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
					$ks .= $prop." ".$dir.",";
				}
				$ks = substr($ks,0,strlen($ks) -1);
				//$dsort = $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " 0=0 AND (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')) ";
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
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
						case 'string' : $qs .= " AND (".$field." LIKE '%".$value."%' OR k.NAMAKAR LIKE '%".$value."%')"; Break;
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
			
			
			$sql = "select p.ID,p.NIK, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, kk.NAMAKEL, p.NAMASHIFT,
				p.SHIFTKE, sjk.JAMDARI, sjk.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA,
				p.POSTING, p.USERNAME,
				(IF(ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300,'Y','N')) AS STATUS,
				LOWER(DAYNAME(p.TANGGAL)) AS NAMAHARI, kalib.JENISLIBUR
			from presensi p
			RIGHT JOIN 
			(
				select t1.TANGGAL, t1.NIK
				from 
				(
				select TANGGAL, NIK, count(*) as jml
				from presensi
				group by TANGGAL, NIK
				) as t1
				where t1.jml > 1
			) as t9
			on p.NIK=t9.NIK AND p.TANGGAL=t9.TANGGAL
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN kalenderlibur kalib ON(kalib.TANGGAL = p.TANGGAL)
			WHERE ".$where;
			
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql);
			
			$total  = $this->db->query("select p.ID,COUNT(p.NIK)AS total, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, p.NAMASHIFT, p.SHIFTKE, sjk.JAMDARI, sjk.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			RIGHT JOIN 
			(
				select t1.TANGGAL, t1.NIK
				from 
				(
				select TANGGAL, NIK, count(*) as jml
				from presensi
				group by TANGGAL, NIK
				) as t1
				where t1.jml > 1
			) as t9
			on p.NIK=t9.NIK AND p.TANGGAL=t9.TANGGAL
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Salah Shift")
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK,p.TANGGAL ASC';
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
					$ks .= $prop." ".$dir.",";
				}
				$ks = substr($ks,0,strlen($ks) -1);
				//$dsort = $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = ' (ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300) ';
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'TANGGAL')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
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
			
			
			$sql = "select p.ID,p.NIK, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, kk.NAMAKEL, u.SINGKATAN, p.NAMASHIFT, p.SHIFTKE, sjk.JAMDARI, sjk.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME, (IF(ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300,'Y','N')) AS STATUS
			from presensi p
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where;
			
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;	
			$query = $this->db->query($sql);
			
			$total  = $this->db->query("select p.ID,count(p.NIK)as total, p.TANGGAL, k.NAMAKAR,u.NAMAUNIT, u.SINGKATAN, p.NAMASHIFT, p.SHIFTKE, sjk.JAMDARI, sjk.JAMSAMPAI, p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			from presensi p
			INNER JOIN karyawan k on k.NIK=p.NIK
			INNER JOIN unitkerja u on u.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query->result() as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		elseif($saring == "Range" && $filters == null)
		{
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = ' p.NIK ASC, p.TANGGAL ASC';
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
					$ks .= $prop." ".$dir.",";
				}
				$ks = substr($ks,0,strlen($ks) -1);
				//$dsort = $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')) ";
			$qs = '';

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
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
			
			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN, kk.NAMAKEL, p.TANGGAL, p.TJMASUK,
				p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,sjk.JAMDARI,sjk.JAMSAMPAI, p.ASALDATA,
				p.POSTING, LOWER(DAYNAME(p.TANGGAL)) AS NAMAHARI, kalib.JENISLIBUR
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN kalenderlibur kalib ON(kalib.TANGGAL = p.TANGGAL)
			WHERE ".$where;
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql)->result();
			
			$total  = $this->db->query("SELECT p.ID,count(p.NIK) as total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN, p.TANGGAL, p.TJMASUK, p.TJKELUAR,p.NAMASHIFT,p.SHIFTKE,sjk.JAMDARI,sjk.JAMSAMPAI, p.ASALDATA, p.POSTING
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
			$data   = array();
			foreach($query as $result){
				$data[] = $result;
			}
			
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total[0]->total,
				'data'      => $data
			);
			
			return $json;
		}
		else
		{			
			if (is_array($sorts)) {
				$encoded = false;
			} else {
				$encoded = true;
				$sorts = json_decode($sorts);
			}
			$dsort = " p.NIK ASC, p.TANGGAL ASC";
			$ks = "";
			
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
					$ks .= $prop." ".$dir.",";
				}
				$ks = substr($ks,0,strlen($ks) -1);
				//$dsort = $ks;
			}
			//$this->firephp->info($dsort);

			// GridFilters sends filters as an Array if not json encoded
			if (is_array($filters)) {
				$encoded = false;
			} else {
				$encoded = true;
				$filters = json_decode($filters);
			}

			$where = " (p.TANGGAL >= DATE('$tglmulai') AND p.TANGGAL <= DATE('$tglsampai')) ";
			$qs = "";

			// loop through filters sent by client
			if (is_array($filters)) {
				for ($i=0;$i<count($filters);$i++){
					$filter = $filters[$i];

					// assign filter data (location depends if encoded or not)
					if ($encoded) {
						if($filter->field == 'NIK')
							$field = "p.".$filter->field;
						elseif($filter->field == 'SHIFTKE')
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
						case 'string' : $qs .= " AND (".$field." LIKE '%".$value."%' OR k.NAMAKAR LIKE '%".$value."%')"; Break;
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
			
			$sql = "SELECT p.ID,p.NIK, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE,
			sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME,
			(IF(ABS(TIMESTAMPDIFF(MINUTE,TIMESTAMP(p.TANGGAL,sjk.JAMDARI),p.TJMASUK)) >= 300,'Y','N')) AS STATUS,
			LOWER(DAYNAME(p.TANGGAL)) AS NAMAHARI, kalib.JENISLIBUR
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			LEFT JOIN kalenderlibur kalib ON(kalib.TANGGAL = p.TANGGAL)
			WHERE ".$where;
			
			//$sql .= " ORDER BY k.NAMAKAR ASC,p.TANGGAL ASC";
			//$sql .= " ORDER BY ".$sortProperty." ".$sortDirection."";
			$sql .= " GROUP BY p.NIK,p.TANGGAL,p.TJMASUK,p.TJKELUAR ";
			$sql .= " ORDER BY ".$dsort;
			$sql .= " LIMIT ".$start.",".$limit;
			$query = $this->db->query($sql)->result();
			//$total = $query->num_rows();
			
			$total  = $this->db->query("SELECT p.ID, COUNT(p.NIK) AS total, k.NAMAKAR,uk.NAMAUNIT,uk.SINGKATAN,kk.NAMAKEL, p.TANGGAL,p.NAMASHIFT,p.SHIFTKE,
			sjk.JAMDARI,sjk.JAMSAMPAI,p.TJMASUK, p.TJKELUAR, p.ASALDATA, p.POSTING, p.USERNAME
			FROM presensi p
			INNER JOIN karyawan k ON k.NIK=p.NIK
			INNER JOIN unitkerja uk ON uk.KODEUNIT=k.KODEUNIT
			INNER JOIN kelompok	kk ON kk.KODEKEL=k.KODEKEL
			INNER JOIN shiftjamkerja sjk ON sjk.NAMASHIFT=p.NAMASHIFT AND sjk.SHIFTKE=p.SHIFTKE AND sjk.JENISHARI=(IF(DAYNAME(p.TANGGAL) = 'Friday','J','N'))
			WHERE ".$where)->result();
			
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
	
	function get_shift($datetime){
		$time = date('H:i:s', strtotime($datetime));
		$dayofweek = (date('w', strtotime($datetime)) == 5 ? 'J' : 'N');
		$datenow = date('Y-m-d');
		$sql = "SELECT shiftjamkerja.NAMASHIFT, shiftjamkerja.SHIFTKE,
				shiftjamkerja.JAMDARI_AWAL, shiftjamkerja.JAMDARI_AKHIR, shiftjamkerja.JAMDARI,
				shiftjamkerja.JAMSAMPAI
			FROM shift
			JOIN shiftjamkerja ON(shiftjamkerja.NAMASHIFT = shift.NAMASHIFT)
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$datetime."','%Y%m%d') AS UNSIGNED)
				AND (CAST(DATE_FORMAT(VALIDTO,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$datetime."','%Y%m%d') AS UNSIGNED)
					OR VALIDTO IS NULL
				)
				AND (
					(
						TIME_TO_SEC(shiftjamkerja.JAMDARI_AWAL) <= TIME_TO_SEC('".$time."')
						AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AKHIR) >= TIME_TO_SEC('".$time."')
					)
					OR (
						shiftjamkerja.SHIFTKE = '3'
						AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AWAL) <= TIME_TO_SEC('".$time."')
						AND TIME_TO_SEC('23:59:59') >= TIME_TO_SEC('".$time."')
					)
					OR (
						shiftjamkerja.SHIFTKE = '3'
						AND TIME_TO_SEC('00:00:00') <= TIME_TO_SEC('".$time."')
						AND TIME_TO_SEC(shiftjamkerja.JAMDARI_AKHIR) >= TIME_TO_SEC('".$time."')
					)
				)
				AND shiftjamkerja.JENISHARI = '".$dayofweek."'
			ORDER BY SHIFTKE DESC";
		$query = $this->db->query($sql);
		
		$data   = array();
		foreach($query->result() as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'data'      => $data
		);
		return $json;
	}
	
	/**
	 * Fungsi	: do_upload
	 *
	 * Untuk menginjeksi data dari Excel ke Database
	 *
	 * @param array $data
	 * @return array
	 */
	function do_upload($data, $filename){
		if(sizeof($data) > 0){
			$p = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				if($p>0){
					break;
				}
				
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$skeepdata = 0;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							//$validfrom = PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
							$namashift = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$shiftke = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$tjmasuk = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd hh:ii:ss');
							$tanggal = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 'yyyy-mm-dd');
							$tjkeluar = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(5, $row)->getValue(), 'yyyy-mm-dd hh:ii:ss');
							$asaldata = 'D';
						}
						
						$data = array(
							'NIK'		=> $nik,
							'NAMASHIFT'	=> $namashift,
							'SHIFTKE'	=> $shiftke,
							'TJMASUK'	=> $tjmasuk,
							'TANGGAL'	=> $tanggal,
							'TJKELUAR'	=> $tjkeluar,
							'ASALDATA'	=> $asaldata
						);
						if($this->db->get_where('presensi', array('NIK'=>$nik, 'TANGGAL'=>date('Y-m-d', strtotime($tanggal))))->num_rows() == 0){
							$this->db->insert('presensi', $data);
						}else{
							$skeepdata++;
						}
						
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> $skeepdata
			);
			return $success;
		}else{
			$error = array(
				'success'	=> false,
				'msg'		=> 'Tidak ada proses, karena data kosong.',
				'filename'	=> $filename
			);
			return $error;
		}
	}
}
?>