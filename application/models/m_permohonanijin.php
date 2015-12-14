<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_permohonanijin
 * 
 * Table	: permohonanijin
 *  
 * @author masongbee
 *
 */
class M_permohonanijin extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function getNIK($item){
		if($item['NIK'] != null)
		{
			$sql = "SELECT (CONCAT(NIK,' - ',NAMAKAR)) AS NAMA
			FROM karyawan
			WHERE NIK=".$this->db->escape($item['NIK']);
			$query = $this->db->query($sql)->result();
		}
		
		$data   = '';
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Loaded data',
			'data'      => $data
		);
		
		return $json;
	}
	
	function getSisa($nik){
		$sisacuti = 0;
		
		$query = $this->db->select('SUM(SISACUTI) AS SISACUTI')
			->where(array('NIK'=>$nik, 'DIKOMPENSASI'=>'N'))
			->group_by('NIK')->get('cutitahunan')->row();
		if(sizeof($query) > 0){
			$sisacuti = $query->SISACUTI;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Loaded data',
			'sisacuti' 	=> $sisacuti
		);
		
		return $json;
		/*if($item['JENIS'] == "SISACUTI")
		{
			$sql = "SELECT SUM(SISACUTI) AS SISACUTI
			FROM cutitahunan
			WHERE NIK = ".$this->db->escape($item['KEY'])." AND DIKOMPENSASI = 'N'
			GROUP BY NIK";
			$query = $this->db->query($sql)->result();
		}
		
		$data   = '';
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
			'success'   => TRUE,
			'message'   => 'Loaded data',
			'data'      => $data
		);
		
		return $json;*/
	}
	
	function get_personalia() {
		$query  = $this->db->query("SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
		FROM s_usergroups gp
		INNER JOIN s_users us ON LOCATE(gp.GROUP_ID,us.USER_GROUP)>0
		INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
		WHERE LOWER(GROUP_NAME) = LOWER('AdmAbsensi')")->result();
		$total  = $this->db->query("SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
		FROM s_usergroups gp
		INNER JOIN s_users us ON LOCATE(gp.GROUP_ID,us.USER_GROUP)>0
		INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
		WHERE LOWER(GROUP_NAME) = LOWER('AdmAbsensi')")->num_rows();
		
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
	
	function get_jenisabsen(){
		
		// KELABSEN=A (untuk alpha) juga diikutkan, krn bisa entri alpha -- bahtiar 24/9
		$where = "KELABSEN='I' OR KELABSEN='P' OR KELABSEN='A'";  
		$query  = $this->db->get_where('jenisabsen',$where)->result();
		$total  = $this->db->get_where('jenisabsen',$where)->num_rows();
		
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
	 * Fungsi	: getAll
	 * 
	 * Untuk mengambil all-data 30 hari terakhir
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($nik,$start, $page, $limit, $tglabsen, $allunit){
		$select = "SELECT permohonanijin.*, karyawan.NAMAKAR, karatasan1.NIK AS NIKATASAN1,
			karatasan1.NAMAKAR AS NAMAKARATASAN1, karhr.NIK AS NIKHR, karhr.NAMAKAR AS NAMAKARHR,
			IFNULL(cutitahunan.SISA, 0) AS SISA,
			jenisabsen.JENISABSEN_ALIAS,jenisabsen.KETERANGAN,
			IF(permohonanijin.AMBILCUTI=0,'POTONG GAJI',IF(permohonanijin.AMBILCUTI=1,'POTONG CUTI','OF')) AS AMBILCUTI_KETERANGAN,
			IF(permohonanijin.STATUSIJIN='A','DIAJUKAN',IF(permohonanijin.STATUSIJIN='T','DITETAPKAN','DIBATALKAN')) AS STATUSIJIN_KETERANGAN";
		$from 	= " FROM permohonanijin 
			LEFT JOIN karyawan ON(karyawan.NIK = permohonanijin.NIK)
			LEFT JOIN karyawan AS karatasan1 ON(karatasan1.NIK = permohonanijin.NIKATASAN1)
			LEFT JOIN karyawan AS karhr ON(karhr.NIK = permohonanijin.NIKPERSONALIA)
			LEFT JOIN jenisabsen ON(jenisabsen.JENISABSEN = permohonanijin.JENISABSEN)
			LEFT JOIN (
				SELECT NIK, SUM(SISACUTI) AS SISA FROM cutitahunan WHERE DIKOMPENSASI = 'N' GROUP BY NIK
			) AS cutitahunan ON(cutitahunan.NIK = permohonanijin.NIK)
			WHERE permohonanijin.TANGGAL >= STR_TO_DATE('".date('Y-m-d', strtotime(date('Y-m-d') . " -30 day"))."', '%Y-%m-%d')
				AND (NIKPERSONALIA = '".$nik."' OR NIKATASAN1 = '".$nik."')";
		$orderby = " ORDER BY permohonanijin.NOIJIN ASC";

		if (! empty($tglabsen)) {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " DATE(permohonanijin.TANGGAL) = STR_TO_DATE('".$tglabsen."','%Y-%m-%d')";
		} else {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " DATE(permohonanijin.TANGGAL) = DATE(now())";
		}

		if (empty($allunit)) {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " karyawan.KODEUNIT = '".$this->session->userdata('user_kodeunit')."'";
		}		

		// $query  = $this->db->select('permohonanijin.*, karyawan.NAMAKAR, karatasan1.NIK AS NIKATASAN1,
		// 		karatasan1.NAMAKAR AS NAMAKARATASAN1, karhr.NIK AS NIKHR, karhr.NAMAKAR AS NAMAKARHR,
		// 		IFNULL(cutitahunan.SISA, 0) AS SISA')
		// 	->limit($limit, $start)->where('NIKPERSONALIA', $nik)->or_where('NIKATASAN1', $nik)
		// 	->from('permohonanijin')->join('karyawan','karyawan.NIK = permohonanijin.NIK', 'left')
		// 	->join('karyawan AS karatasan1', 'karatasan1.NIK = permohonanijin.NIKATASAN1', 'left')
		// 	->join('karyawan AS karhr', 'karhr.NIK = permohonanijin.NIKPERSONALIA', 'left')
		// 	->join("(SELECT NIK, SUM(SISACUTI) AS SISA FROM cutitahunan WHERE DIKOMPENSASI = 'N' GROUP BY NIK) AS cutitahunan", "cutitahunan.NIK = permohonanijin.NIK", "left")
		// 	->where('permohonanijin.TANGGAL >=', date('Y-m-d', strtotime(date('Y-m-d') . " -10 day")))
		// 	->order_by('NOIJIN', 'ASC')->get()->result();
		$sql = $select.$from.$orderby;
		
		$result = $this->db->query($sql)->result();
		$total = sizeof($result);
		
		
		
		//$query  = $this->db->limit($limit, $start)->order_by('NOIJIN', 'ASC')->get('permohonanijin')->result();
		//$total  = $this->db->get('permohonanijin')->num_rows();
		
		// $data   = array();
		// foreach($query as $result){
		// 	$data[] = $result;
		// }
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'total'     => $total,
			'data'      => $result
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
		
		$pkey = array('NOIJIN'=>$data->NOIJIN);
		
		$row = $this->db->get_where('permohonanijin', $pkey)->row();
		
		if(sizeof($row) > 0){
			/*
			 * Data Exist
			 */
			$statusijin_prev = $row->STATUSIJIN;
			
			$arrdatau = array(
				'NIK'=>$data->NIK,
				'JENISABSEN'=>$data->JENISABSEN,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'JAMDARI'=>(isset($data->JAMDARI) ? $data->JAMDARI : NULL),
				'JAMSAMPAI'=>(isset($data->JAMSAMPAI) ? $data->JAMSAMPAI : NULL),
				'KEMBALI'=>(isset($data->KEMBALI) ? $data->KEMBALI : NULL),
				'AMBILCUTI'=>$data->AMBILCUTI,
				'DIAGNOSA'=>(isset($data->DIAGNOSA) ? $data->DIAGNOSA : NULL),
				'TINDAKAN'=>(isset($data->TINDAKAN) ? $data->TINDAKAN : NULL),
				'ANJURAN'=>(isset($data->ANJURAN) ? $data->ANJURAN : NULL),
				'PETUGASKLINIK'=>(isset($data->PETUGASKLINIK) ? $data->PETUGASKLINIK : NULL),
				'NIKATASAN1'=>(isset($data->NIKATASAN1) ? $data->NIKATASAN1 : NULL),
				'STATUSIJIN'=>(isset($data->STATUSIJIN) ? $data->STATUSIJIN : NULL),
				'NIKPERSONALIA'=>(isset($data->NIKPERSONALIA) ? $data->NIKPERSONALIA : NULL),
				'NIKGA'=>(isset($data->NIKGA) ? $data->NIKGA : NULL),
				'NIKDRIVER'=>(isset($data->NIKDRIVER) ? $data->NIKDRIVER : NULL),
				'NIKSECURITY'=>(isset($data->NIKSECURITY) ? $data->NIKSECURITY : NULL),
				'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('permohonanijin', $arrdatau);
			if($data->STATUSIJIN != $statusijin_prev && $data->STATUSIJIN == 'T' && $data->AMBILCUTI == 1){
				$this->cutitahunan_minus($data);
			}elseif($statusijin_prev == 'T' && $data->STATUSIJIN == 'C' && $data->AMBILCUTI == 1){
				$this->cutitahunan_return($data);
			}elseif($statusijin_prev == 'T' && $data->STATUSIJIN == 'T' && $data->AMBILCUTI == 3 ){
				$this->cutitahunan_return($data);
			}
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			
			$n = substr($data->NIKATASAN1,0,1);
			$sql = "SELECT MAX(NOIJIN) AS NOIJIN,NIKATASAN1,
			CONCAT(SUBSTR(NOIJIN,1,1),
			SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOIJIN),2,6)+1)),-6)) AS GEN
			FROM permohonanijin
			WHERE NOIJIN LIKE '".$n."%';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			
			
			$sql2 = "SELECT NOIJIN,NIKATASAN1,CONCAT(SUBSTR(NIKATASAN1,1,1),'000001') AS GEN
			FROM permohonanijin
			WHERE NOIJIN LIKE '".$n."%';";
			$rs2 = $this->db->query($sql2)->result();
			
			if($data->JENISABSEN != 'IP')
			{
				$noijin = ($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN);
				$noijin = ($noijin === NULL ? $n.'000001' : $noijin);
				$arrdatac = array(
					'NOIJIN'=>$noijin,
					'NIK'=>$data->NIK,
					'JENISABSEN'=>$data->JENISABSEN,
					'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
					'AMBILCUTI'=>$data->AMBILCUTI,
					'NIKATASAN1'=>$data->NIKATASAN1,
					'STATUSIJIN'=>'A',
					'NIKPERSONALIA'=>$data->NIKPERSONALIA,
					'USERNAME'=>$data->USERNAME
				);
			}
			else
			{
				$noijin = ($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN);
				$noijin = ($noijin === NULL ? $n.'000001' : $noijin);
				$arrdatac = array(
					'NOIJIN'=>$noijin,
					'NIK'=>$data->NIK,
					'JENISABSEN'=>$data->JENISABSEN,
					'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
					'JAMDARI'=>$data->JAMDARI,
					'JAMSAMPAI'=>$data->JAMSAMPAI,
					'KEMBALI'=>$data->KEMBALI,
					'AMBILCUTI'=>$data->AMBILCUTI,
					'DIAGNOSA'=>$data->DIAGNOSA,
					'TINDAKAN'=>$data->TINDAKAN,
					'ANJURAN'=>$data->ANJURAN,
					'PETUGASKLINIK'=>$data->PETUGASKLINIK,
					'NIKATASAN1'=>$data->NIKATASAN1,
					'STATUSIJIN'=>'A',
					'NIKPERSONALIA'=>$data->NIKPERSONALIA,
					'NIKGA'=>$data->NIKGA,
					'NIKDRIVER'=>$data->NIKDRIVER,
					'NIKSECURITY'=>$data->NIKSECURITY,
					'USERNAME'=>$data->USERNAME
				);
			}
			 
			/*$arrdatac = array('NOIJIN'=>(sizeof($rs) > 0 && !(substr($rs[0]->NOIJIN,1,6)) ? $rs[0]->GEN : $rs2[0]->GEN),'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'AMBILCUTI'=>$data->AMBILCUTI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>substr($data->NIKATASAN1,0,9),'STATUSIJIN'=>'A','NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);*/
			 
			$this->db->insert('permohonanijin', $arrdatac);
			$last   = $this->db->where($pkey)->get('permohonanijin')->row();
			
		}
		
		$total  = $this->db->get('permohonanijin')->num_rows();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						'total'     => $total,
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
		$pkey = array('NOIJIN'=>$data->NOIJIN);
		
		$this->db->where($pkey)->delete('permohonanijin');
		
		$total  = $this->db->get('permohonanijin')->num_rows();
		$last = $this->db->get('permohonanijin')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi: cutitahunan_minus
	 *
	 * Cuti Tahunan di-Ambil
	 */
	function cutitahunan_minus($data){
		$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'0', 'DIKOMPENSASI'=>'N'))
			->set('JMLCUTI', 'JMLCUTI+'.$data->JMLHARI, FALSE)
			->set('SISACUTI', 'SISACUTI-'.$data->JMLHARI, FALSE)
			->update('cutitahunan');
		if($this->db->affected_rows() == 0){
			//update db.cutitahunan dengan JENISCUTI = '1' (Cuti Tambahan)
			$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'1', 'DIKOMPENSASI'=>'N'))
				->set('JMLCUTI', 'JMLCUTI+'.$data->JMLHARI, FALSE)
				->set('SISACUTI', 'SISACUTI-'.$data->JMLHARI, FALSE)
				->update('cutitahunan');
		}
	}
	
	/**
	 * Fungsi: cutitahunan_return
	 *
	 * Cuti Tahunan dikembalikan karena permohonan ijin di-Batalkan
	 */
	function cutitahunan_return($data){
		$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'1', 'DIKOMPENSASI'=>'N', 'JMLCUTI >'=>0))
			->set('JMLCUTI', 'JMLCUTI-'.$data->JMLHARI, FALSE)
			->set('SISACUTI', 'SISACUTI+'.$data->JMLHARI, FALSE)
			->update('cutitahunan');
		if($this->db->affected_rows() == 0){
			//update db.cutitahunan dengan JENISCUTI = '0' (Cuti Tahunan)
			$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'0', 'DIKOMPENSASI'=>'N'))
				->set('JMLCUTI', 'JMLCUTI-'.$data->JMLHARI, FALSE)
				->set('SISACUTI', 'SISACUTI+'.$data->JMLHARI, FALSE)
				->update('cutitahunan');
		}
	}

	function getIjinPerTanggal($user_nik,$tglabsen,$allunit){
		$select = "SELECT permohonanijin.NOIJIN,CONCAT(permohonanijin.NIK,' - ',karyawan.NAMAKAR) AS KARYAWAN,
			CONCAT(permohonanijin.JENISABSEN,' - ',jenisabsen.KETERANGAN) AS JENISABSEN,
			permohonanijin.TANGGAL,permohonanijin.JAMDARI,permohonanijin.JAMSAMPAI,permohonanijin.KEMBALI,
			IF(permohonanijin.AMBILCUTI=0,'POTONG GAJI',IF(permohonanijin.AMBILCUTI=1,'POTONG CUTI','OF')) AS AMBILCUTI,
			IFNULL(cutitahunan.SISA, 0) AS SISACUTI,
			CONCAT(karatasan1.NIK,' - ',karatasan1.NAMAKAR) AS PENGUSUL,
			CONCAT(karhr.NIK,' - ',karhr.NAMAKAR) AS PERSONALIA,
			IF(permohonanijin.STATUSIJIN='A','DIAJUKAN',IF(permohonanijin.STATUSIJIN='T','DITETAPKAN','DIBATALKAN')) AS STATUSIJIN";
		$from 	= " FROM permohonanijin 
			LEFT JOIN karyawan ON(karyawan.NIK = permohonanijin.NIK)
			LEFT JOIN karyawan AS karatasan1 ON(karatasan1.NIK = permohonanijin.NIKATASAN1)
			LEFT JOIN karyawan AS karhr ON(karhr.NIK = permohonanijin.NIKPERSONALIA)
			LEFT JOIN jenisabsen ON(jenisabsen.JENISABSEN = permohonanijin.JENISABSEN)
			LEFT JOIN (
				SELECT NIK, SUM(SISACUTI) AS SISA FROM cutitahunan WHERE DIKOMPENSASI = 'N' GROUP BY NIK
			) AS cutitahunan ON(cutitahunan.NIK = permohonanijin.NIK)
			WHERE permohonanijin.TANGGAL >= STR_TO_DATE('".date('Y-m-d', strtotime(date('Y-m-d') . " -30 day"))."', '%Y-%m-%d')
				AND (NIKPERSONALIA = '".$user_nik."' OR NIKATASAN1 = '".$user_nik."')";
		$orderby = " ORDER BY permohonanijin.NOIJIN ASC";

		if (! empty($tglabsen)) {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " DATE(permohonanijin.TANGGAL) = STR_TO_DATE('".$tglabsen."','%Y-%m-%d')";
		} else {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " DATE(permohonanijin.TANGGAL) = DATE(now())";
		}

		if (empty($allunit)) {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " karyawan.KODEUNIT = '".$this->session->userdata('user_kodeunit')."'";
		}	
		
		$sql = $select.$from.$orderby;
		
		return $this->db->query($sql)->result();
	}
}
?>