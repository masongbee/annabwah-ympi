<?php

class M_public_function extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function getJabatan($start, $page, $limit, $filter){
		/*$query = "SELECT IDJAB, NAMAJAB, unitkerja.KODEUNIT, unitkerja.NAMAUNIT, kelompok.KODEKEL, kelompok.NAMAKEL
			FROM jabatan
			JOIN unitkerja ON(unitkerja.KODEUNIT = jabatan.KODEUNIT)
			LEFT JOIN kelompok ON(kelompok.KODEKEL = jabatan.KODEKEL)
			LIMIT ".$start.",".$limit;*/

		$select = "SELECT IDJAB, NAMAJAB, unitkerja.KODEUNIT, unitkerja.NAMAUNIT, kelompok.KODEKEL, kelompok.NAMAKEL";
		$from = " FROM jabatan
			JOIN unitkerja ON(unitkerja.KODEUNIT = jabatan.KODEUNIT)
			LEFT JOIN kelompok ON(kelompok.KODEKEL = jabatan.KODEKEL)";
		$offset = " LIMIT ".$start.",".$limit;

		if ($filter != '') {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= "(";
				$from .= " jabatan.IDJAB = '".addslashes(strtolower($filter))."'";
			$from .= ")";
		}

		$query = $select.$from.$offset;
		
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('jabatan')->num_rows();
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
		}
		
		$json   = array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'total'     => $total,
				'data'      => $data
		);
		
		return $json;
	}
	
	/**
	 * Fungsi : permohonan_save
	 *
	 * Untuk Proses Permohonan Ijin / Permohonan Cuti yang di-tetapkan (T) atau di-batalkan (C).
	 * Jika 'T' dan AMBILCUTI = 1 ==> akan berakibat pada:
	 * >> 1. db.cutitahunan.JMLCUTI (+)
	 * >> 2. db.cutitahunan.SISACUTI (-)
	 * >> 3. WHERE db.cutitahunan.DIKOMPENSASI = 'N', dan diutamakan db.cutitahunan.JENISCUTI = 0 (Cuti Tahunan)
	 * Jika 'T' dan AMBILCUTI = 0 ==> tidak mengubah db.cutitahunan
	 * Jika 'C', yang dilakukan adalah:
	 * >> 1. Simpan data db.permohonanijin.STATUSIJIN / db.permohonancuti.STATUSCUTI
	 * >> 2. Jika STATUSIJIN / STATUSCUTI sebelumnya adalah 'T' ==> berakibat:
	 * >> 2.a. db.cutitahunan.JMLCUTI (-)
	 * >> 2.b. db.cutitahunan.SISACUTI (+)
	 * >> 2.c. WHERE db.cutitahunan.DIKOMPENSASI = 'N', dan diutamakan db.cutitahunan.JENISCUTI = 0 (Cuti Tahunan)
	 * >> 3. Jika STATUSIJIN / STATUSCUTI sebelumnya adalah 'A'(di-ajukan) ==> tidak berakibat pada db.cutitahunan
	 */
	function permohonan_save($data){
		$last   = NULL;
		
		$tablename = (isset($data->STATUSIJIN) ? 'permohonanijin' : 'permohonancuti');
		$columnstatus = (isset($data->STATUSIJIN) ? 'STATUSIJIN' : 'STATUSCUTI');
		if($tablename == 'permohonanijin'){
			$tablekey = array('NOIJIN'=>$data->NOIJIN, 'NIK'=>$data->NIK,
							  'JENISABSEN'=>$data->JENISABSEN, 'NIKPERSONALIA'=>$data->NIKPERSONALIA);
		}else{
			$tablekey = array('NOCUTI'=>$data->NOIJIN, 'NIKHR'=>$data->NIKHR);
		}
		
		if(((isset($data->STATUSIJIN) ? $data->STATUSIJIN : $data->STATUSCUTI) == 'T')
		   && ($data->AMBILCUTI == 1)){
			$this->firephp->log('status = T, ambil cuti = 1');
			//update db.cutitahunan dengan JENISCUTI = '0' (Cuti Tahunan)
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
			$this->db->where($tablekey)->set($columnstatus, 'T')->update($tablename);
			
		}elseif(((isset($data->STATUSIJIN) ? $data->STATUSIJIN : $data->STATUSCUTI) == 'C')
		   && ($data->AMBILCUTI == 1)){
			$this->firephp->log('status = C, ambil cuti = 1');
			//Jika Permohonan Ijin / Cuti di-Batalkan (C)
			$status_prev = $this->db->select($columnstatus)->where($tablekey)
				->get($tablename)->row()->$columnstatus;
			
			if($status_prev == 'T'){
				//update db.cutitahunan dan kembalikan Jumlah Cuti yang telah dikurangkan sebelumnya
				//utamakan JENISCUTI = '1' (Cuti Tambahan), terbalik dari pengambilan cuti sebelumnya dari Cuti Tahunan -> Cuti Tambahan
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
				$this->db->where($tablekey)->set($columnstatus, 'C')->update($tablename);
				
			}else{
				//hanya update db.permohonanijin / db.permohonancuti tanpa update db.cutitahunan
				$this->db->where($tablekey)->set($columnstatus, 'C')->update($tablename);
			}
		}else{
			$this->firephp->log('status = T/C, ambil cuti = 0');
			//hanya update db.permohonanijin / db.permohonancuti tanpa update db.cutitahunan,
			//karena AMBILCUTI = 0 (Sisa Cuti = 0)
			$this->db->where($tablekey)->set($columnstatus, $data->$columnstatus)->update($tablename);
		}
		
		$last   = $this->db->where($tablekey)->get($tablename)->row();
		$total  = $this->db->get($tablename)->num_rows();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil disimpan',
			"total"     => $total,
			"data"      => $last
		);
		
		return $json;
	}

	function getGroupName(){
		$this->firephp->log($this->session->userdata('group_id')); 
		$query = "SELECT lower(GROUP_NAME) as GROUP_NAME
			FROM s_usergroups
			WHERE GROUP_ID IN(".$this->session->userdata('group_id').")";
		
		$result = $this->db->query($query)->result();
		
		return $result;		
	}

	function gen_nik($kode){
		$maxno = '0001';

		$sql = "SELECT LPAD(CAST((MAX(CAST(SUBSTR(NIK,6,4) AS UNSIGNED))+1) AS CHAR), 4, '0') AS maxno
			FROM karyawan
			WHERE SUBSTR(NIK,1,5) = '".$kode."'";
		$rs = $this->db->query($sql)->row()->maxno;
		if (strlen($rs) > 0) {
			$maxno = $rs;
		}

		return $maxno;
	}

	function getKaryawanByUnitKerja(){
		$sql = "SELECT NIK,NAMAKAR
			FROM karyawan
			WHERE CAST(KODEUNIT AS UNSIGNED) = CAST(".$this->session->userdata('user_kodeunit')." AS UNSIGNED)";
		
		$result = $this->db->query($sql)->result();
		
		return $result;		
	}

	function get_atasan_spl() {
		/*$arrkodeunit_atasan = array();
		$sql_kodeunit_atasan = "SELECT *
			FROM (
				SELECT @row_number:=@row_number+1 AS row_number, parent.KODEUNIT, parent.NAMAUNIT
				FROM unitkerja AS node,
					unitkerja AS parent,
					(SELECT @row_number:=0) AS t
				WHERE node.LFT BETWEEN parent.LFT AND parent.RGT
					AND node.KODEUNIT = '".$this->session->userdata('user_kodeunit')."'
				ORDER BY node.LFT
			) AS vu_single_path
			WHERE vu_single_path.KODEUNIT != '".$this->session->userdata('user_kodeunit')."'";
		$rs_kodeunit_atasan = $this->db->query($sql_kodeunit_atasan)->result();
		foreach ($rs_kodeunit_atasan as $row) {
			array_push($arrkodeunit_atasan, $row->KODEUNIT);
		}

		$query = $this->db->select('NIK,NAMAKAR')->where_in('KODEUNIT',$arrkodeunit_atasan)->get('karyawan')->result();*/
		/*$query = $this->db->select('NIK,NAMAKAR')
			->where('KODEUNIT',$this->session->userdata('user_kodeunit'))
			->where('GRADE >',$this->session->userdata('mygrade'))
			->from('karyawan')
			->join('s_users', 's_users.USER_KARYAWAN = karyawan.NIK')
			->get()->result();*/

		$idjab = $this->db->query("SELECT IDJAB FROM karyawan WHERE NIK = '".$this->session->userdata('user_nik')."'")->row()->IDJAB;
		$idjab_atasan = "";
		if (substr($idjab, 0, 2) == 'ST') {
			$idjab_atasan = "'CH','MN','GM'";
		} elseif (substr($idjab, 0, 2) == 'WK') {
			$idjab_atasan = "'KK','FR','MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'KK') {
			$idjab_atasan = "'FR','MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'CH') {
			$idjab_atasan = "'MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'FR') {
			$idjab_atasan = "'MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'MN') {
			$idjab_atasan = "'GM','JA'";
		}

		if ($idjab_atasan != "") {
			$userkodeunit = $this->session->userdata('user_kodeunit');
			$div = $userkodeunit[0];
			$sql = "SELECT NIK,NAMAKAR
				FROM karyawan 
				JOIN s_users ON(s_users.USER_KARYAWAN = karyawan.NIK)
				WHERE KODEUNIT LIKE '".$div."%'
					AND SUBSTR(IDJAB,1,2) IN(".$idjab_atasan.")
					AND FIND_IN_SET(4,USER_GROUP)";

			$result = $this->db->query($sql)->result();

			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'data'      => $result
			);

		} else {
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'data'      => array()
			);
		}
		
		
		/*$userkodeunit = $this->session->userdata('user_kodeunit');
		$div = $userkodeunit[0];
		$sql = "SELECT NIK,NAMAKAR
			FROM karyawan 
			JOIN s_users ON(s_users.USER_KARYAWAN = karyawan.NIK)
			WHERE KODEUNIT LIKE '".$div."%'
				AND IDJAB LIKE '".$idjab_atasan."'
				AND FIND_IN_SET(4,USER_GROUP)";
		$result = $this->db->query($sql)->result();
		
		// $data   = array();
		// foreach($query as $result){
		// 	$data[] = $result;
		// }
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'data'      => $result
		);*/
		
		return $json;	
	}

	function get_atasan_cuti() {
		$idjab = $this->db->query("SELECT IDJAB FROM karyawan WHERE NIK = '".$this->session->userdata('user_nik')."'")->row()->IDJAB;
		$idjab_atasan = "";
		if (substr($idjab, 0, 2) == 'ST') {
			$idjab_atasan = "'CH','MN','GM'";
		} elseif (substr($idjab, 0, 2) == 'WK') {
			$idjab_atasan = "'KK','FR','MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'KK') {
			$idjab_atasan = "'FR','MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'CH') {
			$idjab_atasan = "'MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'FR') {
			$idjab_atasan = "'MN','GM','JA'";
		} elseif (substr($idjab, 0, 2) == 'MN') {
			$idjab_atasan = "'GM','JA'";
		}

		if ($idjab_atasan != "") {
			$userkodeunit = $this->session->userdata('user_kodeunit');
			$div = $userkodeunit[0];
			$sql = "SELECT NIK,NAMAKAR
				FROM karyawan 
				JOIN s_users ON(s_users.USER_KARYAWAN = karyawan.NIK)
				WHERE KODEUNIT LIKE '".$div."%'
					AND SUBSTR(IDJAB,1,2) IN(".$idjab_atasan.")
					AND FIND_IN_SET(5,USER_GROUP)";

			$result = $this->db->query($sql)->result();

			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'data'      => $result
			);

		} else {
			$json	= array(
				'success'   => TRUE,
				'message'   => "Loaded data",
				'data'      => array()
			);
		}
		
		return $json;
	}

	function get_personalia() {
		$sql = "SELECT NIK,NAMAKAR FROM karyawan WHERE NIK = '".$this->auth->initialization()->NIK_HRD."'";

		$result = $this->db->query($sql)->result();

		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'data'      => $result
		);
		
		return $json;
	}
	
}
?>