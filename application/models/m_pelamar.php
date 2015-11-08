<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_pelamar
 * 
 * Table	: pelamar
 *  
 * @author masongbee
 *
 */
class M_pelamar extends CI_Model{

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
	function getAll($start, $page, $limit){
		$query = "SELECT pelamar.KTP
				,NAMAPELAMAR
				,AGAMA
				,ALAMAT
				,FOTO
				,JENISKEL
				,JURUSAN
				,KAWIN
				,KOTA
				,NAMASEKOLAH
				,PENDIDIKAN
				,TELEPON
				,TGLLAHIR
				,TMPLAHIR
				,STATUSPELAMAR
				,lamaran.GELLOW
				,lamaran.IDJAB
				,unitkerja.NAMAUNIT
				,lamaran.KODEJAB
				,grade.KETERANGAN AS NAMAGRADE
			FROM pelamar
			JOIN lamaran ON(lamaran.KTP = pelamar.KTP)
			JOIN jabatan ON(jabatan.IDJAB = lamaran.IDJAB)
			JOIN leveljabatan ON(leveljabatan.KODEJAB = lamaran.KODEJAB)
			LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = jabatan.KODEUNIT)
			LEFT JOIN grade ON(grade.GRADE = leveljabatan.GRADE)
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('pelamar')->num_rows();
		
		$data   = array();
		foreach($result as $row){
			$data[] = $row;
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
		$message = "Data tidak dapat disimpan";
		
		$pkey = array('KTP'=>$data->KTP);
		
		if($this->db->get_where('pelamar', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'NAMAPELAMAR'   =>$data->NAMAPELAMAR
				,'AGAMA'         =>$data->AGAMA
				,'ALAMAT'        =>$data->ALAMAT
				// ,'FOTO'          =>$data->FOTO
				,'JENISKEL'      =>$data->JENISKEL
				,'JURUSAN'       =>$data->JURUSAN
				,'KAWIN'         =>$data->KAWIN
				,'KOTA'          =>$data->KOTA
				,'NAMASEKOLAH'   =>$data->NAMASEKOLAH
				,'PENDIDIKAN'    =>$data->PENDIDIKAN
				,'TELEPON'       =>$data->TELEPON
				,'TGLLAHIR'      =>(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL)
				,'TMPLAHIR'      =>$data->TMPLAHIR
				,'STATUSPELAMAR' =>$data->STATUSPELAMAR
			);
			
			$this->db->where($pkey)->update('pelamar', $arrdatau);
			$last   = $data;
			$message = "Data berhasil di simpan";
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'KTP'            =>$data->KTP
				,'NAMAPELAMAR'   =>$data->NAMAPELAMAR
				,'AGAMA'         =>$data->AGAMA
				,'ALAMAT'        =>$data->ALAMAT
				// ,'FOTO'          =>$data->FOTO
				,'JENISKEL'      =>$data->JENISKEL
				,'JURUSAN'       =>$data->JURUSAN
				,'KAWIN'         =>$data->KAWIN
				,'KOTA'          =>$data->KOTA
				,'NAMASEKOLAH'   =>$data->NAMASEKOLAH
				,'PENDIDIKAN'    =>$data->PENDIDIKAN
				,'TELEPON'       =>$data->TELEPON
				,'TGLLAHIR'      =>(strlen(trim($data->TGLLAHIR)) > 0 ? date('Y-m-d', strtotime($data->TGLLAHIR)) : NULL)
				,'TMPLAHIR'      =>$data->TMPLAHIR
				,'STATUSPELAMAR' =>$data->STATUSPELAMAR
			);
			
			$this->db->insert('pelamar', $arrdatac);
			$last   = $this->db->where($pkey)->get('pelamar')->row();
			$message = "Data berhasil ditambahkan";
			
		}

		/*Langsung ditambahkan ke table db.lamaran*/
		$this->db->where($pkey)->delete('lamaran');
		$arrdatac_lamaran = array(
			'KTP'      =>$data->KTP
			,'GELLOW'  =>$data->GELLOW
			,'KODEJAB' =>$data->KODEJAB
			,'IDJAB'   =>$data->IDJAB
		);
		$this->db->insert('lamaran', $arrdatac_lamaran);

