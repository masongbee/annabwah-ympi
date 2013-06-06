<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_gajibulanan
 * 
 * Table	: gajibulanan
 *  
 * @author masongbee
 *
 */
class M_gajibulanan extends CI_Model{

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
	function getAll($bulan, $tglmulai, $tglsampai, $start, $page, $limit){
		if($this->db->get_where('gajibulanan', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->hitunggaji_all($bulan, $tglmulai, $tglsampai);
		}
		
		$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('gajibulanan')->result();
		$total  = $this->db->get('gajibulanan')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK);
		
		if($this->db->get_where('gajibulanan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTUNJTETAP'=>$data->RPTUNJTETAP,'RPTUNJTDKTTP'=>$data->RPTUNJTDKTTP,'RPNONUPAH'=>$data->RPNONUPAH,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN,'RPTOTGAJI'=>$data->RPTOTGAJI,'NOACCKAR'=>$data->NOACCKAR,'NAMABANK'=>$data->NAMABANK,'TGLDIBAYAR'=>(strlen(trim($data->TGLDIBAYAR)) > 0 ? date('Y-m-d', strtotime($data->TGLDIBAYAR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('gajibulanan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTUNJTETAP'=>$data->RPTUNJTETAP,'RPTUNJTDKTTP'=>$data->RPTUNJTDKTTP,'RPNONUPAH'=>$data->RPNONUPAH,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN,'RPTOTGAJI'=>$data->RPTOTGAJI,'NOACCKAR'=>$data->NOACCKAR,'NAMABANK'=>$data->NAMABANK,'TGLDIBAYAR'=>(strlen(trim($data->TGLDIBAYAR)) > 0 ? date('Y-m-d', strtotime($data->TGLDIBAYAR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('gajibulanan', $arrdatac);
			$last   = $this->db->where($pkey)->get('gajibulanan')->row();
			
		}
		
		$total  = $this->db->get('gajibulanan')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('gajibulanan');
		
		$total  = $this->db->get('gajibulanan')->num_rows();
		$last = $this->db->get('gajibulanan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data db.periodegaji
	 * 
	 * @return json
	 */
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
	
	function gen_gajibulanan($bulan){
		$sql = "INSERT INTO gajibulanan (NIK, BULAN, NOACCKAR, NAMABANK, USERNAME)
			SELECT NIK, '".$bulan."', NOACCKAR, NAMABANK, '".$this->session->userdata('user_name')."' from karyawan
			where STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function gen_detilgaji($bulan){
		$sql = "INSERT INTO detilgaji (NIK, BULAN, NOREVISI)
			SELECT NIK, '".$bulan."', 1 from KARYAWAN
			where STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rpupahpokok_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr, $upahpokok){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		foreach($grade_arr as $row){
			IF(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') AND
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t1.t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
						AND karyawan.GRADE = '".$row->GRADE."' AND detilgaji.BULAN = '".$bulan."')
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $tglmulai, $tglsampai, $katpekerjaan_arr){
		foreach($katpekerjaan_arr as $row){
			IF(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') AND
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t1.t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
						AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND detilgaji.BULAN = '".$bulan."')
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $tglmulai, $tglsampai, $gradekatpekerjaan_arr){
		foreach($gradekatpekerjaan_arr as $row){
			IF(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan
							ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') AND
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t1.t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
						AND karyawan.GRADE = '".$row->GRADE."' AND detilgaji.BULAN = '".$bulan."')
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
		foreach($nik_arr as $row){
			IF(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.NIK = '".$row->NIK."'
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') AND
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t1.t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN."
					WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND (karyawan.BHSJEPANG = '1' OR karyawan.BHSJEPANG = '2' OR karyawan.BHSJEPANG = '3')
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND (karyawan.BHSJEPANG = '1' OR karyawan.BHSJEPANG = '2' OR karyawan.BHSJEPANG = '3')
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND (karyawan.BHSJEPANG = '1' OR karyawan.BHSJEPANG = '2' OR karyawan.BHSJEPANG = '3')
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function hitunggaji_all($bulan, $tglmulai, $tglsampai){
		/*
		 * Langkah memproses Perhitungan Gaji untuk seluruh Karyawan
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
		 * 99. selesai update db.detilgaji, maka hitung total gaji setiap karyawan di db.detilgaji dan dimasukkan ke db.gajibulanan
		 */
		/* 1.a. */
		if($this->db->get_where('gajibulanan', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->gen_gajibulanan($bulan);
		}
		
		/* 1.b. */
		if($this->db->get_where('detilgaji', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->gen_detilgaji($bulan);
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
			$this->update_detilgaji_rptkeluarga_bygradekodejab($bulan, $nik_arr);
		}
		
		/* 99. */
		$sqlu_gajibulanan = "UPDATE gajibulanan JOIN (
					SELECT detilgaji.NIK, SUM(detilgaji.RPUPAHPOKOK) AS RPUPAHPOKOK
					FROM detilgaji WHERE detilgaji.BULAN = '".$bulan."'
					GROUP BY detilgaji.NIK
				) AS detilgaji_total ON(detilgaji_total.NIK = gajibulanan.NIK AND gajibulanan.BULAN = '".$bulan."')
			SET gajibulanan.RPUPAHPOKOK = detilgaji_total.RPUPAHPOKOK";
		$this->db->query($sqlu_gajibulanan);
		
	}
	
}
?>