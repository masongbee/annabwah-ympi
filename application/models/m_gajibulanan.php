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
	function getAll($hitunggaji, $bulan, $tglmulai, $tglsampai, $start, $page, $limit){
		/*if($this->db->get_where('gajibulanan', array('BULAN'=>$bulan))->num_rows() == 0){
			$this->hitunggaji_all($bulan, $tglmulai, $tglsampai);
		}*/
		if($hitunggaji == 'hitunggaji'){
			/* DELETE db.detilgaji dan db.gajibulanan BY $bulan*/
			$this->db->where(array('BULAN'=>$bulan))->delete('detilgaji');
			$this->db->where(array('BULAN'=>$bulan))->delete('gajibulanan');
			
			/* Proses HITUNGGAJI ALL */
			$this->hitunggaji_all($bulan, $tglmulai, $tglsampai);
		}
		
		$query  = $this->db->where(array('BULAN'=>$bulan))->limit($limit, $start)->order_by('NIK', 'ASC')->get('gajibulanan')->result();
		//$total  = $this->db->get('gajibulanan')->num_rows();
		$query_total = $this->db->select('COUNT(*) AS total')->where(array('BULAN'=>$bulan))->get('gajibulanan')->row();
		$total  = $query_total->total;
		
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
		$sql = "INSERT INTO gajibulanan (NIK, BULAN, NOACCKAR, NAMABANK, USERNAME, RPUPAHPOKOK, RPTUNJTETAP, RPTUNJTDKTTP, RPNONUPAH, RPPOTONGAN, RPTAMBAHAN, RPTOTGAJI)
			SELECT NIK, '".$bulan."', NOACCKAR, NAMABANK, '".$this->session->userdata('user_name')."',
				0, 0, 0, 0, 0, 0, 0
			FROM karyawan
			WHERE STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function gen_detilgaji($bulan, $tglmulai, $tglsampai){
		$sql = "INSERT INTO detilgaji (NIK, BULAN, NOREVISI, GRADE, KODEJAB, MASA_KERJA_BLN, MASA_KERJA_HARI
				,RPUPAHPOKOK
				,RPTJABATAN
				,RPTANAK
				,RPTISTRI
				,RPTBHS
				,RPTTRANSPORT
				,RPTSHIFT
				,RPTPEKERJAAN
				,RPTQCP
				,RPTLEMBUR
				,RPIDISIPLIN
				,RPTHADIR
				,RPKOMPEN
				,RPTMAKAN
				,RPTSIMPATI
				,RPTHR
				,RPBONUS
				,RPTKACAMATA
				,RPTAMBAHAN1
				,RPTAMBAHAN2
				,RPTAMBAHAN3
				,RPTAMBAHAN4
				,RPTAMBAHAN5
				,RPTAMBAHANLAIN
				,RPPUPAHPOKOK
				,RPPMAKAN
				,RPPTRANSPORT
				,RPPJAMSOSTEK
				,RPCICILAN1
				,RPCICILAN2
				,RPPOTONGAN1
				,RPPOTONGAN2
				,RPPOTONGAN3
				,RPPOTONGAN4
				,RPPOTONGAN5
				,RPPOTONGANLAIN)
			SELECT NIK, '".$bulan."', 1, GRADE, KODEJAB, MASA_KERJA_BLN, MASA_KERJA_HARI
				,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0
				,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0
			FROM vu_karyawan
			WHERE STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
		
		/* generate data db.detilgaji untuk karyawan yang memiliki mutasi */
		$sql_mutasi = "SELECT *
			FROM karyawanmut
			WHERE karyawanmut.VALIDTO >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND karyawanmut.VALIDTO <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
			ORDER BY NIK, VALIDTO DESC";
	}
	
	function update_detilgaji_rpupahpokok_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			/*$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;*/
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPUPAHPOKOK.")
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			/*$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;*/
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPUPAHPOKOK.")
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			/*$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK;*/
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK."
				WHERE detilgaji.BULAN = '".$bulan."'
					AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPUPAHPOKOK.")
				WHERE detilgaji.BULAN = '".$bulan."'
					AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpupahpokok_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ".$row->RPUPAHPOKOK."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPUPAHPOKOK = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPUPAHPOKOK.")
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji 
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $katpekerjaan_arr){
		foreach($katpekerjaan_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji JOIN karyawan ON(detilgaji.BULAN = '".$bulan."' 
						AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $gradekatpekerjaan_arr){
		foreach($gradekatpekerjaan_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan
							ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTPEKERJAAN = ".$row->RPTPEKERJAAN." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji JOIN karyawan ON(detilgaji.BULAN = '".$bulan."'
						AND karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."'
						AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTPEKERJAAN = ".$row->RPTPEKERJAAN;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptpekerjaan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
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
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTBHS.")";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTBHS.")";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTBHS.")";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptbhs_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ".$row->RPTBHS."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTBHS.")";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTJABATAN.")
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTJABATAN.")
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.BULAN = '".$bulan."'
					AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTJABATAN.")
				WHERE detilgaji.BULAN = '".$bulan."'
					AND detilgaji.GRADE = '".$row->GRADE."'
					AND detilgaji.KODEJAB = '".$row->KODEJAB."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptjabatan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
			$this->db->query($sql);
			
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTJABATAN.")
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'
					AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bygrade($bulan, $grade_arr){
		/*
		 * CATATAN:
		 * $row->STATTUNKEL == 'P' ==> Hanya Istri / Suami
		 * $row->STATTUNKEL == 'F' ==> Istri / Suami dan Anak
		 * $row->STATTUNKEL == 'A' ==> Hanya Anak
		 */
		foreach($grade_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
				
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
						AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
					SET detilgaji.RPTISTRI = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTKELUARGA.")";
				$this->db->query($sql);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * Anak ke-x yang mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT karyawan.NIK
							FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
								AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
								AND keluarga.STATUSKEL = 'A'
								AND keluarga.NOURUT = ".$anak_ke."
								AND keluarga.TGLMENINGGAL IS NULL";
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) == 0){
					$sql .= " AND keluarga.PELAJAR = '".$row->PELAJAR."'";
				}
				if($row->PELAJAR == 'T' && strlen($row->UMURTO) > 0){
					$sql .= " AND TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO;
				}
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) > 0){
					$sql .= " AND (keluarga.PELAJAR = '".$row->PELAJAR."' OR TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO.")";
				}
				$sql .= " AND keluarga.NIK = karyawan.NIK)) AS t2 ON(t2.NIK = t1.NIK)";
				$sql .= " SET t1.RPTANAK = t1.RPTANAK + ".$row->RPTKELUARGA;
				$sql .= " WHERE detilgaji.MASA_KERJA_BLN > 0";
				$this->db->query($sql);
				
				$sql2 = $sql;
				$sql2 .= " WHERE detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
				$this->db->query($sql2);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
				
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
						AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
					SET detilgaji.RPTISTRI = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTKELUARGA.")";
				$this->db->query($sql);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * Anak ke-x yang mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT karyawan.NIK
							FROM karyawan JOIN keluarga ON(karyawan.KODEJAB = '".$row->KODEJAB."'
								AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
								AND keluarga.STATUSKEL = 'A'
								AND keluarga.NOURUT = ".$anak_ke."
								AND keluarga.TGLMENINGGAL IS NULL";
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) == 0){
					$sql .= " AND keluarga.PELAJAR = '".$row->PELAJAR."'";
				}
				if($row->PELAJAR == 'T' && strlen($row->UMURTO) > 0){
					$sql .= " AND TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO;
				}
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) > 0){
					$sql .= " AND (keluarga.PELAJAR = '".$row->PELAJAR."' OR TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO.")";
				}
				$sql .= " AND keluarga.NIK = karyawan.NIK)) AS t2 ON(t2.NIK = t1.NIK)";
				$sql .= " SET t1.RPTANAK = t1.RPTANAK + ".$row->RPTKELUARGA;
				$sql .= " WHERE detilgaji.MASA_KERJA_BLN > 0";
				$this->db->query($sql);
				
				$sql2 = $sql;
				$sql2 .= " WHERE detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
				$this->db->query($sql2);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KODEJAB = '".$row->KODEJAB."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
				
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KODEJAB = '".$row->KODEJAB."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
						AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
					SET detilgaji.RPTISTRI = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTKELUARGA.")";
				$this->db->query($sql);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * Anak ke-x yang mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT karyawan.NIK
							FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
								AND karyawan.KODEJAB = '".$row->KODEJAB."'
								AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
								AND keluarga.STATUSKEL = 'A'
								AND keluarga.NOURUT = ".$anak_ke."
								AND keluarga.TGLMENINGGAL IS NULL";
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) == 0){
					$sql .= " AND keluarga.PELAJAR = '".$row->PELAJAR."'";
				}
				if($row->PELAJAR == 'T' && strlen($row->UMURTO) > 0){
					$sql .= " AND TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO;
				}
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) > 0){
					$sql .= " AND (keluarga.PELAJAR = '".$row->PELAJAR."' OR TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO.")";
				}
				$sql .= " AND keluarga.NIK = karyawan.NIK)) AS t2 ON(t2.NIK = t1.NIK)";
				$sql .= " SET t1.RPTANAK = t1.RPTANAK + ".$row->RPTKELUARGA;
				$sql .= " WHERE detilgaji.MASA_KERJA_BLN > 0";
				$this->db->query($sql);
				
				$sql2 = $sql;
				$sql2 .= " WHERE detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
				$this->db->query($sql2);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
				
				$sql2 = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
						AND detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0
					SET detilgaji.RPTISTRI = ((detilgaji.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * ".$row->RPTKELUARGA.")";
				$this->db->query($sql2);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * Anak ke-x yang mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT karyawan.NIK
							FROM karyawan JOIN keluarga ON(karyawan.NIK = '".$row->NIK."'
								AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
								AND keluarga.STATUSKEL = 'A'
								AND keluarga.NOURUT = ".$anak_ke."
								AND keluarga.TGLMENINGGAL IS NULL";
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) == 0){
					$sql .= " AND keluarga.PELAJAR = '".$row->PELAJAR."'";
				}
				if($row->PELAJAR == 'T' && strlen($row->UMURTO) > 0){
					$sql .= " AND TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO;
				}
				if($row->PELAJAR == 'Y' && strlen($row->UMURTO) > 0){
					$sql .= " AND (keluarga.PELAJAR = '".$row->PELAJAR."' OR TIMESTAMPDIFF(YEAR, keluarga.TGLLAHIR, DATE(NOW())) <= ".$row->UMURTO.")";
				}
				$sql .= " AND keluarga.NIK = karyawan.NIK)) AS t2 ON(t2.NIK = t1.NIK)";
				$sql .= " SET t1.RPTANAK = t1.RPTANAK + ".$row->RPTKELUARGA;
				$sql .= " WHERE detilgaji.MASA_KERJA_BLN > 0";
				$this->db->query($sql);
				
				$sql2 = $sql;
				$sql2 .= " WHERE detilgaji.MASA_KERJA_BLN = 0 AND detilgaji.MASA_KERJA_HARI > 0";
				$this->db->query($sql2);
			}
		}
	}
	
	function update_detilgaji_rpttransport_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
					FROM hitungpresensi 
					WHERE 
						hitungpresensi.JENISABSEN IN ('AL','IJ','IN','IH','II','IK','SK')
						AND hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."'
					AND t1.GRADE = '".$row>GRADE."'
					AND t2.NIK = t1.NIK
					AND t2.JMLABSEN = ".$row->JMLABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
					FROM hitungpresensi 
					WHERE 
						hitungpresensi.JENISABSEN IN ('AL','IJ','IN','IH','II','IK','SK')
						AND hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."'
					AND t1.KODEJAB = '".$row>KODEJAB."'
					AND t2.NIK = t1.NIK
					AND t2.JMLABSEN = ".$row->JMLABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
					FROM hitungpresensi 
					WHERE 
						hitungpresensi.JENISABSEN IN ('AL','IJ','IN','IH','II','IK','SK')
						AND hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."'
					AND t1.GRADE = '".$row->GRADE."'
					AND t1.KODEJAB = '".$row->KODEJAB."'
					AND t2.NIK = t1.NIK
					AND t2.JMLABSEN = ".$row->JMLABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptlembur_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			if($row->UPENGALI == 'A'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * t3.RPUPAHPOKOK) / 173)
					WHERE t1.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'B'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTTRANSPORT, vu_detilgaji_bynik.RPTPEKERJAAN, vu_detilgaji_bynik.RPTSHIFT
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTTRANSPORT + t3.RPTPEKERJAAN + t3.RPTSHIFT)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'C'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTBHS, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTBHS + t3.RPTJABATAN)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'D'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTJABATAN)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptlembur_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if($row->UPENGALI == 'A'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * t3.RPUPAHPOKOK) / 173)
					WHERE t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'B'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTTRANSPORT, vu_detilgaji_bynik.RPTPEKERJAAN, vu_detilgaji_bynik.RPTSHIFT
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTTRANSPORT + t3.RPTPEKERJAAN + t3.RPTSHIFT)) / 173)
					WHERE t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'C'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTBHS, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTBHS + t3.RPTJABATAN)) / 173)
					WHERE t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'D'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTJABATAN)) / 173)
					WHERE t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptlembur_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if($row->UPENGALI == 'A'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * t3.RPUPAHPOKOK) / 173)
					WHERE t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'B'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTTRANSPORT, vu_detilgaji_bynik.RPTPEKERJAAN, vu_detilgaji_bynik.RPTSHIFT
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTTRANSPORT + t3.RPTPEKERJAAN + t3.RPTSHIFT)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'C'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTBHS, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((t2.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTBHS + t3.RPTJABATAN)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}elseif($row->UPENGALI == 'D'){
				$sql = "UPDATE detilgaji AS t1
					JOIN hitungpresensi AS t2 ON(t2.NIK = t1.NIK AND t2.BULAN = t1.BULAN
						AND t2.JAMLEMBUR >= ".$row->JAMDARI." AND t2.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND t2.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND t2.BULAN = '".$bulan."')
					LEFT JOIN (
							SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK, vu_detilgaji_bynik.RPTISTRI, vu_detilgaji_bynik.RPTANAK, vu_detilgaji_bynik.RPTJABATAN
							FROM vu_detilgaji_bynik
							WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
						) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTLEMBUR = ((hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTJABATAN)) / 173)
					WHERE t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptshift_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KODEJAB = '".$row->KODEJAB."'
							AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KODEJAB = '".$row->KODEJAB."'
							AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE karyawanshift.NIK = '".$row->NIK."'
							AND pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE karyawanshift.NIK = '".$row->NIK."'
							AND pembagianshift.TGLMULAI >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptambahan_bygrade($bulan, $grade_arr){
		$tmp = array(); 
		foreach($grade_arr as $row) 
			$tmp[] = $row->GRADE; 
		array_multisort($tmp, $grade_arr);
		
		$tmp_grade = "";
		
		$i=0;
		foreach($grade_arr as $row){
			if($row->GRADE == $tmp_grade){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPTAMBAHANLAIN = (detilgaji.RPTAMBAHANLAIN + ".$row->RPTAMBAHAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
				
				$tmp_grade = $row->GRADE;
			}
			
		}
	}
	
	function update_detilgaji_rptambahan_bykodejab($bulan, $kodejab_arr){
		$tmp = array(); 
		foreach($kodejab_arr as $row) 
			$tmp[] = $row->KODEJAB; 
		array_multisort($tmp, $kodejab_arr);
		
		$tmp_kodejab = "";
		
		$i=0;
		foreach($kodejab_arr as $row){
			if($row->KODEJAB == $tmp_kodejab){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPTAMBAHANLAIN = (detilgaji.RPTAMBAHANLAIN + ".$row->RPTAMBAHAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
				
				$tmp_kodejab = $row->KODEJAB;
			}
			
		}
		
	}
	
	function update_detilgaji_rptambahan_bygradekodejab($bulan, $gradekodejab_arr){
		$tmp = array(); 
		foreach($gradekodejab_arr as $row) 
			$tmp[] = $row->GRADE; 
		array_multisort($tmp, $gradekodejab_arr);
		
		$tmp_grade = "";
		$tmp_kodejab = "";
		
		$i=0;
		foreach($gradekodejab_arr as $row){
			if(($row->GRADE == $tmp_grade) && ($row->KODEJAB == $tmp_kodejab)){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
						WHERE detilgaji.BULAN = '".$bulan."'
							AND detilgaji.GRADE = '".$row->GRADE."'
							AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPTAMBAHANLAIN = (detilgaji.RPTAMBAHANLAIN + ".$row->RPTAMBAHAN.")
						WHERE detilgaji.BULAN = '".$bulan."'
							AND detilgaji.GRADE = '".$row->GRADE."'
							AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
				
				$tmp_grade = $row->GRADE;
				$tmp_kodejab = $row->KODEJAB;
			}
			
		}
		
	}
	
	function update_detilgaji_rptambahan_bynik($bulan, $nik_arr){
		$tmp = array(); 
		foreach($nik_arr as $row) 
			$tmp[] = $row->NIK; 
		array_multisort($tmp, $nik_arr);
		
		$tmp_nik = "";
		
		$i=0;
		foreach($nik_arr as $row){
			if($row->NIK == $tmp_nik){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPTAMBAHANLAIN = (detilgaji.RPTAMBAHANLAIN + ".$row->RPTAMBAHAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.TAMBAHAN".$i." = '".$row->KODEUPAH."', detilgaji.RPTAMBAHAN".$i." = ".$row->RPTAMBAHAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
				$this->db->query($sql);
				
				$tmp_nik = $row->NIK;
			}
			
		}
		
	}
	
	function update_detilgaji_rppotongan_bygrade($bulan, $grade_arr){
		$tmp = array(); 
		foreach($grade_arr as $row) 
			$tmp[] = $row->GRADE; 
		array_multisort($tmp, $grade_arr);
		
		$tmp_grade = "";
		
		$i=0;
		foreach($grade_arr as $row){
			if($row->GRADE == $tmp_grade){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPPOTONGANLAIN = (detilgaji.RPPOTONGANLAIN + ".$row->RPPOTONGAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.GRADE = '".$row->GRADE."'";
				$this->db->query($sql);
				
				$tmp_grade = $row->GRADE;
			}
			
		}
		
	}
	
	function update_detilgaji_rppotongan_bykodejab($bulan, $kodejab_arr){
		$tmp = array(); 
		foreach($kodejab_arr as $row) 
			$tmp[] = $row->KODEJAB; 
		array_multisort($tmp, $kodejab_arr);
		
		$tmp_kodejab = "";
		
		$i=0;
		foreach($kodejab_arr as $row){
			if($row->KODEJAB == $tmp_kodejab){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPPOTONGANLAIN = (detilgaji.RPPOTONGANLAIN + ".$row->RPPOTONGAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
				
				$tmp_kodejab = $row->KODEJAB;
			}
			
		}
		
	}
	
	function update_detilgaji_rppotongan_bygradekodejab($bulan, $gradekodejab_arr){
		$tmp = array(); 
		foreach($grade_arr as $row) 
			$tmp[] = $row->GRADE; 
		array_multisort($tmp, $grade_arr);
		
		$tmp_grade = "";
		$tmp_kodejab = "";
		
		$i=0;
		foreach($grade_arr as $row){
			if(($row->GRADE == $tmp_grade) && ($row->KODEJAB == $tmp_kodejab)){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
						WHERE detilgaji.BULAN = '".$bulan."'
							AND detilgaji.GRADE = '".$row->GRADE."'
							AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPPOTONGANLAIN = (detilgaji.RPPOTONGANLAIN + ".$row->RPPOTONGAN.")
						WHERE detilgaji.BULAN = '".$bulan."'
							AND detilgaji.GRADE = '".$row->GRADE."'
							AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'";
				$this->db->query($sql);
				
				$tmp_grade = $row->GRADE;
				$tmp_kodejab = $row->KODEJAB;
			}
			
		}
		
	}
	
	function update_detilgaji_rppotongan_bynik($bulan, $nik_arr){
		$tmp = array(); 
		foreach($nik_arr as $row) 
			$tmp[] = $row->NIK; 
		array_multisort($tmp, $nik_arr);
		
		$tmp_nik = "";
		
		$i=0;
		foreach($nik_arr as $row){
			if($row->NIK == $tmp_nik){
				$i++;
				if($i <= 5){
					$sql = "UPDATE detilgaji 
						SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji 
						SET detilgaji.RPPOTONGANLAIN = (detilgaji.RPPOTONGANLAIN + ".$row->RPPOTONGAN.")
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.POTONGAN".$i." = '".$row->KODEPOTONGAN."', detilgaji.RPPOTONGAN".$i." = ".$row->RPPOTONGAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
				$this->db->query($sql);
				
				$tmp_nik = $row->NIK;
			}
			
		}
		
	}
	
	function update_detilgaji_rpcicilan_bynik($bulan, $nik_arr){
		$tmp = array(); 
		foreach($nik_arr as $row) 
			$tmp[] = $row->NIK; 
		array_multisort($tmp, $nik_arr);
		
		$tmp_nik = "";
		
		$i=0;
		foreach($nik_arr as $row){
			if($row->NIK == $tmp_nik){
				$i++;
				if($i <= 2){
					$sql = "UPDATE detilgaji 
						SET detilgaji.CICILAN".$i." = CONCAT('".$row->KETERANGAN."', ' ', '('".$row->CICILANKE."'/'".$row->LAMACICILAN."')'), detilgaji.RPCICILAN".$i." = ".$row->RPCICILAN."
						WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.CICILAN".$i." = CONCAT('".$row->KETERANGAN."', ' ', '('".$row->CICILANKE."'/'".$row->LAMACICILAN."')'), detilgaji.RPCICILAN".$i." = ".$row->RPCICILAN."
					WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
				$this->db->query($sql);
				
				$tmp_nik = $row->NIK;
			}
			
		}
		
	}
	
	function update_detilgaji_rptqcp_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPTQCP = ".$row->RPQCP." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptsimpati_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTSIMPATI = ".$row->RPTSIMPATI."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpbonus_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		foreach($grade_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				if($row->UPENGALI == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * (t1.RPUPAHPOKOK / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'B'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'C'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTISTRI + t1.RPTANAK) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}
				
			}else{
				$sql = "UPDATE detilgaji AS t1
					JOIN karyawan AS t2 ON(t2.GRADE = '".$row->GRADE."' AND
						t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPBONUS = ".$row->RPBONUS." * ".$row->PENGALI." * ".$row->PERSENTASE;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpbonus_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				if($row->UPENGALI == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * (t1.RPUPAHPOKOK / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'B'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'C'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTISTRI + t1.RPTANAK) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}
				
			}else{
				$sql = "UPDATE detilgaji AS t1
					JOIN karyawan AS t2 ON(t2.KODEJAB = '".$row->KODEJAB."' AND
						t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPBONUS = ".$row->RPBONUS." * ".$row->PENGALI." * ".$row->PERSENTASE;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpbonus_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				if($row->UPENGALI == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
								AND karyawan.KODEJAB = '".$row->KODEJAB."'
								AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * (t1.RPUPAHPOKOK / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'B'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
								AND karyawan.KODEJAB = '".$row->KODEJAB."'
								AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'C'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
								AND karyawan.KODEJAB = '".$row->KODEJAB."'
								AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTISTRI + t1.RPTANAK) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}
				
			}else{
				$sql = "UPDATE detilgaji AS t1
					JOIN karyawan AS t2 ON(t2.GRADE = '".$row->GRADE."'
						AND t2.KODEJAB = '".$row->KODEJAB."'
						AND t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPBONUS = ".$row->RPBONUS." * ".$row->PENGALI." * ".$row->PERSENTASE;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpbonus_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				if($row->UPENGALI == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.NIK = '".$row->NIK."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * (t1.RPUPAHPOKOK / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'B'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.NIK = '".$row->NIK."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}elseif($row->UPENGALI == 'C'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
							SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
							FROM hitungpresensi JOIN karyawan ON(karyawan.NIK = '".$row->NIK."' AND karyawan.NIK = hitungpresensi.NIK)
							WHERE 
								hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
								hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
								(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
							GROUP BY hitungpresensi.NIK
						) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
						SET t1.RPBONUS = (t2.JMLHARIKERJA * ((t1.RPUPAHPOKOK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTISTRI + t1.RPTANAK) / 173) * ".$row->PERSENTASE.") + ".$row->RPBONUS;
				}
				
			}else{
				$sql = "UPDATE detilgaji AS t1
					JOIN karyawan AS t2 ON(t2.NIK = '".$row->NIK."' AND
						t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPBONUS = ".$row->RPBONUS." * ".$row->PENGALI." * ".$row->PERSENTASE;
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpthadir_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTHADIR = ".$row->RPTHADIR."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpthr_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if($row->UPENGALI == 'A'){
				$sql = "UPDATE detilgaji AS t1
					JOIN vu_karyawan_masa_kerja AS t2 ON(t1.BULAN = '".$bulan."'
						AND t1.NIK = '".$row->NIK."'
						AND t2.NIK = t1.NIK
						AND t2.MASA_KERJA_BLN >= ".$row->MSKERJADARI;
						if($row->MSKERJASAMPAI > 0){
							$sql .= "AND t2.MASA_KERJA_BLN <= ".$row->MSKERJASAMPAI;
						}
				$sql .= ")";
					if($row->PEMBAGI > 0){
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / ".$row->PEMBAGI.") * ".$row->PENGALI." * t1.RPUPAHPOKOK)";
					}else{
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / t2.MASA_KERJA_BLN) * ".$row->PENGALI." * t1.RPUPAHPOKOK)";
					}
					
			}elseif($row->UPENGALI == 'B'){
				$sql = "UPDATE detilgaji AS t1
					JOIN vu_karyawan_masa_kerja AS t2 ON(t1.BULAN = '".$bulan."'
						AND t1.NIK = '".$row->NIK."'
						AND t2.NIK = t1.NIK
						AND t2.MASA_KERJA_BLN >= ".$row->MSKERJADARI;
						if($row->MSKERJASAMPAI > 0){
							$sql .= "AND t2.MASA_KERJA_BLN <= ".$row->MSKERJASAMPAI;
						}
				$sql .= ")";
					if($row->PEMBAGI > 0){
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / ".$row->PEMBAGI.") * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTJABATAN))";
					}else{
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / t2.MASA_KERJA_BLN) * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTJABATAN))";
					}
					
			}elseif($row->UPENGALI == 'C'){
				$sql = "UPDATE detilgaji AS t1
					JOIN vu_karyawan_masa_kerja AS t2 ON(t1.BULAN = '".$bulan."'
						AND t1.NIK = '".$row->NIK."'
						AND t2.NIK = t1.NIK
						AND t2.MASA_KERJA_BLN >= ".$row->MSKERJADARI;
						if($row->MSKERJASAMPAI > 0){
							$sql .= "AND t2.MASA_KERJA_BLN <= ".$row->MSKERJASAMPAI;
						}
				$sql .= ")";
					if($row->PEMBAGI > 0){
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / ".$row->PEMBAGI.") * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPTJABATAN))";
					}else{
						$sql .= "SET t1.RPTHR = ((t2.MASA_KERJA_BLN / t2.MASA_KERJA_BLN) * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPTJABATAN))";
					}
					
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkacamata_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTKACAMATA = (".$row->RPFRAME." + ".$row->RPLENSA.")
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpkompen_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPKOMPEN = (".$row->SISACUTI." * ".$row->RPKOMPEN.")
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpmakan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTMAKAN = ".$row->RPTMAKAN.", detilgaji.RPPMAKAN = ".$row->RPPMAKAN."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rppupahpokok_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji AS t1
				LEFT JOIN (
						SELECT vu_detilgaji_bynik.NIK, vu_detilgaji_bynik.RPUPAHPOKOK
						FROM vu_detilgaji_bynik
						WHERE vu_detilgaji_bynik.BULAN = CAST((CAST('".$bulan."' AS UNSIGNED) - 1) AS CHAR)
					) AS t2 ON(t2.NIK = t1.NIK)
				SET t1.RPPUPAHPOKOK = ((1 / 173) * t2.RPUPAHPOKOK)
				WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpptransport_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
		/*$sql_rpttransport = "SELECT ttransport.NIK, ttransport.GRADE, ttransport.KODEJAB, ttransport.ZONA, ttransport.RPTTRANSPORT,
				CASE WHEN ttransport.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN ttransport.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN ttransport.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN ttransport.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN ttransport.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN ttransport.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM ttransport
			WHERE ttransport.TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				AND ttransport.TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
			ORDER BY NOURUT";*/
		$sql_rpttransport = "SELECT NIK, GRADE, KODEJAB, ZONA, RPTTRANSPORT, TGLMULAI, TGLSAMPAI
			FROM ttransport
			WHERE TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
			ORDER BY NOURUT";
		$records_rpttransport = $this->db->query($sql_rpttransport)->result();
		
		/* 7.b. */
		if(sizeof($records_rpttransport) > 0){
			/* proses looping rpttransport */
			$ttransport_grade_arr = array();
			$ttransport_kodejab_arr = array();
			$ttransport_gradekodejab_arr = array();
			$ttransport_nik_arr = array();
			
			foreach($records_rpttransport as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($ttransport_grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($ttransport_kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($ttransport_gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($ttransport_nik_arr, $obj);
					
				}
			}
			
			if(sizeof($ttransport_grade_arr) > 0){
				$total_rpptransport = 0;
				$nik_temp = "";
				foreach($nik_arr as $row){
					foreach($ttransport_grade_arr as $ttransport_row){
						if($row->GRADE == $ttransport_row->GRADE
						   && (strtotime($row->TANGGAL) >= strtotime($ttransport_row->TGLMULAI))
						   && (strtotime($row->TANGGAL) <= strtotime($ttransport_row->TGLSAMPAI))
						   && $row->ZONA == $ttransport_row->ZONA){
							if($nik_temp == $row->NIK){
								$total_rpptransport += $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
								
							}else{
								$total_rpptransport = $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
							}
							
						}
					}
					
					$nik_temp = $row->NIK;
				}
				
			}
			if(sizeof($ttransport_kodejab_arr) > 0){
				$total_rpptransport = 0;
				$nik_temp = "";
				foreach($nik_arr as $row){
					foreach($ttransport_kodejab_arr as $ttransport_row){
						if($row->KODEJAB == $ttransport_row->KODEJAB
						   && (strtotime($row->TANGGAL) >= strtotime($ttransport_row->TGLMULAI))
						   && (strtotime($row->TANGGAL) <= strtotime($ttransport_row->TGLSAMPAI))
						   && $row->ZONA == $ttransport_row->ZONA){
							if($nik_temp == $row->NIK){
								$total_rpptransport += $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
								
							}else{
								$total_rpptransport = $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
							}
						}
						
					}
				}
			}
			if(sizeof($ttransport_gradekodejab_arr) > 0){
				$total_rpptransport = 0;
				$nik_temp = "";
				foreach($nik_arr as $row){
					foreach($ttransport_gradekodejab_arr as $ttransport_row){
						if($row->GRADE == $ttransport_row->GRADE
						   && $row->KODEJAB == $ttransport_row->KODEJAB
						   && (strtotime($row->TANGGAL) >= strtotime($ttransport_row->TGLMULAI))
						   && (strtotime($row->TANGGAL) <= strtotime($ttransport_row->TGLSAMPAI))
						   && $row->ZONA == $ttransport_row->ZONA){
							if($nik_temp == $row->NIK){
								$total_rpptransport += $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
								
							}else{
								$total_rpptransport = $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
							}
						}
						
					}
				}
			}
			if(sizeof($ttransport_nik_arr) > 0){
				$total_rpptransport = 0;
				$nik_temp = "";
				foreach($nik_arr as $row){
					foreach($ttransport_nik_arr as $ttransport_row){
						if($row->NIK == $ttransport_row->NIK
						   && (strtotime($row->TANGGAL) >= strtotime($ttransport_row->TGLMULAI))
						   && (strtotime($row->TANGGAL) <= strtotime($ttransport_row->TGLSAMPAI))
						   && $row->ZONA == $ttransport_row->ZONA){
							if($nik_temp == $row->NIK){
								$total_rpptransport += $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
								
							}else{
								$total_rpptransport = $ttransport_row->RPTTRANSPORT;
								/* update db.detilgaji */
								$sql = "UPDATE detilgaji AS t1
									SET t1.RPPTRANSPORT = ".$total_rpptransport."
									WHERE t1.NIK = '".$row->NIK."' AND t1.BULAN = '".$bulan."'";
								$this->db->query($sql);
							}
						}
						
					}
				}
			}
			
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
		 * Perubahan tgl 18 Juli 2013:
		 * 1. Dapatkan BULANGAJI sekarang dengan upah pokok pada bulan yang bersangkutan yaitu antara db.upahpokok.BULANMULAI dan db.upahpokok.BULANSAMPAI
		 * 2. Looping hasil no.1 untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
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
		 * 7.Hitung Tunjangan Transport [db.ttransport]
		 * 7.a.dapatkan satu tanggal paling awal ketemu di db.ttransport.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 7.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 7.a. berdasarkan db.ttransport.NOURUT
		 * 7.b. looping hasil 7.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian ttransport: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 8.Hitung Insentif Disiplin [db.insdisiplin]
		 * 8.a.dapatkan satu tanggal paling awal ketemu di db.insdisiplin.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 8.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 8.a. berdasarkan db.insdisiplin.NOURUT
		 * 8.b. looping hasil 8.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian insdisiplin: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 9.Hitung Upah Lembur [db.lembur]
		 * 9.a.dapatkan satu tanggal paling awal ketemu di db.insdisiplin.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 9.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 9.a. berdasarkan db.insdisiplin.NOURUT
		 * 9.b. looping hasil 9.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian upah lembur: 1.NOURUT
		 *
		 * 10.Hitung Tunjangan Shift [db.tshift]
		 * 10.a.dapatkan satu tanggal paling awal ketemu di db.tshift.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 10.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 10.a. berdasarkan db.tshift.NOURUT
		 * 10.b. looping hasil 10.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tshift: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 11.Hitung Tambahan Komponen Gaji [db.tambahan]
		 * 11.a.dapatkan satu tanggal paling awal ketemu di db.tambahan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 11.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 11.a. berdasarkan db.tambahan.NOURUT
		 * 11.b. looping hasil 11.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tambahan: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 12.Hitung Potongan Komponen Gaji [db.potongan]
		 * 12.a.dapatkan satu tanggal paling awal ketemu di db.potongan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 12.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 12.a. berdasarkan db.potongan.NOURUT
		 * 12.b. looping hasil 12.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian potongan: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
		 *
		 * 13.Hitung Cicilan Komponen Gaji [db.cicilan & db.detilcicilan]
		 * 13.a.dapatkan satu tanggal paling awal ketemu di db.cicilan & db.detilcicilan.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 13.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 13.a. berdasarkan db.cicilan & db.detilcicilan.NOURUT
		 * 13.b. looping hasil 13.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian cicilan: 1.NIK
		 *
		 * 14.Hitung Tunjangan QCP [db.tqcp]
		 * 14.a.dapatkan satu tanggal paling awal ketemu di db.tqcp.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 14.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 14.a. berdasarkan db.tqcp.NOURUT
		 * 14.b. looping hasil 14.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tqcp: 1.NIK+TGLMULAI
		 *
		 * 15.Hitung Uang Simpati [db.uangsimpati]
		 * 15.a.dapatkan satu tanggal paling awal ketemu di db.uangsimpati.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 15.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 15.a. berdasarkan db.uangsimpati.NOURUT
		 * 15.b. looping hasil 15.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian uang simpati: 1.BULAN+NIK
		 * 
		 * 16.Hitung Bonus [db.bonus]
		 * 16.a.dapatkan satu tanggal paling awal ketemu di db.bonus.VALIDFROM yang sama dengan TANGGAL SEKARANG atau tepat sebelum TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 16.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 16.a. berdasarkan db.bonus.NOURUT
		 * 16.b. looping hasil 16.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian tshift: 1.GRADE 2.KODEJAB 3.GRADE+KODEJAB 4.NIK
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
		/*$sql_upahpokok = "SELECT *
			FROM upahpokok
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM upahpokok WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";*/
		/*
		 * Untuk Pekerja yg masuk di bulan gaji, maka jmlmasuk/jmlharikerja*upahpokok
		 */
		/*$sql_upahpokok = "SELECT *
			FROM upahpokok
			WHERE CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED) 
			ORDER BY NOURUT";*/
		$sql_upahpokok = "SELECT *
			FROM upahpokok
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
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
		/* CATATAN:
		 * >> mencari tglmulai dan tglsampai di BULAN LALU
		 */
		/*$sql_rptpekerjaan = "SELECT tpekerjaan.NIK, tpekerjaan.GRADE, tpekerjaan.KATPEKERJAAN, tpekerjaan.RPTPEKERJAAN,
				CASE WHEN tpekerjaan.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN tpekerjaan.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN tpekerjaan.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN tpekerjaan.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN tpekerjaan.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN tpekerjaan.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM tpekerjaan
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY tpekerjaan.TGLMULAI, tpekerjaan.NOURUT";*/
		$sql_rptpekerjaan = "SELECT NIK, GRADE, KATPEKERJAAN, RPTPEKERJAAN, TGLMULAI, TGLSAMPAI
			FROM tpekerjaan
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY tpekerjaan.TGLMULAI, tpekerjaan.NOURUT";
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
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KATPEKERJAAN)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KATPEKERJAAN = $record->KATPEKERJAAN;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($katpekerjaan_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KATPEKERJAAN)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KATPEKERJAAN = $record->KATPEKERJAAN;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($gradekatpekerjaan_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KATPEKERJAAN)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTPEKERJAAN = $record->RPTPEKERJAAN;
					$obj->FPENGALI = $record->FPENGALI;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptpekerjaan ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptpekerjaan_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rptpekerjaan ke-2 berdasarkan KATPEKERJAAN */
			//$this->update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $tglmulai, $tglsampai, $katpekerjaan_arr);
			/* urutan rptpekerjaan ke-3 berdasarkan GRADE+KATPEKERJAAN */
			//$this->update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $tglmulai, $tglsampai, $gradekatpekerjaan_arr);
			/* urutan rptpekerjaan ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rptpekerjaan_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
			
			/* urutan rptpekerjaan ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptpekerjaan_bygrade($bulan, $grade_arr);
			/* urutan rptpekerjaan ke-2 berdasarkan GRADE+KATPEKERJAAN */
			$this->update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $gradekatpekerjaan_arr);
			/* urutan rptpekerjaan ke-3 berdasarkan NIK */
			$this->update_detilgaji_rptpekerjaan_bynik($bulan, $nik_arr);
			/* urutan rptpekerjaan ke-4 berdasarkan KATPEKERJAAN */
			$this->update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $katpekerjaan_arr);
		}
		
		/* 4.a. */
		$sql_rptbhs = "SELECT *
			FROM tbhs
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rptbhs = $this->db->query($sql_rptbhs)->result();
		
		/* 4.b. */
		if(sizeof($records_rptbhs) > 0){
			/* proses looping rptbhs */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptbhs as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
					&& (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->BHSJEPANG = $record->BHSJEPANG;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
					&& (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->BHSJEPANG = $record->BHSJEPANG;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
					&& (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->BHSJEPANG = $record->BHSJEPANG;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->BHSJEPANG = $record->BHSJEPANG;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptbhs ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptbhs_bygrade($bulan, $grade_arr);
			/* urutan rptbhs ke-2 berdasarkan KODEJAB | Catatan: tbhs tidak bergantung ke KODEJAB */
			//$this->update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr);
			/* urutan rptbhs ke-3 berdasarkan GRADE+KODEJAB | Catatan: tbhs tidak bergantung ke KODEJAB */
			//$this->update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rptbhs ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptbhs_bynik($bulan, $nik_arr);
		}
		
		/* 5.a. */
		/*$sql_rptjabatan = "SELECT *
			FROM tjabatan
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tjabatan WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";*/
		$sql_rptjabatan = "SELECT *
			FROM tjabatan
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
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
			$this->update_detilgaji_rptjabatan_bynik($bulan, $nik_arr);
		}
		
		/* 6.a. */
		/*$sql_rptkeluarga = "SELECT *
			FROM tkeluarga
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tkeluarga WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";*/
		$sql_rptkeluarga = "SELECT *
			FROM tkeluarga
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT(STR_TO_DATE('".$bulan."','%Y%m'),'%Y%m') AS UNSIGNED)
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
		/*$sql_rpttransport = "SELECT ttransport.NIK, ttransport.GRADE, ttransport.KODEJAB, ttransport.ZONA, ttransport.RPTTRANSPORT,
				CASE WHEN ttransport.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN ttransport.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN ttransport.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN ttransport.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN ttransport.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN ttransport.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM ttransport
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY NOURUT";*/
		$sql_rpttransport = "SELECT NIK, GRADE, KODEJAB, ZONA, RPTTRANSPORT, TGLMULAI, TGLSAMPAI
			FROM ttransport
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
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
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->ZONA = $record->ZONA;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTTRANSPORT = $record->RPTTRANSPORT;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rpttransport ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rpttransport_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rpttransport ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rpttransport_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			/* urutan rpttransport ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rpttransport_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			/* urutan rpttransport ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rpttransport_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
			
			/* urutan rpttransport ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpttransport_bygrade($bulan, $grade_arr);
			/* urutan rpttransport ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpttransport_bykodejab($bulan, $kodejab_arr);
			/* urutan rpttransport ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpttransport_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rpttransport ke-4 berdasarkan NIK */
			$this->update_detilgaji_rpttransport_bynik($bulan, $nik_arr);
		}
		
		/* 8.a. */
		$sql_rpinsdisiplin = "SELECT *
			FROM insdisiplin
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rpinsdisiplin = $this->db->query($sql_rpinsdisiplin)->result();
		
		/* 8.b. */
		if(sizeof($records_rpinsdisiplin) > 0){
			/* proses looping rpinsdisiplin */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			
			foreach($records_rpinsdisiplin as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->JMLABSEN = $record->JMLABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JMLABSEN = $record->JMLABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JMLABSEN = $record->JMLABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($gradekodejab_arr, $obj);
					
				}
			}
			
			/* urutan rpinsdisiplin ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rpinsdisiplin ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			/* urutan rpinsdisiplin ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
		}
		
		/* 9.a. */
		$sql_rptlembur = "SELECT *
			FROM lembur
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rptlembur = $this->db->query($sql_rptlembur)->result();
		
		/* 9.b. */
		if(sizeof($records_rptlembur) > 0){
			/* proses looping rptlembur */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			
			foreach($records_rptlembur as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->JAMDARI = $record->JAMDARI;
					$obj->JAMSAMPAI = $record->JAMSAMPAI;
					$obj->JENISLEMBUR = $record->JENISLEMBUR;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JAMDARI = $record->JAMDARI;
					$obj->JAMSAMPAI = $record->JAMSAMPAI;
					$obj->JENISLEMBUR = $record->JENISLEMBUR;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JAMDARI = $record->JAMDARI;
					$obj->JAMSAMPAI = $record->JAMSAMPAI;
					$obj->JENISLEMBUR = $record->JENISLEMBUR;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					array_push($gradekodejab_arr, $obj);
					
				}
			}
			
			/* urutan rptlembur ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptlembur_bygrade($bulan, $grade_arr);
			/* urutan rptlembur ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptlembur_bykodejab($bulan, $kodejab_arr);
			/* urutan rptlembur ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptlembur_bygradekodejab($bulan, $gradekodejab_arr);
		}
		
		/* 10.a. */
		/*$sql_rptshift = "SELECT tshift.NIK, tshift.GRADE, tshift.KODEJAB, tshift.SHIFTKE, tshift.FPENGALI, tshift.RPTSHIFT,
				CASE WHEN tshift.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN tshift.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN tshift.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN tshift.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN tshift.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN tshift.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM tshift
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY tshift.TGLMULAI, tshift.NOURUT";*/
		$sql_rptshift = "SELECT NIK, GRADE, KODEJAB, SHIFTKE, FPENGALI, RPTSHIFT, TGLMULAI, TGLSAMPAI
			FROM tshift
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY tshift.TGLMULAI, tshift.NOURUT";
		$records_rptshift = $this->db->query($sql_rptshift)->result();
		
		/* 10.b. */
		if(sizeof($records_rptshift) > 0){
			/* proses looping rptshift */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptshift as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptshift ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptshift_bygrade($bulan, $grade_arr);
			/* urutan rptshift ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptshift_bykodejab($bulan, $kodejab_arr);
			/* urutan rptshift ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptshift_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rptshift ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptshift_bynik($bulan, $nik_arr);
		}
		
		/* 11.a. */
		$sql_rptambahan = "SELECT *
			FROM tambahanlain2
			WHERE BULAN = '".$bulan."'
			ORDER BY NOURUT";
		$records_rptambahan = $this->db->query($sql_rptambahan)->result();
		
		/* 11.b. */
		if(sizeof($records_rptambahan) > 0){
			/* proses looping rptambahan */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptambahan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptambahan ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptambahan_bygrade($bulan, $grade_arr);
			/* urutan rptambahan ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptambahan_bykodejab($bulan, $kodejab_arr);
			/* urutan rptambahan ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptambahan_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rptambahan ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptambahan_bynik($bulan, $nik_arr);
		}
		
		/* 12.a. */
		$sql_rppotongan = "SELECT *
			FROM potonganlain2
			WHERE BULAN = '".$bulan."'
			ORDER BY NOURUT";
		$records_rppotongan = $this->db->query($sql_rppotongan)->result();
		
		/* 12.b. */
		if(sizeof($records_rppotongan) > 0){
			/* proses looping rppotongan */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rppotongan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rppotongan ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rppotongan_bygrade($bulan, $grade_arr);
			/* urutan rppotongan ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rppotongan_bykodejab($bulan, $kodejab_arr);
			/* urutan rppotongan ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rppotongan_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rppotongan ke-4 berdasarkan NIK */
			$this->update_detilgaji_rppotongan_bynik($bulan, $nik_arr);
		}
		
		/* 13.a. */
		$sql_rpcicilan = "SELECT *
			FROM pcicilan 
			WHERE BULAN = '".$bulan."'
			ORDER BY NOURUT";
		$records_rpcicilan = $this->db->query($sql_rpcicilan)->result();
		
		/* 13.b. */
		if(sizeof($records_rpcicilan) > 0){
			/* proses looping rpcicilan */
			$nik_arr = array();
			
			foreach($records_rpcicilan as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->CICILANKE = $record->CICILANKE;
				$obj->LAMACICILAN = $record->LAMACICILAN;
				$obj->KETERANGAN = $record->KETERANGAN;
				$obj->RPCICILAN = $record->RPCICILAN;
				array_push($nik_arr, $obj);
			}
			
			/* urutan rpcicilan ke-1 berdasarkan NIK */
			$this->update_detilgaji_rpcicilan_bynik($bulan, $nik_arr);
		}
		
		/* 14.a */
		/*$sql_rptqcp = "SELECT tqcp.NIK, tqcp.RPQCP,
				CASE WHEN tqcp.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN tqcp.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN tqcp.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN tqcp.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN tqcp.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN tqcp.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM tqcp
			WHERE tqcp.TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				OR tqcp.TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
			ORDER BY tqcp.NIK, tqcp.TGLMULAI";*/
		$sql_rptqcp = "SELECT NIK, RPQCP, TGLMULAI, TGLSAMPAI
			FROM tqcp
			WHERE TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
			ORDER BY NIK, TGLMULAI";
		$records_rptqcp = $this->db->query($sql_rptqcp)->result();
		
		/* 14.b */
		if(sizeof($records_rptqcp) > 0){
			/* proses looping rptqcp */
			$nik_arr = array();
			
			foreach($records_rptqcp as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->TGLMULAI = $record->TGLMULAI;
				$obj->TGLSAMPAI = $record->TGLSAMPAI;
				$obj->RPQCP = $record->RPQCP;
				array_push($nik_arr, $obj);
			}
			
			/* urutan rptqcp ke-1 berdasarkan NIK */
			$this->update_detilgaji_rptqcp_bynik($bulan, $nik_arr);
		}
		
		/* 15.a. */
		$sql_rptsimpati = "SELECT *
			FROM uangsimpati
			WHERE BULAN = '".$bulan."'
			ORDER BY NIK";
		$records_rptsimpati = $this->db->query($sql_rptsimpati)->result();
		
		/* 15.b. */
		if(sizeof($records_rptsimpati) > 0){
			/* proses looping rptsimpati */
			$nik_arr = array();
			
			foreach($records_rptsimpati as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTSIMPATI = $record->RPTSIMPATI;
				array_push($nik_arr, $obj);
			}
			
			/* urutan rptsimpati ke-1 berdasarkan NIK */
			$this->update_detilgaji_rptsimpati_bynik($bulan, $nik_arr);
		}
		
		/* 16.a. */
		/*$sql_rpbonus = "SELECT BULAN ,NOURUT ,NIK ,GRADE ,KODEJAB ,RPBONUS ,FPENGALI ,PENGALI ,UPENGALI ,PERSENTASE ,USERNAME
				,CASE WHEN TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN TGLMULAI
					ELSE NULL END AS TGLMULAI
				,CASE WHEN TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM bonus
			WHERE BULAN = '".$bulan."'
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY NOURUT";*/
		$sql_rpbonus = "SELECT BULAN ,NOURUT ,NIK ,GRADE ,KODEJAB ,RPBONUS ,FPENGALI ,PENGALI ,UPENGALI
				,PERSENTASE ,USERNAME ,TGLMULAI ,TGLSAMPAI
			FROM bonus
			WHERE BULAN = '".$bulan."'
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m%d') AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rpbonus = $this->db->query($sql_rpbonus)->result();
		
		/* 16.b. */
		if(sizeof($records_rpbonus) > 0){
			/* proses looping rpbonus */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rpbonus as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->PERSENTASE = $record->PERSENTASE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					$obj->RPBONUS = $record->RPBONUS;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->PERSENTASE = $record->PERSENTASE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					$obj->RPBONUS = $record->RPBONUS;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->PERSENTASE = $record->PERSENTASE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					$obj->RPBONUS = $record->RPBONUS;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->PERSENTASE = $record->PERSENTASE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->PENGALI = $record->PENGALI;
					$obj->UPENGALI = $record->UPENGALI;
					$obj->RPBONUS = $record->RPBONUS;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rpbonus ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpbonus_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rpbonus ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpbonus_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			/* urutan rpbonus ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpbonus_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			/* urutan rpbonus ke-4 berdasarkan NIK */
			$this->update_detilgaji_rpbonus_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
		}
		
		/* 17.a. */
		$sql_rpthadir = "SELECT *
			FROM tkehadiran
			WHERE BULAN = '".$bulan."'";
		$records_rpthadir = $this->db->query($sql_rpthadir)->result();
		
		/* 17.b. */
		if(sizeof($records_rpthadir) > 0){
			/* proses looping rpthadir */
			$nik_arr = array();
			
			foreach($records_rpthadir as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTHADIR = $record->RPTHADIR;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpthadir_bynik($bulan, $nik_arr);
		}
		
		/* 18.a. */
		$sql_rpthr = "SELECT *
			FROM thr
			WHERE BULAN = '".$bulan."'";
		$records_rpthr = $this->db->query($sql_rpthr)->result();
		
		/* 18.b. */
		if(sizeof($records_rpthr) > 0){
			/* proses looping rpthr */
			$nik_arr = array();
			
			foreach($records_rpthr as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->MSKERJADARI = $record->MSKERJADARI;
				$obj->MSKERJASAMPAI = $record->MSKERJASAMPAI;
				$obj->PEMBAGI = $record->PEMBAGI;
				$obj->PENGALI = $record->PENGALI;
				$obj->UPENGALI = $record->UPENGALI;
				$obj->RPTHR = $record->RPTHR;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpthr_bynik($bulan, $nik_arr);
		}
		
		/* 19.a. */
		$sql_rptkacamata = "SELECT *
			FROM tkacamata
			WHERE BULAN = '".$bulan."'";
		$records_rptkacamata = $this->db->query($sql_rptkacamata)->result();
		
		/* 19.b. */
		if(sizeof($records_rptkacamata) > 0){
			/* proses looping rptkacamata */
			$nik_arr = array();
			
			foreach($records_rptkacamata as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPFRAME = $record->RPFRAME;
				$obj->RPLENSA = $record->RPLENSA;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rptkacamata_bynik($bulan, $nik_arr);
		}
		
		/* 20.a. */
		$sql_rpkompen = "SELECT *
			FROM kompensasicuti
			WHERE BULAN = '".$bulan."'";
		$records_rpkompen = $this->db->query($sql_rpkompen)->result();
		
		/* 20.b. */
		if(sizeof($records_rpkompen) > 0){
			/* proses looping rpkompen */
			$nik_arr = array();
			
			foreach($records_rpkompen as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->SISACUTI = $record->SISACUTI;
				$obj->RPKOMPEN = $record->RPKOMPEN;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpkompen_bynik($bulan, $nik_arr);
		}
		
		/* 21.a. */
		$sql_rpmakan = "SELECT NIK, SUM(RPTMAKAN) AS RPTMAKAN, SUM(RPPMAKAN) AS RPPMAKAN
			FROM trmakan
			WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
			GROUP BY NIK";
		$records_rpmakan = $this->db->query($sql_rpmakan)->result();
		
		/* 21.b. */
		if(sizeof($records_rpmakan) > 0){
			/* proses looping rpmakan */
			$nik_arr = array();
			
			foreach($records_rpmakan as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTMAKAN = $record->RPTMAKAN;
				$obj->RPPMAKAN = $record->RPPMAKAN;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpmakan_bynik($bulan, $nik_arr);
		}
		
		/* 22.a. */
		$sql_rppupahpokok = "SELECT NIK, SUM(JAMKURANG) AS JAMKURANG
			FROM hitungpresensi
			WHERE BULAN = '".$bulan."' AND JAMKURANG > 2
			GROUP BY NIK";
		$records_rppupahpokok = $this->db->query($sql_rppupahpokok)->result();
		
		/* 22.b. */
		if(sizeof($records_rppupahpokok) > 0){
			/* proses looping rppupahpokok */
			$nik_arr = array();
			
			foreach($records_rppupahpokok as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rppupahpokok_bynik($bulan, $nik_arr);
		}
		
		/* 23.a. */
		$sql_rpptransport = "SELECT jemputankar.NIK, jemputankar.TANGGAL, karyawan.GRADE, karyawan.KODEJAB, jemputankar.ZONA
			FROM karyawan 
			JOIN jemputankar ON(jemputankar.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND jemputankar.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				AND jemputankar.NIK = karyawan.NIK
				AND jemputankar.IKUTJEMPUTAN = 'Y')
			ORDER BY jemputankar.NIK";
		$records_rpptransport = $this->db->query($sql_rpptransport)->result();
		
		/* 23.b. */
		if(sizeof($records_rpptransport) > 0){
			/* proses looping rpptransport */
			$nik_arr = array();
			
			foreach($records_rpptransport as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->TANGGAL = $record->TANGGAL;
				$obj->GRADE = $record->GRADE;
				$obj->KODEJAB = $record->KODEJAB;
				$obj->ZONA = $record->ZONA;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpptransport_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
		}
		
		/* 99. */
		$sqlu_gajibulanan = "UPDATE gajibulanan AS t1 JOIN (
					SELECT detilgaji.NIK,
						SUM(detilgaji.RPUPAHPOKOK) AS RPUPAHPOKOK,
						SUM(detilgaji.RPTJABATAN) AS RPTJABATAN,
						SUM(detilgaji.RPTISTRI) AS RPTISTRI,
						SUM(detilgaji.RPTANAK) AS RPTANAK,
						SUM(detilgaji.RPTBHS) AS RPTBHS,
						SUM(detilgaji.RPTTRANSPORT) AS RPTTRANSPORT,
						SUM(detilgaji.RPTSHIFT) AS RPTSHIFT,
						SUM(detilgaji.RPTPEKERJAAN) AS RPTPEKERJAAN,
						SUM(detilgaji.RPTQCP) AS RPTQCP,
						SUM(detilgaji.RPIDISIPLIN) AS RPIDISIPLIN,
						SUM(detilgaji.RPTLEMBUR) AS RPTLEMBUR,
						SUM(detilgaji.RPTHADIR) AS RPTHADIR,
						SUM(detilgaji.RPTHR) AS RPTHR,
						SUM(detilgaji.RPBONUS) AS RPBONUS,
						SUM(detilgaji.RPKOMPEN) AS RPKOMPEN,
						SUM(detilgaji.RPTMAKAN) AS RPTMAKAN,
						SUM(detilgaji.RPTSIMPATI) AS RPTSIMPATI,
						SUM(detilgaji.RPTKACAMATA) AS RPTKACAMATA,
						SUM(detilgaji.RPPUPAHPOKOK) AS RPPUPAHPOKOK,
						SUM(detilgaji.RPPMAKAN) AS RPPMAKAN,
						SUM(detilgaji.RPPTRANSPORT) AS RPPTRANSPORT,
						SUM(detilgaji.RPCICILAN1) AS RPCICILAN1,
						SUM(detilgaji.RPCICILAN2) AS RPCICILAN2,
						SUM(detilgaji.RPPOTONGAN1) AS RPPOTONGAN1,
						SUM(detilgaji.RPPOTONGAN2) AS RPPOTONGAN2,
						SUM(detilgaji.RPPOTONGAN3) AS RPPOTONGAN3,
						SUM(detilgaji.RPPOTONGAN4) AS RPPOTONGAN4,
						SUM(detilgaji.RPPOTONGAN5) AS RPPOTONGAN5,
						SUM(detilgaji.RPPOTONGANLAIN) AS RPPOTONGANLAIN,
						SUM(detilgaji.RPTAMBAHAN1) AS RPTAMBAHAN1,
						SUM(detilgaji.RPTAMBAHAN2) AS RPTAMBAHAN2,
						SUM(detilgaji.RPTAMBAHAN3) AS RPTAMBAHAN3,
						SUM(detilgaji.RPTAMBAHAN4) AS RPTAMBAHAN4,
						SUM(detilgaji.RPTAMBAHAN5) AS RPTAMBAHAN5,
						SUM(detilgaji.RPTAMBAHANLAIN) AS RPTAMBAHANLAIN
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
					GROUP BY detilgaji.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t2.NIK = t1.NIK)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK,
				t1.RPTUNJTETAP = (t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS),
				t1.RPTUNJTDKTTP = (t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP),
				t1.RPNONUPAH = (t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA),
				t1.RPPOTONGAN = (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + t2.RPPOTONGAN1 + t2.RPPOTONGAN2 + t2.RPPOTONGAN3 + t2.RPPOTONGAN4 + t2.RPPOTONGAN5 + t2.RPPOTONGANLAIN),
				t1.RPTAMBAHAN = (t2.RPTAMBAHAN1 + t2.RPTAMBAHAN2 + t2.RPTAMBAHAN3 + t2.RPTAMBAHAN4 + t2.RPTAMBAHAN5 + t2.RPTAMBAHANLAIN),
				t1.RPTOTGAJI = (t2.RPUPAHPOKOK
					+ t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS
					+ t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP
					+ t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA
					+ t2.RPTAMBAHAN1 + t2.RPTAMBAHAN2 + t2.RPTAMBAHAN3 + t2.RPTAMBAHAN4 + t2.RPTAMBAHAN5 + t2.RPTAMBAHANLAIN)
					- (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + t2.RPPOTONGAN1 + t2.RPPOTONGAN2 + t2.RPPOTONGAN3 + t2.RPPOTONGAN4 + t2.RPPOTONGAN5 + t2.RPPOTONGANLAIN)";
		$this->db->query($sqlu_gajibulanan);
		
	}
	
}
?>