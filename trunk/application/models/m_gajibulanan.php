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
		$sql = "INSERT INTO gajibulanan (NIK, BULAN, NOACCKAR, NAMABANK, USERNAME)
			SELECT NIK, '".$bulan."', NOACCKAR, NAMABANK, '".$this->session->userdata('user_name')."' from karyawan
			where STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function gen_detilgaji($bulan, $tglmulai, $tglsampai){
		$sql = "INSERT INTO detilgaji (NIK, BULAN, NOREVISI)
			SELECT NIK, '".$bulan."', 1 from KARYAWAN
			where STATUS='T' or STATUS='K' or STATUS='C'";
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
	
	function update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr){
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
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
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
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
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
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan
							ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KATPEKERJAAN = '".$row->KATPEKERJAAN."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
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
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
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
		$this->firephp->log($nik_arr);
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTJABATAN = ".$row->RPTJABATAN."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkeluarga_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.STATTUNKEL = 'F'
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
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
								AND keluarga.NOURUT = '".$anak_ke."'
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
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.STATTUNKEL = 'F'
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
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
								AND keluarga.NOURUT = '".$anak_ke."'
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
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.STATTUNKEL = 'F'
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
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
								AND keluarga.NOURUT = '".$anak_ke."'
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
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptkeluarga_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND karyawan.STATTUNKEL = 'F'
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
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
								AND keluarga.NOURUT = '".$anak_ke."'
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
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rpttransport_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
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
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpttransport_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji AS t1 JOIN (
					SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
					FROM hitungpresensi JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND karyawan.STATTUNTRAN = 'Y'
						AND karyawan.ZONA = '".$row->ZONA."'
						AND karyawan.NIK = hitungpresensi.NIK)
					WHERE 
						hitungpresensi.JENISABSEN = 'HD' AND
						hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
						hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					GROUP BY hitungpresensi.NIK
				) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
				SET t1.RPTTRANSPORT = ".$row->RPTTRANSPORT." * t2.JMLHADIR";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				JOIN vu_jmlabsen ON(vu_jmlabsen.NIK = detilgaji.NIK AND vu_jmlabsen.JMLABSEN = ".$row->FABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				JOIN vu_jmlabsen ON(vu_jmlabsen.NIK = detilgaji.NIK AND vu_jmlabsen.JMLABSEN = ".$row->FABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				JOIN vu_jmlabsen ON(vu_jmlabsen.NIK = detilgaji.NIK AND vu_jmlabsen.JMLABSEN = ".$row->FABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpinsdisiplin_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji
				JOIN vu_jmlabsen ON(vu_jmlabsen.NIK = detilgaji.NIK AND vu_jmlabsen.JMLABSEN = ".$row->FABSEN."
					AND detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."')
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptlembur_bynourut($bulan, $lembur_arr){
		foreach($lembur_arr as $row){
			if($row->JENISLEMBUR == 'A'){
				$sql = "UPDATE detilgaji
					JOIN hitungpresensi ON(hitungpresensi.NIK = detilgaji.NIK AND hitungpresensi.BULAN = detilgaji.BULAN
						AND hitungpresensi.JAMLEMBUR >= ".$row->JAMDARI." AND hitungpresensi.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND hitungpresensi.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND hitungpresensi.BULAN = '".$bulan."')
					SET detilgaji.RPTLEMBUR = (hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * detilgaji.RPUPAHPOKOK) / 173";
				$this->db->query($sql);
			}elseif($row->JENISLEMBUR == 'B'){
				$sql = "UPDATE detilgaji
					JOIN hitungpresensi ON(hitungpresensi.NIK = detilgaji.NIK AND hitungpresensi.BULAN = detilgaji.BULAN
						AND hitungpresensi.JAMLEMBUR >= ".$row->JAMDARI." AND hitungpresensi.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND hitungpresensi.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND hitungpresensi.BULAN = '".$bulan."')
					SET detilgaji.RPTLEMBUR = (hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (detilgaji.RPUPAHPOKOK + detilgaji.RPTTRANSPORT + detilgaji.RPTPEKERJAAN + detilgaji.RPTSHIFT)) / 173";
				$this->db->query($sql);
			}elseif($row->JENISLEMBUR == 'C'){
				$sql = "UPDATE detilgaji
					JOIN hitungpresensi ON(hitungpresensi.NIK = detilgaji.NIK AND hitungpresensi.BULAN = detilgaji.BULAN
						AND hitungpresensi.JAMLEMBUR >= ".$row->JAMDARI." AND hitungpresensi.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND hitungpresensi.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND hitungpresensi.BULAN = '".$bulan."')
					SET detilgaji.RPTLEMBUR = (hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (detilgaji.RPUPAHPOKOK + detilgaji.RPTISTRI + detilgaji.RPTANAK + detilgaji.RPTBHS + detilgaji.RPTJABATAN)) / 173";
				$this->db->query($sql);
			}elseif($row->JENISLEMBUR == 'D'){
				$sql = "UPDATE detilgaji
					JOIN hitungpresensi ON(hitungpresensi.NIK = detilgaji.NIK AND hitungpresensi.BULAN = detilgaji.BULAN
						AND hitungpresensi.JAMLEMBUR >= ".$row->JAMDARI." AND hitungpresensi.JAMLEMBUR <= ".$row->JAMSAMPAI."
						AND hitungpresensi.JENISLEMBUR = '".$row->JENISLEMBUR."'
						AND hitungpresensi.BULAN = '".$bulan."')
					SET detilgaji.RPTLEMBUR = (hitungpresensi.JAMLEMBUR * ".$row->PENGALI." * (detilgaji.RPUPAHPOKOK + detilgaji.RPTISTRI + detilgaji.RPTANAK + detilgaji.RPTJABATAN)) / 173";
				$this->db->query($sql);
			}
		}
	}
	
	function update_detilgaji_rptshift_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		foreach($grade_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t2.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.KODEJAB = '".$row->KODEJAB."'
							AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
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
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptshift_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.NIK = '".$row->NIK."' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE karyawanshift.NIK = '".$row->NIK."'
							AND pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
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
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					JOIN (
						SELECT karyawanshift.NIK
						FROM karyawanshift JOIN pembagianshift ON(pembagianshift.KODESHIFT = karyawanshift.KODESHIFT)
						WHERE karyawanshift.NIK = '".$row->NIK."'
							AND pembagianshift.TGLMULAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
							AND pembagianshift.TGLSAMPAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
							AND pembagianshift.SHIFTKE = '".$row->SHIFTKE."'
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT."
					WHERE t2.JMLHADIR > 0";
			}
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptambahan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTAMBAHAN = detilgaji.RPTAMBAHAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptambahan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTAMBAHAN = detilgaji.RPTAMBAHAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptambahan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTAMBAHAN = detilgaji.RPTAMBAHAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptambahan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTAMBAHAN = detilgaji.RPTAMBAHAN + ".$row->JUMLAH."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rppotongan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPPOTONGAN = detilgaji.RPPOTONGAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rppotongan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPPOTONGAN = detilgaji.RPPOTONGAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rppotongan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPPOTONGAN = detilgaji.RPPOTONGAN + ".$row->JUMLAH;
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rppotongan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPPOTONGAN = detilgaji.RPPOTONGAN + ".$row->JUMLAH."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpcicilan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPCICILAN = detilgaji.RPCICILAN + ".$row->RPCICILAN."
				WHERE detilgaji.NIK = '".$row->NIK."' AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
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
				/*$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHARIKERJA
						FROM hitungpresensi JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' AND karyawan.NIK = hitungpresensi.NIK)
						WHERE 
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d') AND
							(hitungpresensi.JENISABSEN = 'HD' OR SUBSTR(hitungpresensi.JENISABSEN,1,1) = 'C')
						GROUP BY hitungpresensi.NIK
					) AS t2 ON(t2.NIK = t1.NIK AND t1.BULAN = '".$bulan."')
					SET t1.RPBONUS = ((t2.JMLHARIKERJA / ((SELECT HariKerja(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')))
						- ))
						() )";*/
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
		$this->firephp->log($records_rptpekerjaan);
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
			$this->update_detilgaji_rptjabatan_bynik($bulan, $nik_arr);
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
		
		/* 8.a. */
		$sql_rpinsdisiplin = "SELECT *
			FROM insdisiplin
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM insdisiplin WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rpinsdisiplin = $this->db->query($sql_rpinsdisiplin)->result();
		
		/* 8.b. */
		if(sizeof($records_rpinsdisiplin) > 0){
			/* proses looping rpinsdisiplin */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rpinsdisiplin as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->FABSEN = $record->FABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->FABSEN = $record->FABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->FABSEN = $record->FABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->FABSEN = $record->FABSEN;
					$obj->RPIDISIPLIN = $record->RPIDISIPLIN;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rpinsdisiplin ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rpinsdisiplin_bygrade($bulan, $grade_arr);
			/* urutan rpinsdisiplin ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rpinsdisiplin_bykodejab($bulan, $kodejab_arr);
			/* urutan rpinsdisiplin ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $gradekodejab_arr);
			/* urutan rpinsdisiplin ke-4 berdasarkan NIK */
			$this->update_detilgaji_rpinsdisiplin_bynik($bulan, $nik_arr);
		}
		
		/* 9.a. */
		$sql_rptlembur = "SELECT *
			FROM lembur
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM lembur WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
		$records_rptlembur = $this->db->query($sql_rptlembur)->result();
		
		/* 9.b. */
		if(sizeof($records_rptlembur) > 0){
			/* proses looping rptlembur */
			$lembur_arr = array();
			
			foreach($records_rptlembur as $record){
				$obj = new stdClass();
				$obj->NOURUT = $record->NOURUT;
				$obj->JAMDARI = $record->JAMDARI;
				$obj->JAMSAMPAI = $record->JAMSAMPAI;
				$obj->JENISLEMBUR = $record->JENISLEMBUR;
				$obj->PENGALI = $record->PENGALI;
				$obj->UPENGALI = $record->UPENGALI;
				array_push($lembur_arr, $obj);
			}
			
			/* urutan rptlembur ke-1 berdasarkan NOURUT */
			$this->update_detilgaji_rptlembur_bynourut($bulan, $lembur_arr);
		}
		
		/* 10.a. */
		$sql_rptshift = "SELECT *
			FROM tshift
			WHERE VALIDFROM = (
				SELECT VALIDFROM FROM tshift WHERE VALIDFROM <= DATE_FORMAT(NOW(),'%Y-%m-%d') ORDER BY VALIDFROM DESC LIMIT 1
			)
			ORDER BY NOURUT";
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
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->SHIFTKE = $record->SHIFTKE;
					$obj->FPENGALI = $record->FPENGALI;
					$obj->RPTSHIFT = $record->RPTSHIFT;
					array_push($nik_arr, $obj);
					
				}
			}
			
			/* urutan rptshift ke-1 berdasarkan GRADE */
			$this->update_detilgaji_rptshift_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			/* urutan rptshift ke-2 berdasarkan KODEJAB */
			$this->update_detilgaji_rptshift_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			/* urutan rptshift ke-3 berdasarkan GRADE+KODEJAB */
			$this->update_detilgaji_rptshift_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			/* urutan rptshift ke-4 berdasarkan NIK */
			$this->update_detilgaji_rptshift_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
		}
		
		/* 11.a. */
		$sql_rptambahan = "SELECT *
			FROM tambahan
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
					$obj->JUMLAH = $record->JUMLAH;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JUMLAH = $record->JUMLAH;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JUMLAH = $record->JUMLAH;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->JUMLAH = $record->JUMLAH;
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
			FROM potongan
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
					$obj->JUMLAH = $record->JUMLAH;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JUMLAH = $record->JUMLAH;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->JUMLAH = $record->JUMLAH;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->JUMLAH = $record->JUMLAH;
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
		$sql_rpcicilan = "SELECT cicilan.NIK, detilcicilan.RPCICILAN
			FROM cicilan JOIN detilcicilan ON(detilcicilan.NOCICILAN = cicilan.NOCICILAN)
			WHERE detilcicilan.BULAN = '".$bulan."'
			ORDER BY cicilan.NIK";
		$records_rpcicilan = $this->db->query($sql_rpcicilan)->result();
		
		/* 13.b. */
		if(sizeof($records_rpcicilan) > 0){
			/* proses looping rpcicilan */
			$nik_arr = array();
			
			foreach($records_rpcicilan as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPCICILAN = $record->RPCICILAN;
				array_push($nik_arr, $obj);
			}
			
			/* urutan rpcicilan ke-1 berdasarkan NIK */
			$this->update_detilgaji_rpcicilan_bynik($bulan, $nik_arr);
		}
		
		/* 14.a */
		$sql_rptqcp = "SELECT tqcp.NIK, tqcp.RPQCP,
				CASE WHEN tqcp.TGLMULAI = STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					WHEN tqcp.TGLMULAI > STR_TO_DATE('".$tglmulai."', '%Y-%m-%d') THEN tqcp.TGLMULAI
					ELSE NULL END AS TGLMULAI,
				CASE WHEN tqcp.TGLSAMPAI = STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					WHEN tqcp.TGLSAMPAI < STR_TO_DATE('".$tglsampai."', '%Y-%m-%d') THEN tqcp.TGLSAMPAI
					ELSE NULL END AS TGLSAMPAI
			FROM tqcp
			WHERE tqcp.TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				OR tqcp.TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
			ORDER BY tqcp.NIK, tqcp.TGLMULAI";
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
			WHERE uangsimpati.BULAN = '".$bulan."'
			ORDER BY uangsimpati.NIK";
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
		$sql_rpbonus = "SELECT *
			FROM bonus
			WHERE BULAN = '".$bulan."'
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
	
}
?>