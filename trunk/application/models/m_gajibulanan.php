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
			/* DELETE db.detilgajitambahan, db.detilgajipotongan, db.detilgaji, dan db.gajibulanan BY $bulan*/
			$this->db->where(array('BULAN'=>$bulan))->delete('detilgajitambahan');
			$this->db->where(array('BULAN'=>$bulan))->delete('detilgajipotongan');
			$this->db->where(array('BULAN'=>$bulan))->delete('detilgaji');
			$this->db->where(array('BULAN'=>$bulan))->delete('gajibulanan');
			
			/* Proses HITUNGGAJI ALL */
			$this->hitunggaji_all($bulan, $tglmulai, $tglsampai);
		}
		
		//$query  = $this->db->where(array('BULAN'=>$bulan))->limit($limit, $start)->order_by('NIK', 'ASC')->get('gajibulanan')->result();
		//$total  = $this->db->get('gajibulanan')->num_rows();
		$sql = "SELECT gajibulanan.BULAN, gajibulanan.NIK,
				gajibulanan.RPUPAHPOKOK, gajibulanan.RPTUNJTETAP, gajibulanan.RPTUNJTDKTTP, gajibulanan.RPNONUPAH,
				gajibulanan.RPPOTONGAN, gajibulanan.RPTAMBAHAN, gajibulanan.RPTOTGAJI,
				gajibulanan.NOACCKAR, gajibulanan.NAMABANK, gajibulanan.TGLDIBAYAR,
				karyawan.NAMAKAR, karyawan.GRADE, karyawan.TGLMASUK, unitkerja.SINGKATAN,
				karyawan.NPWP, leveljabatan.NAMALEVEL,
				CASE WHEN (karyawan.STATUS = 'T') THEN 'Tetap'
					WHEN (karyawan.STATUS = 'K') THEN 'Kontrak'
					WHEN (karyawan.STATUS = 'C') THEN 'Percobaan'
					WHEN (karyawan.STATUS = 'P') THEN 'Pensiun'
					WHEN (karyawan.STATUS = 'H') THEN 'PHK'
					ELSE 'Meninggal' END AS STATUSKAR,
				v_detilgaji.RPUMSK, v_detilgaji.RPTJABATAN, v_detilgaji.RPTANAK, v_detilgaji.RPTISTRI,
				v_detilgaji.RPTBHS, v_detilgaji.RPTTRANSPORT, v_detilgaji.RPTSHIFT, v_detilgaji.RPTPEKERJAAN,
				v_detilgaji.RPTQCP, v_detilgaji.RPTHADIR, v_detilgaji.RPTLEMBUR, v_detilgaji.RPIDISIPLIN,
				v_detilgaji.RPKOMPEN, v_detilgaji.RPTMAKAN, v_detilgaji.RPTSIMPATI,
				v_detilgaji.RPPUPAHPOKOK, v_detilgaji.RPPMAKAN, v_detilgaji.RPPTRANSPORT,
				v_detilgaji.RPPJAMSOSTEK, v_detilgaji.RPCICILAN1, v_detilgaji.RPCICILAN2, v_detilgaji.RPPOTSP,
				v_detilgajitambahan_b.BTAMBAHAN_KODEUPAH, v_detilgajitambahan_b.BTAMBAHAN_NAMAUPAH,
				v_detilgajitambahan_b.BTAMBAHAN_KETERANGAN, v_detilgajitambahan_b.BTAMBAHAN_RPTAMBAHAN,
				v_detilgajitambahan_j.JTAMBAHAN_KODEUPAH, v_detilgajitambahan_j.JTAMBAHAN_NAMAUPAH,
				v_detilgajitambahan_j.JTAMBAHAN_KETERANGAN, v_detilgajitambahan_j.JTAMBAHAN_RPTAMBAHAN,
				v_detilgajitambahan_l.LTAMBAHAN_KODEUPAH, v_detilgajitambahan_l.LTAMBAHAN_NAMAUPAH,
				v_detilgajitambahan_l.LTAMBAHAN_KETERANGAN, v_detilgajitambahan_l.LTAMBAHAN_RPTAMBAHAN,
				v_detilgajipotongan_b.BPOTONGAN_KODEPOTONGAN, v_detilgajipotongan_b.BPOTONGAN_NAMAPOTONGAN,
				v_detilgajipotongan_b.BPOTONGAN_KETERANGAN, v_detilgajipotongan_b.BPOTONGAN_RPPOTONGAN,
				v_detilgajipotongan_j.JPOTONGAN_KODEPOTONGAN, v_detilgajipotongan_j.JPOTONGAN_NAMAPOTONGAN,
				v_detilgajipotongan_j.JPOTONGAN_KETERANGAN, v_detilgajipotongan_j.JPOTONGAN_RPPOTONGAN,
				v_detilgajipotongan_l.LPOTONGAN_KODEPOTONGAN, v_detilgajipotongan_l.LPOTONGAN_NAMAPOTONGAN,
				v_detilgajipotongan_l.LPOTONGAN_KETERANGAN, v_detilgajipotongan_l.LPOTONGAN_RPPOTONGAN,
				v_detilgaji.RPTHR,
				cutitahunan.SISACUTI,
				satlembur.SATLEMBUR
			FROM gajibulanan
			JOIN (
				SELECT detilgaji.BULAN, detilgaji.NIK,
					SUM(RPUPAHPOKOK) AS RPUPAHPOKOK, SUM(RPTJABATAN) AS RPTJABATAN, SUM(RPTANAK) AS RPTANAK,
					SUM(RPTISTRI) AS RPTISTRI, SUM(RPTBHS) AS RPTBHS, SUM(RPTTRANSPORT) AS RPTTRANSPORT,
					SUM(RPTSHIFT) AS RPTSHIFT, SUM(RPTPEKERJAAN) AS RPTPEKERJAAN, SUM(RPTQCP) AS RPTQCP,
					SUM(RPTLEMBUR) AS RPTLEMBUR, SUM(RPIDISIPLIN) AS RPIDISIPLIN, SUM(RPTHADIR) AS RPTHADIR,
					SUM(RPKOMPEN) AS RPKOMPEN, SUM(RPTMAKAN) AS RPTMAKAN, SUM(RPTSIMPATI) AS RPTSIMPATI,
					SUM(RPTHR) AS RPTHR, SUM(RPBONUS) AS RPBONUS, SUM(RPTKACAMATA) AS RPTKACAMATA,
					SUM(RPPUPAHPOKOK) AS RPPUPAHPOKOK, SUM(RPPMAKAN) AS RPPMAKAN,
					SUM(RPPTRANSPORT) AS RPPTRANSPORT, SUM(RPPJAMSOSTEK) AS RPPJAMSOSTEK,
					SUM(RPCICILAN1) AS RPCICILAN1, SUM(RPCICILAN2) AS RPCICILAN2,
					SUM(RPPOTSP) AS RPPOTSP, SUM(RPUMSK) AS RPUMSK
				FROM detilgaji
				WHERE detilgaji.BULAN = '".$bulan."'
				GROUP BY detilgaji.BULAN, detilgaji.NIK
			) AS v_detilgaji ON(v_detilgaji.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK,
					GROUP_CONCAT(IFNULL(KODEUPAH, '')) AS BTAMBAHAN_KODEUPAH,
					GROUP_CONCAT(IFNULL(NAMAUPAH, '')) AS BTAMBAHAN_NAMAUPAH,
					GROUP_CONCAT(IFNULL(KETERANGAN, '')) AS BTAMBAHAN_KETERANGAN,
					GROUP_CONCAT(IFNULL(RPTAMBAHAN, 0)) AS BTAMBAHAN_RPTAMBAHAN
				FROM detilgajitambahan
				WHERE detilgajitambahan.POSCETAK = 'B' AND detilgajitambahan.BULAN = '".$bulan."'
				GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK
			) AS v_detilgajitambahan_b ON(v_detilgajitambahan_b.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK,
					GROUP_CONCAT(IFNULL(KODEUPAH, '')) AS JTAMBAHAN_KODEUPAH,
					'Lain-lain' AS JTAMBAHAN_NAMAUPAH,
					GROUP_CONCAT(IFNULL(KETERANGAN, '')) AS JTAMBAHAN_KETERANGAN,
					SUM(RPTAMBAHAN) AS JTAMBAHAN_RPTAMBAHAN
				FROM detilgajitambahan
				WHERE detilgajitambahan.POSCETAK = 'J' AND detilgajitambahan.BULAN = '".$bulan."'
				GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK
			) AS v_detilgajitambahan_j ON(v_detilgajitambahan_j.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK,
					GROUP_CONCAT(IFNULL(detilgajitambahan.KODEUPAH, '')) AS LTAMBAHAN_KODEUPAH,
					GROUP_CONCAT(IFNULL(jenistambahan.NAMAUPAHALTERNATIF, '')) AS LTAMBAHAN_NAMAUPAH,
					GROUP_CONCAT(IFNULL(detilgajitambahan.KETERANGAN, '')) AS LTAMBAHAN_KETERANGAN,
					GROUP_CONCAT(IFNULL(detilgajitambahan.RPTAMBAHAN, 0)) AS LTAMBAHAN_RPTAMBAHAN
				FROM detilgajitambahan
				LEFT JOIN jenistambahan ON(jenistambahan.KODEUPAH = detilgajitambahan.KODEUPAH)
				WHERE detilgajitambahan.POSCETAK = 'L' AND detilgajitambahan.BULAN = '".$bulan."'
				GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK
			) AS v_detilgajitambahan_l ON(v_detilgajitambahan_l.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK,
					GROUP_CONCAT(IFNULL(KODEPOTONGAN, '')) AS BPOTONGAN_KODEPOTONGAN,
					GROUP_CONCAT(IFNULL(NAMAPOTONGAN, '')) AS BPOTONGAN_NAMAPOTONGAN,
					GROUP_CONCAT(IFNULL(KETERANGAN, '')) AS BPOTONGAN_KETERANGAN,
					GROUP_CONCAT(IFNULL(RPPOTONGAN, 0)) AS BPOTONGAN_RPPOTONGAN
				FROM detilgajipotongan
				WHERE detilgajipotongan.POSCETAK = 'B' AND detilgajipotongan.BULAN = '".$bulan."'
				GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK
			) AS v_detilgajipotongan_b ON(v_detilgajipotongan_b.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK,
					GROUP_CONCAT(IFNULL(KODEPOTONGAN, '')) AS JPOTONGAN_KODEPOTONGAN,
					'Lain-lain' AS JPOTONGAN_NAMAPOTONGAN,
					GROUP_CONCAT(IFNULL(KETERANGAN, '')) AS JPOTONGAN_KETERANGAN,
					SUM(RPPOTONGAN) AS JPOTONGAN_RPPOTONGAN
				FROM detilgajipotongan
				WHERE detilgajipotongan.POSCETAK = 'J' AND detilgajipotongan.BULAN = '".$bulan."'
				GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK
			) AS v_detilgajipotongan_j ON(v_detilgajipotongan_j.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK,
					GROUP_CONCAT(IFNULL(detilgajipotongan.KODEPOTONGAN, '')) AS LPOTONGAN_KODEPOTONGAN,
					GROUP_CONCAT(IFNULL(jenispotongan.NAMAPOTONGANALTERNATIF, '')) AS LPOTONGAN_NAMAPOTONGAN,
					GROUP_CONCAT(IFNULL(detilgajipotongan.KETERANGAN, '')) AS LPOTONGAN_KETERANGAN,
					GROUP_CONCAT(IFNULL(detilgajipotongan.RPPOTONGAN, 0)) AS LPOTONGAN_RPPOTONGAN
				FROM detilgajipotongan
				LEFT JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = detilgajipotongan.KODEPOTONGAN)
				WHERE detilgajipotongan.POSCETAK = 'L' AND detilgajipotongan.BULAN = '".$bulan."'
				GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK
			) AS v_detilgajipotongan_l ON(v_detilgajipotongan_l.NIK = gajibulanan.NIK)
			LEFT JOIN karyawan ON(karyawan.NIK = gajibulanan.NIK)
			LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = karyawan.KODEUNIT)
			LEFT JOIN leveljabatan ON(leveljabatan.KODEJAB = karyawan.KODEJAB)
			LEFT JOIN (
				SELECT NIK, SUM(SISACUTI) AS SISACUTI
				FROM cutitahunan
				WHERE DIKOMPENSASI = 'N'
				GROUP BY NIK
			) AS cutitahunan ON(cutitahunan.NIK = gajibulanan.NIK)
			LEFT JOIN (
				SELECT NIK, SUM(SATLEMBUR) AS SATLEMBUR
				FROM hitungpresensi
				WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				GROUP BY NIK
			) AS satlembur ON(satlembur.NIK = gajibulanan.NIK)
			WHERE gajibulanan.BULAN = '".$bulan."'";
		$sql .= " LIMIT ".$start.",".$limit;
		$query  = $this->db->query($sql)->result();
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
	 * Fungsi	: get_periodegaji
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
		$sql = "INSERT INTO detilgaji (NIK, BULAN, NOREVISI, GRADE, KODEJAB, KODESP, MASA_KERJA_BLN, MASA_KERJA_HARI
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
				,RPPOTONGANLAIN
				,RPPOTSP
				,RPUMSK)
			SELECT NIK, '".$bulan."', 1, GRADE, KODEJAB, KODESP
				,IFNULL(period_diff(date_format(now(), '%Y%m'),date_format(karyawan.TGLMASUK,'%Y%m')),0) AS MASA_KERJA_BLN
				,(IFNULL(DATEDIFF(LAST_DAY(NOW()),TGLMASUK),0)+1) AS MASA_KERJA_HARI
				,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0
				,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0 ,0
				,0 ,0
			FROM karyawan
			WHERE STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
		
		/* generate data db.detilgaji untuk karyawan yang memiliki mutasi */
		$sql_mutasi = "SELECT *
			FROM karyawanmut
			WHERE karyawanmut.VALIDTO >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND karyawanmut.VALIDTO <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
			ORDER BY NIK, VALIDTO DESC";
	}
	
	/*function update_detilgaji_rpupahpokok_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
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
	}*/
	function update_detilgaji_rpupahpokok_bygrade($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, GRADE, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK";
		$this->db->query($sql);
		
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, GRADE, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPUPAHPOKOK = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d'))))
				* t2.RPUPAHPOKOK)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpupahpokok_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
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
	}*/
	function update_detilgaji_rpupahpokok_bykodejab($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, KODEJAB, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK";
		$this->db->query($sql);
		
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, KODEJAB, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPUPAHPOKOK = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d'))))
				* t2.RPUPAHPOKOK)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
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
	}*/
	function update_detilgaji_rpupahpokok_bygradekodejab($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, GRADE, KODEJAB, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK";
		$this->db->query($sql);
		
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, GRADE, KODEJAB, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE AND t2.KODEJAB = t1.KODEJAB
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPUPAHPOKOK = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d'))))
				* t2.RPUPAHPOKOK)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpupahpokok_bynik($bulan, $nik_arr){
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
	}*/
	function update_detilgaji_rpupahpokok_bynik($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, NIK, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK";
		$this->db->query($sql);
		
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NOURUT, NIK, RPUPAHPOKOK
				FROM upahpokok
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPUPAHPOKOK = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d'))))
				* t2.RPUPAHPOKOK)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptpekerjaan_bygrade($bulan, $grade_arr){
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
	}*/
	function update_detilgaji_rptpekerjaan_bygrade($bulan, $tglmulai, $tglsampai){
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'H'
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'H'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KATPEKERJAAN IS NULL OR KATPEKERJAAN = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t3 ON(t3.NIK = t1.NIK
				AND t3.TANGGAL >= t2.TGLMULAI
				AND t3.TANGGAL <= t2.TGLSAMPAI)
			SET t1.RPTPEKERJAAN = (t2.RPTPEKERJAAN * t3.JMLHADIR)";
		$this->db->query($sql);
		
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'L'
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'L'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KATPEKERJAAN IS NULL OR KATPEKERJAAN = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			SET t1.RPTPEKERJAAN = t2.RPTPEKERJAAN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $katpekerjaan_arr){
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
	}*/
	function update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $tglmulai, $tglsampai){
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'H'
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT KATPEKERJAAN, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'H'
					AND KATPEKERJAAN IS NOT NULL AND KATPEKERJAAN != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KATPEKERJAAN
			) AS t3 ON(t3.KATPEKERJAAN = t2.KATPEKERJAAN)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTPEKERJAAN = (t3.RPTPEKERJAAN * t4.JMLHADIR)";
		$this->db->query($sql);
		
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'L'
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT KATPEKERJAAN, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'L'
					AND KATPEKERJAAN IS NOT NULL AND KATPEKERJAAN != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KATPEKERJAAN
			) AS t3 ON(t3.KATPEKERJAAN = t2.KATPEKERJAAN)
			SET t1.RPTPEKERJAAN = t3.RPTPEKERJAAN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $gradekatpekerjaan_arr){
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
	}*/
	function update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $tglmulai, $tglsampai){
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'H'
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT GRADE, KATPEKERJAAN, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'H'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KATPEKERJAAN IS NOT NULL AND KATPEKERJAAN != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KATPEKERJAAN
			) AS t3 ON(t3.GRADE = t2.GRADE AND t3.KATPEKERJAAN = t2.KATPEKERJAAN)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTPEKERJAAN = (t3.RPTPEKERJAAN * t4.JMLHADIR)";
		$this->db->query($sql);
		
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'L'
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT GRADE, KATPEKERJAAN, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'L'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KATPEKERJAAN IS NOT NULL AND KATPEKERJAAN != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KATPEKERJAAN
			) AS t3 ON(t3.GRADE = t2.GRADE AND t3.KATPEKERJAAN = t2.KATPEKERJAAN)
			SET t1.RPTPEKERJAAN = t3.RPTPEKERJAAN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptpekerjaan_bynik($bulan, $nik_arr){
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
	}*/
	function update_detilgaji_rptpekerjaan_bynik($bulan, $tglmulai, $tglsampai){
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'H'
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT NIK, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'H'
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KATPEKERJAAN IS NULL OR KATPEKERJAAN = '')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t3 ON(t3.NIK = t2.NIK
				AND t3.TANGGAL >= t2.TGLMULAI
				AND t3.TANGGAL <= t2.TGLSAMPAI)
			SET t1.RPTPEKERJAAN = (t2.RPTPEKERJAAN * t3.JMLHADIR)";
		$this->db->query($sql);
		
		/**
		 * Proses Tunj. Pekerjaan yang FPENGALI = 'L'
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT NIK, RPTPEKERJAAN
				FROM tpekerjaan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
					AND FPENGALI = 'L'
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KATPEKERJAAN IS NULL OR KATPEKERJAAN = '')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTPEKERJAAN = t2.RPTPEKERJAAN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptbhs_bylevel($bulan, $bhsjepang_arr){
		foreach($bhsjepang_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptbhs_bylevel($bulan){
		$sql = "UPDATE detilgaji t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT BHSJEPANG, RPTBHS
				FROM tbhs
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND BHSJEPANG IS NOT NULL AND BHSJEPANG != ''
					AND (NIK IS NULL OR NIK = '')
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY BHSJEPANG
			) AS t3 ON(t3.BHSJEPANG = t2.BHSJEPANG)
			SET t1.RPTBHS = t3.RPTBHS";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptbhs_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptbhs_bygrade($bulan){
		$sql = "UPDATE detilgaji t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT GRADE, RPTBHS
				FROM tbhs
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (BHSJEPANG IS NULL OR BHSJEPANG = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t3 ON(t3.GRADE = t2.GRADE)
			SET t1.RPTBHS = t3.RPTBHS";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptbhs_bykodejab($bulan){
		$sql = "UPDATE detilgaji t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT KODEJAB, RPTBHS
				FROM tbhs
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (BHSJEPANG IS NULL OR BHSJEPANG = '')
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t3 ON(t3.KODEJAB = t2.KODEJAB)
			SET t1.RPTBHS = t3.RPTBHS";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."' 
					AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.BHSJEPANG = '".$row->BHSJEPANG."' 
					AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
				SET detilgaji.RPTBHS = ".$row->RPTBHS;
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptbhs_bygradekodejab($bulan){
		$sql = "UPDATE detilgaji t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT GRADE, KODEJAB, RPTBHS
				FROM tbhs
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (BHSJEPANG IS NULL OR BHSJEPANG = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t3 ON(t3.GRADE = t2.GRADE AND t3.KODEJAB = t2.KODEJAB)
			SET t1.RPTBHS = t3.RPTBHS";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptbhs_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji 
				SET detilgaji.RPTBHS = ".$row->RPTBHS."
				WHERE detilgaji.BULAN = '".$bulan."' AND detilgaji.NIK = '".$row->NIK."'";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptbhs_bynik($bulan){
		$sql = "UPDATE detilgaji t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			JOIN (
				SELECT NIK, RPTBHS
				FROM tbhs
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (BHSJEPANG IS NULL OR BHSJEPANG = '')
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t3 ON(t3.NIK = t2.NIK)
			SET t1.RPTBHS = t3.RPTBHS";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptjabatan_bygrade($bulan, $grade_arr){
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
	}*/
	function update_detilgaji_rptjabatan_bygrade($bulan){
		/**
		 * Karyawan dengan Masa Kerja lebih dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			SET t1.RPTJABATAN = t2.RPTJABATAN";
		$this->db->query($sql);
		
		/**
		 * Karyawan dengan Masa Kerja kurang dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPTBHS = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * t2.RPTJABATAN)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptjabatan_bykodejab($bulan, $kodejab_arr){
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
	}*/
	function update_detilgaji_rptjabatan_bykodejab($bulan){
		/**
		 * Karyawan dengan Masa Kerja lebih dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT KODEJAB, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPTJABATAN = t2.RPTJABATAN";
		$this->db->query($sql);
		
		/**
		 * Karyawan dengan Masa Kerja kurang dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT KODEJAB, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPTBHS = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * t2.RPTJABATAN)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptjabatan_bygradekodejab($bulan, $gradekodejab_arr){
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
	}*/
	function update_detilgaji_rptjabatan_bygradekodejab($bulan){
		/**
		 * Karyawan dengan Masa Kerja lebih dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, KODEJAB, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPTJABATAN = t2.RPTJABATAN";
		$this->db->query($sql);
		
		/**
		 * Karyawan dengan Masa Kerja kurang dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, KODEJAB, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPTBHS = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * t2.RPTJABATAN)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptjabatan_bynik($bulan, $nik_arr){
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
	}*/
	function update_detilgaji_rptjabatan_bynik($bulan){
		/**
		 * Karyawan dengan Masa Kerja lebih dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT NIK, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTJABATAN = t2.RPTJABATAN";
		$this->db->query($sql);
		
		/**
		 * Karyawan dengan Masa Kerja kurang dari 1 tahun
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT NIK, RPTJABATAN
				FROM tjabatan
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t1.MASA_KERJA_BLN = 0 AND t1.MASA_KERJA_HARI > 0)
			SET t1.RPTBHS = ((t1.MASA_KERJA_HARI / DAY(LAST_DAY(STR_TO_DATE('".$bulan."01','%Y%m%d')))) * t2.RPTJABATAN)";
		$this->db->query($sql);
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
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * >> Anak ke-x yang mendapat tunjangan keluarga
				 * Jika $row->STATUSKEL2 = A (tidak ada x)
				 * >> Maka setiap anak akan mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				if($anak_ke == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK, COUNT(*) JMLANAK
								FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
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
					$sql .= " AND keluarga.NIK = karyawan.NIK) GROUP BY karyawan.NIK) AS t2 ON(t2.NIK = t1.NIK)";
					$sql .= " SET t1.RPTANAK = t1.RPTANAK + (t2.JMLANAK * ".$row->RPTKELUARGA.")";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK
								FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
									AND keluarga.NOURUT = CAST(".$anak_ke." AS UNSIGNED)
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
	}
	
	function update_detilgaji_rptkeluarga_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * >> Anak ke-x yang mendapat tunjangan keluarga
				 * Jika $row->STATUSKEL2 = A (tidak ada x)
				 * >> Maka setiap anak akan mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				if($anak_ke == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK, COUNT(*) JMLANAK
								FROM karyawan JOIN keluarga ON(karyawan.KODEJAB = '".$row->KODEJAB."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
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
					$sql .= " AND keluarga.NIK = karyawan.NIK) GROUP BY karyawan.NIK) AS t2 ON(t2.NIK = t1.NIK)";
					$sql .= " SET t1.RPTANAK = t1.RPTANAK + (t2.JMLANAK * ".$row->RPTKELUARGA.")";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK
								FROM karyawan JOIN keluarga ON(karyawan.KODEJAB = '".$row->KODEJAB."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
									AND keluarga.NOURUT = CAST(".$anak_ke." AS UNSIGNED)
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
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * >> Anak ke-x yang mendapat tunjangan keluarga
				 * Jika $row->STATUSKEL2 = A (tidak ada x)
				 * >> Maka setiap anak akan mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				if($anak_ke == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK, COUNT(*) JMLANAK
								FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
									AND karyawan.KODEJAB = '".$row->KODEJAB."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
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
					$sql .= " AND keluarga.NIK = karyawan.NIK) GROUP BY karyawan.NIK) AS t2 ON(t2.NIK = t1.NIK)";
					$sql .= " SET t1.RPTANAK = t1.RPTANAK + (t2.JMLANAK * ".$row->RPTKELUARGA.")";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK
								FROM karyawan JOIN keluarga ON(karyawan.GRADE = '".$row->GRADE."'
									AND karyawan.KODEJAB = '".$row->KODEJAB."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
									AND keluarga.NOURUT = CAST(".$anak_ke." AS UNSIGNED)
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
	}
	
	function update_detilgaji_rptkeluarga_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if($row->STATUSKEL2 == 'P'){
				$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = '".$row->NIK."'
						AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'P')
						AND detilgaji.BULAN = '".$bulan."' AND karyawan.NIK = detilgaji.NIK)
					SET detilgaji.RPTISTRI = ".$row->RPTKELUARGA;
				$this->db->query($sql);
			}elseif(substr($row->STATUSKEL2, 0, 1) == 'A'){
				/*
				 * $row->STATUSKEL2 = Ax (Anak ke-x)
				 * >> Anak ke-x yang mendapat tunjangan keluarga
				 * Jika $row->STATUSKEL2 = A (tidak ada x)
				 * >> Maka setiap anak akan mendapat tunjangan keluarga
				 */
				$anak_ke = substr($row->STATUSKEL2, -1);
				if($anak_ke == 'A'){
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK, COUNT(*) JMLANAK
								FROM karyawan JOIN keluarga ON(karyawan.NIK = '".$row->NIK."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
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
					$sql .= " AND keluarga.NIK = karyawan.NIK) GROUP BY karyawan.NIK) AS t2 ON(t2.NIK = t1.NIK)";
					$sql .= " SET t1.RPTANAK = t1.RPTANAK + (t2.JMLANAK * ".$row->RPTKELUARGA.")";
					$this->db->query($sql);
				}else{
					$sql = "UPDATE detilgaji AS t1 JOIN (
								SELECT karyawan.NIK
								FROM karyawan JOIN keluarga ON(karyawan.NIK = '".$row->NIK."'
									AND (karyawan.STATTUNKEL = 'F' OR karyawan.STATTUNKEL = 'A')
									AND keluarga.STATUSKEL = 'A'
									AND keluarga.NOURUT = CAST(".$anak_ke." AS UNSIGNED)
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
	}
	
	/*function update_detilgaji_rpttransport_bygrade($bulan, $grade_arr){
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
	}*/
	function update_detilgaji_rpttransport_bygrade($bulan, $tglmulai, $tglsampai){
		/**
		 * GRADE + ZONA
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.STATTUNTRAN = 'Y')
			JOIN (
				SELECT GRADE, ZONA, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
				FROM ttransport
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND GRADE IS NOT NULL AND GRADE != ''
					AND ZONA IS NOT NULL AND ZONA != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, ZONA
			) AS t3 ON(t3.GRADE = t2.GRADE AND t3.ZONA = t2.ZONA)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTTRANSPORT = (t3.RPTTRANSPORT * t4.JMLHADIR)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpttransport_bykodejab($bulan, $kodejab_arr){
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
	}*/
	function update_detilgaji_rpttransport_bykodejab($bulan, $tglmulai, $tglsampai){
		/**
		 * KODEJAB + ZONA
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.STATTUNTRAN = 'Y')
			JOIN (
				SELECT KODEJAB, ZONA, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
				FROM ttransport
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND ZONA IS NOT NULL AND ZONA != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB, ZONA
			) AS t3 ON(t3.KODEJAB = t2.KODEJAB AND t3.ZONA = t2.ZONA)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTTRANSPORT = (t3.RPTTRANSPORT * t4.JMLHADIR)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpttransport_bygradekodejab($bulan, $gradekodejab_arr){
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
	}*/
	function update_detilgaji_rpttransport_bygradekodejab($bulan, $tglmulai, $tglsampai){
		/**
		 * GRADE + KODEJAB + ZONA 
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.STATTUNTRAN = 'Y')
			JOIN (
				SELECT GRADE, KODEJAB, ZONA, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
				FROM ttransport
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND ZONA IS NOT NULL AND ZONA != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB, ZONA
			) AS t3 ON(t3.GRADE = t2.GRADE AND t3.KODEJAB = t2.KODEJAB AND t3.ZONA = t2.ZONA)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTTRANSPORT = (t3.RPTTRANSPORT * t4.JMLHADIR)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpttransport_bynik($bulan, $nik_arr){
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
	}*/
	function update_detilgaji_rpttransport_bynik($bulan, $tglmulai, $tglsampai){
		/**
		 * ZONA tidak diperhitungkan
		 */
		$sql = "UPDATE detilgaji AS t1
			JOIN karyawan AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.STATTUNTRAN = 'Y')
			JOIN (
				SELECT NIK, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							TGLMULAI) AS TGLMULAI,
					IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
				FROM ttransport
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
					AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
					AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND NIK IS NOT NULL AND NIK != ''
					AND (ZONA IS NULL OR ZONA = '')
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t3 ON(t3.NIK = t2.NIK)
			JOIN (
				SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
				FROM hitungpresensi
				WHERE hitungpresensi.JENISABSEN = 'HD'
				GROUP BY hitungpresensi.NIK
			) AS t4 ON(t4.NIK = t2.NIK
				AND t4.TANGGAL >= t3.TGLMULAI
				AND t4.TANGGAL <= t3.TGLSAMPAI)
			SET t1.RPTTRANSPORT = (t3.RPTTRANSPORT * t4.JMLHADIR)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		// CATATAN: mengambil jenis absen yang terkena insentif disiplin di db.jenisabsen.INSDISIPLIN = 'Y'
		// dimana JMLABSEN <= (kurang dari sama dengan)
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
					AND t2.JMLABSEN <= ".$row->JMLABSEN.")
				SET detilgaji.RPIDISIPLIN = ".$row->RPIDISIPLIN;
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai){
		// CATATAN: mengambil jenis absen yang terkena insentif disiplin di db.jenisabsen.INSDISIPLIN = 'Y'
		// dimana JMLABSEN <= (kurang dari sama dengan)
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, JMLABSEN, RPIDISIPLIN
				FROM insdisiplin
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND JMLABSEN IS NOT NULL
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			JOIN (
				SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
				FROM hitungpresensi
				JOIN jenisabsen ON(jenisabsen.JENISABSEN = hitungpresensi.JENISABSEN
					AND jenisabsen.INSDISIPLIN = 'Y')
				WHERE hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				GROUP BY hitungpresensi.NIK
			) AS t3 ON(t3.NIK = t1.NIK
				AND t3.JMLABSEN <= t2.JMLABSEN)
			SET t1.RPIDISIPLIN = t2.RPIDISIPLIN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
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
	}*/
	function update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT KODEJAB, JMLABSEN, RPIDISIPLIN
				FROM insdisiplin
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND JMLABSEN IS NOT NULL
					AND (GRADE IS NULL OR GRADE = '')
				GROUP BY KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB)
			JOIN (
				SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
				FROM hitungpresensi
				JOIN jenisabsen ON(jenisabsen.JENISABSEN = hitungpresensi.JENISABSEN
					AND jenisabsen.INSDISIPLIN = 'Y')
				WHERE hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				GROUP BY hitungpresensi.NIK
			) AS t3 ON(t3.NIK = t1.NIK
				AND t3.JMLABSEN <= t2.JMLABSEN)
			SET t1.RPIDISIPLIN = t2.RPIDISIPLIN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
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
	}*/
	function update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1 JOIN (
				SELECT GRADE, KODEJAB, JMLABSEN, RPIDISIPLIN
				FROM insdisiplin
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND JMLABSEN IS NOT NULL
				GROUP BY GRADE, KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB)
			JOIN (
				SELECT hitungpresensi.NIK, COUNT(*) AS JMLABSEN
				FROM hitungpresensi
				JOIN jenisabsen ON(jenisabsen.JENISABSEN = hitungpresensi.JENISABSEN
					AND jenisabsen.INSDISIPLIN = 'Y')
				WHERE hitungpresensi.TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND hitungpresensi.TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				GROUP BY hitungpresensi.NIK
			) AS t3 ON(t3.NIK = t1.NIK
				AND t3.JMLABSEN <= t2.JMLABSEN)
			SET t1.RPIDISIPLIN = t2.RPIDISIPLIN";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptlembur_bygrade($bulan, $grade_arr){
		// CATATAN: t2.JAMLEMBUR * ".$row->PENGALI." ==> diganti dengan hitungpresensi.SATLEMBUR
		// >> sudah tidak menggunakan JENISLEMBUR
		// >> Tunj. TETAP = (Upah Pokok + UMSK) + (TJabatan + TBhs + TKeluarga)
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
				//rumus RPTLEMBUR = (hitungpresensi.SATLEMBUR * (t3.RPUPAHPOKOK + t3.RPTISTRI + t3.RPTANAK + t3.RPTBHS + t3.RPTJABATAN)) / 173
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
	}*/
	function update_detilgaji_rptlembur_bygrade($bulan, $tglmulai, $tglsampai){
		/**
		 * CATATAN: t2.JAMLEMBUR * ".$row->PENGALI." ==> diganti dengan hitungpresensi.SATLEMBUR
		 * >> sudah tidak menggunakan JENISLEMBUR
		 * >> Tunj. TETAP = (Upah Pokok + UMSK) + (TJabatan + TBhs + TKeluarga)
		 * >> Tunj. TIDAK TETAP = TTransport + TPekerjaan + TShift
		 * >> UPENGALI = 'A' ==> dikalikan dengan RPUPAHPOKOK
		 * >> UPENGALI = 'B' ==> dikalikan dengan (RPUPAHPOKOK + Tunj. TIDAK TETAP)
		 * >> UPENGALI = 'C' ==> dikalikan dengan (RPUPAHPOKOK + Tunj. TETAP)
		 * >> UPENGALI = 'D' ==> dikalikan dengan (RPUPAHPOKOK + Tunj. TETAP - TBhs)
		 */
		
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				/*menghitung rplembur total dalam 1 bulan di periode gaji (bulangaji - 1)*/
				SELECT t11.NIK, SUM(t11.RPTLEMBUR) AS RPTLEMBUR
				FROM (
					/*menghitung rplembur per hari sesuai dengan db.hitungpresensi.SATLEMBUR dan db.lembur.UPENGALI*/
					SELECT t114.NIK, ((t114.SATLEMBUR *
						(IF(t111.UPENGALI = 'A', t113.RPUPAHPOKOK,
							IF(t111.UPENGALI = 'B', (t113.RPUPAHPOKOK + t113.RPTTRANSPORT + t113.RPTPEKERJAAN + t113.RPTSHIFT),
								IF(t111.UPENGALI = 'C', (t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTBHS + t113.RPTANAK + t113.RPTISTRI),
									(t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTANAK + t113.RPTISTRI)))))) / 173) AS RPTLEMBUR
					FROM (
						SELECT GRADE, UPENGALI
						FROM lembur
						WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
							AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
							AND GRADE IS NOT NULL AND GRADE != ''
							AND (KODEJAB IS NULL OR KODEJAB = '')
							/*AND UPENGALI = 'A'*/
						GROUP BY GRADE
					) AS t111
					JOIN karyawan AS t112 ON(t112.GRADE = t111.GRADE)
					JOIN (
						SELECT NIK, SUM(RPUPAHPOKOK) AS RPUPAHPOKOK, SUM(RPTTRANSPORT) AS RPTTRANSPORT,
							SUM(RPTPEKERJAAN) AS RPTPEKERJAAN, SUM(RPTSHIFT) AS RPTSHIFT,
							SUM(RPTJABATAN) AS RPTJABATAN, SUM(RPTBHS) AS RPTBHS,
							SUM(RPTISTRI) AS RPTISTRI, SUM(RPTANAK) AS RPTANAK, SUM(RPUMSK) AS RPUMSK
						FROM detilgaji
						WHERE CAST(BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						GROUP BY NIK
					) AS t113 ON(t113.NIK = t112.NIK)
					JOIN hitungpresensi AS t114 ON(CAST(t114.BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						AND t114.NIK = t113.NIK)
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTLEMBUR = t2.RPTLEMBUR";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptlembur_bykodejab($bulan, $kodejab_arr){
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
	}*/
	function update_detilgaji_rptlembur_bykodejab($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				/*menghitung rplembur total dalam 1 bulan di periode gaji (bulangaji - 1)*/
				SELECT t11.NIK, SUM(t11.RPTLEMBUR) AS RPTLEMBUR
				FROM (
					/*menghitung rplembur per hari sesuai dengan db.hitungpresensi.SATLEMBUR dan db.lembur.UPENGALI*/
					SELECT t114.NIK, ((t114.SATLEMBUR *
						(IF(t111.UPENGALI = 'A', t113.RPUPAHPOKOK,
							IF(t111.UPENGALI = 'B', (t113.RPUPAHPOKOK + t113.RPTTRANSPORT + t113.RPTPEKERJAAN + t113.RPTSHIFT),
								IF(t111.UPENGALI = 'C', (t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTBHS + t113.RPTANAK + t113.RPTISTRI),
									(t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTANAK + t113.RPTISTRI)))))) / 173) AS RPTLEMBUR
					FROM (
						SELECT KODEJAB, UPENGALI
						FROM lembur
						WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
							AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
							AND KODEJAB IS NOT NULL AND KODEJAB != ''
							AND (GRADE IS NULL OR GRADE = '')
							/*AND UPENGALI = 'A'*/
						GROUP BY KODEJAB
					) AS t111
					JOIN karyawan AS t112 ON(t112.KODEJAB = t111.KODEJAB)
					JOIN (
						SELECT NIK, SUM(RPUPAHPOKOK) AS RPUPAHPOKOK, SUM(RPTTRANSPORT) AS RPTTRANSPORT,
							SUM(RPTPEKERJAAN) AS RPTPEKERJAAN, SUM(RPTSHIFT) AS RPTSHIFT,
							SUM(RPTJABATAN) AS RPTJABATAN, SUM(RPTBHS) AS RPTBHS,
							SUM(RPTISTRI) AS RPTISTRI, SUM(RPTANAK) AS RPTANAK, SUM(RPUMSK) AS RPUMSK
						FROM detilgaji
						WHERE CAST(BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						GROUP BY NIK
					) AS t113 ON(t113.NIK = t112.NIK)
					JOIN hitungpresensi AS t114 ON(CAST(t114.BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						AND t114.NIK = t113.NIK)
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTLEMBUR = t2.RPTLEMBUR";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptlembur_bygradekodejab($bulan, $gradekodejab_arr){
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
	}*/
	function update_detilgaji_rptlembur_bygradekodejab($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				/*menghitung rplembur total dalam 1 bulan di periode gaji (bulangaji - 1)*/
				SELECT t11.NIK, SUM(t11.RPTLEMBUR) AS RPTLEMBUR
				FROM (
					/*menghitung rplembur per hari sesuai dengan db.hitungpresensi.SATLEMBUR dan db.lembur.UPENGALI*/
					SELECT t114.NIK, ((t114.SATLEMBUR *
						(IF(t111.UPENGALI = 'A', t113.RPUPAHPOKOK,
							IF(t111.UPENGALI = 'B', (t113.RPUPAHPOKOK + t113.RPTTRANSPORT + t113.RPTPEKERJAAN + t113.RPTSHIFT),
								IF(t111.UPENGALI = 'C', (t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTBHS + t113.RPTANAK + t113.RPTISTRI),
									(t113.RPUPAHPOKOK + t113.RPUMSK + t113.RPTJABATAN + t113.RPTANAK + t113.RPTISTRI)))))) / 173) AS RPTLEMBUR
					FROM (
						SELECT GRADE, KODEJAB, UPENGALI
						FROM lembur
						WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
							AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
							AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
							AND GRADE IS NOT NULL AND GRADE != ''
							AND KODEJAB IS NOT NULL AND KODEJAB != ''
							/*AND UPENGALI = 'A'*/
						GROUP BY GRADE, KODEJAB
					) AS t111
					JOIN karyawan AS t112 ON(t112.GRADE = t111.GRADE AND t112.KODEJAB = t111.KODEJAB)
					JOIN (
						SELECT NIK, SUM(RPUPAHPOKOK) AS RPUPAHPOKOK, SUM(RPTTRANSPORT) AS RPTTRANSPORT,
							SUM(RPTPEKERJAAN) AS RPTPEKERJAAN, SUM(RPTSHIFT) AS RPTSHIFT,
							SUM(RPTJABATAN) AS RPTJABATAN, SUM(RPTBHS) AS RPTBHS,
							SUM(RPTISTRI) AS RPTISTRI, SUM(RPTANAK) AS RPTANAK, SUM(RPUMSK) AS RPUMSK
						FROM detilgaji
						WHERE CAST(BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						GROUP BY NIK
					) AS t113 ON(t113.NIK = t112.NIK)
					JOIN hitungpresensi AS t114 ON(CAST(t114.BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) -1)
						AND t114.NIK = t113.NIK)
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTLEMBUR = t2.RPTLEMBUR";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptshift_bygrade($bulan, $grade_arr){
		// CATATAN: db.presensi.SHIFTKE per tanggal di HADIR atau tidak
		// Jika Hadir maka diikutkan ke perhitungan rptshift
		
		foreach($grade_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."' AND t2.NIK = t1.NIK)
					JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t3.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."' AND t2.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT;
			}
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptshift_bygrade($bulan, $tglmulai, $tglsampai){
		// CATATAN: db.presensi.SHIFTKE per tanggal di HADIR atau tidak
		// Jika Hadir maka diikutkan ke perhitungan rptshift
		
		/**
		 * menghitung rptshift berdasarkan FPENGALI = 'H' yaitu DIKALIKAN dengan jumlah kehadiran dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan data total tunjangan shift per NIK dalam sebulan
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFTPERSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan per tanggal satu NIK memiliki SHIFTKE 1x atau lebih,
					 * dan sekaligus dihitung tunjangan per shift
					 */
					SELECT t111.TANGGAL, t111.NIK, t111.SHIFTKE, t112.RPTSHIFT,
						(COUNT(t111.SHIFTKE) * t112.RPTSHIFT) AS RPTSHIFTPERSHIFT
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT GRADE, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'H'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND GRADE IS NOT NULL AND GRADE != ''
								AND (KODEJAB IS NULL OR KODEJAB = '')
								AND (NIK IS NULL OR NIK = '')
							GROUP BY GRADE, SHIFTKE
						) AS t1112 ON(t1112.GRADE = t1111.GRADE)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.TANGGAL, t111.NIK, t111.SHIFTKE
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
		
		/**
		 * menambahkan rptshift TANPA DIKALIKAN dengan jumlah kehadiran dalam sebulan,
		 * dengan syarat karyawan itu hadir 1x atau lebih dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan NIK dan total RPTSHIFT
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan NIK yang hadir per tanggal berdasarkan SHIFTKE yang terkena Tunj.Shift,
					 * dan rupiah shiftke yang terkait
					 */
					SELECT t111.NIK, t111.SHIFTKE, t112.RPTSHIFT, COUNT(t111.SHIFTKE) AS SHIFTKEJML
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT GRADE, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'L'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND GRADE IS NOT NULL AND GRADE != ''
								AND (KODEJAB IS NULL OR KODEJAB = '')
								AND (NIK IS NULL OR NIK = '')
							GROUP BY GRADE, SHIFTKE
						) AS t1112 ON(t1112.GRADE = t1111.GRADE)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.NIK, t111.SHIFTKE, t112.RPTSHIFT
					HAVING SHIFTKEJML > 0
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptshift_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.KODEJAB = '".$row->KODEJAB."' AND t2.NIK = t1.NIK)
					JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t3.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.KODEJAB = '".$row->KODEJAB."' AND t2.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT;
			}
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptshift_bykodejab($bulan, $tglmulai, $tglsampai){
		/**
		 * menghitung rptshift berdasarkan FPENGALI = 'H' yaitu DIKALIKAN dengan jumlah kehadiran dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan data total tunjangan shift per NIK dalam sebulan
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFTPERSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan per tanggal satu NIK memiliki SHIFTKE 1x atau lebih,
					 * dan sekaligus dihitung tunjangan per shift
					 */
					SELECT t111.TANGGAL, t111.NIK, t111.SHIFTKE, t112.RPTSHIFT,
						(COUNT(t111.SHIFTKE) * t112.RPTSHIFT) AS RPTSHIFTPERSHIFT
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT KODEJAB, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'H'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND KODEJAB IS NOT NULL AND KODEJAB != ''
								AND (GRADE IS NULL OR GRADE = '')
								AND (NIK IS NULL OR NIK = '')
							GROUP BY KODEJAB, SHIFTKE
						) AS t1112 ON(t1112.KODEJAB = t1111.KODEJAB)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.TANGGAL, t111.NIK, t111.SHIFTKE
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
		
		/**
		 * menambahkan rptshift TANPA DIKALIKAN dengan jumlah kehadiran dalam sebulan,
		 * dengan syarat karyawan itu hadir 1x atau lebih dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan NIK dan total RPTSHIFT
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan NIK yang hadir per tanggal berdasarkan SHIFTKE yang terkena Tunj.Shift,
					 * dan rupiah shiftke yang terkait
					 */
					SELECT t111.NIK, t111.SHIFTKE, t112.RPTSHIFT, COUNT(t111.SHIFTKE) AS SHIFTKEJML
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT KODEJAB, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'L'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND KODEJAB IS NOT NULL AND KODEJAB != ''
								AND (GRADE IS NULL OR GRADE = '')
								AND (NIK IS NULL OR NIK = '')
							GROUP BY KODEJAB, SHIFTKE
						) AS t1112 ON(t1112.KODEJAB = t1111.KODEJAB)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.NIK, t111.SHIFTKE, t112.RPTSHIFT
					HAVING SHIFTKEJML > 0
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptshift_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."'
						AND t1.KODEJAB = '".$row->KODEJAB."' AND t2.NIK = t1.NIK)
					JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t3.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."'
						 AND t1.KODEJAB = '".$row->KODEJAB."' AND t2.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT;
			}
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptshift_bygradekodejab($bulan, $tglmulai, $tglsampai){
		/**
		 * menghitung rptshift berdasarkan FPENGALI = 'H' yaitu DIKALIKAN dengan jumlah kehadiran dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan data total tunjangan shift per NIK dalam sebulan
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFTPERSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan per tanggal satu NIK memiliki SHIFTKE 1x atau lebih,
					 * dan sekaligus dihitung tunjangan per shift
					 */
					SELECT t111.TANGGAL, t111.NIK, t111.SHIFTKE, t112.RPTSHIFT,
						(COUNT(t111.SHIFTKE) * t112.RPTSHIFT) AS RPTSHIFTPERSHIFT
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT GRADE, KODEJAB, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'H'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND GRADE IS NOT NULL AND GRADE != ''
								AND KODEJAB IS NOT NULL AND KODEJAB != ''
								AND (NIK IS NULL OR NIK = '')
							GROUP BY GRADE, KODEJAB, SHIFTKE
						) AS t1112 ON(t1112.GRADE = t1111.GRADE AND t1112.KODEJAB = t1111.KODEJAB)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.TANGGAL, t111.NIK, t111.SHIFTKE
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
		
		/**
		 * menambahkan rptshift TANPA DIKALIKAN dengan jumlah kehadiran dalam sebulan,
		 * dengan syarat karyawan itu hadir 1x atau lebih dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan NIK dan total RPTSHIFT
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan NIK yang hadir per tanggal berdasarkan SHIFTKE yang terkena Tunj.Shift,
					 * dan rupiah shiftke yang terkait
					 */
					SELECT t111.NIK, t111.SHIFTKE, t112.RPTSHIFT, COUNT(t111.SHIFTKE) AS SHIFTKEJML
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT GRADE, KODEJAB, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'L'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND GRADE IS NOT NULL AND GRADE != ''
								AND KODEJAB IS NOT NULL AND KODEJAB != ''
								AND (NIK IS NULL OR NIK = '')
							GROUP BY GRADE, KODEJAB, SHIFTKE
						) AS t1112 ON(t1112.GRADE = t1111.GRADE AND t1112.KODEJAB = t1111.KODEJAB)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.NIK, t111.SHIFTKE, t112.RPTSHIFT
					HAVING SHIFTKEJML > 0
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptshift_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen($row->FPENGALI)=='H'){
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.NIK = '".$row->NIK."' AND t2.NIK = t1.NIK)
					JOIN (
						SELECT hitungpresensi.NIK, SUM(hitungpresensi.HARIKERJA) AS JMLHADIR
						FROM hitungpresensi
						WHERE 
							hitungpresensi.JENISABSEN = 'HD' AND
							hitungpresensi.TANGGAL >= STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d') AND
							hitungpresensi.TANGGAL <= STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')
						GROUP BY hitungpresensi.NIK
					) AS t3 ON(t3.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT." * t3.JMLHADIR";
			}else{
				$sql = "UPDATE detilgaji AS t1 JOIN (
						SELECT presensi.NIK
						FROM presensi
						WHERE presensi.SHIFTKE = '".$row->SHIFTKE."'
							AND (presensi.TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
								AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d'))
						GROUP BY presensi.TANGGAL, presensi.NIK
					) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.NIK = '".$row->NIK."' AND t2.NIK = t1.NIK)
					SET t1.RPTSHIFT = ".$row->RPTSHIFT;
			}
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptshift_bynik($bulan, $tglmulai, $tglsampai){
		/**
		 * menghitung rptshift berdasarkan FPENGALI = 'H' yaitu DIKALIKAN dengan jumlah kehadiran dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan data total tunjangan shift per NIK dalam sebulan
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFTPERSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan per tanggal satu NIK memiliki SHIFTKE 1x atau lebih,
					 * dan sekaligus dihitung tunjangan per shift
					 */
					SELECT t111.TANGGAL, t111.NIK, t111.SHIFTKE, t112.RPTSHIFT,
						(COUNT(t111.SHIFTKE) * t112.RPTSHIFT) AS RPTSHIFTPERSHIFT
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT NIK, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'H'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND NIK IS NOT NULL AND NIK != ''
								AND (GRADE IS NULL OR GRADE = '')
								AND (KODEJAB IS NULL OR KODEJAB = '')
							GROUP BY NIK, SHIFTKE
						) AS t1112 ON(t1112.NIK = t1111.NIK)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.TANGGAL, t111.NIK, t111.SHIFTKE
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
		
		/**
		 * menambahkan rptshift TANPA DIKALIKAN dengan jumlah kehadiran dalam sebulan,
		 * dengan syarat karyawan itu hadir 1x atau lebih dalam sebulan
		 */
		$sql = "UPDATE detilgaji AS t1 JOIN (
				/**
				 * mendapatkan NIK dan total RPTSHIFT
				 */
				SELECT t11.NIK, SUM(t11.RPTSHIFT) AS RPTSHIFT
				FROM (
					/**
					 * mendapatkan NIK yang hadir per tanggal berdasarkan SHIFTKE yang terkena Tunj.Shift,
					 * dan rupiah shiftke yang terkait
					 */
					SELECT t111.NIK, t111.SHIFTKE, t112.RPTSHIFT, COUNT(t111.SHIFTKE) AS SHIFTKEJML
					FROM presensi AS t111 JOIN (
						SELECT t1111.NIK, t1112.SHIFTKE, t1112.TGLMULAI, t1112.TGLSAMPAI, t1112.RPTSHIFT
						FROM karyawan AS t1111
						JOIN (
							SELECT NIK, SHIFTKE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI, RPTSHIFT
							FROM tshift
							WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
								AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
								AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
								AND FPENGALI = 'L'
								AND SHIFTKE IS NOT NULL AND SHIFTKE != ''
								AND NIK IS NOT NULL AND NIK != ''
								AND (GRADE IS NULL OR GRADE = '')
								AND (KODEJAB IS NULL OR KODEJAB = '')
							GROUP BY NIK, SHIFTKE
						) AS t1112 ON(t1112.NIK = t1111.NIK)
					) AS t112 ON(t112.NIK = t111.NIK AND t112.SHIFTKE = t111.SHIFTKE
						AND t111.TANGGAL >= t112.TGLMULAI
						AND t111.TANGGAL >= t112.TGLSAMPAI)
					GROUP BY t111.NIK, t111.SHIFTKE, t112.RPTSHIFT
					HAVING SHIFTKEJML > 0
				) AS t11
				GROUP BY t11.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTSHIFT = t2.RPTSHIFT";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptambahan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "INSERT INTO detilgajitambahan (BULAN,NIK,NOREVISI,NOURUT,KODEUPAH,NAMAUPAH
					,POSCETAK,KETERANGAN,RPTAMBAHAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEUPAH."',
					'".$row->NAMAUPAH."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPTAMBAHAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
				) AS t1 LEFT JOIN (
					SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI, (MAX(detilgajitambahan.NOURUT) + 1) AS NOURUT
					FROM detilgajitambahan
					GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptambahan_bygrade($bulan){
		$sql = "INSERT INTO detilgajitambahan (BULAN, NIK, NOREVISI,
				NOURUT, KODEUPAH, NAMAUPAH, POSCETAK, KETERANGAN, RPTAMBAHAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEUPAH, t1.NAMAUPAH, t1.POSCETAK,
				t1.KETERANGAN, t1.RPTAMBAHAN
			FROM (
				SELECT tambahanlain2.GRADE, tambahanlain2.KODEUPAH, tambahanlain2.KETERANGAN,
					jenistambahan.NAMAUPAH, jenistambahan.POSCETAK, tambahanlain2.RPTAMBAHAN
				FROM tambahanlain2
				JOIN jenistambahan ON(jenistambahan.KODEUPAH = tambahanlain2.KODEUPAH)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajitambahan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptambahan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "INSERT INTO detilgajitambahan (BULAN,NIK,NOREVISI,NOURUT,KODEUPAH,NAMAUPAH
					,POSCETAK,KETERANGAN,RPTAMBAHAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEUPAH."',
					'".$row->NAMAUPAH."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPTAMBAHAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'
				) AS t1 LEFT JOIN (
					SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI, (MAX(detilgajitambahan.NOURUT) + 1) AS NOURUT
					FROM detilgajitambahan
					GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.KODEJAB = '".$row->KODEJAB."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptambahan_bykodejab($bulan){
		$sql = "INSERT INTO detilgajitambahan (BULAN, NIK, NOREVISI,
				NOURUT, KODEUPAH, NAMAUPAH, POSCETAK, KETERANGAN, RPTAMBAHAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEUPAH, t1.NAMAUPAH, t1.POSCETAK,
				t1.KETERANGAN, t1.RPTAMBAHAN
			FROM (
				SELECT tambahanlain2.KODEJAB, tambahanlain2.KODEUPAH, tambahanlain2.KETERANGAN,
					jenistambahan.NAMAUPAH, jenistambahan.POSCETAK, tambahanlain2.RPTAMBAHAN
				FROM tambahanlain2
				JOIN jenistambahan ON(jenistambahan.KODEUPAH = tambahanlain2.KODEUPAH)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajitambahan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptambahan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "INSERT INTO detilgajitambahan (BULAN,NIK,NOREVISI,NOURUT,KODEUPAH,NAMAUPAH
					,POSCETAK,KETERANGAN,RPTAMBAHAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEUPAH."',
					'".$row->NAMAUPAH."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPTAMBAHAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'
				) AS t1 LEFT JOIN (
					SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI, (MAX(detilgajitambahan.NOURUT) + 1) AS NOURUT
					FROM detilgajitambahan
					GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."'
					AND t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptambahan_bygradekodejab($bulan){
		$sql = "INSERT INTO detilgajitambahan (BULAN, NIK, NOREVISI,
				NOURUT, KODEUPAH, NAMAUPAH, POSCETAK, KETERANGAN, RPTAMBAHAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEUPAH, t1.NAMAUPAH, t1.POSCETAK,
				t1.KETERANGAN, t1.RPTAMBAHAN
			FROM (
				SELECT tambahanlain2.GRADE, tambahanlain2.KODEJAB, tambahanlain2.KODEUPAH, tambahanlain2.KETERANGAN,
					jenistambahan.NAMAUPAH, jenistambahan.POSCETAK, tambahanlain2.RPTAMBAHAN
				FROM tambahanlain2
				JOIN jenistambahan ON(jenistambahan.KODEUPAH = tambahanlain2.KODEUPAH)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajitambahan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rptambahan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "INSERT INTO detilgajitambahan (BULAN,NIK,NOREVISI,NOURUT,KODEUPAH,NAMAUPAH
					,POSCETAK,KETERANGAN,RPTAMBAHAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEUPAH."',
					'".$row->NAMAUPAH."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPTAMBAHAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.NIK = '".$row->NIK."'
				) AS t1 LEFT JOIN (
					SELECT detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI, (MAX(detilgajitambahan.NOURUT) + 1) AS NOURUT
					FROM detilgajitambahan
					GROUP BY detilgajitambahan.BULAN, detilgajitambahan.NIK, detilgajitambahan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.NIK = '".$row->NIK."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rptambahan_bynik($bulan){
		$sql = "INSERT INTO detilgajitambahan (BULAN, NIK, NOREVISI,
				NOURUT, KODEUPAH, NAMAUPAH, POSCETAK, KETERANGAN, RPTAMBAHAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEUPAH, t1.NAMAUPAH, t1.POSCETAK,
				t1.KETERANGAN, t1.RPTAMBAHAN
			FROM (
				SELECT tambahanlain2.NIK, tambahanlain2.KODEUPAH, tambahanlain2.KETERANGAN,
					jenistambahan.NAMAUPAH, jenistambahan.POSCETAK, tambahanlain2.RPTAMBAHAN
				FROM tambahanlain2
				JOIN jenistambahan ON(jenistambahan.KODEUPAH = tambahanlain2.KODEUPAH)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajitambahan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			) AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rppotongan_bygrade($bulan, $grade_arr){
		foreach($grade_arr as $row){
			$sql = "INSERT INTO detilgajipotongan (BULAN,NIK,NOREVISI,NOURUT,KODEPOTONGAN,NAMAPOTONGAN
					,POSCETAK,KETERANGAN,RPPOTONGAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEPOTONGAN."',
					'".$row->NAMAPOTONGAN."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPPOTONGAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
				) AS t1 LEFT JOIN (
					SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI, (MAX(detilgajipotongan.NOURUT) + 1) AS NOURUT
					FROM detilgajipotongan
					GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.GRADE = '".$row->GRADE."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rppotongan_bygrade($bulan){
		$sql = "INSERT INTO detilgajipotongan (BULAN, NIK, NOREVISI,
				NOURUT, KODEPOTONGAN, NAMAPOTONGAN,
				POSCETAK, KETERANGAN, RPPOTONGAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEPOTONGAN, t1.NAMAPOTONGAN, t1.POSCETAK,
				t1.KETERANGAN, t1.RPPOTONGAN
			FROM (
				SELECT potonganlain2.GRADE, potonganlain2.KODEPOTONGAN, potonganlain2.KETERANGAN,
					jenispotongan.NAMAPOTONGAN, jenispotongan.POSCETAK, potonganlain2.RPPOTONGAN
				FROM potonganlain2
				JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = potonganlain2.KODEPOTONGAN)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajipotongan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			)AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rppotongan_bykodejab($bulan, $kodejab_arr){
		foreach($kodejab_arr as $row){
			$sql = "INSERT INTO detilgajipotongan (BULAN,NIK,NOREVISI,NOURUT,KODEPOTONGAN,NAMAPOTONGAN
					,POSCETAK,KETERANGAN,RPPOTONGAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEPOTONGAN."',
					'".$row->NAMAPOTONGAN."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPPOTONGAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'
				) AS t1 LEFT JOIN (
					SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI, (MAX(detilgajipotongan.NOURUT) + 1) AS NOURUT
					FROM detilgajipotongan
					GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.KODEJAB = '".$row->KODEJAB."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rppotongan_bykodejab($bulan){
		$sql = "INSERT INTO detilgajipotongan (BULAN, NIK, NOREVISI,
				NOURUT, KODEPOTONGAN, NAMAPOTONGAN,
				POSCETAK, KETERANGAN, RPPOTONGAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEPOTONGAN, t1.NAMAPOTONGAN, t1.POSCETAK,
				t1.KETERANGAN, t1.RPPOTONGAN
			FROM (
				SELECT potonganlain2.KODEJAB, potonganlain2.KODEPOTONGAN, potonganlain2.KETERANGAN,
					jenispotongan.NAMAPOTONGAN, jenispotongan.POSCETAK, potonganlain2.RPPOTONGAN
				FROM potonganlain2
				JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = potonganlain2.KODEPOTONGAN)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajipotongan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			)AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rppotongan_bygradekodejab($bulan, $gradekodejab_arr){
		foreach($gradekodejab_arr as $row){
			$sql = "INSERT INTO detilgajipotongan (BULAN,NIK,NOREVISI,NOURUT,KODEPOTONGAN,NAMAPOTONGAN
					,POSCETAK,KETERANGAN,RPPOTONGAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEPOTONGAN."',
					'".$row->NAMAPOTONGAN."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPPOTONGAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.GRADE = '".$row->GRADE."'
						AND detilgaji.KODEJAB = '".$row->KODEJAB."'
				) AS t1 LEFT JOIN (
					SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI, (MAX(detilgajipotongan.NOURUT) + 1) AS NOURUT
					FROM detilgajipotongan
					GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."'
					AND t1.GRADE = '".$row->GRADE."' AND t1.KODEJAB = '".$row->KODEJAB."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rppotongan_bygradekodejab($bulan){
		$sql = "INSERT INTO detilgajipotongan (BULAN, NIK, NOREVISI,
				NOURUT, KODEPOTONGAN, NAMAPOTONGAN,
				POSCETAK, KETERANGAN, RPPOTONGAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEPOTONGAN, t1.NAMAPOTONGAN, t1.POSCETAK,
				t1.KETERANGAN, t1.RPPOTONGAN
			FROM (
				SELECT potonganlain2.GRADE, potonganlain2.KODEJAB, potonganlain2.KODEPOTONGAN, potonganlain2.KETERANGAN,
					jenispotongan.NAMAPOTONGAN, jenispotongan.POSCETAK, potonganlain2.RPPOTONGAN
				FROM potonganlain2
				JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = potonganlain2.KODEPOTONGAN)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajipotongan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			)AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rppotongan_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "INSERT INTO detilgajipotongan (BULAN,NIK,NOREVISI,NOURUT,KODEPOTONGAN,NAMAPOTONGAN
					,POSCETAK,KETERANGAN,RPPOTONGAN)
				SELECT t1.BULAN, t1.NIK, t1.NOREVISI, IFNULL(t2.NOURUT,1) AS NOURUT, '".$row->KODEPOTONGAN."',
					'".$row->NAMAPOTONGAN."', '".$row->POSCETAK."', '".$row->KETERANGAN."', ".$row->RPPOTONGAN."
				FROM (
					SELECT detilgaji.BULAN, detilgaji.NIK, detilgaji.NOREVISI
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
						AND detilgaji.NIK = '".$row->NIK."'
				) AS t1 LEFT JOIN (
					SELECT detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI, (MAX(detilgajipotongan.NOURUT) + 1) AS NOURUT
					FROM detilgajipotongan
					GROUP BY detilgajipotongan.BULAN, detilgajipotongan.NIK, detilgajipotongan.NOREVISI
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t1.NIK = '".$row->NIK."'
					AND t2.BULAN = t1.BULAN AND t2.NIK = t1.NIK AND t2.NOREVISI = t1.NOREVISI)";
			$this->db->query($sql);
		}
	}*/
	function update_detilgaji_rppotongan_bynik($bulan){
		$sql = "INSERT INTO detilgajipotongan (BULAN, NIK, NOREVISI,
				NOURUT, KODEPOTONGAN, NAMAPOTONGAN,
				POSCETAK, KETERANGAN, RPPOTONGAN)
			SELECT t2.BULAN, t2.NIK, t2.NOREVISI,
				t3.NOURUT, t1.KODEPOTONGAN, t1.NAMAPOTONGAN, t1.POSCETAK,
				t1.KETERANGAN, t1.RPPOTONGAN
			FROM (
				SELECT potonganlain2.NIK, potonganlain2.KODEPOTONGAN, potonganlain2.KETERANGAN,
					jenispotongan.NAMAPOTONGAN, jenispotongan.POSCETAK, potonganlain2.RPPOTONGAN
				FROM potonganlain2
				JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = potonganlain2.KODEPOTONGAN)
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t1
			JOIN detilgaji AS t2 ON(CAST(t2.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK
				AND t2.NOREVISI = 1)
			LEFT JOIN (
				SELECT BULAN, NIK, NOREVISI, (MAX(NOURUT) + 1) AS NOURUT
				FROM detilgajipotongan
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY BULAN, NIK, NOREVISI
			)AS t3 ON(CAST(t3.BULAN AS UNSIGNED) = CAST(t2.BULAN AS UNSIGNED)
				AND t3.NIK = t2.NIK
				AND t3.NOREVISI = t2.NOREVISI)";
		$this->db->query($sql);
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
						SET detilgaji.CICILAN".$i." = CONCAT('".$row->KETERANGAN."', ' ', '".($row->CICILANKE == ''? '' : '('.$row->CICILANKE.'/'.$row->LAMACICILAN.')')."'), detilgaji.RPCICILAN".$i." = ".$row->RPCICILAN."
						WHERE CAST(detilgaji.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED) AND detilgaji.NIK = '".$row->NIK."'";
					$this->db->query($sql);
				}
			}else{
				$i=1;
				$sql = "UPDATE detilgaji 
					SET detilgaji.CICILAN".$i." = CONCAT('".$row->KETERANGAN."', ' ', '".($row->CICILANKE == ''? '' : '('.$row->CICILANKE.'/'.$row->LAMACICILAN.')')."'), detilgaji.RPCICILAN".$i." = ".$row->RPCICILAN."
					WHERE CAST(detilgaji.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED) AND detilgaji.NIK = '".$row->NIK."'";
				$this->db->query($sql);
				
				$tmp_nik = $row->NIK;
			}
			
		}
		
	}
	
	function update_detilgaji_rptqcp_bynik($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t11.NIK, t11.RPQCP, SUM(IFNULL(t12.HARIKERJA, 0)) AS JMLHADIR
				FROM (
					SELECT *
					FROM (
						SELECT t1111.NIK, t1111.TGLMULAI, t1111.TGLSAMPAI, t1111.RPQCP
						FROM (
							SELECT NIK, RPQCP, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
										TGLMULAI) AS TGLMULAI,
								IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
									STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
										TGLSAMPAI) AS TGLSAMPAI
							FROM tqcp
							WHERE TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
								AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
						) AS t1111
						GROUP BY t1111.NIK, t1111.TGLMULAI, t1111.TGLSAMPAI
						ORDER BY t1111.NIK ASC, t1111.TGLMULAI DESC
					) AS t111
					GROUP BY t111.NIK
				) AS t11
				JOIN hitungpresensi AS t12 ON(t12.NIK = t11.NIK
					AND t12.TANGGAL >= t11.TGLMULAI AND t12.TANGGAL <= t11.TGLSAMPAI
					AND t12.JENISABSEN = 'HD')
				GROUP BY t12.NIK
			) AS t2 ON(t2.NIK = t1.NIK)
			SET t1.RPTQCP = (t2.RPQCP * t2.JMLHADIR)";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rptsimpati_bynik($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NIK, SUM(RPTSIMPATI) AS RPTSIMPATI
				FROM uangsimpati
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				GROUP BY NIK
			) AS t2 ON(t2.NIK = t1.NIK)
			SET t1.RPTSIMPATI = t2.RPTSIMPATI";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpbonus_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr){
		//????????????????????????????????????????????????
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
	}*/
	function update_detilgaji_rpbonus_bygrade($bulan){
		/**
		 * Tunj. TETAP = (Upah Pokok + UMSK) + (TJabatan + TBhs + TKeluarga)
		 * Tunj. TIDAK TETAP = TTransport + TPekerjaan + TShift
		 *
		 * Catatan:
		 * 1. Bonus diberikan biasanya diberikan 2 periode yaitu di BULANGAJI Juni dan Desember
		 * >> Periode Bonus Bulan Juni/2013 ==> kehadiran antara TGLMULAI = 1 Desember 2012 dan TGLSAMPAI = 31 Mei 2013
		 * >> Periode Bonus Bulan Desember/2013 ==> kehadiran antara TGLMULAI = 1 Juni 2013 dan TGLSAMPAI = 30 November 2013
		 * 2. Kehadiran = 'HD'/'C' dimana C adalah semua Cuti (CT,CN,dst)
		 * 3. FPENGALI = 'L' / 'H'
		 * 4. PENGALI = angka pengali (1,125 atau 2,25 dst)
		 * 5. PERSENTASE = angka (50 atau 100) per 100 (%)
		 * 6. UPENGALI:
		 * >> UPENGALI = 'A' ==> dikalikan dengan RPUPAHPOKOK (BULANGAJI)
		 * >> UPENGALI = 'B' ==> dikalikan dengan (RPUPAHPOKOK + TJabatan) (BULANGAJI)
		 * >> UPENGALI = 'C' ==> dikalikan dengan (RPUPAHPOKOK + Tunj. TETAP) (BULANGAJI)
		 * 7. Perhitungan Bonus:
		 * 7.a.
		 * >> Jika FPENGALI = 'H' ==> berdasar Jumlah Hari Kerja antara TGLMULAI dan TGLSAMPAI
		 * >> Kasus Periode Bonus Bulan Juni/2013
		 * >> Dasar Dokumentasi ==> File "RANCANGAN UI vInternal v2_4Juli2013.docx"
		 * >>> RPBONUS = [[((JMLHADIR antara TGLMULAI dan TGLSAMPAI)/(Jumlah HARIKERJA selama Desember/2013 s.d Mei/2013))
		 * >>> * (db.bonus.PERSENTASE / 100) * (UPENGALI / 173)] + db.bonus.RPBONUS]
		 * 7.b.
		 * >> Jika FPENGALI = 'L' ==> berdasar Jumlah Hari Kerja antara TGLMULAI dan TGLSAMPAI
		 * >> Kasus Periode Bonus Bulan Juni/2013
		 * >> Dasar Dokumentasi ==> File "Prosedur SIMSDM YMPI.ppt"
		 * >>> RPBONUS = [db.bonus.RPBONUS * db.bonus.PENGALI * db.bonus.PERSENTASE]
		 */
		//FPENGALI = 'H'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, t11.PENGALI, t11.UPENGALI, t11.PERSENTASE, t11.RPBONUS,
					COUNT(*) AS JMLHADIR, t11.TOTALHARIKERJA
				FROM (
					SELECT GRADE ,PENGALI ,UPENGALI ,PERSENTASE ,RPBONUS ,TGLMULAI ,TGLSAMPAI
						,(
							SELECT SUM(JMLHARI)
							FROM harikerja
							WHERE CAST(BULAN AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m') AS UNSIGNED)
								AND CAST(BULAN AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m') AS UNSIGNED)
							GROUP BY BULAN
						) AS TOTALHARIKERJA
					FROM bonus
					WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
						AND FPENGALI = 'H'
						AND GRADE IS NOT NULL AND GRADE != ''
						AND (KODEJAB IS NULL OR KODEJAB = '')
						AND (NIK IS NULL OR NIK = '')
					GROUP BY GRADE
				) AS t11
				JOIN karyawan AS t12 ON(t12.GRADE = t11.GRADE)
				JOIN hitungpresensi AS t13 ON(t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI
					AND t13.NIK = t12.NIK
					AND (t13.JENISABSEN = 'HD' OR SUBSTR(t13.JENISABSEN,1,1) = 'C'))
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPBONUS = (((t2.JMLHARI / t2.TOTALHARIKERJA) * (t2.PERSENTASE / 100)
				* ((IF(t2.UPENGALI = 'A', t1.RPUPAHPOKOK,
					IF(t2.UPENGALI = 'B', (t1.RPUPAHPOKOK + t1.RPTJABATAN),
						(t1.RPUPAHPOKOK + t1.RPUMSK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTANAK + t1.RPTISTRI))))/173)) + t2.RPBONUS)";
		$this->db->query($sql);
		
		//FPENGALI = 'L'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT GRADE ,PENGALI ,PERSENTASE ,RPBONUS 
				FROM bonus
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND FPENGALI = 'L'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND (KODEJAB IS NULL OR KODEJAB = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			SET t1.RPBONUS = (t2.RPBONUS * t2.PENGALI * (t2.PERSENTASE / 100))";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpbonus_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr){
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
	}*/
	function update_detilgaji_rpbonus_bykodejab($bulan){
		//FPENGALI = 'H'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, t11.PENGALI, t11.UPENGALI, t11.PERSENTASE, t11.RPBONUS,
					COUNT(*) AS JMLHADIR, t11.TOTALHARIKERJA
				FROM (
					SELECT KODEJAB ,PENGALI ,UPENGALI ,PERSENTASE ,RPBONUS ,TGLMULAI ,TGLSAMPAI
						,(
							SELECT SUM(JMLHARI)
							FROM harikerja
							WHERE CAST(BULAN AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m') AS UNSIGNED)
								AND CAST(BULAN AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m') AS UNSIGNED)
							GROUP BY BULAN
						) AS TOTALHARIKERJA
					FROM bonus
					WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
						AND FPENGALI = 'H'
						AND KODEJAB IS NOT NULL AND KODEJAB != ''
						AND (GRADE IS NULL OR GRADE = '')
						AND (NIK IS NULL OR NIK = '')
					GROUP BY KODEJAB
				) AS t11
				JOIN karyawan AS t12 ON(t12.KODEJAB = t11.KODEJAB)
				JOIN hitungpresensi AS t13 ON(t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI
					AND t13.NIK = t12.NIK
					AND (t13.JENISABSEN = 'HD' OR SUBSTR(t13.JENISABSEN,1,1) = 'C'))
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPBONUS = (((t2.JMLHARI / t2.TOTALHARIKERJA) * (t2.PERSENTASE / 100)
				* ((IF(t2.UPENGALI = 'A', t1.RPUPAHPOKOK,
					IF(t2.UPENGALI = 'B', (t1.RPUPAHPOKOK + t1.RPTJABATAN),
						(t1.RPUPAHPOKOK + t1.RPUMSK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTANAK + t1.RPTISTRI))))/173)) + t2.RPBONUS)";
		$this->db->query($sql);
		
		//FPENGALI = 'L'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT KODEJAB ,PENGALI ,PERSENTASE ,RPBONUS 
				FROM bonus
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND FPENGALI = 'L'
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (NIK IS NULL OR NIK = '')
				GROUP BY KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPBONUS = (t2.RPBONUS * t2.PENGALI * (t2.PERSENTASE / 100))";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpbonus_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr){
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
	}*/
	function update_detilgaji_rpbonus_bygradekodejab($bulan){
		//FPENGALI = 'H'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, t11.PENGALI, t11.UPENGALI, t11.PERSENTASE, t11.RPBONUS,
					COUNT(*) AS JMLHADIR, t11.TOTALHARIKERJA
				FROM (
					SELECT GRADE ,KODEJAB ,PENGALI ,UPENGALI ,PERSENTASE ,RPBONUS ,TGLMULAI ,TGLSAMPAI
						,(
							SELECT SUM(JMLHARI)
							FROM harikerja
							WHERE CAST(BULAN AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m') AS UNSIGNED)
								AND CAST(BULAN AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m') AS UNSIGNED)
							GROUP BY BULAN
						) AS TOTALHARIKERJA
					FROM bonus
					WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
						AND FPENGALI = 'H'
						AND GRADE IS NOT NULL AND GRADE != ''
						AND KODEJAB IS NOT NULL AND KODEJAB != ''
						AND (NIK IS NULL OR NIK = '')
					GROUP BY GRADE, KODEJAB
				) AS t11
				JOIN karyawan AS t12 ON(t12.GRADE = t11.GRADE AND t12.KODEJAB = t11.KODEJAB)
				JOIN hitungpresensi AS t13 ON(t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI
					AND t13.NIK = t12.NIK
					AND (t13.JENISABSEN = 'HD' OR SUBSTR(t13.JENISABSEN,1,1) = 'C'))
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPBONUS = (((t2.JMLHARI / t2.TOTALHARIKERJA) * (t2.PERSENTASE / 100)
				* ((IF(t2.UPENGALI = 'A', t1.RPUPAHPOKOK,
					IF(t2.UPENGALI = 'B', (t1.RPUPAHPOKOK + t1.RPTJABATAN),
						(t1.RPUPAHPOKOK + t1.RPUMSK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTANAK + t1.RPTISTRI))))/173)) + t2.RPBONUS)";
		$this->db->query($sql);
		
		//FPENGALI = 'L'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT GRADE ,KODEJAB ,PENGALI ,PERSENTASE ,RPBONUS 
				FROM bonus
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND FPENGALI = 'L'
					AND GRADE IS NOT NULL AND GRADE != ''
					AND KODEJAB IS NOT NULL AND KODEJAB != ''
					AND (NIK IS NULL OR NIK = '')
				GROUP BY GRADE, KODEJAB
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE
				AND t2.KODEJAB = t1.KODEJAB)
			SET t1.RPBONUS = (t2.RPBONUS * t2.PENGALI * (t2.PERSENTASE / 100))";
		$this->db->query($sql);
	}
	
	/*function update_detilgaji_rpbonus_bynik($bulan, $tglmulai, $tglsampai, $nik_arr){
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
	}*/
	function update_detilgaji_rpbonus_bynik($bulan){
		//FPENGALI = 'H'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, t11.PENGALI, t11.UPENGALI, t11.PERSENTASE, t11.RPBONUS,
					COUNT(*) AS JMLHADIR, t11.TOTALHARIKERJA
				FROM (
					SELECT NIK ,PENGALI ,UPENGALI ,PERSENTASE ,RPBONUS ,TGLMULAI ,TGLSAMPAI
						,(
							SELECT SUM(JMLHARI)
							FROM harikerja
							WHERE CAST(BULAN AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m') AS UNSIGNED)
								AND CAST(BULAN AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m') AS UNSIGNED)
							GROUP BY BULAN
						) AS TOTALHARIKERJA
					FROM bonus
					WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
						AND FPENGALI = 'H'
						AND NIK IS NOT NULL AND NIK != ''
						AND (GRADE IS NULL OR GRADE = '')
						AND (KODEJAB IS NULL OR KODEJAB = '')
					GROUP BY NIK
				) AS t11
				JOIN karyawan AS t12 ON(t12.NIK = t11.NIK)
				JOIN hitungpresensi AS t13 ON(t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI
					AND t13.NIK = t12.NIK
					AND (t13.JENISABSEN = 'HD' OR SUBSTR(t13.JENISABSEN,1,1) = 'C'))
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPBONUS = (((t2.JMLHARI / t2.TOTALHARIKERJA) * (t2.PERSENTASE / 100)
				* ((IF(t2.UPENGALI = 'A', t1.RPUPAHPOKOK,
					IF(t2.UPENGALI = 'B', (t1.RPUPAHPOKOK + t1.RPTJABATAN),
						(t1.RPUPAHPOKOK + t1.RPUMSK + t1.RPTJABATAN + t1.RPTBHS + t1.RPTANAK + t1.RPTISTRI))))/173)) + t2.RPBONUS)";
		$this->db->query($sql);
		
		//FPENGALI = 'L'
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NIK ,PENGALI ,PERSENTASE ,RPBONUS 
				FROM bonus
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
					AND FPENGALI = 'L'
					AND NIK IS NOT NULL AND NIK != ''
					AND (GRADE IS NULL OR GRADE = '')
					AND (KODEJAB IS NULL OR KODEJAB = '')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPBONUS = (t2.RPBONUS * t2.PENGALI * (t2.PERSENTASE / 100))";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rpthadir_bynik($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NIK, RPTHADIR
				FROM tkehadiran
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTHADIR = t2.RPTHADIR";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rpthr($bulan, $nik_arr){
		foreach($nik_arr as $row){
			if(strlen(trim($row->NIK)) > 0){
				$sql = "UPDATE detilgaji AS t1
					SET t1.RPTHR = ".$row->RPTHR."
					WHERE t1.NIK = '".$row->NIK."'";
			}else{
				if($row->UPENGALI == 'A'){
					$sql = "UPDATE detilgaji AS t1
						JOIN karyawan AS t2 ON(t1.BULAN = '".$bulan."'
							AND t2.NIK = t1.NIK
							AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) >= ".$row->MSKERJADARI;
							if($row->MSKERJASAMPAI > 0){
								$sql .= " AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) <= ".$row->MSKERJASAMPAI;
							}
					$sql .= ")";
						if($row->PEMBAGI > 0){
							$sql .= " SET t1.RPTHR = ((ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) / ".$row->PEMBAGI.") * ".$row->PENGALI." * t1.RPUPAHPOKOK)";
						}else{
							$sql .= " SET t1.RPTHR = (".$row->PENGALI." * t1.RPUPAHPOKOK)";
						}
						
				}elseif($row->UPENGALI == 'B'){
					$sql = "UPDATE detilgaji AS t1
						JOIN karyawan AS t2 ON(t1.BULAN = '".$bulan."'
							AND t2.NIK = t1.NIK
							AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) >= ".$row->MSKERJADARI;
							if($row->MSKERJASAMPAI > 0){
								$sql .= " AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) <= ".$row->MSKERJASAMPAI;
							}
					$sql .= ")";
						if($row->PEMBAGI > 0){
							$sql .= " SET t1.RPTHR = ((ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) / ".$row->PEMBAGI.") * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTJABATAN))";
						}else{
							$sql .= " SET t1.RPTHR = (".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTJABATAN))";
						}
						
				}elseif($row->UPENGALI == 'C'){
					$sql = "UPDATE detilgaji AS t1
						JOIN karyawan AS t2 ON(t1.BULAN = '".$bulan."'
							AND t2.NIK = t1.NIK
							AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) >= ".$row->MSKERJADARI;
							if($row->MSKERJASAMPAI > 0){
								$sql .= " AND ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) <= ".$row->MSKERJASAMPAI;
							}
					$sql .= ")";
						if($row->PEMBAGI > 0){
							$sql .= " SET t1.RPTHR = ((ROUND(TIMESTAMPDIFF(DAY,t2.TGLMASUK,'".$row->TGLCUTOFF."') / 30) / ".$row->PEMBAGI.") * ".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPTJABATAN))";
						}else{
							$sql .= " SET t1.RPTHR = (".$row->PENGALI." * (t1.RPUPAHPOKOK + t1.RPTISTRI + t1.RPTANAK + t1.RPTBHS + t1.RPTJABATAN))";
						}
						
				}
			}
			
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rptkacamata_bynik($bulan, $nik_arr){
		foreach($nik_arr as $row){
			$sql = "UPDATE detilgaji
				SET detilgaji.RPTKACAMATA = (".$row->RPFRAME." + ".$row->RPLENSA.")
				WHERE detilgaji.NIK = '".$row->NIK."'
					AND detilgaji.BULAN = '".$bulan."'";
			$this->db->query($sql);
		}
	}
	
	function update_detilgaji_rpkompen_bynik($bulan){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NIK, RPKOMPEN
				FROM kompensasicuti
				WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPKOMPEN = t2.RPKOMPEN";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rpmakan_bynik($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT NIK, IFNULL(SUM(RPTMAKAN),0) AS RPTMAKAN, IFNULL(SUM(RPPMAKAN),0) AS RPPMAKAN
				FROM trmakan
				WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
					AND TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				GROUP BY NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPTMAKAN = t2.RPTMAKAN, t1.RPPMAKAN = t2.RPPMAKAN";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rppupahpokok_bynik($bulan, $nik_arr){
		$i = 1;
		foreach($nik_arr as $row){
			if($i > 50){
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
			if($i == 120){
				break;
			}
			$i++;
		}
	}
	
	function update_detilgaji_rpptransport_bynik($bulan, $tglmulai, $tglsampai){
		/**
		 * mendapatkan data Tunj.Transport yang telah ditambahkan, dengan adanya jemputan karyawan,
		 * maka ditambahkan ke RPPTRANSPORT sesuai dengan rupiah Tunj.Transport
		 */
		//by GRADE, ZONA 
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, (COUNT(*) * t11.RPTTRANSPORT) AS RPPTRANSPORT
				FROM (
					SELECT GRADE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
								TGLMULAI) AS TGLMULAI,
						IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
								TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
					FROM ttransport
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
						AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
						AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND GRADE IS NOT NULL AND GRADE != ''
						AND ZONA IS NOT NULL AND ZONA != ''
						AND (KODEJAB IS NULL OR KODEJAB = '')
						AND (NIK IS NULL OR NIK = '')
					GROUP BY GRADE, ZONA
				) AS t11
				JOIN karyawan AS t12 ON(t2.GRADE = t11.GRADE AND t12.ZONA = t11.ZONA
					AND t12.STATTUNTRAN = 'Y')
				JOIN (
					SELECT NIK, TANGGAL
					FROM jemputankar
					WHERE IKUTJEMPUTAN = 'Y'
				) AS t13 ON(t13.NIK = t12.NIK
					AND t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI)
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPPTRANSPORT = t2.RPPTRANSPORT";
		$this->db->query($sql);
		
		//by KODEJAB, ZONA 
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, (COUNT(*) * t11.RPTTRANSPORT) AS RPPTRANSPORT
				FROM (
					SELECT GRADE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
								TGLMULAI) AS TGLMULAI,
						IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
								TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
					FROM ttransport
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
						AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
						AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND KODEJAB IS NOT NULL AND KODEJAB != ''
						AND ZONA IS NOT NULL AND ZONA != ''
						AND (GRADE IS NULL OR GRADE = '')
						AND (NIK IS NULL OR NIK = '')
					GROUP BY KODEJAB, ZONA
				) AS t11
				JOIN karyawan AS t12 ON(t2.KODEJAB = t11.KODEJAB AND t12.ZONA = t11.ZONA
					AND t12.STATTUNTRAN = 'Y')
				JOIN (
					SELECT NIK, TANGGAL
					FROM jemputankar
					WHERE IKUTJEMPUTAN = 'Y'
				) AS t13 ON(t13.NIK = t12.NIK
					AND t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI)
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPPTRANSPORT = t2.RPPTRANSPORT";
		$this->db->query($sql);
		
		//by GRADE, KODEJAB, ZONA 
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, (COUNT(*) * t11.RPTTRANSPORT) AS RPPTRANSPORT
				FROM (
					SELECT GRADE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
								TGLMULAI) AS TGLMULAI,
						IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
								TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
					FROM ttransport
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
						AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
						AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND GRADE IS NOT NULL AND GRADE != ''
						AND KODEJAB IS NOT NULL AND KODEJAB != ''
						AND ZONA IS NOT NULL AND ZONA != ''
						AND (NIK IS NULL OR NIK = '')
					GROUP BY GRADE, KODEJAB, ZONA
				) AS t11
				JOIN karyawan AS t12 ON(t2.GRADE = t11.GRADE
					AND t2.KODEJAB = t11.KODEJAB
					AND t12.ZONA = t11.ZONA
					AND t12.STATTUNTRAN = 'Y')
				JOIN (
					SELECT NIK, TANGGAL
					FROM jemputankar
					WHERE IKUTJEMPUTAN = 'Y'
				) AS t13 ON(t13.NIK = t12.NIK
					AND t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI)
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPPTRANSPORT = t2.RPPTRANSPORT";
		$this->db->query($sql);
		
		//by NIK
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t13.NIK, (COUNT(*) * t11.RPTTRANSPORT) AS RPPTRANSPORT
				FROM (
					SELECT GRADE, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
								TGLMULAI) AS TGLMULAI,
						IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
							STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
								TGLSAMPAI) AS TGLSAMPAI, RPTTRANSPORT
					FROM ttransport
					WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
						AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
						AND TGLMULAI <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
						AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
						AND NIK IS NOT NULL AND NIK != ''
						AND (GRADE IS NULL OR GRADE = '')
						AND (KODEJAB IS NULL OR KODEJAB = '')
						AND (ZONA IS NULL OR ZONA = '')
					GROUP BY NIK
				) AS t11
				JOIN karyawan AS t12 ON(t2.NIK = t11.NIK
					AND t12.STATTUNTRAN = 'Y')
				JOIN (
					SELECT NIK, TANGGAL
					FROM jemputankar
					WHERE IKUTJEMPUTAN = 'Y'
				) AS t13 ON(t13.NIK = t12.NIK
					AND t13.TANGGAL >= t11.TGLMULAI AND t13.TANGGAL <= t11.TGLSAMPAI)
				GROUP BY t13.NIK
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPPTRANSPORT = t2.RPPTRANSPORT";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rppotsp_bykodesp($bulan, $tglmulai, $tglsampai){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT KODESP, RPPOTSP
				FROM potongansp
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.KODESP = t1.KODESP)
			SET t1.RPPOTSP = t2.RPPOTSP";
		$this->db->query($sql);
	}
	
	function update_detilgaji_rpumsk_bygrade($bulan, $grade_arr){
		$sql = "UPDATE detilgaji AS t1
			JOIN (
				SELECT GRADE, RPUMSK
				FROM umsk
				WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
					AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
					AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.GRADE = t1.GRADE)
			SET t1.RPUMSK = t2.RPUMSK";
		$this->db->query($sql);
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
		/*
		 * Untuk Pekerja yg masuk di bulan gaji, maka jmlmasuk/jmlharikerja*upahpokok
		 */
		$sql_upahpokok = "SELECT *
			FROM upahpokok
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NOURUT";
		$records_upahpokok = $this->db->query($sql_upahpokok)->result();
		
		/* 2.b. */
		if(sizeof($records_upahpokok) > 0){
			/* proses looping upah pokok */
			/*$grade_arr = array();
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
			}*/
			/* urutan upah pokok ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rpupahpokok_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rpupahpokok_bygrade($bulan);
			/* urutan upah pokok ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rpupahpokok_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rpupahpokok_bykodejab($bulan);
			/* urutan upah pokok ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rpupahpokok_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rpupahpokok_bygradekodejab($bulan);
			/* urutan upah pokok ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rpupahpokok_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rpupahpokok_bynik($bulan);
		}
		
		/* 25.a. */
		$sql_rpumsk = "SELECT GRADE, RPUMSK
			FROM umsk
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			LIMIT 1";
		$records_rpumsk = $this->db->query($sql_rpumsk)->result();
		
		/* 25.b. */
		if(sizeof($records_rpumsk) > 0){
			/* proses looping rpptransport */
			/*$grade_arr = array();
			
			foreach($records_rpumsk as $record){
				$obj = new stdClass();
				$obj->GRADE = $record->GRADE;
				$obj->RPUMSK = $record->RPUMSK;
				array_push($grade_arr, $obj);
			}*/
			
			$this->update_detilgaji_rpumsk_bygrade($bulan, $tglmulai, $tglsampai);
		}
		
		/* 3.a. */
		/* CATATAN:
		 * >> mencari tglmulai dan tglsampai di BULAN LALU
		 */
		$sql_rptpekerjaan = "SELECT NIK, GRADE, KATPEKERJAAN, RPTPEKERJAAN, TGLMULAI, TGLSAMPAI
			FROM tpekerjaan
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
			ORDER BY tpekerjaan.TGLMULAI, tpekerjaan.NOURUT";
		$records_rptpekerjaan = $this->db->query($sql_rptpekerjaan)->result();
		
		/* 3.b. */
		if(sizeof($records_rptpekerjaan) > 0){
			/* proses looping rptpekerjaan */
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rptpekerjaan ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptpekerjaan_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptpekerjaan_bygrade($bulan, $tglmulai, $tglsampai);
			/* urutan rptpekerjaan ke-2 berdasarkan GRADE+KATPEKERJAAN */
			//$this->update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $gradekatpekerjaan_arr);
			$this->update_detilgaji_rptpekerjaan_bygradekatpekerjaan($bulan, $tglmulai, $tglsampai);
			/* urutan rptpekerjaan ke-3 berdasarkan NIK */
			//$this->update_detilgaji_rptpekerjaan_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rptpekerjaan_bynik($bulan, $tglmulai, $tglsampai);
			/* urutan rptpekerjaan ke-4 berdasarkan KATPEKERJAAN */
			//$this->update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $katpekerjaan_arr);
			$this->update_detilgaji_rptpekerjaan_bykatpekerjaan($bulan, $tglmulai, $tglsampai);
		}
		
		/* 4.a. */
		$sql_rptbhs = "SELECT *
			FROM tbhs
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rptbhs = $this->db->query($sql_rptbhs)->result();
		
		/* 4.b. */
		if(sizeof($records_rptbhs) > 0){
			/* proses looping rptbhs */
			/*$bhsjepang_arr = array();
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptbhs as $record){
				if((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
					&& (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->BHSJEPANG = $record->BHSJEPANG;
					$obj->RPTBHS = $record->RPTBHS;
					array_push($bhsjepang_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
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
			}*/
			
			/* urutan rptbhs ke-1 berdasarkan LEVEL BAHASA JEPANG */
			//$this->update_detilgaji_rptbhs_bylevel($bulan, $bhsjepang_arr);
			$this->update_detilgaji_rptbhs_bylevel($bulan);
			/* urutan rptbhs ke-2 berdasarkan GRADE */
			//$this->update_detilgaji_rptbhs_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptbhs_bygrade($bulan);
			/* urutan rptbhs ke-3 berdasarkan KODEJAB | Catatan: tbhs tidak bergantung ke KODEJAB */
			//$this->update_detilgaji_rptbhs_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rptbhs_bykodejab($bulan);
			/* urutan rptbhs ke-4 berdasarkan GRADE+KODEJAB | Catatan: tbhs tidak bergantung ke KODEJAB */
			//$this->update_detilgaji_rptbhs_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rptbhs_bygradekodejab($bulan);
			/* urutan rptbhs ke-5 berdasarkan NIK */
			//$this->update_detilgaji_rptbhs_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rptbhs_bynik($bulan);
		}
		
		/* 5.a. */
		$sql_rptjabatan = "SELECT *
			FROM tjabatan
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rptjabatan = $this->db->query($sql_rptjabatan)->result();
		
		/* 5.b. */
		if(sizeof($records_rptjabatan) > 0){
			/* proses looping rptjabatan */
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rptjabatan ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptjabatan_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptjabatan_bygrade($bulan);
			/* urutan rptjabatan ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rptjabatan_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rptjabatan_bykodejab($bulan);
			/* urutan rptjabatan ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rptjabatan_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rptjabatan_bygradekodejab($bulan);
			/* urutan rptjabatan ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rptjabatan_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rptjabatan_bynik($bulan);
		}
		
		/* 6.a. */
		$sql_rptkeluarga = "SELECT *
			FROM tkeluarga
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST('".$bulan."' AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST('".$bulan."' AS UNSIGNED)
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
		
		/* 10.a. */
		$sql_rptshift = "SELECT NIK, GRADE, KODEJAB, SHIFTKE, FPENGALI, RPTSHIFT, TGLMULAI, TGLSAMPAI
			FROM tshift
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
			ORDER BY tshift.TGLMULAI, tshift.NOURUT";
		$records_rptshift = $this->db->query($sql_rptshift)->result();
		
		/* 10.b. */
		if(sizeof($records_rptshift) > 0){
			/* proses looping rptshift */
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rptshift ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptshift_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptshift_bygrade($bulan, $tglmulai, $tglsampai);
			/* urutan rptshift ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rptshift_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rptshift_bykodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rptshift ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rptshift_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rptshift_bygradekodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rptshift ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rptshift_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rptshift_bynik($bulan, $tglmulai, $tglsampai);
		}
		
		/* 7.a. */
		$sql_rpttransport = "SELECT NIK, GRADE, KODEJAB, ZONA, RPTTRANSPORT, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
					STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						TGLMULAI) AS TGLMULAI,
				IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
					STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						TGLSAMPAI) AS TGLSAMPAI
			FROM ttransport
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED))
				AND TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
			LIMIT 1";
		$records_rpttransport = $this->db->query($sql_rpttransport)->result();
		
		/* 7.b. */
		if(sizeof($records_rpttransport) > 0){
			/* proses looping rpttransport */
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rpttransport ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rpttransport_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rpttransport_bygrade($bulan, $tglmulai, $tglsampai);
			/* urutan rpttransport ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rpttransport_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rpttransport_bykodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rpttransport ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rpttransport_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rpttransport_bygradekodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rpttransport ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rpttransport_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rpttransport_bynik($bulan, $tglmulai, $tglsampai);
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
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rpinsdisiplin ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			$this->update_detilgaji_rpinsdisiplin_bygrade($bulan, $tglmulai, $tglsampai);
			/* urutan rpinsdisiplin ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			$this->update_detilgaji_rpinsdisiplin_bykodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rpinsdisiplin ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			$this->update_detilgaji_rpinsdisiplin_bygradekodejab($bulan, $tglmulai, $tglsampai);
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
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rptlembur ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptlembur_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptlembur_bygrade($bulan, $tglmulai, $tglsampai);
			/* urutan rptlembur ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rptlembur_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rptlembur_bykodejab($bulan, $tglmulai, $tglsampai);
			/* urutan rptlembur ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rptlembur_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rptlembur_bygradekodejab($bulan, $tglmulai, $tglsampai);
		}
		
		/* 11.a. */
		$sql_rptambahan = "SELECT tambahanlain2.*, jenistambahan.NAMAUPAH, jenistambahan.POSCETAK
			FROM tambahanlain2 JOIN jenistambahan ON(jenistambahan.KODEUPAH = tambahanlain2.KODEUPAH)
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rptambahan = $this->db->query($sql_rptambahan)->result();
		
		/* 11.b. */
		if(sizeof($records_rptambahan) > 0){
			/* proses looping rptambahan */
			/*$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rptambahan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->NAMAUPAH = $record->NAMAUPAH;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->NAMAUPAH = $record->NAMAUPAH;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->NAMAUPAH = $record->NAMAUPAH;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->KODEUPAH = $record->KODEUPAH;
					$obj->NAMAUPAH = $record->NAMAUPAH;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPTAMBAHAN = $record->RPTAMBAHAN;
					array_push($nik_arr, $obj);
					
				}
			}*/
			
			/* urutan rptambahan ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rptambahan_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rptambahan_bygrade($bulan);
			/* urutan rptambahan ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rptambahan_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rptambahan_bykodejab($bulan);
			/* urutan rptambahan ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rptambahan_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rptambahan_bygradekodejab($bulan);
			/* urutan rptambahan ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rptambahan_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rptambahan_bynik($bulan);
		}
		
		/* 12.a. */
		$sql_rppotongan = "SELECT potonganlain2.*, jenispotongan.NAMAPOTONGAN, jenispotongan.POSCETAK
			FROM potonganlain2 JOIN jenispotongan ON(jenispotongan.KODEPOTONGAN = potonganlain2.KODEPOTONGAN)
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NOURUT";
		$records_rppotongan = $this->db->query($sql_rppotongan)->result();
		
		/* 12.b. */
		if(sizeof($records_rppotongan) > 0){
			/* proses looping rppotongan */
			/*$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			$nik_arr = array();
			
			foreach($records_rppotongan as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->NAMAPOTONGAN = $record->NAMAPOTONGAN;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->NAMAPOTONGAN = $record->NAMAPOTONGAN;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->NAMAPOTONGAN = $record->NAMAPOTONGAN;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($gradekodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					$obj = new stdClass();
					$obj->NIK = $record->NIK;
					$obj->KODEPOTONGAN = $record->KODEPOTONGAN;
					$obj->NAMAPOTONGAN = $record->NAMAPOTONGAN;
					$obj->POSCETAK = $record->POSCETAK;
					$obj->KETERANGAN = $record->KETERANGAN;
					$obj->RPPOTONGAN = $record->RPPOTONGAN;
					array_push($nik_arr, $obj);
					
				}
			}*/
			
			/* urutan rppotongan ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rppotongan_bygrade($bulan, $grade_arr);
			$this->update_detilgaji_rppotongan_bygrade($bulan);
			/* urutan rppotongan ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rppotongan_bykodejab($bulan, $kodejab_arr);
			$this->update_detilgaji_rppotongan_bykodejab($bulan);
			/* urutan rppotongan ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rppotongan_bygradekodejab($bulan, $gradekodejab_arr);
			$this->update_detilgaji_rppotongan_bygradekodejab($bulan);
			/* urutan rppotongan ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rppotongan_bynik($bulan, $nik_arr);
			$this->update_detilgaji_rppotongan_bynik($bulan);
		}
		
		/* 13.a. */
		$sql_rpcicilan = "SELECT *
			FROM pcicilan 
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
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
		$sql_rptqcp = "SELECT NIK, RPQCP, IF(TGLMULAI <= STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
					STR_TO_DATE('".$tglmulai."','%Y-%m-%d'),
						TGLMULAI) AS TGLMULAI,
				IF(TGLSAMPAI >= STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
					STR_TO_DATE('".$tglsampai."','%Y-%m-%d'),
						TGLSAMPAI) AS TGLSAMPAI
			FROM tqcp
			WHERE TGLMULAI <= STR_TO_DATE('".$tglsampai."','%Y-%m-%d')
				AND TGLSAMPAI >= STR_TO_DATE('".$tglmulai."','%Y-%m-%d')
			LIMIT 1";
		$records_rptqcp = $this->db->query($sql_rptqcp)->result();
		
		/* 14.b */
		if(sizeof($records_rptqcp) > 0){
			/* proses looping rptqcp */
			/*$nik_arr = array();
			
			foreach($records_rptqcp as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->TGLMULAI = $record->TGLMULAI;
				$obj->TGLSAMPAI = $record->TGLSAMPAI;
				$obj->RPQCP = $record->RPQCP;
				array_push($nik_arr, $obj);
			}*/
			
			/* urutan rptqcp ke-1 berdasarkan NIK */
			$this->update_detilgaji_rptqcp_bynik($bulan);
		}
		
		/* 15.a. */
		$sql_rptsimpati = "SELECT BULAN, NIK, JNSSIMPATI, RPTSIMPATI
			FROM uangsimpati
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			ORDER BY NIK
			LIMIT 1";
		$records_rptsimpati = $this->db->query($sql_rptsimpati)->result();
		
		/* 15.b. */
		if(sizeof($records_rptsimpati) > 0){
			/* proses looping rptsimpati */
			/*$nik_arr = array();
			
			foreach($records_rptsimpati as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTSIMPATI = $record->RPTSIMPATI;
				array_push($nik_arr, $obj);
			}*/
			
			/* urutan rptsimpati ke-1 berdasarkan NIK */
			$this->update_detilgaji_rptsimpati_bynik($bulan);
		}
		
		/* 16.a. */
		$sql_rpbonus = "SELECT BULAN ,NOURUT ,NIK ,GRADE ,KODEJAB ,RPBONUS ,FPENGALI ,PENGALI ,UPENGALI
				,PERSENTASE ,USERNAME ,TGLMULAI ,TGLSAMPAI
			FROM bonus
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			LIMIT 1";
		$records_rpbonus = $this->db->query($sql_rpbonus)->result();
		
		/* 16.b. */
		if(sizeof($records_rpbonus) > 0){
			/* proses looping rpbonus */
			/*$grade_arr = array();
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
			}*/
			
			/* urutan rpbonus ke-1 berdasarkan GRADE */
			//$this->update_detilgaji_rpbonus_bygrade($bulan, $tglmulai, $tglsampai, $grade_arr);
			$this->update_detilgaji_rpbonus_bygrade($bulan);
			/* urutan rpbonus ke-2 berdasarkan KODEJAB */
			//$this->update_detilgaji_rpbonus_bykodejab($bulan, $tglmulai, $tglsampai, $kodejab_arr);
			$this->update_detilgaji_rpbonus_bykodejab($bulan);
			/* urutan rpbonus ke-3 berdasarkan GRADE+KODEJAB */
			//$this->update_detilgaji_rpbonus_bygradekodejab($bulan, $tglmulai, $tglsampai, $gradekodejab_arr);
			$this->update_detilgaji_rpbonus_bygradekodejab($bulan);
			/* urutan rpbonus ke-4 berdasarkan NIK */
			//$this->update_detilgaji_rpbonus_bynik($bulan, $tglmulai, $tglsampai, $nik_arr);
			$this->update_detilgaji_rpbonus_bynik($bulan);
		}
		
		/* 17.a. */
		$sql_rpthadir = "SELECT NIK, RPTHADIR
			FROM tkehadiran
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			LIMIT 1";
		$records_rpthadir = $this->db->query($sql_rpthadir)->result();
		
		/* 17.b. */
		if(sizeof($records_rpthadir) > 0){
			/* proses looping rpthadir */
			/*$nik_arr = array();
			
			foreach($records_rpthadir as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTHADIR = $record->RPTHADIR;
				array_push($nik_arr, $obj);
			}*/
			
			$this->update_detilgaji_rpthadir_bynik($bulan);
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
				$obj->TGLCUTOFF = $record->TGLCUTOFF;
				$obj->MSKERJADARI = $record->MSKERJADARI;
				$obj->MSKERJASAMPAI = $record->MSKERJASAMPAI;
				$obj->PEMBAGI = $record->PEMBAGI;
				$obj->PENGALI = $record->PENGALI;
				$obj->UPENGALI = $record->UPENGALI;
				$obj->RPTHR = $record->RPTHR;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rpthr($bulan, $nik_arr);
		}
		
		/* 19.a. */
		$sql_rptkacamata = "SELECT BULAN
				,NIK
				,TANGGAL
				,IFNULL(RPFRAME,0) AS RPFRAME
				,IFNULL(RPLENSA,0) AS RPLENSA
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
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
			LIMIT 1";
		$records_rpkompen = $this->db->query($sql_rpkompen)->result();
		
		/* 20.b. */
		if(sizeof($records_rpkompen) > 0){
			/* proses looping rpkompen */
			/*$nik_arr = array();
			
			foreach($records_rpkompen as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->SISACUTI = $record->SISACUTI;
				$obj->RPKOMPEN = $record->RPKOMPEN;
				array_push($nik_arr, $obj);
			}*/
			
			$this->update_detilgaji_rpkompen_bynik($bulan);
		}
		
		/* 21.a. */
		$sql_rpmakan = "SELECT NIK, IFNULL(SUM(RPTMAKAN),0) AS RPTMAKAN, IFNULL(SUM(RPPMAKAN),0) AS RPPMAKAN
			FROM trmakan
			WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
			GROUP BY NIK
			LIMIT 1";
		$records_rpmakan = $this->db->query($sql_rpmakan)->result();
		
		/* 21.b. */
		if(sizeof($records_rpmakan) > 0){
			/* proses looping rpmakan */
			/*$nik_arr = array();
			
			foreach($records_rpmakan as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->RPTMAKAN = $record->RPTMAKAN;
				$obj->RPPMAKAN = $record->RPPMAKAN;
				array_push($nik_arr, $obj);
			}*/
			
			$this->update_detilgaji_rpmakan_bynik($bulan, $tglmulai, $tglsampai);
		}
		
		/* 22.a. */
		/*$sql_rppupahpokok = "SELECT NIK, SUM(JAMKURANG) AS JMLJAMKURANG
			FROM hitungpresensi
			WHERE CAST(BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED) AND JAMKURANG > 2
			GROUP BY NIK";
		$records_rppupahpokok = $this->db->query($sql_rppupahpokok)->result();*/
		/**
		 * CATATAN: TOTAL JMLJAMKURANG dalam sebulan * (1/173) * UPAHPOKOK Bulan sebelumnya
		 */
		$sql_rppupahpokok = "UPDATE detilgaji AS t1
			JOIN (
				SELECT t11.NIK, (t12.JMLJAMKURANG * (1/173) * t11.RPUPAHPOKOK) AS RPPUPAHPOKOK
				FROM
				(
					SELECT NIK, RPUPAHPOKOK
					FROM gajibulanan
					WHERE CAST(BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) - 1)
				) AS t11
				JOIN (
					SELECT NIK, SUM(JAMKURANG) AS JMLJAMKURANG
					FROM hitungpresensi
					WHERE CAST(BULAN AS UNSIGNED) = (CAST('".$bulan."' AS UNSIGNED) - 1) AND JAMKURANG > 2
					GROUP BY NIK
				) AS t12 ON(t12.NIK = t11.NIK)
			) AS t2 ON(CAST(t1.BULAN AS UNSIGNED) = CAST('".$bulan."' AS UNSIGNED)
				AND t2.NIK = t1.NIK)
			SET t1.RPPUPAHPOKOK = t2.RPPUPAHPOKOK";
		$this->db->query($sql_rppupahpokok);
		
		/* 22.b. */
		/*if(sizeof($records_rppupahpokok) > 0){
			// proses looping rppupahpokok 
			$nik_arr = array();
			
			foreach($records_rppupahpokok as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				array_push($nik_arr, $obj);
			}
			
			$this->update_detilgaji_rppupahpokok_bynik($bulan, $nik_arr);
		}*/
		
		/* 23.a. */
		$sql_rpptransport = "SELECT NIK, TANGGAL
			FROM jemputankar
			WHERE TANGGAL >= STR_TO_DATE('".$tglmulai."', '%Y-%m-%d')
				AND TANGGAL <= STR_TO_DATE('".$tglsampai."', '%Y-%m-%d')
				AND IKUTJEMPUTAN = 'Y'
			LIMIT 1";
		$records_rpptransport = $this->db->query($sql_rpptransport)->result();
		
		/* 23.b. */
		if(sizeof($records_rpptransport) > 0){
			/* proses looping rpptransport */
			/*$nik_arr = array();
			
			foreach($records_rpptransport as $record){
				$obj = new stdClass();
				$obj->NIK = $record->NIK;
				$obj->TANGGAL = $record->TANGGAL;
				$obj->GRADE = $record->GRADE;
				$obj->KODEJAB = $record->KODEJAB;
				$obj->ZONA = $record->ZONA;
				array_push($nik_arr, $obj);
			}*/
			
			$this->update_detilgaji_rpptransport_bynik($bulan, $tglmulai, $tglsampai);
		}
		
		/* 24.a. */
		$sql_rppotsp = "SELECT KODESP, RPPOTSP
			FROM potongansp
			WHERE CAST(DATE_FORMAT(VALIDFROM,'%Y%m') AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND (VALIDTO IS NULL OR CAST(DATE_FORMAT(VALIDTO,'%Y%m') AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED))
				AND CAST(BULANMULAI AS UNSIGNED) <= CAST(DATE_FORMAT('".$tglmulai."','%Y%m') AS UNSIGNED)
				AND CAST(BULANSAMPAI AS UNSIGNED) >= CAST(DATE_FORMAT('".$tglsampai."','%Y%m') AS UNSIGNED)
			LIMIT 1";
		$records_rppotsp = $this->db->query($sql_rppotsp)->result();
		
		/* 24.b. */
		if(sizeof($records_rppotsp) > 0){
			/* proses looping rpptransport */
			/*$kodesp_arr = array();
			
			foreach($records_rppotsp as $record){
				$obj = new stdClass();
				$obj->KODESP = $record->KODESP;
				$obj->RPPOTSP = $record->RPPOTSP;
				array_push($kodesp_arr, $obj);
			}*/
			
			$this->update_detilgaji_rppotsp_bykodesp($bulan, $tglmulai, $tglsampai);
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
						SUM(detilgaji.RPTAMBAHANLAIN) AS RPTAMBAHANLAIN,
						SUM(detilgaji.RPPOTSP) AS RPPOTSP,
						SUM(detilgaji.RPUMSK) AS RPUMSK
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
					GROUP BY detilgaji.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t2.NIK = t1.NIK)
				LEFT JOIN (
					SELECT detilgajitambahan.NIK, SUM(RPTAMBAHAN) AS RPTAMBAHAN
					FROM detilgajitambahan
					WHERE detilgajitambahan.BULAN = '".$bulan."'
					GROUP BY detilgajitambahan.NIK
				) AS t3 ON(t1.BULAN = '".$bulan."' AND t3.NIK = t1.NIK)
				LEFT JOIN (
					SELECT detilgajipotongan.NIK, SUM(RPPOTONGAN) AS RPPOTONGAN
					FROM detilgajipotongan
					WHERE detilgajipotongan.BULAN = '".$bulan."'
					GROUP BY detilgajipotongan.NIK
				) AS t4 ON(t1.BULAN = '".$bulan."' AND t4.NIK = t1.NIK)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK,
				t1.RPTUNJTETAP = (t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS + t2.RPUMSK),
				t1.RPTUNJTDKTTP = (t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP),
				t1.RPNONUPAH = (t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA),
				t1.RPPOTONGAN = (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + IFNULL(t4.RPPOTONGAN, 0) + t2.RPPOTSP),
				t1.RPTAMBAHAN = IFNULL(t3.RPTAMBAHAN, 0),
				t1.RPTOTGAJI = (t2.RPUPAHPOKOK
					+ t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS
					+ t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP
					+ t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA
					+ IFNULL(t3.RPTAMBAHAN, 0))
					- (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + IFNULL(t4.RPPOTONGAN, 0) + t2.RPPOTSP)";
		
		$this->db->query($sqlu_gajibulanan);
		/*$sqlu_gajibulanan = "UPDATE gajibulanan AS t1 JOIN (
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
						SUM(detilgaji.RPTAMBAHANLAIN) AS RPTAMBAHANLAIN,
						SUM(detilgaji.RPPOTSP) AS RPPOTSP
					FROM detilgaji
					WHERE detilgaji.BULAN = '".$bulan."'
					GROUP BY detilgaji.NIK
				) AS t2 ON(t1.BULAN = '".$bulan."' AND t2.NIK = t1.NIK)
			SET t1.RPUPAHPOKOK = t2.RPUPAHPOKOK,
				t1.RPTUNJTETAP = (t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS),
				t1.RPTUNJTDKTTP = (t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP),
				t1.RPNONUPAH = (t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA),
				t1.RPPOTONGAN = (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + t2.RPPOTONGAN1 + t2.RPPOTONGAN2 + t2.RPPOTONGAN3 + t2.RPPOTONGAN4 + t2.RPPOTONGAN5 + t2.RPPOTONGANLAIN + t2.RPPOTSP),
				t1.RPTAMBAHAN = (t2.RPTAMBAHAN1 + t2.RPTAMBAHAN2 + t2.RPTAMBAHAN3 + t2.RPTAMBAHAN4 + t2.RPTAMBAHAN5 + t2.RPTAMBAHANLAIN),
				t1.RPTOTGAJI = (t2.RPUPAHPOKOK
					+ t2.RPTJABATAN + t2.RPTISTRI + t2.RPTANAK + t2.RPTBHS
					+ t2.RPTTRANSPORT + t2.RPTSHIFT + t2.RPTPEKERJAAN + t2.RPTQCP
					+ t2.RPIDISIPLIN + t2.RPTLEMBUR + t2.RPTHADIR + t2.RPTHR + t2.RPBONUS + t2.RPKOMPEN + t2.RPTMAKAN + t2.RPTSIMPATI + t2.RPTKACAMATA
					+ t2.RPTAMBAHAN1 + t2.RPTAMBAHAN2 + t2.RPTAMBAHAN3 + t2.RPTAMBAHAN4 + t2.RPTAMBAHAN5 + t2.RPTAMBAHANLAIN)
					- (t2.RPPUPAHPOKOK + t2.RPPMAKAN + t2.RPPTRANSPORT + t2.RPCICILAN1 + t2.RPCICILAN2 + t2.RPPOTONGAN1 + t2.RPPOTONGAN2 + t2.RPPOTONGAN3 + t2.RPPOTONGAN4 + t2.RPPOTONGAN5 + t2.RPPOTONGANLAIN)";
		$this->db->query($sqlu_gajibulanan);*/
		
	}
	
}
?>