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

	function getAbjad2Decimal($abjad){
		$arrabjad = array("A"=>65,"B"=>66,"C"=>67,"D"=>68,"S"=>83);
		return $arrabjad[$abjad];
	}

	function getResultAverage($num){
		$arrnum = array(148=>"A+",131=>"B+",133=>"C+",135=>"D+",149=>"A");
		return $arrnum[$num];
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
				'NILAI'    => strtoupper($data->NILAI)
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
				,'NILAI'   => strtoupper($data->NILAI)
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

	function lapkrjkar($thn1, $thn2, $nik){
		$gen_year= "";
		if ($thn1 == $thn2 || (is_numeric($thn1) && $thn2 == "")) {
			# Untuk 1 Tahun
			for ($i=0; $i < 2; $i++) { 
				$colname = $thn1."-".($i+1);
				$gen_year .= ",";
				$gen_year .= "GROUP_CONCAT(if(SUBSTR(KODE,-1) = ".($i+1).", NILAI, NULL)) AS '".$colname."'";
			}

			$colaverage = "AVERAGE ".$thn1;
			$select  = "SELECT tkinerja.NIK,karyawan.NAMAKAR AS NAMA
				".$gen_year."
				,IF(SUM(ASCII(NILAI)) = 148, 'A+', 
					IF(SUM(ASCII(NILAI)) = 131, 'B+',
						IF(SUM(ASCII(NILAI)) = 133, 'C+',
							IF(SUM(ASCII(NILAI)) = 135, 'D+',
								'A')))) AS '".$colaverage."'";
			$from    = " FROM tkinerja
				LEFT JOIN karyawan ON(karyawan.NIK = tkinerja.NIK)";
			$groupby = " GROUP BY tkinerja.NIK";

			if ($nik != "0000000000") {
				$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
				$from .= "(tkinerja.NIK = '".$nik."')";
			}
			
		} else {
			# Untuk lebih dari 1 Tahun
			# Jumlah looping = $thn2 - $tn1 +1;
			$maxi = $thn2 - $thn1 + 1;
			for ($i=0; $i < $maxi; $i++) { 
				$colname = $thn1+$i;
				$colaverage = "AVERAGE ".$colname;
				$gen_year .= ",";
				$gen_year .= "GROUP_CONCAT(
						IF(t1.TAHUN = '".$colname."',
							IF(t1.NILAI = 148, 'A+',
								IF(t1.NILAI = 131, 'B+',
									IF(t1.NILAI = 133, 'C+',
										IF(t1.NILAI = 135, 'D+', 'A')))),
								NULL)) AS '".$colaverage."'";
			}

			$select  = "SELECT t1.NIK,t2.NAMAKAR AS NAMA
				".$gen_year;
			$from    = " FROM (
					SELECT NIK, SUBSTR(KODE,1,4) AS TAHUN, SUM(ASCII(NILAI)) AS NILAI
					FROM tkinerja
					GROUP BY NIK, SUBSTR(KODE,1,4)
				) AS t1
				LEFT JOIN karyawan AS t2 ON(t2.NIK = t1.NIK)";
			$groupby = " GROUP BY t1.NIK";

			if ($nik != "0000000000") {
				$from .= preg_match("/WHERE/i",$from)? " AND ":" WHERE ";
				$from .= "(t1.NIK = '".$nik."')";
			}
		}
		
		$sql     = $select.$from.$groupby;

		$query = $this->db->query($sql);
		$results = $query->result();

		$columns = array();
		$fields  = array();

		foreach ($query->list_fields() as $field){
			$objColumns = new stdClass();
			$objColumns->text      = $field;
			$objColumns->dataIndex = $field;
			if ($field == 'NAMA') {
				$objColumns->width = 319;
			}
			array_push($columns, $objColumns);

		   	$objFields = new stdClass();
			$objFields->name = $field;
			array_push($fields, $objFields);
		}

		$json	= array(
			'success' => TRUE,
			'message' => "Loaded data",
			'columns' => $columns,
			'fields'  => $fields,
			'data'    => $results
		);
		
		return $json;
	}
}
?>