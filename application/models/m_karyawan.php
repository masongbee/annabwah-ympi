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
	function getAll($start, $page, $limit, $filter){
		if($filter == ''){
			$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('vu_karyawan')->result();
		}else{
			$query  = $this->db->like('NAMAKAR', $filter)->or_like('NIK', $filter)->limit($limit, $start)->order_by('NIK', 'ASC')->get('vu_karyawan')->result();
		}
		
		$total  = $this->db->get('karyawan')->num_rows();
		
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
			'KODEUNIT'=>$data->KODEUNIT,
			'KODEJAB'=>$data->KODEJAB,
			'GRADE'=>$data->GRADE,
			'NAMAKAR'=>$data->NAMAKAR,
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
			'FOTO'=>$data->FOTO,
			'USERNAME'=>$data->USERNAME,
			'STATTUNKEL'=>$data->STATTUNKEL,
			'ZONA'=>$data->ZONA,
			'STATTUNTRAN'=>($data->STATTUNTRAN == 'on' ? 'Y' : 'T')
		);
		
		$arrdatac = $arrdatau;
		$arrdatac['NIK'] = $data->NIK;
		
		if($this->db->get_where('karyawan', $pkey)->num_rows() > 0){
			$this->firephp->log('update data');
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
			$this->firephp->log($oldrecord);
			/*
			 * Data Exist
			 */			 
			
			move_uploaded_file($data->FOTO_TMP,"./photos/".$data->FOTO);
			
			$this->db->where($pkey)->update('karyawan', $arrdatau);
			/*$this->firephp->log($arrdatau);
			
			$this->firephp->log($tglmasuk_oldvalue);
			$this->firephp->log((strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d', strtotime($data->TGLMASUK)) : NULL));
			if($tglmasuk_oldvalue!=(strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d', strtotime($data->TGLMASUK)) : NULL)){
				$this->firephp->log('beda');
			}*/
			
			/* Setelah update db.karyawan => dicek apakah ada perubahan pada field berikut, jika terjadi perubahan maka akan terjadi MUTASI ke db.karyawanmut */
			//$this->firephp->log($kawin_oldvalue);
			//$this->firephp->log($data->KAWIN);
			/*if($kawin_oldvalue<>$data->KAWIN){
				$this->firephp->log('kawin tidak sama');
			}*/
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
				$this->firephp->log('proses mutasi');
				if($this->db->get_where('karyawanmut', array('NIK'=>$data->NIK, 'VALIDTO'=>date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'))))->num_rows() == 0){
					$oldrecord->VALIDTO = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 day'));
					unset($oldrecord->KODEUNIT);
					$this->firephp->log($oldrecord);
					$this->db->insert('karyawanmut', $oldrecord);
				}
				
			}
			
			$last   = $data;
			
		}else{
			$this->firephp->log('insert data');
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
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
}
?>