<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_posisilowongan
 * 
 * Table	: posisilowongan
 *  
 * @author masongbee
 *
 */
class M_posisilowongan extends CI_Model{

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
		$query = "SELECT posisilowongan.GELLOW
				,posisilowongan.IDJAB
				,NAMAUNIT
				,posisilowongan.KODEJAB
				,grade.KETERANGAN AS NAMAGRADE
				,JMLPOSISI
				,lowongan.KETERANGAN AS GELLOW_KETERANGAN
			FROM posisilowongan
			JOIN jabatan ON(jabatan.IDJAB = posisilowongan.IDJAB)
			JOIN leveljabatan ON(leveljabatan.KODEJAB = posisilowongan.KODEJAB)
			LEFT JOIN unitkerja ON(unitkerja.KODEUNIT = jabatan.KODEUNIT)
			LEFT JOIN grade ON(grade.GRADE = leveljabatan.GRADE)
			LEFT JOIN lowongan ON(lowongan.GELLOW = posisilowongan.GELLOW)
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('posisilowongan')->num_rows();
		
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
		
		$pkey = array('GELLOW'=>$data->GELLOW,'IDJAB'=>$data->IDJAB,'KODEJAB'=>$data->KODEJAB);
		
		if($this->db->get_where('posisilowongan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'JMLPOSISI'=>$data->JMLPOSISI
			);
			
			$this->db->where($pkey)->update('posisilowongan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'GELLOW'=>$data->GELLOW,
				'IDJAB'=>$data->IDJAB,
				'KODEJAB'=>$data->KODEJAB,
				'JMLPOSISI'=>$data->JMLPOSISI
			);
			
			$this->db->insert('posisilowongan', $arrdatac);
			$last   = $this->db->where($pkey)->get('posisilowongan')->row();
			
		}
		
		$total  = $this->db->get('posisilowongan')->num_rows();
		
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
		$pkey = array('GELLOW'=>$data->GELLOW,'IDJAB'=>$data->IDJAB,'KODEJAB'=>$data->KODEJAB);
		
		$this->db->where($pkey)->delete('posisilowongan');
		
		$total  = $this->db->get('posisilowongan')->num_rows();
		$last = $this->db->get('posisilowongan')->result();
		
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