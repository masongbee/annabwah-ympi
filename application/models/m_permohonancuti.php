<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_permohonancuti
 * 
 * Table	: permohonancuti
 *  
 * @author masongbee
 *
 */
class M_permohonancuti extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function setStatusCuti($data){
		$this->db->where(array('NOCUTI'=>$data->NOCUTI,'STATUSCUTI !='=>$data->NOCUTI))->update('rinciancuti',array('STATUSCUTI'=>$data->STATUSCUTI));
	}
	
	function getSisa($item){
		if($item['JENIS'] == "SISACUTI")
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
		
		return $json;
	}
	
	function get_personalia() {
		$sql = "SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
			FROM s_usergroups gp
			INNER JOIN s_users us ON LOCATE(gp.GROUP_ID,us.USER_GROUP)>0
			INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
			WHERE LOWER(GROUP_NAME) = LOWER('AdmAbsensi')";
		$sql_total = "SELECT COUNT(*) AS total
			FROM s_usergroups gp
			INNER JOIN s_users us ON LOCATE(gp.GROUP_ID,us.USER_GROUP)>0
			INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
			WHERE LOWER(GROUP_NAME) = LOWER('AdmAbsensi')";
		$query  = $this->db->query($sql)->result();
		$total  = $this->db->query($sql_total)->row()->total;
		
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
		$where = "KELABSEN='C' OR KELABSEN='T'";
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
	 * Untuk mengambil all-data
	 * 
	 * @param number $start
	 * @param number $page
	 * @param number $limit
	 * @return json
	 */
	function getAll($nik, $start, $page, $limit){
		//$query  = $this->db->limit($limit, $start)->order_by('NOCUTI', 'ASC')->get('permohonancuti')->result();
		//$total  = $this->db->get('permohonancuti')->num_rows();
		
		/*$sql = "SELECT pc.NOCUTI,pc.KODEUNIT,pc.NIKATASAN1,k.NAMAKAR AS NAMAATASAN1,
		pc.NIKATASAN2,k1.NAMAKAR AS NAMAATASAN2,pc.NIKHR,k2.NAMAKAR AS NAMAHR,
		pc.TGLATASAN1,pc.TGLATASAN2,pc.TGLHR,pc.CUTIMASAL,pc.STATUSCUTI,pc.USERNAME
		FROM permohonancuti pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKATASAN1
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKATASAN2
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKHR
		WHERE pc.NIKATASAN1 = '" .$nik . "' OR pc.NIKATASAN2='" .$nik . "' OR pc.NIKHR='" .$nik . "'
		ORDER BY NOCUTI
		LIMIT ".$start.",".$limit;*/
		
		$sql = "SELECT pc.NOCUTI,pc.KODEUNIT,pc.NIKATASAN1,k.NAMAKAR AS NAMAATASAN1,
		pc.NIKATASAN2,k1.NAMAKAR AS NAMAATASAN2,pc.NIKHR,k2.NAMAKAR AS NAMAHR,
		DATE(pc.TGLATASAN1) AS TGLATASAN1,pc.TGLATASAN2,pc.TGLHR,pc.CUTIMASAL,pc.STATUSCUTI,pc.USERNAME
		FROM permohonancuti pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKATASAN1
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKATASAN2
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKHR
		WHERE pc.NIKATASAN1 = '" . $nik . "' OR pc.NIKATASAN2 = '" . $nik . "' OR pc.NIKHR = '" . $nik . "'
		ORDER BY NOCUTI
		LIMIT ".$start.",".$limit;
		
		// $this->firephp->log($sql);

		$query = $this->db->query($sql)->result();
		$total  = $this->db->query("SELECT COUNT(pc.NOCUTI) AS total,pc.NIKATASAN1,k.NAMAKAR AS NAMAATASAN1,
		pc.NIKATASAN2,k1.NAMAKAR AS NAMAATASAN2,pc.NIKHR,k2.NAMAKAR AS NAMAHR,
		pc.TGLATASAN1,pc.TGLATASAN2,pc.TGLHR,pc.CUTIMASAL,pc.STATUSCUTI,pc.USERNAME
		FROM permohonancuti pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKATASAN1
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKATASAN2
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKHR
		WHERE pc.NIKATASAN1 = '" .$nik . "' OR pc.NIKATASAN2='" .$nik . "' OR pc.NIKHR='" .$nik . "'")->num_rows();
		//WHERE pc.NIKATASAN1 = '" .$nik . "' OR pc.NIKATASAN2='" .$nik . "' OR pc.NIKHR='" .$nik . "'")->result();
		
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
	
	function uTglA1($data){
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NIKATASAN1'=>$data->NIKATASAN1);
		$rs = $this->db->where($pkey)->update('permohonancuti',array('TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN1)) : NULL)));
		
		$last   = $this->db->where($pkey)->get('permohonancuti')->row();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil disimpan',
			"data"      => $last
		);
		
		return $json;
	}
	
	function uTglA2($data){
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NIKATASAN2'=>$data->NIKATASAN2);
		$rs = $this->db->where($pkey)->update('permohonancuti',array('TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN2)) : NULL)));
		
		$last   = $this->db->where($pkey)->get('permohonancuti')->row();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil disimpan',
			"data"      => $last
		);
		
		return $json;
	}
	
	function uTglHR($data){
		$pkey = array('NOCUTI'=>$data->NOCUTI,'NIKHR'=>$data->NIKHR);
		$rs = $this->db->where($pkey)->update('permohonancuti',array('TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLHR)) : NULL)));
		
		$last   = $this->db->where($pkey)->get('permohonancuti')->row();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil disimpan',
			"data"      => $last
		);
		
		return $json;
	}
	
	function save($data){
		$last   = NULL;
		
		$pkey = array('NOCUTI'=>$data->NOCUTI);
		
		if($this->db->get_where('permohonancuti', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array(
				'KODEUNIT'=>null,
				'NIKATASAN1'=>$data->NIKATASAN1,
				'NIKATASAN2'=>$data->NIKATASAN2,
				'NIKHR'=>$data->NIKHR,
				'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN1)) : NULL),
				'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN2)) : NULL),
				'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLHR)) : NULL),
				'STATUSCUTI'=>$data->STATUSCUTI,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('permohonancuti', $arrdatau);
			
			if($this->db->affected_rows()){
				/**
				 * akibat update db.permohonancuti, maka update status-cuti di db.rinciancuti
				 *
				 * Jika $data->STATUSCUTI = 'S' / 'C' ==> update seluruh db.rinciancuti.STATUSCUTI = 'S'
				 * Jika $data->STATUSCUTI = 'T', maka:
				 * >> update seluruh db.rinciancuti.STATUSCUTI = 'T' (where db.rinciancuti.STATUSCUTI = 'S')
				 */
				if($data->STATUSCUTI == 'S' OR $data->STATUSCUTI == 'C' OR $data->STATUSCUTI == 'A'){
					$this->db->where($pkey)->set('STATUSCUTI',$data->STATUSCUTI)->update('rinciancuti');
				}elseif($data->STATUSCUTI == 'T'){
					$this->db->where(array('NOCUTI'=>$data->NOCUTI, 'STATUSCUTI'=>'S'))->set('STATUSCUTI',$data->STATUSCUTI)->update('rinciancuti');
					
					if($this->db->affected_rows()){
						/**
						 * 1. Jika NIKHR meng-update STATUSCUTI = 'T' di master(permohonancuti), maka:
						 * 1.a. Cari di detail(rinciancuti) siapa saja yang STATUSCUTI = 'T' dan JENISABSEN = 'CT' berdasar NOCUTI
						 * 1.b. Dari hasil 1.a. ==> update ke db.cutitahunan
						 */
						$rows = $this->db->where(array('NOCUTI'=>$data->NOCUTI, 'STATUSCUTI'=>'T', 'JENISABSEN'=>'CT'))->get('rinciancuti')->result();
						if(sizeof($rows) > 0){
							foreach($rows as $row){
								$this->cutitahunan_minus($row);
							}
						}
					}
					
				}
				
			}
			
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$n = substr($data->NIKATASAN1,0,1);
			$sql = "SELECT MAX(NOCUTI) AS NOCUTI,NIKATASAN1,
			IF(ISNULL(MAX(NOCUTI)),'".$n."000001',CONCAT(SUBSTR(NOCUTI,1,1), SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOCUTI),2,8)+1)),-6))) AS GEN
			FROM permohonancuti
			WHERE NOCUTI LIKE '".$n."%';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			 
			$arrdatac = array('NOCUTI'=>($rs->num_rows() > 0 && !(substr($hasil[0]->NOCUTI,1,6) == '999999') ? $hasil[0]->GEN : $hasil[0]->GEN),'KODEUNIT'=> NULL,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSCUTI'=>'A','NIKATASAN2'=>$data->NIKATASAN2,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>date('Y-m-d H:i:s'),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN2)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			
			$this->db->insert('permohonancuti', $arrdatac);
			$last   = $this->db->where('NOCUTI',$hasil[0]->GEN)->get('permohonancuti')->row();
			
		}
		
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
		$pkey = array('NOCUTI'=>$data->NOCUTI);
		
		$this->db->where($pkey)->delete('permohonancuti');
		
		$total  = $this->db->get('permohonancuti')->num_rows();
		$last = $this->db->get('permohonancuti')->result();
		
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
			->set('JMLCUTI', 'JMLCUTI+'.$data->LAMA, FALSE)
			->set('SISACUTI', 'SISACUTI-'.$data->LAMA, FALSE)
			->update('cutitahunan');
		if($this->db->affected_rows() == 0){
			//update db.cutitahunan dengan JENISCUTI = '1' (Cuti Tambahan)
			$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'1', 'DIKOMPENSASI'=>'N'))
				->set('JMLCUTI', 'JMLCUTI+'.$data->LAMA, FALSE)
				->set('SISACUTI', 'SISACUTI-'.$data->LAMA, FALSE)
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
			->set('JMLCUTI', 'JMLCUTI-'.$data->LAMA, FALSE)
			->set('SISACUTI', 'SISACUTI+'.$data->LAMA, FALSE)
			->update('cutitahunan');
		if($this->db->affected_rows() == 0){
			//update db.cutitahunan dengan JENISCUTI = '0' (Cuti Tahunan)
			$this->db->where(array('NIK'=>$data->NIK, 'JENISCUTI'=>'0', 'DIKOMPENSASI'=>'N'))
				->set('JMLCUTI', 'JMLCUTI-'.$data->LAMA, FALSE)
				->set('SISACUTI', 'SISACUTI+'.$data->LAMA, FALSE)
				->update('cutitahunan');
		}
	}
}
?>