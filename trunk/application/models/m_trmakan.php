<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class	: M_trmakan
 * 
 * Table	: trmakan
 *  
 * @author masongbee
 *
 */
class M_trmakan extends CI_Model{

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
		$query  = $this->db->limit($limit, $start)->order_by('TANGGAL DESC, NIK ASC')->get('trmakan')->result();
		$total  = $this->db->get('trmakan')->num_rows();
		
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
		
		$pkey = array('NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		if($this->db->get_where('trmakan', $pkey)->num_rows() > 0){
			/*
			 * Data Exist
			 */
			
			if($data->MODE == 'update'){
				$arrdatau = array(
					'FMAKAN'=>$data->FMAKAN,
					'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
					'RPPMAKAN'=>(trim($data->RPPMAKAN) == '' ? 0 : $data->RPPMAKAN),
					'KETERANGAN'=>$data->KETERANGAN,
					'USERNAME'=>$data->USERNAME
				);
				
				$this->db->where($pkey)->update('trmakan', $arrdatau);
				$last   = $data;
			}else{
				$last   = $data;
				
				$total  = $this->db->get('trmakan')->num_rows();
				
				$json   = array(
								"success"   => FALSE,
								"message"   => 'Data tidak dapat disimpan, karena data sudah ada.',
								"total"     => $total,
								"data"      => $last
				);
			}
			
		}else{
			/*
			 * Data Not Exist
			 * 
			 * Process Insert
			 */
			
			$arrdatac = array(
				'NIK'=>$data->NIK,
				'TANGGAL'=>(strlen(trim($data->TANGGAL)) > 0 ? date('Y-m-d', strtotime($data->TANGGAL)) : NULL),
				'FMAKAN'=>$data->FMAKAN,
				'RPTMAKAN'=>(trim($data->RPTMAKAN) == '' ? 0 : $data->RPTMAKAN),
				'RPPMAKAN'=>(trim($data->RPPMAKAN) == '' ? 0 : $data->RPPMAKAN),
				'KETERANGAN'=>$data->KETERANGAN,
				'USERNAME'=>$data->USERNAME
			);
			
			$this->db->insert('trmakan', $arrdatac);
			$last   = $this->db->where($pkey)->get('trmakan')->row();
			
			$total  = $this->db->get('trmakan')->num_rows();
			
			$json   = array(
							"success"   => TRUE,
							"message"   => 'Data berhasil disimpan',
							"total"     => $total,
							"data"      => $last
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
		$pkey = array('NIK'=>$data->NIK,'TANGGAL'=>date('Y-m-d', strtotime($data->TANGGAL)));
		
		$this->db->where($pkey)->delete('trmakan');
		
		$total  = $this->db->get('trmakan')->num_rows();
		$last = $this->db->get('trmakan')->result();
		
		$json   = array(
						"success"   => TRUE,
						"message"   => 'Data berhasil dihapus',
						"total"     => $total,
						"data"      => $last
		);				
		return $json;
	}
	
	function get_tmakan($filter){
		$sql = "SELECT * 
			FROM tmakan
			WHERE FMAKAN = '".$filter->fmakan."'
				AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
				AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
				AND NIK = '".$filter->nik."'
			LIMIT 1";
		$query = $this->db->query($sql)->result();
		if(sizeof($query) == 0){
			$sql = "SELECT * 
				FROM tmakan
				WHERE FMAKAN = '".$filter->fmakan."'
					AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
					AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
					AND GRADE = '".$filter->grade."' AND KODEJAB = '".$filter->kodejab."'
				LIMIT 1";
			$query = $this->db->query($sql)->result();
			if(sizeof($query) == 0){
				$sql = "SELECT * 
					FROM tmakan
					WHERE FMAKAN = '".$filter->fmakan."'
						AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
						AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
						AND KODEJAB = '".$filter->kodejab."'
					LIMIT 1";
				$query = $this->db->query($sql)->result();
				if(sizeof($query) == 0){
					$sql = "SELECT * 
						FROM tmakan
						WHERE FMAKAN = '".$filter->fmakan."'
							AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) >= CAST(DATE_FORMAT(TGLMULAI,'%Y%m%d') AS UNSIGNED)
							AND CAST(DATE_FORMAT('".$filter->tanggal."','%Y%m%d') AS UNSIGNED) <= CAST(DATE_FORMAT(TGLSAMPAI,'%Y%m%d') AS UNSIGNED)
							AND GRADE = '".$filter->grade."'
						LIMIT 1";
					$query = $this->db->query($sql)->result();
				}
			}
		}
		
		$data   = array();
		foreach($query as $result){
			$data[] = $result;
		}
		
		$json	= array(
						'success'   => TRUE,
						'message'   => "Loaded data",
						'data'      => $data,
						'total'		=> sizeof($data)
		);
		
		return $json;
	}
	
	function gen_ramadhan_bygrade($grade_arr){
		/*clear data*/
		foreach($grade_arr as $row){
			$sqld = "DELETE trmakan
				FROM trmakan
				JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.NIK = trmakan.NIK)
				WHERE TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
					AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')";
			$this->db->query($sqld);
		}
		
