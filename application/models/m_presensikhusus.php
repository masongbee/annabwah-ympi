<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_presensikhusus
 * 
 * Table	: presensikhusus
 *  
 * @author masongbee
 *
 */
class M_presensikhusus extends CI_Model{

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
	function getAll($start, $page, $limit, $tglmulai, $tglsampai){
		//$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('presensikhusus')->result();
		$sql = "SELECT presensikhusus.*, karyawan.NAMAKAR
			FROM presensikhusus
			LEFT JOIN karyawan ON(karyawan.NIK = presensikhusus.NIK)
			WHERE TANGGAL >= DATE('".$tglmulai."')
				AND TANGGAL <= DATE('".$tglsampai."')
			ORDER BY NIK";
		$query 	= $this->db->query($sql)->result();
		$total  = $this->db->get('presensikhusus')->num_rows();
		
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
		
		$pkey = array('ID'=>$data->ID,'NIK'=>$data->NIK);
		
		if($this->db->get_where('presensikhusus', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array('NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'JENISABSEN'=>$data->JENISABSEN,'JENISLEMBUR'=>$data->JENISLEMBUR,'EXTRADAY'=>$data->EXTRADAY);
			 
			$this->db->where($pkey)->update('presensikhusus', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array('NIK'=>$data->NIK,'NAMASHIFT'=>$data->NAMASHIFT,'SHIFTKE'=>$data->SHIFTKE,'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),'TJMASUK'=>(strlen(trim($data->TJMASUK)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJMASUK)) : NULL),'TJKELUAR'=>(strlen(trim($data->TJKELUAR)) > 0 ? date('Y-m-d H:i:s', strtotime($data->TJKELUAR)) : NULL),'ASALDATA'=>$data->ASALDATA,'JENISABSEN'=>$data->JENISABSEN,'JENISLEMBUR'=>$data->JENISLEMBUR,'EXTRADAY'=>$data->EXTRADAY);
			 
			$this->db->insert('presensikhusus', $arrdatac);
			$last   = $this->db->where($pkey)->get('presensikhusus')->row();
			
		}
		
		$total  = $this->db->get('presensikhusus')->num_rows();
		
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
		$pkey = array('ID'=>$data->ID,'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('presensikhusus');
		
		$total  = $this->db->get('presensikhusus')->num_rows();
		$last = $this->db->get('presensikhusus')->result();
		
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
							//$validfrom = PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
							$namashift = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$shiftke = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$tanggal = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd');
							$tjmasuk = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(4, $row)->getValue(), 'yyyy-mm-dd hh:ii:ss');
							$tjkeluar = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(5, $row)->getValue(), 'yyyy-mm-dd hh:ii:ss');
							$jenisabsen = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
							$jenislembur = (trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
							$extraday = (trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()) == ''? 0 : trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
							$asaldata = 'D';
						}
						
						$data = array(
							'NIK'		=> $nik,
							'NAMASHIFT'	=> $namashift,
							'SHIFTKE'	=> $shiftke,
							'TANGGAL'	=> $tanggal,
							'TJMASUK'	=> $tjmasuk,
							'TJKELUAR'	=> $tjkeluar,
							'ASALDATA'	=> $asaldata,
							'JENISABSEN'=> $jenisabsen,
							'JENISLEMBUR'=> $jenislembur,
							'EXTRADAY'	=> $extraday
						);
						$key_presensikhusus = array(
							'NIK'		=> $nik,
							'TANGGAL'	=> date('Y-m-d', strtotime($tanggal)),
							'NAMASHIFT'	=> $namashift,
							'SHIFTKE'	=> $shiftke
						);
						if($this->db->get_where('presensikhusus', $key_presensikhusus)->num_rows() == 0){
							$this->db->insert('presensikhusus', $data);
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