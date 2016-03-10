<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_karyawan
 * 
 * Table	: karyawan
 *  
 * @author masongbee
 *
 */
class M_karyawan extends CI_Model{

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
	function getAll($start, $page, $limit, $filter, $filters, $filter_sisa_masa_kerja, $status, $masakerja, $sisamasakerja, $pertanggal){
		$filters = json_decode($filters);
		
		$this->db->select("NIK,karyawan.IDJAB,karyawan.KODEJAB,karyawan.GRADE,karyawan.KODEUNIT,karyawan.KODEKEL,NAMAKAR,TGLMASUK,JENISKEL,
					ALAMAT,DESA,RT,RW,KECAMATAN,KOTA,TELEPON,TMPLAHIR,TGLLAHIR,ANAKKE,JMLSAUDARA,
					PENDIDIKAN,JURUSAN,NAMASEKOLAH,AGAMA,NAMAAYAH,STATUSAYAH,ALAMATAYAH,PENDDKAYAH,
					PEKERJAYAH,NAMAIBU,STATUSIBU,ALAMATIBU,PENDDKIBU,PEKERJIBU,KAWIN,TGLKAWIN,NAMAPASANGAN,
					ALAMATPAS,TMPLAHIRPAS,TGLLAHIRPAS,AGAMAPAS,PEKERJPAS,KATPEKERJAAN,BHSJEPANG,
					IF((JAMSOSTEK = 'Y'),1,0) AS JAMSOSTEK,
					TGLJAMSOSTEK,STATUS,TGLSTATUS,TGLMUTASI,NOURUTKTRK,TGLKONTRAK,LAMAKONTRAK,
					NOACCKAR,NAMABANK,FOTO,USERNAME,STATTUNKEL,ZONA,
					IF((STATTUNTRAN = 'Y'),1,0) AS STATTUNTRAN,
					ifnull(period_diff(date_format(now(), '%Y%m'),date_format(TGLMASUK,'%Y%m')),0) AS MASA_KERJA_BLN,
					(IFNULL(DATEDIFF(LAST_DAY(NOW()),TGLMASUK),0)+1) AS MASA_KERJA_HARI,
					NPWP,KODESP,nametag.WARNATAGR,nametag.WARNATAGG,nametag.WARNATAGB,
					unitkerja.NAMAUNIT,unitkerja.SINGKATAN,kelompok.NAMAKEL,grade.KETERANGAN,
					jabatan.NAMAJAB,leveljabatan.NAMALEVEL");
		
		if(sizeof($filters) > 0){
			foreach($filters as $row){
				$propertyorfield = (isset($row->property) ? 'property' : 'field');
				
				if(isset($row->value) && !is_null($row->value)){
					$this->db->like($row->$propertyorfield, $row->value);
				}
			}
		}
		
		
		
		if($filter == '' && $filter_sisa_masa_kerja == '' && $status == ''){
			$query  = $this->db->from('karyawan')
				->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where_in('karyawan.STATUS',array('T','K','C'))
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
			$query_total = $this->db->select('COUNT(*) AS total')->where_in('karyawan.STATUS',array('T','K','C'))->get('karyawan')->row()->total;
		}elseif($filter != '' && $filter_sisa_masa_kerja == '' && $status == ''){
			$query  = $this->db->like('NAMAKAR', $filter)->or_like('NIK', $filter)
				->from('karyawan')->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where_in('karyawan.STATUS',array('T','K','C'))
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
			$query_total = $this->db->select('COUNT(*) AS total')->like('NAMAKAR', $filter)->or_like('NIK', $filter)->where_in('karyawan.STATUS',array('T','K','C'))->get('karyawan')->row()->total;
		}elseif($filter == '' && $filter_sisa_masa_kerja != '' && $status == ''){
			$query  = $this->db->where("(STATUS='K' OR STATUS='C')")
				->where("IFNULL(LAMAKONTRAK,0) - IFNULL(PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'),DATE_FORMAT(TGLKONTRAK,'%Y%m')),0)<".$filter_sisa_masa_kerja."")
				->where("LAMAKONTRAK IS NOT NULL AND LAMAKONTRAK != '' AND TGLKONTRAK IS NOT NULL AND TGLKONTRAK != ''")
				->from('karyawan')->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where_in('karyawan.STATUS',array('T','K','C'))
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
			$query_total = $this->db->select('COUNT(*) AS total')->where("IFNULL(LAMAKONTRAK,0) - IFNULL(PERIOD_DIFF(DATE_FORMAT(NOW(),'%Y%m'),DATE_FORMAT(TGLKONTRAK,'%Y%m')),0)<".$filter_sisa_masa_kerja." AND (STATUS='K' OR STATUS='C')")
				->where_in('karyawan.STATUS',array('T','K','C'))->get('karyawan')->row()->total;
		}elseif ($status != '' && $masakerja == '' && $sisamasakerja == '' && $pertanggal == '') {
			$query  = $this->db->from('karyawan')
				->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where('karyawan.STATUS',$status)
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
			$query_total = $this->db->select('COUNT(*) AS total')->where_in('karyawan.STATUS',array($status))->get('karyawan')->row()->total;
		}elseif ($status == 'T' && $masakerja != '' && $sisamasakerja == '' && $pertanggal == '') {
			$masakerja = (int) $masakerja;
			if ($masakerja == 13) {
				$query  = $this->db->from('karyawan')
					->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
					->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
					->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
					->join('grade','grade.GRADE = karyawan.GRADE','left')
					->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
					->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
					->where('karyawan.STATUS',$status)
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) >', 365)
					/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
				$query_total = $this->db->select('COUNT(*) AS total')
					->where_in('karyawan.STATUS',array($status))
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) >', 365)
					->get('karyawan')->row()->total;
			} elseif ($masakerja == 12) {
				$query  = $this->db->from('karyawan')
					->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
					->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
					->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
					->join('grade','grade.GRADE = karyawan.GRADE','left')
					->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
					->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
					->where('karyawan.STATUS',$status)
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) <', 365)
					/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
				$query_total = $this->db->select('COUNT(*) AS total')
					->where_in('karyawan.STATUS',array($status))
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) <', 365)
					->get('karyawan')->row()->total;
			} else {
				$masakerja = $masakerja * 30;
				$query  = $this->db->from('karyawan')
					->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
					->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
					->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
					->join('grade','grade.GRADE = karyawan.GRADE','left')
					->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
					->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
					->where('karyawan.STATUS',$status)
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) <', $masakerja)
					/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
				$query_total = $this->db->select('COUNT(*) AS total')
					->where_in('karyawan.STATUS',array($status))
					->where('TO_DAYS(CURRENT_DATE()) - TO_DAYS(karyawan.TGLMASUK) <', $masakerja)
					->get('karyawan')->row()->total;
			}
		}elseif ($status == 'K' && $masakerja == '' && $sisamasakerja != '' && $pertanggal == '') {
			$sisamasakerja = (int) $sisamasakerja;
			if ($sisamasakerja == 12) {
				$query  = $this->db->from('karyawan')
					->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
					->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
					->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
					->join('grade','grade.GRADE = karyawan.GRADE','left')
					->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
					->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
					->where('karyawan.STATUS',$status)
					->where('TO_DAYS(DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH)) - TO_DAYS(CURRENT_DATE()) <', 365)
					/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
				$query_total = $this->db->select('COUNT(*) AS total')
					->where_in('karyawan.STATUS',array($status))
					->where('TO_DAYS(DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH)) - TO_DAYS(CURRENT_DATE()) <', 365)
					->get('karyawan')->row()->total;
			} else {
				$sisamasakerja = $sisamasakerja * 30;
				$query  = $this->db->from('karyawan')
					->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
					->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
					->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
					->join('grade','grade.GRADE = karyawan.GRADE','left')
					->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
					->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
					->where('karyawan.STATUS',$status)
					->where('TO_DAYS(DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH)) - TO_DAYS(CURRENT_DATE()) <', $sisamasakerja)
					/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
				$query_total = $this->db->select('COUNT(*) AS total')
					->where_in('karyawan.STATUS',array($status))
					->where('TO_DAYS(DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH)) - TO_DAYS(CURRENT_DATE()) <', $sisamasakerja)
					->get('karyawan')->row()->total;
			}
		}elseif ($status == 'K' && $masakerja == '' && $sisamasakerja == '' && $pertanggal != '') {
			$query  = $this->db->from('karyawan')
				->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where('karyawan.STATUS',$status)
				->where('DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH) =', date('Y-m-d', strtotime($pertanggal)))
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC')->get()->result();
			$query_total = $this->db->select('COUNT(*) AS total')
				->where_in('karyawan.STATUS',array($status))
				->where('DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH) =', date('Y-m-d', strtotime($pertanggal)))
				->get('karyawan')->row()->total;
			$sql = $this->db->from('karyawan')
				->join('nametag','nametag.KODEJAB = karyawan.KODEJAB','left')
				->join('unitkerja','unitkerja.KODEUNIT = karyawan.KODEUNIT','left')
				->join('kelompok','kelompok.KODEKEL = karyawan.KODEKEL','left')
				->join('grade','grade.GRADE = karyawan.GRADE','left')
				->join('jabatan','jabatan.IDJAB = karyawan.IDJAB','left')
				->join('leveljabatan','leveljabatan.KODEJAB = karyawan.KODEJAB','left')
				->where('karyawan.STATUS',$status)
				->where('DATE_ADD(TGLKONTRAK,INTERVAL LAMAKONTRAK MONTH) =', date('Y-m-d', strtotime($pertanggal)))
				/*->limit($limit, $start)*/->order_by('NIK', 'ASC');
		}
		
		//$total  = $this->db->get('karyawan')->num_rows();
		$total = $query_total;
		
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
		
		$pkey = array('NIK'=>$data->NIK);
		
		$arrdatau = array(
			'IDJAB'=>$data->IDJAB,
			'KODEJAB'=>$data->KODEJAB,
			'GRADE'=>$data->GRADE,
			'KODEUNIT'=>$data->KODEUNIT,
			'KODEKEL'=>$data->KODEKEL,
			'NAMAKAR'=>$data->NAMAKAR,
			'NAMASINGKAT'=>$data->NAMASINGKAT,
			'TGLMASUK'=>(strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d', strtotime($data->TGLMASUK)) : NULL),
			'JENISKEL'=>$data->JENISKEL,
			'ALAMAT'=>$data->ALAMAT,
			'DESA'=>$data->DESA,
			'RT'=>$data->RT,
			'RW'=>$data->RW,
			'KECAMATAN'=>$data->KECAMATAN,
			'KOTA'=>$data->KOTA,
			'TELEPON'=>$data->TELEPON,
			'TMPLAHIR'=>$data->TMPLAHIR,
			'TGLLAHIR'=>(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL),
			'ANAKKE'=>($data->ANAKKE > 0 ? $data->ANAKKE : NULL),
			'JMLSAUDARA'=>($data->JMLSAUDARA > 0 ? $data->JMLSAUDARA : NULL),
			'PENDIDIKAN'=>$data->PENDIDIKAN,
			'JURUSAN'=>$data->JURUSAN,
			'NAMASEKOLAH'=>$data->NAMASEKOLAH,
			'AGAMA'=>$data->AGAMA,
			'NAMAAYAH'=>$data->NAMAAYAH,
			'STATUSAYAH'=>$data->STATUSAYAH,
			'ALAMATAYAH'=>$data->ALAMATAYAH,
			'PENDDKAYAH'=>$data->PENDDKAYAH,
			'PEKERJAYAH'=>$data->PEKERJAYAH,
			'NAMAIBU'=>$data->NAMAIBU,
			'STATUSIBU'=>$data->STATUSIBU,
			'ALAMATIBU'=>$data->ALAMATIBU,
			'PENDDKIBU'=>$data->PENDDKIBU,
			'PEKERJIBU'=>$data->PEKERJIBU,
			'KAWIN'=>$data->KAWIN,
			'TGLKAWIN'=>(strlen(trim($data->TGLKAWIN)) > 0 ? date('Y-m-d', strtotime($data->TGLKAWIN)) : NULL),
			'NAMAPASANGAN'=>$data->NAMAPASANGAN,
			'ALAMATPAS'=>$data->ALAMATPAS,
			'TMPLAHIRPAS'=>$data->TMPLAHIRPAS,
			'TGLLAHIRPAS'=>(strlen(trim($data->TGLLAHIRPAS)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIRPAS)) : NULL),
			'AGAMAPAS'=>$data->AGAMAPAS,
			'PEKERJPAS'=>$data->PEKERJPAS,
			'KATPEKERJAAN'=>$data->KATPEKERJAAN,
			'BHSJEPANG'=>$data->BHSJEPANG,
			'JAMSOSTEK'=>($data->JAMSOSTEK == 'on' ? 'Y' : 'T'),
			'TGLJAMSOSTEK'=>(strlen(trim($data->TGLJAMSOSTEK)) > 0 ? date('Y-m-d', strtotime($data->TGLJAMSOSTEK)) : NULL),
			'STATUS'=>$data->STATUS,
			'TGLSTATUS'=>(strlen(trim($data->TGLSTATUS)) > 0 ? date('Y-m-d', strtotime($data->TGLSTATUS)) : NULL),
			'TGLMUTASI'=>(strlen(trim($data->TGLMUTASI)) > 0 ? date('Y-m-d', strtotime($data->TGLMUTASI)) : NULL),
			'NOURUTKTRK'=>($data->NOURUTKTRK > 0 ? $data->NOURUTKTRK : NULL),
			'TGLKONTRAK'=>(strlen(trim($data->TGLKONTRAK)) > 0 ? date('Y-m-d', strtotime($data->TGLKONTRAK)) : NULL),
			'LAMAKONTRAK'=>($data->LAMAKONTRAK > 0 ? $data->LAMAKONTRAK : NULL),
			'NOACCKAR'=>$data->NOACCKAR,
			'NAMABANK'=>$data->NAMABANK,
			'USERNAME'=>$data->USERNAME,
			'STATTUNKEL'=>$data->STATTUNKEL,
			'ZONA'=>$data->ZONA,
			'STATTUNTRAN'=>($data->STATTUNTRAN == 'on' ? 'Y' : 'T'),
			'NPWP'=>$data->NPWP,
			'KODESP'=>$data->KODESP
		);
		if(strlen(trim($data->FOTO_EXT)) > 0){
			$arrdatau['FOTO'] = $data->NIK.'.'.$data->FOTO_EXT;
		}
		
		$arrdatac = $arrdatau;
		$arrdatac['NIK'] = $data->NIK;
		
		if($this->db->get_where('karyawan', $pkey)->num_rows() > 0){
			/* Old Data */
			$oldrecord = $this->db->get_where('karyawan', $pkey)->row();
			$status_oldvalue = $oldrecord->STATUS;
			$tglstatus_oldvalue = $oldrecord->TGLSTATUS;
			$tglkontrak_oldvalue = $oldrecord->TGLKONTRAK;
			$kawin_oldvalue = $oldrecord->KAWIN;
			$tglkawin_oldvalue = $oldrecord->TGLKAWIN;
			$tglmasuk_oldvalue = $oldrecord->TGLMASUK;
			$tgllahir_oldvalue = $oldrecord->TGLLAHIR;
			$agama_oldvalue = $oldrecord->AGAMA;
			$bhsjepang_oldvalue = $oldrecord->BHSJEPANG;
			$jamsostek_oldvalue = $oldrecord->JAMSOSTEK;
			$tgljamsostek_oldvalue = $oldrecord->TGLJAMSOSTEK;
			$kodeunit_oldvalue = $oldrecord->KODEUNIT;
			$kodejab_oldvalue = $oldrecord->KODEJAB;
			$grade_oldvalue = $oldrecord->GRADE;
			$stattunkel_oldvalue = $oldrecord->STATTUNKEL;
			//$this->firephp->log($oldrecord);
			
			/*
			 * Data Exist
			 */			 
			
			move_uploaded_file($data->FOTO_TMP,"./photos/".$data->NIK.'.'.$data->FOTO_EXT);
			
			$this->db->where($pkey)->update('karyawan', $arrdatau);
			
			if($status_oldvalue!=$data->STATUS || $tglstatus_oldvalue!=(strlen(trim($data->TGLSTATUS)) > 0 ? date('Y-m-d', strtotime($data->TGLSTATUS)) : NULL)
			   || $tglkontrak_oldvalue!=(strlen(trim($data->TGLKONTRAK)) > 0 ? date('Y-m-d', strtotime($data->TGLKONTRAK)) : NULL)
			   || $kawin_oldvalue!=$data->KAWIN || $tglkawin_oldvalue!=(strlen(trim($data->TGLKAWIN)) > 0 ? date('Y-m-d', strtotime($data->TGLKAWIN)) : NULL)
			   || $tglmasuk_oldvalue!=(strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d', strtotime($data->TGLMASUK)) : NULL)
			   || $tgllahir_oldvalue!=(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL)
			   || $agama_oldvalue!=$data->AGAMA || $bhsjepang_oldvalue!=$data->BHSJEPANG || $jamsostek_oldvalue!=$data->JAMSOSTEK
			   || $tgljamsostek_oldvalue!=(strlen(trim($data->TGLJAMSOSTEK)) > 0 ? date('Y-m-d', strtotime($data->TGLJAMSOSTEK)) : NULL)
			   || $kodeunit_oldvalue!=$data->KODEUNIT || $kodejab_oldvalue!=$data->KODEJAB
			   || $grade_oldvalue!=$data->GRADE || $stattunkel_oldvalue!=($data->STATTUNTRAN == 'on' ? 'Y' : 'T')){
				/* proses mutasi ke db.karyawanmut*/
				if($this->db->get_where('karyawanmut', array('NIK'=>$data->NIK, 'VALIDTO'=>date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'))))->num_rows() == 0){
					$oldrecord->VALIDTO = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'));
					unset($oldrecord->KODEUNIT);
					$this->db->insert('karyawanmut', $oldrecord);
				}
				
			}
			
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			move_uploaded_file($data->FOTO_TMP,"./photos/".$data->NIK.'.'.$data->FOTO_EXT);
			
			$this->db->insert('karyawan', $arrdatac);
			$last   = $this->db->where($pkey)->get('karyawan')->row();
			
		}
		
		$total  = $this->db->get('karyawan')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('karyawan');
		
		$total  = $this->db->get('karyawan')->num_rows();
		$last = $this->db->get('karyawan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						'total'     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: nonaktifKaryawan
	 * 
	 * Untuk menonaktifkan dat karyawan
	 * 
	 * @param array $data
	 * @return json
	 */
	function nonaktifKaryawan($data,$status){
		foreach ($data as $row) {
			$pkey = array('NIK'=>$row->NIK);
			$oldrecord = $this->db->get_where('karyawan', $pkey)->row();

			$this->db->where($pkey)->update('karyawan', array('STATUS'=>$status,'TGLSTATUS'=>date('Y-m-d')));

			/* proses mutasi ke db.karyawanmut*/
			if($this->db->get_where('karyawanmut', array('NIK'=>$row->NIK, 'VALIDTO'=>date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'))))->num_rows() == 0){
				$oldrecord->VALIDTO = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'));
				$this->db->insert('karyawanmut', $oldrecord);
			}
			unset($oldrecord);
		}
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data Karyawan berhasil dinonaktifkan'
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
			// $p = 0;
			$skeepdata = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				$worksheetTitle     = $worksheet->getTitle();
				// if($p>0){
				// 	break;
				// }

				$worksheetTitle     = $worksheet->getTitle();
				if ($worksheetTitle == 'KARYAWANAKTIF') {
					$this->importKaryawanAktif($worksheet);
				}
				
				// $p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> 0// $skeepdata
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

	function importKaryawanAktif($worksheet){
		$highestRow         = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$skeepdata = 0;
		for ($row = 6; $row <= $highestRow; ++ $row) {
			for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				$nik = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
				$namakar = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
				$div = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
				$dept = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
				$sec = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
				$subsec = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
				$group = (trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
				//get kodeunit
				$kodeunit = $div.$dept.$sec.$subsec.$group;
				//get kodejab
				$jabatan = (trim($worksheet->getCellByColumnAndRow(11, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
				$query_jabatan = $this->db->query("SELECT KODEJAB, GRADE FROM leveljabatan WHERE LOWER(NAMALEVEL) = '".addslashes(strtolower($jabatan))."'");
				if ($query_jabatan->num_rows() > 0) {
					$row_jabatan = $query_jabatan->row();
					
					$kodejab = $row_jabatan->KODEJAB;
					$grade = $row_jabatan->GRADE;

					$idjab = $kodejab.$kodeunit;
				} else {
					$kodejab = NULL;
					$grade = NULL;
					$idjab = NULL;
				}
				$tglmasuk = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(9, $row)->getValue(), 'yyyy-mm-dd');
				$jeniskel = (trim($worksheet->getCellByColumnAndRow(13, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
				$alamat = (trim($worksheet->getCellByColumnAndRow(21, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(21, $row)->getValue()));
				$desa = (trim($worksheet->getCellByColumnAndRow(23, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(23, $row)->getValue()));

				$rt = NULL;
				$rw = NULL;
				$arr_rtrw = array("","");
				$rtrw = (trim($worksheet->getCellByColumnAndRow(22, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(22, $row)->getValue()));
				if (substr_count($rtrw, '/')) {
					$arr_rtrw = explode('/', $rtrw);
					$rt = $arr_rtrw[0];
					$rw = $arr_rtrw[1];
				}

				$kecamatan = (trim($worksheet->getCellByColumnAndRow(24, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(24, $row)->getValue()));
				$kota = (trim($worksheet->getCellByColumnAndRow(25, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(25, $row)->getValue()));
				$telepon = (trim($worksheet->getCellByColumnAndRow(28, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(28, $row)->getValue()));
				$tmplahir = (trim($worksheet->getCellByColumnAndRow(19, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(19, $row)->getValue()));
				$tgllahir = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(18, $row)->getValue(), 'yyyy-mm-dd');
				$pendidikan = (trim($worksheet->getCellByColumnAndRow(29, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(29, $row)->getValue()));
				$jurusan = (trim($worksheet->getCellByColumnAndRow(31, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(31, $row)->getValue()));
				$namasekolah = (trim($worksheet->getCellByColumnAndRow(30, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(30, $row)->getValue()));
				$agama_temp = (trim($worksheet->getCellByColumnAndRow(32, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(32, $row)->getValue()));
				$agama = substr($agama_temp, 0, 1);
				$status_temp = (trim($worksheet->getCellByColumnAndRow(10, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
				$status = substr($status_temp, 0, 1);
			}

			if (!is_null($nik)) {
				$data = array(
					'NIK'         => $nik,
					'IDJAB'       => $idjab,
					'KODEUNIT'    => $kodeunit,
					'KODEJAB'     => $kodejab,
					'GRADE'       => $grade,
					'NAMAKAR'     => $namakar,
					'TGLMASUK'    => $tglmasuk,
					'JENISKEL'    => $jeniskel,
					'ALAMAT'      => $alamat,
					'DESA'        => $desa,
					'RT'          => $rt,
					'RW'          => $rw,
					'KECAMATAN'   => $kecamatan,
					'KOTA'        => $kota,
					'TELEPON'     => $telepon,
					'TMPLAHIR'    => $tmplahir,
					'TGLLAHIR'    => $tgllahir,
					'PENDIDIKAN'  => $pendidikan,
					'JURUSAN'     => $jurusan,
					'NAMASEKOLAH' => $namasekolah,
					'AGAMA'       => $agama,
					'STATUS'      => $status
				);
				if($this->db->get_where('karyawan', array('NIK'=>$nik))->num_rows() == 0){
					$this->db->insert('karyawan', $data);
				}else{
					$skeepdata++;
				}
			}
		}
	}
}
?>