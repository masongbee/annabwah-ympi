<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_penugasankar
 * 
 * Table	: sptugas
 *  
 * @author masongbee
 *
 */
class M_penugasankar extends CI_Model{

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
		//$query  = $this->db->limit($limit, $start)->order_by('NOURUT', 'ASC')->get('sptugas')->result();
		$query = "SELECT NOTUGAS
				,sptugas.NIK
				,TGLMULAI
				,TGLSAMPAI
				,sptugas.LAMA
				,sptugas.KOTA
				,RINCIANTUGAS
				,sptugas.KETERANGAN
				,sptugas.NIKATASAN1
				,sptugas.NIKPERSONALIA
				,kar.NAMAKAR AS NAMAKAR
				,karatasan1.NAMAKAR AS NAMAKARATASAN1
				,karhr.NAMAKAR AS NAMAKARHR
			FROM sptugas
			LEFT JOIN karyawan AS kar ON(kar.NIK = sptugas.NIK)
			LEFT JOIN karyawan AS karatasan1 ON(karatasan1.NIK = sptugas.NIKATASAN1)
			LEFT JOIN karyawan AS karhr ON(karhr.NIK = sptugas.NIKPERSONALIA)
			LIMIT ".$start.",".$limit;
		$result = $this->db->query($query)->result();
		$total  = $this->db->get('sptugas')->num_rows();
		
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
		
		$pkey = array('NOTUGAS'=>$data->NOTUGAS,'NIK'=>$data->NIK);
		
		if($this->db->get_where('sptugas', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			$arrdatau = array(
				'TGLMULAI'      => (strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL)
				,'TGLSAMPAI'     => (strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL)
				,'LAMA'          => $data->LAMA
				,'KOTA'          => $data->KOTA
				,'RINCIANTUGAS'  => $data->RINCIANTUGAS
				,'KETERANGAN'    => $data->KETERANGAN
				,'NIKATASAN1'    => $data->NIKATASAN1
				,'NIKPERSONALIA' => $data->NIKPERSONALIA
			);
			
			$this->db->where($pkey)->update('sptugas', $arrdatau);
			$last   = $data;
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			$arrdatac = array(
				'NOTUGAS'        => $data->NOTUGAS
				,'NIK'           => $data->NIK
				,'TGLMULAI'      => (strlen(trim($data->TGLMULAI)) > 0 ? date('Y-m-d', strtotime($data->TGLMULAI)) : NULL)
				,'TGLSAMPAI'     => (strlen(trim($data->TGLSAMPAI)) > 0 ? date('Y-m-d', strtotime($data->TGLSAMPAI)) : NULL)
				,'LAMA'          => $data->LAMA
				,'KOTA'          => $data->KOTA
				,'RINCIANTUGAS'  => $data->RINCIANTUGAS
				,'KETERANGAN'    => $data->KETERANGAN
				,'NIKATASAN1'    => $data->NIKATASAN1
				,'NIKPERSONALIA' => $data->NIKPERSONALIA
			);
			
			$this->db->insert('sptugas', $arrdatac);
			$last   = $this->db->where($pkey)->get('sptugas')->row();
			
		}
		
		$total  = $this->db->get('sptugas')->num_rows();
		
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
		$pkey = array('NOTUGAS'=>$data->NOTUGAS,'NIK'=>$data->NIK);
		
		$this->db->where($pkey)->delete('sptugas');
		
		$total = $this->db->get('sptugas')->num_rows();
		$last  = $this->db->get('sptugas')->result();
		
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
							$notugas       = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
							$nik           = (trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
							$tglmulai      = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(2, $row)->getValue(), 'yyyy-mm-dd');
							$tglsampai     = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCellByColumnAndRow(3, $row)->getValue(), 'yyyy-mm-dd');
							$kota          = (trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
							$rinciantugas  = (trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
							$keterangan    = (trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
							$nikatasan1    = (trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
							$nikpersonalia = (trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()) == ''? NULL : trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
							
							$datetime1 = new DateTime($tglmulai);
							$datetime2 = new DateTime($tglsampai);
							$difference = $datetime1->diff($datetime2);
						}
						
						$data = array(
							'NOTUGAS'       => $notugas,
							'NIK'           => $nik,
							'TGLMULAI'      => $tglmulai,
							'TGLSAMPAI'     => $tglsampai,
							'LAMA'          => $difference->days,
							'KOTA'          => $kota,
							'RINCIANTUGAS'  => $rinciantugas,
							'KETERANGAN'    => $keterangan,
							'NIKATASAN1'    => $nikatasan1,
							'NIKPERSONALIA' => $nikpersonalia
						);
						if($this->db->get_where('sptugas', array('NOTUGAS'=>$notugas,'NIK'=>$nik))->num_rows() == 0){
							$this->db->insert('sptugas', $data);
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