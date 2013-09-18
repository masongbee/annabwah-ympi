<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_tambahanlain2
 * 
 * Table	: tambahanlain2
 *  
 * @author masongbee
 *
 */
class M_tambahanlain2 extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('tambahanlain2')->result();
		$query = "SELECT STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,NOURUT
				,KODEUPAH
				,TANGGAL
				,NIK
				,GRADE
				,KODEJAB
				,KETERANGAN
				,RPTAMBAHAN
				,USERNAME
			FROM tambahanlain2
			ORDER BY BULAN, NOURUT
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('tambahanlain2')->num_rows();
		
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
		
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NOURUT'=>$data->NOURUT);
		
		if($this->db->get_where('tambahanlain2', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'KODEUPAH'=>$data->KODEUPAH,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'KETERANGAN'=>$data->KETERANGAN,
				'RPTAMBAHAN'=>(trim($data->RPTAMBAHAN) == '' ? 0 : $data->RPTAMBAHAN),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->where($pkey)->update('tambahanlain2', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('BULAN', date('Ym', strtotime($data->BULAN)))->get('tambahanlain2')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'NOURUT'=>$nourut,
				'KODEUPAH'=>$data->KODEUPAH,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'NIK'=>(trim($data->NIK) == '' ? NULL : $data->NIK),
				'GRADE'=>(trim($data->GRADE) == '' ? NULL : $data->GRADE),
				'KODEJAB'=>(trim($data->KODEJAB) == '' ? NULL : $data->KODEJAB),
				'KETERANGAN'=>$data->KETERANGAN,
				'RPTAMBAHAN'=>(trim($data->RPTAMBAHAN) == '' ? 0 : $data->RPTAMBAHAN),
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('tambahanlain2', $arrdatac);
			$last   = $this->db->where($pkey)->get('tambahanlain2')->row();
			
		}
		
		$total  = $this->db->get('tambahanlain2')->num_rows();
		
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
		
		$this->db->where($pkey)->delete('tambahanlain2');
		
		$total  = $this->db->get('tambahanlain2')->num_rows();
		$last = $this->db->get('tambahanlain2')->result();
		
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
							$bulan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nourut = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
							$kodeupah = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$tanggal = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd');
							$nik = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
							$grade = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
							$kodejab = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
							$keterangan = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$rptambahan = ($worksheet->getCellByColumnAndRow(8, $row)->getValue() == ''? 0 : $worksheet->getCellByColumnAndRow(8, $row)->getValue());
						}
						
						$data = array(
							'BULAN' => $bulan,
							'NOURUT' => $nourut,
							'KODEUPAH' => $kodeupah,
							'TANGGAL' => $tanggal,
							'NIK' => $nik,
							'GRADE' => $grade,
							'KODEJAB' => $kodejab,
							'KETERANGAN' => $keterangan,
							'RPTAMBAHAN' => $rptambahan
						);
						if($this->db->get_where('tambahanlain2', array('BULAN'=>$bulan,'NOURUT'=>$nourut))->num_rows() == 0){
							$this->db->insert('tambahanlain2', $data);
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