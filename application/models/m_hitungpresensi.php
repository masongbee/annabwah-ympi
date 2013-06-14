<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_hitungpresensi
 * 
 * Table	: hitungpresensi
 *  
 * @author masongbee
 *
 */
class M_hitungpresensi extends CI_Model{

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
	 
	function JamKerja($bulangaji){
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
	 
	function get_periodegaji(){
		$sql = "SELECT periodegaji.BULAN,
				CONCAT(bulan.bulan_nama,', ',SUBSTRING(periodegaji.BULAN,1,4)) AS BULAN_GAJI,
				TGLMULAI, TGLSAMPAI
			FROM periodegaji JOIN bulan ON(bulan.bulan_kode = SUBSTRING(periodegaji.BULAN,-2))";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->get('periodegaji')->num_rows();
		
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
	
	function HitungPresensi($bulan, $tglmulai, $tglsampai){
		/*
		 * Langkah memproses Perhitungan Presensi untuk seluruh Karyawan
		 * 1. Persiapkan data Karyawan dalam db.gajibulanan dan db.detilgaji
		 * 1.a. cek db.gajibulanan.BULAN => apakah bulan gaji yang akan dihitung sudah ada, jika belum maka insert seluruh db.karyawan dengan status = 'T' or 'K' or'C'
		 * 1.b. cek db.detilgaji.BULAN => apakah bulan gaji yang akan dihitung sudah ada, jika belum maka insert seluruh db.karyawan dengan status = 'T' or 'K' or'C'
		 * 
		 * 2. Hitung Upah Pokok [db.upahpokok]
		 * 2.a. dapatkan satu tanggal paling awal ketemu di db.upahpokok.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 2.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 2.a. berdasarkan db.upahpokok.NOURUT
		 * 2.b. looping hasil 2.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian upah pokok: 1.GRADE, 2.KODEJAB, 3.GRADE+KODEJAB, 4.NIK
		 * 
		 * 3.Hitung Tunjangan Pekerjaan [db.tpekerjaan]
		 * 3.a.dapatkan satu tanggal paling awal ketemu di db.tpekerjaan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 3.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 3.a. berdasarkan db.tpekerjaan.NOURUT
		 * 3.b. looping hasil 3.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tpekerjaan: 1.GRADE 2.KATPEKERJAAN 3.GRADE+KATPEKERJAAN 4.NIK
		 * 
		 * 4.Hitung Tunjangan Bhs Jepang [db.tbhs]
		 * 4.a.dapatkan satu tanggal paling awal ketemu di db.tbhs.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 4.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 4.a. berdasarkan db.tbhs.NOURUT
		 * 4.b. looping hasil 4.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tbhs: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 5.Hitung Tunjangan Jabatan [db.tjabatan]
		 * 5.a.dapatkan satu tanggal paling awal ketemu di db.tjabatan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 5.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 5.a. berdasarkan db.tjabatan.NOURUT
		 * 5.b. looping hasil 5.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tjabatan: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 6.Hitung Tunjangan Keluarga [db.tkeluarga]
		 * 6.a.dapatkan satu tanggal paling awal ketemu di db.tjabatan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 6.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 6.a. berdasarkan db.tkeluarga.NOURUT
		 * 6.b. looping hasil 6.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tjabatan: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 * 
		 * 7.Hitung Tunjangan Pekerjaan [db.tpekerjaan]
		 * 7.a.dapatkan satu tanggal paling awal ketemu di db.tpekerjaan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 7.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 7.a. berdasarkan db.tpekerjaan.NOURUT
		 * 7.b. looping hasil 7.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tpekerjaan: 1.GRADE 2.KODEJAB 7.GRADE+KODEJAB 4.NIK
		 * 
		 * 99. selesai update db.detilgaji, maka hitung total gaji setiap karyawan di db.detilgaji dan dimasukkan ke db.gajibulanan
		 */
		/* 1.a. */
		if($this->db->get_where('gajibulanan', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->gen_gajibulanan($bulan);
		}
		
		/* 1.b. */
		if($this->db->get_where('detilgaji', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->gen_detilgaji($bulan, $tglmulai, $tglsampai);
		}
		
		/* 2.a. */
		$sql_upahpokok = "SELECT *
			FROM upahpokok
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM upahpokok WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_upahpokok = $this->db->query($sql_upahpokok)->result();
		
		/* 2.b. */
		if(sizeof($records_upahpokok) > 0){
			/* proses looping upah pokok */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_upahpokok as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->RPUPAHPOKOK = $record->RPUPAHPOKOK;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPUPAHPOKOK = $record->RPUPAHPOKOK;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPUPAHPOKOK = $record->RPUPAHPOKOK;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->RPUPAHPOKOK = $record->RPUPAHPOKOK;
					array_push($nik_arr, $obj);
					
				}
			}
			/* urutan upah pokok ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpupahpokok_bygrade($bulan, $grade_arr);
			/* urutan upah pokok ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpupahpokok_bykodejab($bulan, $kodejab_arr);
			/* urutan upah pokok ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan upah pokok ke-4 berdasarkan NIK */
			$this->update_detilgaji_rpupahpokok_bynik($bulan, $nik_arr);
		}
		
		/* 3.a. */
		$sql_rptpekerjaan = "SELECT *
			FROM tpekerjaan
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tpekerjaan WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rptpekerjaan = $this->db->query($sql_rptpekerjaan)->result();
		
		/* 3.b. */
		if(sizeof($records_rptpekerjaan) > 0){
			/* proses looping rptpekerjaan */
			$grade_arr = array();
			$katpekerjaan_arr = array();
			$gradekatpekerjaan_arr = array();
			$nik_arr = array();
			
			foreach($records_rptpekerjaan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KATPEKERJAAN)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KATPEKERJAAN)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KATPEKERJAAN = $record->KATPEKERJAAN;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($katpekerjaan_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KATPEKERJAAN)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KATPEKERJAAN = $record->KATPEKERJAAN;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($gradekatpekerjaan_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KATPEKERJAAN)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptpekerjaan ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptpekerjaan_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rptpekerjaan ke-2 berdasarkan KATPEKERJAAN */
			$this->update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $tglmulai, $tglsampai, $katpekerjaan_arr);
			/* urutan rptpekerjaan ke-3 berdasarkan GRADE+KATPEKERJAAN */
			$this->update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $tglmulai, $tglsampai, $gradekatpekerjaan_arr);
			/* urutan rptpekerjaan ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptpekerjaan_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
		}
		
		/* 4.a. */
		$sql_rptbhs = "SELECT *
			FROM tbhs
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tbhs WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rptbhs = $this->db->query($sql_rptbhs)->result();
		
		/* 4.b. */
		if(sizeof($records_rptbhs) > 0){
			/* proses looping rptbhs */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			
			foreach($records_rptbhs as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($gradekodejab_arr, $obj);
					
				}
			}
			
			/* urutan rptbhs ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptbhs_bygrade($bulan, $grade_arr);
			/* urutan rptbhs ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr);
			/* urutan rptbhs ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr);
		}
		
		/* 5.a. */
		$sql_rptjabatan = "SELECT *
			FROM tjabatan
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tjabatan WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rptjabatan = $this->db->query($sql_rptjabatan)->result();
		
		/* 5.b. */
		if(sizeof($records_rptjabatan) > 0){
			/* proses looping rptjabatan */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptjabatan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->RPTJABATAN = $record->RPTJABATAN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPTJABATAN = $record->RPTJABATAN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->RPTJABATAN = $record->RPTJABATAN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->RPTJABATAN = $record->RPTJABATAN;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptjabatan ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptjabatan_bygrade($bulan, $grade_arr);
			/* urutan rptjabatan ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptjabatan_bykodejab($bulan, $kodejab_arr);
			/* urutan rptjabatan ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptjabatan_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rptjabatan ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptjabatan_bygradekodejab($bulan, $nik_arr);
		}
		
		/* 6.a. */
		$sql_rptkeluarga = "SELECT *
			FROM tkeluarga
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tkeluarga WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rptkeluarga = $this->db->query($sql_rptkeluarga)->result();
		
		/* 6.b. */
		if(sizeof($records_rptkeluarga) > 0){
			/* reset ke angka NOL untuk db.detilgaji.RPTISTRI dan db.detilgaji.RPTANAK */
			$this->db->where(array('BULAN'=>$bulan))->update('detilgaji', array('RPTISTRI'=>0, 'RPTANAK'=>0));
			
			/* proses looping rptkeluarga */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptkeluarga as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->STATUSKEL2 = $record->STATUSKEL2;
					$obj->PELAJAR = $record->PELAJAR;
					$obj->UMURTO = $record->UMURTO;
					$obj->RPTKELUARGA = $record->RPTKELUARGA;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->STATUSKEL2 = $record->STATUSKEL2;
					$obj->PELAJAR = $record->PELAJAR;
					$obj->UMURTO = $record->UMURTO;
					$obj->RPTKELUARGA = $record->RPTKELUARGA;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->STATUSKEL2 = $record->STATUSKEL2;
					$obj->PELAJAR = $record->PELAJAR;
					$obj->UMURTO = $record->UMURTO;
					$obj->RPTKELUARGA = $record->RPTKELUARGA;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->STATUSKEL2 = $record->STATUSKEL2;
					$obj->PELAJAR = $record->PELAJAR;
					$obj->UMURTO = $record->UMURTO;
					$obj->RPTKELUARGA = $record->RPTKELUARGA;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptkeluarga ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptkeluarga_bygrade($bulan, $grade_arr);
			/* urutan rptkeluarga ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptkeluarga_bykodejab($bulan, $kodejab_arr);
			/* urutan rptkeluarga ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptkeluarga_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rptkeluarga ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptkeluarga_bynik($bulan, $nik_arr);
		}
		
		/* 7.a. */
		$sql_rpttransport = "SELECT *
			FROM ttransport
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM ttransport WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rpttransport = $this->db->query($sql_rpttransport)->result();
		
		/* 7.b. */
		if(sizeof($records_rpttransport) > 0){
			/* proses looping rpttransport */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rpttransport as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->ZONA = $record->ZONA;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->ZONA = $record->ZONA;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rpttransport ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpttransport_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rpttransport ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpttransport_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			/* urutan rpttransport ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpttransport_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			/* urutan rpttransport ke-4 berdasarkan NIK */
			$this->update_detilgaji_rpttransport_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
		}
		
		/* 99. */
		$sqlu_gajibulanan = "UPDATE gajibulanan JOIN (
					SELECT detilgaji.NIK,
						SUM(detilgaji.RPUPAHPOKOK) AS RPUPAHPOKOK,
						SUM(detilgaji.RPTISTRI) AS RPTISTRI,
						SUM(detilgaji.RPTANAK) AS RPTANAK,
						SUM(detilgaji.RPTBHS) AS RPTBHS,
						SUM(detilgaji.RPTJABATAN) AS RPTJABATAN,
						SUM(detilgaji.RPTTRANSPORT) AS RPTTRANSPORT,
						SUM(detilgaji.RPTPEKERJAAN) AS RPTPEKERJAAN
					FROM detilgaji WHERE detilgaji.BULAN = '".$bulan."'
					GROUP BY detilgaji.NIK
				) AS detilgaji_total ON(detilgaji_total.NIK = gajibulanan.NIK AND gajibulanan.BULAN = '".$bulan."')
			SET gajibulanan.RPUPAHPOKOK = detilgaji_total.RPUPAHPOKOK,
				gajibulanan.RPTUNJTETAP = (IFNULL(detilgaji_total.RPTISTRI,0) + IFNULL(detilgaji_total.RPTANAK,0) + IFNULL(detilgaji_total.RPTBHS,0) + IFNULL(detilgaji_total.RPTJABATAN,0)),
				gajibulanan.RPTUNJTDKTTP = (IFNULL(detilgaji_total.RPTTRANSPORT,0) + IFNULL(detilgaji_total.RPTPEKERJAAN,0))";
		$this->db->query($sqlu_gajibulanan);
		
	}
	 
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('TANGGAL', 'ASC')->get('hitungpresensi')->result();
		$total  = $this->db->get('hitungpresensi')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'BULAN'=>$data->BULAN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('hitungpresensi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('JENISABSEN'=>$data->JENISABSEN,'HARIKERJA'=>$data->HARIKERJA,'JAMKERJA'=>$data->JAMKERJA,'JAMLEMBUR'=>$data->JAMLEMBUR,'JAMKURANG'=>$data->JAMKURANG,'JAMBOLOS'=>$data->JAMBOLOS,'EXTRADAY'=>$data->EXTRADAY,'TERLAMBAT'=>$data->TERLAMBAT,'PLGLBHAWAL'=>$data->PLGLBHAWAL,'USERNAME'=>$data->USERNAME,'POSTING'=>$data->POSTING);
			 
			$this->db->where($pkey)->update('hitungpresensi', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'BULAN'=>$data->BULAN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JENISABSEN'=>$data->JENISABSEN,'HARIKERJA'=>$data->HARIKERJA,'JAMKERJA'=>$data->JAMKERJA,'JAMLEMBUR'=>$data->JAMLEMBUR,'JAMKURANG'=>$data->JAMKURANG,'JAMBOLOS'=>$data->JAMBOLOS,'EXTRADAY'=>$data->EXTRADAY,'TERLAMBAT'=>$data->TERLAMBAT,'PLGLBHAWAL'=>$data->PLGLBHAWAL,'USERNAME'=>$data->USERNAME,'POSTING'=>$data->POSTING);
			 
			$this->db->insert('hitungpresensi', $arrdatac);
			$last   = $this->db->where($pkey)->get('hitungpresensi')->row();
			
		}
		
		$total  = $this->db->get('hitungpresensi')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK,'BULAN'=>$data->BULAN,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('hitungpresensi');
		
		$total  = $this->db->get('hitungpresensi')->num_rows();
		$last = $this->db->get('hitungpresensi')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
}
?>