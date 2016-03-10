<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_mkinerja
 * 
 * Table	: mkinerja
 *  
 * @author masongbee
 *
 */
class M_mkinerja extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('mkinerja')->result();
		$query = "SELECT KODE
				,NAMAPENILAIAN
				,TGLMULAI
				,TGLSAMPAI
			FROM mkinerja
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('mkinerja')->num_rows();
		
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
		$tahun = substr($data->KODE, 0, 4);
		
		$pkey = array('KODE'=>$data->KODE);

		$sql_tahun = "SELECT COUNT(*) AS total FROM mkinerja WHERE SUBSTR(KODE,1,4) = '".$tahun."'";
		$query_tahun = $this->db->query($sql_tahun);

		if ($query_tahun->num_rows() > 0) {
			$row_tahun = $query_tahun->row();

			if ($row_tahun->total < 2) {
				if($this->db->get_where('mkinerja', $pkey)->num_rows() > 0){
					/*
					 * Data Exist
					 */
					$arrdatau = array(
						'NAMAPENILAIAN' => $data->NAMAPENILAIAN
						,'TGLMULAI'     => (strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL)
						,'TGLSAMPAI'    => (strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL)
					);
					
					$this->db->where($pkey)->update('mkinerja', $arrdatau);
					$last   = $data;

					$total  = $this->db->get('mkinerja')->num_rows();
					
					$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
					);
					
				}else{
					/*
					 * Data Not Exist
					 * 
					 * Process Insert
					 */
					$arrdatac = array(
						'KODE'           => $data->KODE
						,'NAMAPENILAIAN' => $data->NAMAPENILAIAN
						,'TGLMULAI'      => (strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL)
						,'TGLSAMPAI'     => (strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL)
					);
					
					$this->db->insert('mkinerja', $arrdatac);
					$last   = $this->db->where($pkey)->get('mkinerja')->row();

					$total  = $this->db->get('mkinerja')->num_rows();
					
					$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil disimpan',
						"total"     => $total,
						"data"      => $last
					);
					
				}
			} else {
				$json   = array(
					"success"   => TRUE,
					"message"   => 'Data tidak dapat disimpan, karena sudah ada 2 data untuk tahun yang dimaksud.',
					"total"     => 0,
					"data"      => NULL
				);
			}
		} else {
			$json   = array(
				"success"   => TRUE,
				"message"   => 'Tidak Ada Data.',
				"total"     => 0,
				"data"      => NULL
			);
		}
		
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
		
		$this->db->where($pkey)->delete('mkinerja');
		
		$total = $this->db->get('mkinerja')->num_rows();
		$last  = $this->db->get('mkinerja')->result();
		
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
							$kode          = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$namapenilaian = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$tglmulai      = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(2, $row)->getValue(), 'yyyy-mm-dd');
							$tglsampai     = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd');
						}
						
						$data = array(
							'KODE'          => $kode,
							'NAMAPENILAIAN' => $namapenilaian,
							'TGLMULAI'      => $tglmulai,
							'TGLSAMPAI'     => $tglsampai,
						);
						if($this->db->get_where('mkinerja', array('KODE'=>$kode))->num_rows() == 0){
							$this->db->insert('mkinerja', $data);
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