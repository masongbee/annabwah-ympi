<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_mohoncuti
 * 
 * Table	: PERMOHONANCUTI
 *  
 * @author masongbee
 *
 */
class M_mohoncuti extends CI_Model{

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
	 
	function get_jenisabsen(){
		
		$query  = $this->db->get_where('jenisabsen',array('KELABSEN' => 'C'))->result();
		$total  = $this->db->get_where('jenisabsen',array('KELABSEN' => 'C'))->num_rows();
		
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
	
	function getAll($nik,$start, $page, $limit){
		//$query  = $this->db->limit($limit, $start)->where('NIKATASAN1', $nik)->or_where('NIKATASAN2', $nik)->or_where('NIKHR', $nik)->order_by('NOCUTI', 'ASC')->get('PERMOHONANCUTI')->result();
		//$total  = $this->db->where('NIKATASAN1', $nik)->or_where('NIKATASAN2', $nik)->or_where('NIKHR', $nik)->order_by('NOCUTI', 'ASC')->get('PERMOHONANCUTI')->num_rows();
		
		//$query  = $this->db->limit($limit, $start)->order_by('NOCUTI', 'ASC')->get('PERMOHONANCUTI')->result();
		//$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		
		$sql = "SELECT pc.NOCUTI,pc.KODEUNIT,pc.NIKATASAN1,k.NAMAKAR AS NAMAATASAN1,
		pc.NIKATASAN2,k1.NAMAKAR AS NAMAATASAN2,pc.NIKHR,k2.NAMAKAR AS NAMAHR,
		pc.TGLATASAN1,pc.TGLATASAN2,pc.TGLHR,pc.CUTIMASAL,pc.STATUSCUTI,pc.USERNAME
		FROM permohonancuti pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKATASAN1
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKATASAN2
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKHR
		WHERE pc.NIKATASAN1 = '" .$nik . "' OR pc.NIKATASAN2='" .$nik . "' OR pc.NIKHR='" .$nik . "'
		ORDER BY NOCUTI
		LIMIT ".$start.",".$limit;
		
		
		$query = $this->db->query($sql)->result();
		$total  = $this->db->query("SELECT COUNT(pc.NOCUTI) AS total,pc.NIKATASAN1,k.NAMAKAR AS NAMAATASAN1,
		pc.NIKATASAN2,k1.NAMAKAR AS NAMAATASAN2,pc.NIKHR,k2.NAMAKAR AS NAMAHR,
		pc.TGLATASAN1,pc.TGLATASAN2,pc.TGLHR,pc.CUTIMASAL,pc.STATUSCUTI,pc.USERNAME
		FROM permohonancuti pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIKATASAN1
		LEFT JOIN karyawan k1 ON k1.NIK = pc.NIKATASAN2
		LEFT JOIN karyawan k2 ON k2.NIK = pc.NIKHR
		WHERE pc.NIKATASAN1 = '" .$nik . "' OR pc.NIKATASAN2='" .$nik . "' OR pc.NIKHR='" .$nik . "'")->result();
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		//$this->firephp->info($sql);
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'total'     => $total[0]->total,
			//'total'     => $total,
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
		
		$pkey = array('NOCUTI'=>$data->NOCUTI);
		
		if($this->db->get_where('PERMOHONANCUTI', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */			 
				
			 
			$arrdatau = array('KODEUNIT'=>$data->KODEUNIT,'NIKATASAN1'=>$data->NIKATASAN1,'STATUSCUTI'=>$data->STATUSCUTI,'NIKATASAN2'=>$data->NIKATASAN2,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>(strlen(trim($data->TGLATASAN1)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN1)) : NULL),'TGLATASAN2'=>(strlen(trim($data->TGLATASAN2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASAN2)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('PERMOHONANCUTI', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			 
			$arrdatac = array('NOCUTI'=>$data->NOCUTI,'KODEUNIT'=>$data->KODEUNIT,'NIKATASAN1'=>substr($data->NIKATASANC1,0,9),'STATUSCUTI'=>'A','NIKATASAN2'=>$data->NIKATASANC2,'NIKHR'=>$data->NIKHR,'TGLATASAN1'=>date('Y-m-d H:i:s'),'TGLATASAN2'=>(strlen(trim($data->TGLATASANC2)) > 0 ? date('Y-m-d', strtotime($data->TGLATASANC2)) : NULL),'TGLHR'=>(strlen(trim($data->TGLHR)) > 0 ? date('Y-m-d', strtotime($data->TGLHR)) : NULL),'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('PERMOHONANCUTI', $arrdatac);
			$last   = $this->db->where($pkey)->get('PERMOHONANCUTI')->row();
			
		}
		
		$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('PERMOHONANCUTI');
		
		$total  = $this->db->get('PERMOHONANCUTI')->num_rows();
		$last = $this->db->get('PERMOHONANCUTI')->result();
		
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