<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_splembur
 * 
 * Table	: splembur
 *  
 * @author masongbee
 *
 */
class M_splembur extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function get_personalia() {
		$sql = "SELECT us.USER_NAME as USERNAME,us.USER_KARYAWAN AS NIK,ka.NAMAKAR AS NAMAKAR
			FROM s_usergroups gp
			INNER JOIN s_users us ON us.USER_GROUP=gp.GROUP_ID
			INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
			WHERE LOWER(GROUP_NAME) = LOWER('AdmLembur')";
		$sql_total = "SELECT COUNT(*) AS total
			FROM s_usergroups gp
			INNER JOIN s_users us ON us.USER_GROUP=gp.GROUP_ID
			INNER JOIN karyawan ka ON ka.NIK = us.USER_KARYAWAN
			WHERE LOWER(GROUP_NAME) = LOWER('AdmLembur')";
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
	function getAll($nik,$start, $page, $limit){

		//$query  = $this->db->limit($limit, $start)->where('NIKUSUL', $nik)->or_where('NIKSETUJU', $nik)->order_by('NOLEMBUR', 'ASC')->get('splembur')->result();
		//$total  = $this->db->where('NIKUSUL', $nik)->or_where('NIKSETUJU', $nik)->get('splembur')->num_rows();
		
		$sql = "SELECT pc.NOLEMBUR,pc.TANGGAL,pc.NIKUSUL,k.NAMAKAR AS NAMAUSUL,
		pc.NIKSETUJU,k1.NAMAKAR AS NAMASETUJU,pc.NIKPERSONALIA,k2.NAMAKAR AS NAMAPERSONALIA,
		pc.TGLSETUJU,pc.TGLPERSONALIA,pc.KEPERLUAN,pc.USERNAME
		FROM splembur pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKUSUL
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKSETUJU
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKPERSONALIA
		WHERE NIKUSUL='".$nik."' OR NIKSETUJU='".$nik."' OR NIKPERSONALIA='".$nik."'
		ORDER BY NOLEMBUR
		LIMIT ".$start.",".$limit;
		
		
		$query = $this->db->query($sql)->result();
		$total  = $this->db->query("SELECT pc.NOLEMBUR,pc.TANGGAL,pc.NIKUSUL,k.NAMAKAR AS NAMAUSUL,
		pc.NIKSETUJU,k1.NAMAKAR AS NAMASETUJU,pc.NIKPERSONALIA,k2.NAMAKAR AS NAMAPERSONALIA,
		pc.TGLSETUJU,pc.TGLPERSONALIA,pc.KEPERLUAN,pc.USERNAME
		FROM splembur pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKUSUL
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKSETUJU
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKPERSONALIA
		WHERE NIKUSUL='".$nik."' OR NIKSETUJU='".$nik."'")->num_rows();
		
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
		
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR);
		$this->firephp->info($data->TGLSETUJU);
		if($this->db->get_where('splembur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('KODEUNIT'=>NULL,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TANGGAL)) : NULL),'KEPERLUAN'=>$data->KEPERLUAN,'NIKUSUL'=>$data->NIKUSUL,'NIKSETUJU'=>$data->NIKSETUJU,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'TGLSETUJU'=>(strlen(trim($data->TGLSETUJU)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLSETUJU)) : NULL),'TGLPERSONALIA'=>(strlen(trim($data->TGLPERSONALIA)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLPERSONALIA)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('splembur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$n = substr($data->NIKUSUL,0,1);
			$sql = "SELECT MAX(NOLEMBUR) AS NOLEMBUR,NIKUSUL,
					IF(ISNULL(MAX(NOLEMBUR)),CONCAT('".$n."','000001'),CONCAT(SUBSTR(NOLEMBUR,1,1),
					SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOLEMBUR),2,8)+1)),-6))) AS GEN
					FROM SPLEMBUR
					WHERE NOLEMBUR LIKE '".$n."%';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			 
			$arrdatac = array('NOLEMBUR'=>$hasil[0]->GEN,'KODEUNIT'=>NULL,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TANGGAL)) : NULL),
							  'KEPERLUAN'=>$data->KEPERLUAN,'NIKUSUL'=>$data->NIKUSUL,'NIKSETUJU'=>$data->NIKSETUJU,
							  'NIKPERSONALIA'=>$data->NIKPERSONALIA,'TGLSETUJU'=>(strlen(trim($data->TGLSETUJU)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLSETUJU)) : NULL),
							  'TGLPERSONALIA'=>(strlen(trim($data->TGLPERSONALIA)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TGLPERSONALIA)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('splembur', $arrdatac);
			$last   = $this->db->where($pkey)->get('splembur')->row();
		}
		
		$total  = $this->db->get('splembur')->num_rows();
		
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
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR);
		
		$this->db->where($pkey)->delete('splembur');
		
		$total  = $this->db->get('splembur')->num_rows();
		$last = $this->db->get('splembur')->result();
		
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