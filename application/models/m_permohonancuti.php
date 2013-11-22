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
		$query  = $this->db->query("SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
		FROM s_usergroups gp
		INNER JOIN s_users us ON us.USER_GROUP=gp.GROUP_ID
		INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
		WHERE LOWER(GROUP_NAME) = LOWER('AdmAbsensi')")->result();
		$total  = $this->db->query("SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
		FROM s_usergroups gp
		INNER JOIN s_users us ON us.USER_GROUP=gp.GROUP_ID
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
	function getAll($start, $page, $limit){
		$query  = $this->db->limit($limit, $start)->order_by('NOCUTI', 'ASC')->get('permohonancuti')->result();
		$total  = $this->db->get('permohonancuti')->num_rows();
		
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
				
			 
			$arrdatau = array('KODEUNIT'=>null,'NIKATASAN1'=>$data->NIKATASAN1,'NIKATASAN2'=>$data->NIKATASAN2,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLATASAN2)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLHR)) : NULL),'STATUSCUTI'=>$data->STATUSCUTI,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('permohonancuti', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$n = substr($data->NIKATASAN1,0,1);
			$sql = "SELECT MAX(NOCUTI) AS NOCUTI,NIKATASAN1,
			IF(ISNULL(MAX(NOCUTI)),'A000001',CONCAT(SUBSTR(NOCUTI,1,1), SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOCUTI),2,8)+1)),-6))) AS GEN
			FROM permohonancuti
			WHERE NOCUTI LIKE '".$n."%';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			
			
			$sql2 = "SELECT NOCUTI,NIKATASAN1,CONCAT(SUBSTR(NIKATASAN1,1,1),'000001') AS GEN
			FROM permohonancuti
			WHERE NIKATASAN1='".$data->NIKATASAN1."';";
			$rs2 = $this->db->query($sql2)->result();
			 
			$arrdatac = array('NOCUTI'=>($rs->num_rows() > 0 && !(substr($hasil[0]->NOCUTI,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN),'KODEUNIT'=> NULL,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSCUTI'=>'A','NIKATASAN2'=>$data->NIKATASAN2,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>date('Y-m-d H:i:s'),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN2)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('PERMOHONANCUTI', $arrdatac);
			$last   = $this->db->where($pkey)->get('PERMOHONANCUTI')->row();
			
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
}
?>