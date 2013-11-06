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
			$sql = "SELECT *
			FROM cutitahunan
			WHERE NIK=".$this->db->escape($item['KEY']);
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
			 
			$arrdatac = array('NOIJIN'=>$data->NOIJIN,'NIK'=>$data->NIK,'JENISABSEN'=>$data->JENISABSEN,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'JAMDARI'=>$data->JAMDARI,'JAMSAMPAI'=>$data->JAMSAMPAI,'KEMBALI'=>$data->KEMBALI,'AMBILCUTI'=>$data->AMBILCUTI,'DIAGNOSA'=>$data->DIAGNOSA,'TINDAKAN'=>$data->TINDAKAN,'ANJURAN'=>$data->ANJURAN,'PETUGASKLINIK'=>$data->PETUGASKLINIK,'NIKATASAN1'=>substr($data->NIKATASAN1,0,9),'STATUSIJIN'=>'A','NIKPERSONALIA'=>$data->NIKPERSONALIA,'NIKGA'=>$data->NIKGA,'NIKDRIVER'=>$data->NIKDRIVER,'NIKSECURITY'=>$data->NIKSECURITY,'USERNAME'=>$data->USERNAME);
			 
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