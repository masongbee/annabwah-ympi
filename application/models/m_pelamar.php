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

		$this->db->where($pkey)->delete('lamaran');
		$arrdatac_lamaran = array(
			'KTP'      =>$data->KTP
			,'GELLOW'  =>$data->GELLOW
			,'KODEJAB' =>$data->KODEJAB
			,'IDJAB'   =>$data->IDJAB
		);
		$this->db->insert('lamaran', $arrdatac_lamaran);
		
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
}
?>