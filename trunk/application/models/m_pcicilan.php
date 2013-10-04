<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_pcicilan
 * 
 * Table	: pcicilan
 *  
 * @author masongbee
 *
 */
class M_pcicilan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('pcicilan')->result();
		$total  = $this->db->get('pcicilan')->num_rows();
		
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
		
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('pcicilan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NIK'=>$data->NIK,'CICILANKE'=>$data->CICILANKE,'RPCICILAN'=>$data->RPCICILAN,'LAMACICILAN'=>$data->LAMACICILAN,'KETERANGAN'=>$data->KETERANGAN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->where($pkey)->update('pcicilan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT,'NIK'=>$data->NIK,'CICILANKE'=>$data->CICILANKE,'RPCICILAN'=>$data->RPCICILAN,'LAMACICILAN'=>$data->LAMACICILAN,'KETERANGAN'=>$data->KETERANGAN,'USERNAME'=>$data->USERNAME);
			 
			$this->db->insert('pcicilan', $arrdatac);
			$last   = $this->db->where($pkey)->get('pcicilan')->row();
			
		}
		
		$total  = $this->db->get('pcicilan')->num_rows();
		
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
		$pkey = array('BULAN'=>$data->BULAN,'NOURUT'=>$data->NOURUT);
		
		$this->db->where($pkey)->delete('pcicilan');
		
		$total  = $this->db->get('pcicilan')->num_rows();
		$last = $this->db->get('pcicilan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	/**
	 * Fungsi	: check_upload
	 *
	 * Untuk data dari Excel ke Database, apakah sudah pernah diinjekkan ataukah belum?
	 *
	 * @param array $data
	 * @return array
	 */
	function check_upload($data, $filename){
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
				$existsdata = 0;
				for ($row = 1; $row <= 2; ++ $row) {
					if($row>1){
						for ($col = 0; $col < $highestColumnIndex; ++ $col) {
							$bulan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$rpcicilan = ($worksheet->getCellByColumnAndRow(2, $row)->getValue() == ''? 0 : $worksheet->getCellByColumnAndRow(2, $row)->getValue());
							$keterangan = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
							$cicilanke = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : $worksheet->getCellByColumnAndRow(4, $row)->getValue());
							$lamacicilan = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : $worksheet->getCellByColumnAndRow(5, $row)->getValue());
							
							if(substr($keterangan,0,4) == 'KOPE'){
								$nourut = 2;
							}elseif(substr($keterangan,0,4) == 'PINJ'){
								$nourut = 1;
							}else{
								$nourut = 3;
							}
						}
						
						$data = array(
							'BULAN' => $bulan,
							'NOURUT' => $nourut,
							'NIK' => $nik,
							'CICILANKE' => $cicilanke,
							'RPCICILAN' => $rpcicilan,
							'LAMACICILAN' => $lamacicilan,
							'KETERANGAN' => $keterangan
						);
						if($this->db->get_where('pcicilan', array('BULAN'=>$bulan, 'KETERANGAN'=>$keterangan))->num_rows() > 0){
							$existsdata++;
						}
						
					}
					
					if($existsdata > 0){
						break;
					}
				}
				
				$p++;
			}
			
			$success = array(
				'success'	=> true,
				'msg'		=> 'Data sudah pernah ditambahkan. Apakah Anda akan melanjutkan update data?',
				'filename'	=> $filename,
				'existsdata'=> $existsdata
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
							$bulan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$rpcicilan = ($worksheet->getCellByColumnAndRow(2, $row)->getValue() == ''? 0 : $worksheet->getCellByColumnAndRow(2, $row)->getValue());
							$keterangan = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
							$cicilanke = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : $worksheet->getCellByColumnAndRow(4, $row)->getValue());
							$lamacicilan = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : $worksheet->getCellByColumnAndRow(5, $row)->getValue());
							
							if(substr($keterangan,0,4) == 'KOPE'){
								$nourut = 2;
							}elseif(substr($keterangan,0,4) == 'PINJ'){
								$nourut = 1;
							}else{
								$nourut = 3;
							}
							
						}
						
						$data = array(
							'BULAN' => $bulan,
							'NOURUT' => $nourut,
							'NIK' => $nik,
							'CICILANKE' => $cicilanke,
							'RPCICILAN' => $rpcicilan,
							'LAMACICILAN' => $lamacicilan,
							'KETERANGAN' => $keterangan
						);
						if($this->db->get_where('pcicilan', array('BULAN'=>$bulan,'NIK'=>$nik,'NOURUT'=>$nourut))->num_rows() == 0){
							$this->db->insert('pcicilan', $data);
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