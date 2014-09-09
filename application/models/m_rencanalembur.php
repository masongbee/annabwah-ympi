<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rencanalembur
 * 
 * Table	: rencanalembur
 *  
 * @author masongbee
 *
 */
class M_rencanalembur extends CI_Model{

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
	function getAll($nolembur,$start, $page, $limit){
		//$query  = $this->db->where('NOLEMBUR',$nolembur)->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('rencanalembur')->result();
		//$total  = $this->db->get('rencanalembur')->num_rows();
		
		$sql = "SELECT pc.NOLEMBUR,pc.NOURUT,pc.NIK,k.NAMAKAR,
		pc.TJMASUK,
		pc.TJKELUAR,
		DATE(pc.TJMASUK) AS TGLMASUK,
		TIME(pc.TJMASUK) AS JAMMASUK,
		DATE(pc.TJKELUAR) AS TGLKELUAR,
		TIME(pc.TJKELUAR) AS JAMKELUAR,
		pc.ANTARJEMPUT,pc.MAKAN,pc.JENISLEMBUR
		FROM RENCANALEMBUR pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIK
		WHERE NOLEMBUR='".$nolembur."'
		ORDER BY NOURUT
		LIMIT ".$start.",".$limit;
		
		
		$query = $this->db->query($sql)->result();
		$total  = $this->db->query("SELECT pc.NOLEMBUR,pc.NOURUT,pc.NIK,k.NAMAKAR,
		pc.TJMASUK,pc.TJKELUAR,
		pc.ANTARJEMPUT,pc.MAKAN,pc.JENISLEMBUR
		FROM RENCANALEMBUR pc
		LEFT JOIN karyawan k ON k.NIK=pc.NIK
		WHERE NOLEMBUR='".$nolembur."'")->num_rows();
		
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
		
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('rencanalembur', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$tglmasuk = date('Y-m-d', strtotime($data->TGLMASUK));
			$jammasuk = date('H:i:s', strtotime($data->JAMMASUK));
			$tglkeluar = date('Y-m-d', strtotime($data->TGLKELUAR));
			$jamkeluar = date('H:i:s', strtotime($data->JAMKELUAR));
			$tjmasuk = (strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($tglmasuk.' '.$jammasuk)) : NULL);
			$tjkeluar = (strlen(trim($data->TGLKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($tglkeluar.' '.$jamkeluar)) : NULL);
			$arrdatau = array(
				'NIK'=>$data->NIK,
				'TJMASUK'=>$tjmasuk,
				'TJKELUAR'=>$tjkeluar,
				'ANTARJEMPUT'=>$data->ANTARJEMPUT,
				'MAKAN'=>$data->MAKAN,
				'JENISLEMBUR'=>$data->JENISLEMBUR
			);
			 
			$this->db->where($pkey)->update('rencanalembur', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$sql = "SELECT NOLEMBUR,MAX(NOURUT) AS NOURUT,NIK,
			IF(ISNULL(MAX(NOURUT)),1,MAX(NOURUT) + 1) AS GEN
			FROM rencanalembur
			WHERE NOLEMBUR='".$data->NOLEMBUR."';";
			$rs = $this->db->query($sql);
			$hasil = $rs->result();
			
			$tglmasuk = date('Y-m-d', strtotime($data->TGLMASUK));
			$jammasuk = date('H:i:s', strtotime($data->JAMMASUK));
			$tglkeluar = date('Y-m-d', strtotime($data->TGLKELUAR));
			$jamkeluar = date('H:i:s', strtotime($data->JAMKELUAR));
			$tjmasuk = (strlen(trim($data->TGLMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($tglmasuk.' '.$jammasuk)) : NULL);
			$tjkeluar = (strlen(trim($data->TGLKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($tglkeluar.' '.$jamkeluar)) : NULL);
			$arrdatac = array(
				'NOLEMBUR'=>$data->NOLEMBUR,
				'NOURUT'=>$hasil[0]->GEN,
				'NIK'=>$data->NIK,
				'TJMASUK'=>$tjmasuk,
				'TJKELUAR'=>$tjkeluar,
				'ANTARJEMPUT'=>$data->ANTARJEMPUT,
				'MAKAN'=>$data->MAKAN,
				'JENISLEMBUR'=>$data->JENISLEMBUR
			);
			 
			$this->db->insert('rencanalembur', $arrdatac);
			$last   = $this->db->where($pkey)->get('rencanalembur')->row();
			
		}
		
		$total  = $this->db->get('rencanalembur')->num_rows();
		
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
		$pkey = array('NOLEMBUR'=>$data->NOLEMBUR,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('rencanalembur');
		
		$total  = $this->db->get('rencanalembur')->num_rows();
		$last = $this->db->get('rencanalembur')->result();
		
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