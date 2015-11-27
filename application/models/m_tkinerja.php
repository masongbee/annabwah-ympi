<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tkinerja
 * 
 * Table	: tkinerja
 *  
 * @author masongbee
 *
 */
class M_tkinerja extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tkinerja')->result();
		$query = "SELECT NIK
				,KODE
				,NILAI
				,CATATAN
			FROM tkinerja
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('tkinerja')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'KODE'=>$data->KODE);
		
		if($this->db->get_where('tkinerja', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$arrdatau = array(
				'NILAI'    => $data->NILAI
				,'CATATAN' => $data->CATATAN
			);
			
			$this->db->where($pkey)->update('tkinerja', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$arrdatac = array(
				'NIK'      => $data->NIK
				,'KODE'    => $data->KODE
				,'NILAI'   => $data->NILAI
				,'CATATAN' => $data->CATATAN
			);
			
			$this->db->insert('tkinerja', $arrdatac);
			$last   = $this->db->where($pkey)->get('tkinerja')->row();
			
		}
		
		$total  = $this->db->get('tkinerja')->num_rows();
		
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
		$pkey = array('KODE'=>$data->KODE);
		
		$this->db->where($pkey)->delete('tkinerja');
		
		$total = $this->db->get('tkinerja')->num_rows();
		$last  = $this->db->get('tkinerja')->result();
		
		$json   = array(
			"success"   => TRUE,
			"message"   => 'Data berhasil dihapus',
			"total"     => $total,
			"data"      => $last
		);				
		return $json;
	}

	/**
	 * Fungsi	: do_upload
	 *
	 * Untuk menginjeksi data dari Excel ke Database
	 *
	 * @param array $data
	 * @return array
	 */
	function do_upload($data, $filename){
		if(sizeof($data) > 0){
			$p = 0;
			foreach($data->getWorksheetIterator() as $worksheet){
				if($p>0){
					break;
				}
				
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$skeepdata = 0;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							$nik     = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$kode    = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
							$nilai   = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$catatan = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
						}
						
						$data = array(
							'NIK'     => $nik,
							'KODE'    => $kode,
							'NILAI'   => $nilai,
							'CATATAN' => $catatan
						);
						if($this->db->get_where('tkinerja', array('NIK'=>$nik,'KODE'=>$kode))->num_rows() == 0){
							$this->db->insert('tkinerja', $data);
						}else{
							$skeepdata++;
						}
						
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data telah berhasil ditambahkan.',
				'filename'	=> $filename,
				'skeepdata'	=> $skeepdata
			);
			return $success;
		}else{
			$error = array(
				'success'	=> false,
				'msg'		=> 'Tidak ada proses, karena data kosong.',
				'filename'	=> $filename
			);
			return $error;
		}
	}
}
?>