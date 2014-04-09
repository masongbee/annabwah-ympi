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
	function getAll($start, $page, $limit, $tglmulai, $tglsampai, $bulan){
		//$query  = $this->db->limit($limit, $start)->order_by('NIK', 'ASC')->get('presensibln')->result();
		$sql = "SELECT presensibln.NIK, STR_TO_DATE(CONCAT(presensibln.BULAN,'01'),'%Y%m%d') AS BULAN, presensibln.HARIKERJA,
				presensibln.EXTRADAY, presensibln.XPOTONG, presensibln.SATLEMBUR, karyawan.NAMAKAR
			FROM presensibln
			LEFT JOIN karyawan ON(karyawan.NIK = presensibln.NIK)
			WHERE presensibln.BULAN = '".$bulan."'
			ORDER BY NIK";
		$query 	= $this->db->query($sql)->result();
		$total  = $this->db->get('presensibln')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK, 'BULAN'=>date('Ym', strtotime($data->BULAN)));
		
		if($this->db->get_where('presensibln', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'HARIKERJA'=>$data->HARIKERJA,
				'EXTRADAY'=>$data->EXTRADAY,
				'XPOTONG'=>$data->XPOTONG,
				'SATLEMBUR'=>$data->SATLEMBUR);
			 
			$this->db->where($pkey)->update('presensibln', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'NIK'=>$data->NIK,
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'HARIKERJA'=>$data->HARIKERJA,
				'EXTRADAY'=>$data->EXTRADAY,
				'XPOTONG'=>$data->XPOTONG,
				'SATLEMBUR'=>$data->SATLEMBUR);
			 
			$this->db->insert('presensibln', $arrdatac);
			$last   = $this->db->where($pkey)->get('presensibln')->row();
			
		}
		
		$total  = $this->db->get('presensibln')->num_rows();
		
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
		$pkey = array('NIK'=>$data->NIK, 'BULAN'=>date('Ym', strtotime($data->BULAN)));
		
		$this->db->where($pkey)->delete('presensibln');
		
		$total  = $this->db->get('presensibln')->num_rows();
		$last = $this->db->get('presensibln')->result();
		
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
							$nik       = (trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
							$bulan     = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
							$harikerja = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? 0 : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$extraday  = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? 0 : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
							$xpotong   = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? 0 : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
							$satlembur = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? 0 : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
						}
						
						$data = array(
							'NIK'       => $nik,
							'BULAN'     => $bulan,
							'HARIKERJA' => $harikerja,
							'EXTRADAY'  => $extraday,
							'XPOTONG'   => $xpotong,
							'SATLEMBUR' => $satlembur
						);
						$key_presensibln = array(
							'NIK'   => $nik,
							'BULAN' => $bulan
						);
						
						if($this->db->get_where('presensibln', $key_presensibln)->num_rows() == 0){
							$this->db->insert('presensibln', $data);
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