<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tahapseleksi
 * 
 * Table	: tahapseleksi
 *  
 * @author masongbee
 *
 */
class M_tahapseleksi extends CI_Model{

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
		$query = "SELECT KTP
				,GELLOW
				,KODEJAB
				,IDJAB
				,NOURUT
				,tahapseleksi.KODESELEKSI
				,jenisseleksi.NAMASELEKSI
				,LULUS
				,TANGGAL
			FROM tahapseleksi
			LEFT JOIN jenisseleksi ON(jenisseleksi.KODESELEKSI = tahapseleksi.KODESELEKSI)
			WHERE LULUS = 'P'
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('tahapseleksi')->num_rows();
		
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
		
		$pkey = array('KTP'=>$data->KTP,'NOURUT'=>$data->NOURUT);

		if($this->db->get_where('tahapseleksi', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$rec_tahapseleksi = $this->db->where($pkey)->get('tahapseleksi')->row();
			$prev_lulus = $rec_tahapseleksi->LULUS;
			$prev_kodeseleksi = $rec_tahapseleksi->KODESELEKSI;

			$arrdatau = array(
				'LULUS'=>$data->LULUS
			);
			$arrdatau_pelamar = array(
				'STATUSPELAMAR'=>$prev_kodeseleksi
			);

			if ($data->LULUS != $prev_lulus) {
				if ($data->LULUS == 'Y') {
					$this->db->where($pkey)->update('tahapseleksi', $arrdatau);
					$this->db->where('KTP', $data->KTP)->update('pelamar', $arrdatau_pelamar);

					/*Tambahkan data untuk Tahap Selanjutnya*/
					$nourut_last = $this->db->select('COUNT(*) AS total')->where('KTP', $data->KTP)->get('tahapseleksi')->row();
					$nourut = $nourut_last->total + 1;

					$next_kodeseleksi = chr(ord($prev_kodeseleksi)+1);
					if ($prev_kodeseleksi != 'F') {
						$arrdatac_tahapseleksi = array(
							'KTP'          =>$data->KTP
							,'GELLOW'      =>$data->GELLOW
							,'KODEJAB'     =>$data->KODEJAB
							,'IDJAB'       =>$data->IDJAB
							,'NOURUT'      =>$nourut
							,'KODESELEKSI' =>$next_kodeseleksi
							,'LULUS'       =>'P'
						);
						$this->db->insert('tahapseleksi', $arrdatac_tahapseleksi);
					}
				} else if ($data->LULUS == 'T') {
					$this->db->where($pkey)->update('tahapseleksi', $arrdatau);
				}
				
			}
			
			$last   = $data;
			
		}
		
		$total  = $this->db->get('tahapseleksi')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('tahapseleksi');
		
		$total  = $this->db->get('tahapseleksi')->num_rows();
		$last = $this->db->get('tahapseleksi')->result();
		
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