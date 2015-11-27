<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_rekapjemputan
 * 
 * Table	: rekapjemputan
 *  
 * @author masongbee
 *
 */
class M_rekapjemputan extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('rekapjemputan')->result();
		$query = "SELECT NIK
				,STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,JMLJEMPUT,KETERANGAN
			FROM rekapjemputan
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('rekapjemputan')->num_rows();
		
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
		
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK);
		
		if($this->db->get_where('rekapjemputan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			$arrdatau = array(
				'JMLJEMPUT'=>$data->JMLJEMPUT,
				'KETERANGAN'=>$data->KETERANGAN
			);
			
			$this->db->where($pkey)->update('rekapjemputan', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$nourut_last = $this->db->select('COUNT(*) AS total')->where('BULAN', date('Ym', strtotime($data->BULAN)))->get('rekapjemputan')->row();
			$nourut = $nourut_last->total + 1;
			
			$arrdatac = array(
				'NIK'=>$data->NIK,
				'BULAN'=>date('Ym', strtotime($data->BULAN)),
				'JMLJEMPUT'=>$data->JMLJEMPUT,
				'KETERANGAN'=>$data->KETERANGAN
			);
			
			$this->db->insert('rekapjemputan', $arrdatac);
			$last   = $this->db->where($pkey)->get('rekapjemputan')->row();
			
		}
		
		$total  = $this->db->get('rekapjemputan')->num_rows();
		
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
		$pkey = array('BULAN'=>date('Ym', strtotime($data->BULAN)),'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('rekapjemputan');
		
		$total  = $this->db->get('rekapjemputan')->num_rows();
		$last = $this->db->get('rekapjemputan')->result();
		
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
							$bulan = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$jmljemput = (trim($worksheet->getCellByColumnAndRow(2, $row)->getValue()) == ''? NULL : $worksheet->getCellByColumnAndRow(2, $row)->getValue());
							$keterangan = (trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
							
						}
						
						$data = array(
							'BULAN' => $bulan,
							'NIK' => $nik,
							'JMLJEMPUT' => $jmljemput,
							'KETERANGAN' => $keterangan
						);
						if($this->db->get_where('rekapjemputan', array('BULAN'=>$bulan,'NIK'=>$nik))->num_rows() == 0){
							$this->db->insert('rekapjemputan', $data);
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

	function lapjempkar($bulan, $nik){
		$select = "SELECT NIK
				,STR_TO_DATE(CONCAT(BULAN,'01'),'%Y%m%d') AS BULAN
				,JMLJEMPUT,KETERANGAN";
		$from   = " FROM rekapjemputan";

		if (! empty($bulan)) {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " BULAN = '".date('Ym', strtotime($bulan))."'";
		}

		if (! empty($nik) && $nik != '0000000000') {
			$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
			$from .= " NIK = '".$nik."'";
		}

		$sql = $select.$from;
		$result = $this->db->query($sql)->result();
		
		$json	= array(
			'success'   => TRUE,
			'message'   => "Loaded data",
			'data'      => $result
		);
		
		return $json;
	}
}
?>