		foreach($grade_arr as $row){
			for($i=0; $i<$row->JMLHARI; $i++){
				$sqli = "INSERT INTO trmakan (NIK, TANGGAL, FMAKAN, RPTMAKAN, USERNAME)
					SELECT presensi.NIK, DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY),
						'R', ".$row->RPTMAKAN.", '".$this->session->userdata('user_name')."'
					FROM presensi
					JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.NIK = presensi.NIK)
					WHERE presensi.TANGGAL = DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY)
						AND presensi.SHIFTKE = '1' OR presensi.SHIFTKE = '3'
						AND NOT EXISTS (
							SELECT NIK, TANGGAL
							FROM trmakan
							WHERE NIK = presensi.NIK AND TANGGAL = presensi.TANGGAL
						)
					GROUP BY presensi.NIK";
				$this->db->query($sqli);
				
			}
		}
	}
	
	function gen_ramadhan_bykodejab($kodejab_arr){
		/*clear data*/
		foreach($grade_arr as $row){
			$sqld = "DELETE trmakan
				FROM trmakan
				JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.NIK = trmakan.NIK)
				WHERE TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
					AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')";
			$this->db->query($sqld);
		}
		
		foreach($kodejab_arr as $row){
			for($i=0; $i<$row->JMLHARI; $i++){
				$sqli = "INSERT INTO trmakan (NIK, TANGGAL, FMAKAN, RPTMAKAN, USERNAME)
					SELECT presensi.NIK, DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY),
						'R', ".$row->RPTMAKAN.", '".$this->session->userdata('user_name')."'
					FROM presensi
					JOIN karyawan ON(karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.NIK = presensi.NIK)
					WHERE presensi.TANGGAL = DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY)
						AND presensi.SHIFTKE = '1' OR presensi.SHIFTKE = '3'
						AND NOT EXISTS (
							SELECT NIK, TANGGAL
							FROM trmakan
							WHERE NIK = presensi.NIK AND TANGGAL = presensi.TANGGAL
						)
					GROUP BY presensi.NIK";
				$this->db->query($sqli);
				
			}
		}
	}
	
	function gen_ramadhan_bygradekodejab($gradekodejab_arr){
		/*clear data*/
		foreach($grade_arr as $row){
			$sqld = "DELETE trmakan
				FROM trmakan
				JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
					AND karyawan.KODEJAB = '".$row->KODEJAB."'
					AND karyawan.NIK = trmakan.NIK)
				WHERE TANGGAL BETWEEN STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d')
					AND STR_TO_DATE('".$row->TGLSAMPAI."', '%Y-%m-%d')";
			$this->db->query($sqld);
		}
		
		foreach($gradekodejab_arr as $row){
			for($i=0; $i<$row->JMLHARI; $i++){
				$sqli = "INSERT INTO trmakan (NIK, TANGGAL, FMAKAN, RPTMAKAN, USERNAME)
					SELECT presensi.NIK, DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY),
						'R', ".$row->RPTMAKAN.", '".$this->session->userdata('user_name')."'
					FROM presensi
					JOIN karyawan ON(karyawan.GRADE = '".$row->GRADE."'
						AND karyawan.KODEJAB = '".$row->KODEJAB."'
						AND karyawan.NIK = presensi.NIK)
					WHERE presensi.TANGGAL = DATE_ADD(STR_TO_DATE('".$row->TGLMULAI."', '%Y-%m-%d'), INTERVAL ".$i." DAY)
						AND presensi.SHIFTKE = '1' OR presensi.SHIFTKE = '3'
						AND NOT EXISTS (
							SELECT NIK, TANGGAL
							FROM trmakan
							WHERE NIK = presensi.NIK AND TANGGAL = presensi.TANGGAL
						)
					GROUP BY presensi.NIK";
				$this->db->query($sqli);
				
			}
		}
	}
	
	function gen_ramadhan($year){
		$sql = "SELECT TGLMULAI, TGLSAMPAI, GRADE, KODEJAB, RPTMAKAN,
				(DATEDIFF(TGLSAMPAI,TGLMULAI) + 1) AS JMLHARI
			FROM tmakan
			WHERE FMAKAN = 'R'
				AND CAST(DATE_FORMAT(TGLMULAI,'%Y') AS UNSIGNED) = CAST('".$year."' AS UNSIGNED)";
		$records = $this->db->query($sql)->result();
		
		if(sizeof($records) > 0){
			/* proses looping rptpekerjaan */
			$grade_arr = array();
			$kodejab_arr = array();
			$gradekodejab_arr = array();
			
			foreach($records as $record){
				if((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)==0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTMAKAN = $record->RPTMAKAN;
					$obj->JMLHARI = $record->JMLHARI;
					array_push($grade_arr, $obj);
					
				}elseif((strlen($record->GRADE)==0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTMAKAN = $record->RPTMAKAN;
					$obj->JMLHARI = $record->JMLHARI;
					array_push($kodejab_arr, $obj);
					
				}elseif((strlen($record->GRADE)!=0) && (strlen($record->KODEJAB)!=0)){
					$obj = new stdClass();
					$obj->GRADE = $record->GRADE;
					$obj->KODEJAB = $record->KODEJAB;
					$obj->TGLMULAI = $record->TGLMULAI;
					$obj->TGLSAMPAI = $record->TGLSAMPAI;
					$obj->RPTMAKAN = $record->RPTMAKAN;
					$obj->JMLHARI = $record->JMLHARI;
					array_push($gradekodejab_arr, $obj);
					
				}
			}
			
			/* urutan rptpekerjaan ke-1 berdasarkan GRADE */
			$this->gen_ramadhan_bygrade($grade_arr);
			/* urutan rptpekerjaan ke-2 berdasarkan GRADE+KATPEKERJAAN */
			$this->gen_ramadhan_bykodejab($kodejab_arr);
			/* urutan rptpekerjaan ke-3 berdasarkan NIK */
			$this->gen_ramadhan_bygradekodejab($gradekodejab_arr);
		}
		
		return 1;
	}
}
?>