		/*Langsung ditambahkan ke table db.tahapseleksi ==> untuk diproses ke tahap2 selanjutnya*/
		$this->db->where($pkey)->delete('tahapseleksi');
		$arrdatac_tahapseleksi = array(
			'KTP'          =>$data->KTP
			,'GELLOW'      =>$data->GELLOW
			,'KODEJAB'     =>$data->KODEJAB
			,'IDJAB'       =>$data->IDJAB
			,'NOURUT'      =>1
			,'KODESELEKSI' =>'A'
			,'LULUS'       =>'P'
		);
		$this->db->insert('tahapseleksi', $arrdatac_tahapseleksi);
		
		$total  = $this->db->get('pelamar')->num_rows();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => $message,
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
		$pkey = array('KTP'=>$data->KTP);
		
		$this->db->where($pkey)->delete('tahapseleksi');
		$this->db->where($pkey)->delete('lamaran');
		$this->db->where($pkey)->delete('pelamar');
		
		$total  = $this->db->get('pelamar')->num_rows();
		$last = $this->db->get('pelamar')->result();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil dihapus',
			"total"     => $total,
			"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: mutasiPelamar
	 * 
	 * Untuk memutasi pelamar yang sudah lulus seleksi akhir
	 * 
	 * @param array $data
	 * @return json
	 */
	function mutasiPelamar($data,$status,$tglmasuk,$tglkontrak,$lamakontrak){
		$array_abjad = array(1=>"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

		$nowyear = date('Y');
		$nowyear_int = (int) $nowyear;
		$nowmonth = date('n');
		$interval = $nowyear_int - 1996;
		$kode_abjad = $array_abjad[$interval];
		// $kode_nik = 'S15';
		$kode_nik = $kode_abjad.$nowyear.$nowmonth;

		foreach ($data as $row) {
			$maxno = $this->m_public_function->gen_nik($kode_nik);
			$next_nik = $kode_nik.$maxno;

			$arrdatac = array(
				'NIK'         =>$next_nik,
				'IDJAB'       =>$row->IDJAB,
				'KODEJAB'     =>$row->KODEJAB,
				'GRADE'       =>$this->db->query("SELECT GRADE FROM leveljabatan WHERE KODEJAB = '".$row->KODEJAB."'")->row()->GRADE,
				'KODEUNIT'    =>$this->db->query("SELECT KODEUNIT FROM jabatan WHERE IDJAB = '".$row->IDJAB."'")->row()->KODEUNIT,
				'NAMAKAR'     =>$row->NAMAPELAMAR,
				'TGLMASUK'    =>date('Y-m-d',strtotime($tglmasuk)),
				'JENISKEL'    =>$row->JENISKEL,
				'ALAMAT'      =>$row->ALAMAT,
				'KOTA'        =>$row->KOTA,
				'TELEPON'     =>$row->TELEPON,
				'TMPLAHIR'    =>$row->TMPLAHIR,
				'TGLLAHIR'    =>$row->TGLLAHIR,
				'PENDIDIKAN'  =>$row->PENDIDIKAN,
				'JURUSAN'     =>$row->JURUSAN,
				'NAMASEKOLAH' =>$row->NAMASEKOLAH,
				'AGAMA'       =>$row->AGAMA,
				'KAWIN'       =>$row->KAWIN,
				'STATUS'      =>$status,
				'TGLSTATUS'   =>date('Y-m-d',strtotime($tglmasuk)),
				'TGLKONTRAK'  =>date('Y-m-d',strtotime($tglkontrak)),
				'LAMAKONTRAK' =>$lamakontrak
			);

			$this->db->insert('karyawan', $arrdatac);

			/*update statuspelamar di db.pelamar menjadi = 'G'*/
			$this->db->where(array('KTP'=>$row->KTP))->update('pelamar', array('STATUSPELAMAR'=>'G'));
		}
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil disimpan'
		);				
		return $json;
	}
}
?>