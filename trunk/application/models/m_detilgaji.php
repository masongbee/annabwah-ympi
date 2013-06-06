<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_detilgaji
 * 
 * Table	: detilgaji
 *  
 * @author masongbee
 *
 */
class M_detilgaji extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$username = $this->session->userdata('user_name');
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
		$query  = $this->db->limit($limit, $start)->order_by('NOREVISI', 'ASC')->get('detilgaji')->result();
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		if($this->db->get_where('detilgaji', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->where($pkey)->update('detilgaji', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI,'RPUPAHPOKOK'=>$data->RPUPAHPOKOK,'RPTANAK'=>$data->RPTANAK,'RPTBHS'=>$data->RPTBHS,'RPTHR'=>$data->RPTHR,'RPTISTRI'=>$data->RPTISTRI,'RPTJABATAN'=>$data->RPTJABATAN,'RPTPEKERJAAN'=>$data->RPTPEKERJAAN,'RPTSHIFT'=>$data->RPTSHIFT,'RPTTRANSPORT'=>$data->RPTTRANSPORT,'RPBONUS'=>$data->RPBONUS,'RPIDISIPLIN'=>$data->RPIDISIPLIN,'RPTLEMBUR'=>$data->RPTLEMBUR,'RPTKACAMATA'=>$data->RPTKACAMATA,'RPTSIMPATI'=>$data->RPTSIMPATI,'RPTMAKAN'=>$data->RPTMAKAN,'RPPSKORSING'=>$data->RPPSKORSING,'RPPSAKITCUTI'=>$data->RPPSAKITCUTI,'RPPJAMSOSTEK'=>$data->RPPJAMSOSTEK,'RPPOTONGAN'=>$data->RPPOTONGAN,'RPTAMBAHAN'=>$data->RPTAMBAHAN);
			 
			$this->db->insert('detilgaji', $arrdatac);
			$last   = $this->db->where($pkey)->get('detilgaji')->row();
			
		}
		
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NIK'=>$data->NIK,'NOREVISI'=>$data->NOREVISI);
		
		$this->db->where($pkey)->delete('detilgaji');
		
		$total  = $this->db->get('detilgaji')->num_rows();
		$last = $this->db->get('detilgaji')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function gen_gajibulanan($bulan){
		$sql = "INSERT INTO gajibulanan (NIK, BULAN, NOACCKAR, NAMABANK, USERNAME)
			SELECT NIK, ".$bulan.", NOACCKAR, NAMABANK, ".$username." from karyawan
			where STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function gen_detilgaji($bulan){
		$sql = "INSERT INTO detilgaji (NIK, BULAN, REVISI)
			SELECT NIK, ".$bulan.", 1 from KARYAWAN
			where STATUS='T' or STATUS='K' or STATUS='C'";
		$this->db->query($sql);
	}
	
	function update_detilgaji_bygrade($bulan, $grade, $upahpokok){
		$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
				AND karyawan.GRADE = '".$grade."' AND detilgaji.BULAN = '".$bulan."')
			SET detilgaji.RPUPAHPOKOK = ".$upahpokok;
		$this->db->query($sql);
	}
	
	function update_detilgaji_bykodejab($bulan, $kodejab, $upahpokok){
		$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
				AND karyawan.KODEJAB = '".$kodejab."' AND detilgaji.BULAN = '".$bulan."')
			SET detilgaji.RPUPAHPOKOK = ".$upahpokok;
		$this->db->query($sql);
	}
	
	function update_detilgaji_bygradekodejab($bulan, $grade, $kodejab, $upahpokok){
		$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
				AND karyawan.GRADE = '".$grade."' AND karyawan.KODEJAB = '".$kodejab."' AND detilgaji.BULAN = '".$bulan."')
			SET detilgaji.RPUPAHPOKOK = ".$upahpokok;
		$this->db->query($sql);
	}
	
	function update_detilgaji_bynik($bulan, $nik, $upahpokok){
		$sql = "UPDATE detilgaji JOIN karyawan ON(karyawan.NIK = detilgaji.NIK
				AND karyawan.NIK = '".$nik."' AND detilgaji.BULAN = '".$bulan."')
			SET detilgaji.RPUPAHPOKOK = ".$upahpokok;
		$this->db->query($sql);
	}
	
	function hitunggaji_all($bulan){
		/*
		 * Langkah memproses Perhitungan Gaji untuk seluruh Karyawan
		 * 1. Persiapkan data Karyawan dalam db.gajibulanan dan db.detilgaji
		 * 1.a. cek db.gajibulanan.BULAN => apakah bulan gaji yang akan dihitung sudah ada, jika belum maka insert seluruh db.karyawan dengan status = 'T' or 'K' or'C'
		 * 1.b. cek db.detilgaji.BULAN => apakah bulan gaji yang akan dihitung sudah ada, jika belum maka insert seluruh db.karyawan dengan status = 'T' or 'K' or'C'
		 * 2. Hitung Upah Pokok
		 * 2.a. dapatkan satu tanggal paling awal ketemu di db.upahpokok.VALIDFROM (seharusnya VALIDTO) yang sama dengan TANGGAL SEKARANG atau lebih besar daripada TANGGAL SEKARANG
		 * >> tanggal yang sama dengan hasil 2.a. itu kemungkinan besar memiliki lebih dari satu record
		 * >> urutkan record hasil 2.a. berdasarkan db.upahpokok.NOURUT
		 * 2.b. looping hasil 2.a. untuk menghitung gaji karyawan dengan meng-UPDATE db.detilgaji
		 * >> urutan pemberian upah pokok: 1.GRADE, 2.KODEJAB, 3.GRADE+KODEJAB, 4.NIK
		 * 
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
				SELECT VALIDFROM FROM upahpokok WHERE VALIDFROM >= DATE_FORMAT(NOW(),'%Y-%m-%d') LIMIT 1
			)
			ORDER BY NOURUT";
		$records_upahpokok = $this->db->query($sql_upahpokok)->result();
		
		/* 2.b. */
		if(sizeof($records_upahpokok) > 0){
			/* proses looping upah pokok */
			foreach($records_upahpokok as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)==0)){
					/* urutan upah pokok ke-1 berdasarkan GRADE */
					$this->update_detilgaji_bygrade($bulan, $record->GRADE, $record->RPUPAHPOKOK);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					/* urutan upah pokok ke-2 berdasarkan KODEJAB */
					$this->update_detilgaji_bykodejab($bulan, $record->KODEJAB, $record->RPUPAHPOKOK);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)
				   && (strlen($record->NIK)==0)){
					/* urutan upah pokok ke-3 berdasarkan GRADE+KODEJAB */
					$this->update_detilgaji_bygradekodejab($bulan, $record->GRADE, $record->KODEJAB, $record->RPUPAHPOKOK);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)==0)
				   && (strlen($record->NIK)!=0)){
					/* urutan upah pokok ke-3 berdasarkan NIK */
					$this->update_detilgaji_bynik($bulan, $record->NIK, $record->RPUPAHPOKOK);
					
				}
			}
		}
		
		/* HASIL HITUNG GAJI => */
		$query  = $this->db->limit($limit, $start)->order_by('NOREVISI', 'ASC')->get('detilgaji')->result();
		$total  = $this->db->get('detilgaji')->num_rows();
		
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
}
?>