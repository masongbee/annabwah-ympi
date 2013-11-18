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
		
		$query  = $this->db->get_where('jenisabsen',array('KELABSEN' => 'I'))->result();
		$total  = $this->db->get_where('jenisabsen',array('KELABSEN' => 'I'))->num_rows();
		
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
		$query  = $this->db->limit($limit, $start)->where('NIKPERSONALIA', $nik)->or_where('NIKATASAN1', $nik)->order_by('NOIJIN', 'ASC')->get('permohonanijin')->result();
			
		$total = $this->db->where('NIKPERSONALIA', $nik)->or_where('NIKATASAN1', $nik)->order_by('NOIJIN', 'ASC')->get('permohonanijin')->num_rows();
		
		//$query  = $this->db->limit($limit, $start)->order_by('NOIJIN', 'ASC')->get('permohonanijin')->result();
		//$total  = $this->db->get('permohonanijin')->num_rows();
		
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
		
		$pkey = array('NOIJIN'=>$data->NOIJIN);
		
		if($this->db->get_where('permohonanijin', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
				
			 
			$arrdatau = array('NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'AMBILCUTI'=>$data->AMBILCUTI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSIJIN'=>$data->STATUSIJIN,'NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('permohonanijin', $arrdatau);
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
			SUBSTR(CONCAT('000000',(SUBSTR(MAX(NOIJIN),2,8)+1)),-6)) AS GEN
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
				$arrdatac = array('NOIJIN'=>($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN),'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'KEMBALI'=>$data->KEMBALI,'AMBILCUTI'=>$data->AMBILCUTI,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSIJIN'=>'A','NIKPERSONALIA'=>$data->NIKPERSONALIA,'USERNAME'=>$data->USERNAME);
			}
			else
			{
				
				$arrdatac = array('NOIJIN'=>($rs->num_rows() > 0 && !(substr($hasil[0]->NOIJIN,1,6) == '999999') ? $hasil[0]->GEN : $rs2[0]->GEN),'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'AMBILCUTI'=>$data->AMBILCUTI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSIJIN'=>'A','NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);
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
}
?>